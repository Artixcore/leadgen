<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\PlanUsage;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\PlanSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadModuleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(PlanSeeder::class);
    }

    protected function userWithLeadsPermission(): User
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');

        return $user;
    }

    public function test_guest_cannot_access_leads_index(): void
    {
        $response = $this->get(route('leads.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_with_search_leads_permission_can_access_leads_index(): void
    {
        $user = $this->userWithLeadsPermission();
        Lead::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('leads.index'));

        $response->assertOk();
        $response->assertSee(__('Leads'));
    }

    public function test_leads_index_paginates_and_shows_search(): void
    {
        $user = $this->userWithLeadsPermission();
        Lead::factory()->count(20)->create();

        $response = $this->actingAs($user)->get(route('leads.index'));

        $response->assertOk();
        $response->assertViewHas('leads');
        $this->assertTrue($response->viewData('leads')->total() >= 20);
    }

    public function test_leads_index_filters_by_city_and_has_email_and_sort(): void
    {
        $user = $this->userWithLeadsPermission();
        Lead::factory()->create(['city' => 'Berlin', 'email' => null]);
        $match = Lead::factory()->create(['city' => 'Berlin', 'email' => 'berlin@example.com', 'quality_score' => 80]);
        Lead::factory()->create(['city' => 'Munich', 'email' => 'munich@example.com']);

        $response = $this->actingAs($user)->get(route('leads.index', [
            'city' => 'Berlin',
            'has_email' => '1',
            'sort' => 'highest_quality',
        ]));

        $response->assertOk();
        $leads = $response->viewData('leads');
        $this->assertSame(1, $leads->total());
        $this->assertSame($match->id, $leads->first()->id);
    }

    public function test_user_can_view_lead_detail_and_usage_increments(): void
    {
        $user = $this->userWithLeadsPermission();
        $lead = Lead::factory()->create();

        $this->actingAs($user)->get(route('leads.show', $lead));

        $usage = PlanUsage::where('user_id', $user->id)->where('period', PlanUsage::currentPeriod())->first();
        $this->assertNotNull($usage);
        $this->assertSame(1, $usage->leads_count);
    }

    public function test_user_at_lead_limit_redirected_from_lead_show(): void
    {
        $user = $this->userWithLeadsPermission();
        PlanUsage::create([
            'user_id' => $user->id,
            'period' => PlanUsage::currentPeriod(),
            'leads_count' => 50,
            'exports_count' => 0,
        ]);
        $lead = Lead::factory()->create();

        $response = $this->actingAs($user)->get(route('leads.show', $lead));

        $response->assertRedirect(route('leads.index'));
        $response->assertSessionHas('error');
    }

    public function test_user_can_bookmark_lead(): void
    {
        $user = $this->userWithLeadsPermission();
        $user->givePermissionTo('bookmark-leads');
        $lead = Lead::factory()->create();

        $response = $this->actingAs($user)->post(route('leads.bookmark.store', $lead));

        $response->assertRedirect();
        $response->assertSessionHas('status');
        $this->assertTrue($user->bookmarkedLeads()->where('leads.id', $lead->id)->exists());
    }

    public function test_user_can_remove_bookmark(): void
    {
        $user = $this->userWithLeadsPermission();
        $user->givePermissionTo('bookmark-leads');
        $lead = Lead::factory()->create();
        $user->bookmarkedLeads()->attach($lead->id);

        $response = $this->actingAs($user)->delete(route('leads.bookmark.destroy', $lead));

        $response->assertRedirect();
        $this->assertFalse($user->bookmarkedLeads()->where('leads.id', $lead->id)->exists());
    }

    public function test_user_can_add_note_to_lead(): void
    {
        $user = $this->userWithLeadsPermission();
        $lead = Lead::factory()->create();

        $response = $this->actingAs($user)->post(route('leads.notes.store', $lead), [
            'body' => 'Test note content',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('lead_notes', [
            'lead_id' => $lead->id,
            'user_id' => $user->id,
            'body' => 'Test note content',
        ]);
    }

    public function test_user_can_delete_own_note(): void
    {
        $user = $this->userWithLeadsPermission();
        $lead = Lead::factory()->create();
        $note = LeadNote::create(['lead_id' => $lead->id, 'user_id' => $user->id, 'body' => 'Note']);

        $response = $this->actingAs($user)->delete(route('leads.notes.destroy', $note));

        $response->assertRedirect();
        $this->assertDatabaseMissing('lead_notes', ['id' => $note->id]);
    }

    public function test_user_can_attach_tag_to_lead(): void
    {
        $user = $this->userWithLeadsPermission();
        $lead = Lead::factory()->create();
        $tag = Tag::create(['name' => 'Test Tag', 'slug' => 'test-tag']);

        $response = $this->actingAs($user)->post(route('leads.tags.store', $lead), [
            'tag_ids' => [$tag->id],
        ]);

        $response->assertRedirect();
        $this->assertTrue($lead->fresh()->tags()->where('tags.id', $tag->id)->exists());
    }

    public function test_user_can_create_list_and_add_lead(): void
    {
        $user = $this->userWithLeadsPermission();
        $user->givePermissionTo('manage-lists');
        $lead = Lead::factory()->create();

        $response = $this->actingAs($user)->post(route('lists.store'), ['name' => 'My List']);
        $response->assertRedirect();
        $list = $user->leadLists()->first();
        $this->assertNotNull($list);
        $this->assertSame('My List', $list->name);

        $response = $this->actingAs($user)->post(route('leads.lists.add', $lead), ['list_id' => $list->id]);
        $response->assertRedirect();
        $response->assertSessionHas('status');
        $this->assertTrue($list->fresh()->leads()->where('leads.id', $lead->id)->exists());
    }

    public function test_user_can_view_rename_and_delete_list(): void
    {
        $user = $this->userWithLeadsPermission();
        $user->givePermissionTo('manage-lists');
        $list = $user->leadLists()->create(['name' => 'Original']);

        $response = $this->actingAs($user)->get(route('lists.show', $list));
        $response->assertOk();
        $response->assertSee('Original');

        $response = $this->actingAs($user)->patch(route('lists.update', $list), ['name' => 'Renamed']);
        $response->assertRedirect(route('lists.show', $list));
        $this->assertSame('Renamed', $list->fresh()->name);

        $response = $this->actingAs($user)->delete(route('lists.destroy', $list));
        $response->assertRedirect(route('lists.index'));
        $this->assertModelMissing($list);
    }

    public function test_user_can_remove_lead_from_list(): void
    {
        $user = $this->userWithLeadsPermission();
        $user->givePermissionTo('manage-lists');
        $list = $user->leadLists()->create(['name' => 'My List']);
        $lead = Lead::factory()->create();
        $list->leads()->attach($lead->id);

        $response = $this->actingAs($user)->delete(route('lists.leads.remove', [$list, $lead]));

        $response->assertRedirect();
        $response->assertSessionHas('status');
        $this->assertFalse($list->fresh()->leads()->where('leads.id', $lead->id)->exists());
    }

    public function test_export_by_list_id_exports_list_leads(): void
    {
        $user = $this->userWithLeadsPermission();
        $user->givePermissionTo('export-leads');
        $list = $user->leadLists()->create(['name' => 'Export List']);
        $lead = Lead::factory()->create();
        $list->leads()->attach($lead->id);

        $response = $this->actingAs($user)->post(route('leads.export'), [
            'format' => 'csv',
            'list_id' => $list->id,
        ]);

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString($lead->email, $response->streamedContent());
    }

    public function test_export_leads_downloads_csv_and_increments_export_count(): void
    {
        $user = $this->userWithLeadsPermission();
        $user->givePermissionTo('export-leads');
        $lead = Lead::factory()->create();

        $response = $this->actingAs($user)->post(route('leads.export'), [
            'format' => 'csv',
            'lead_ids' => [$lead->id],
        ]);

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $usage = PlanUsage::where('user_id', $user->id)->where('period', PlanUsage::currentPeriod())->first();
        $this->assertNotNull($usage);
        $this->assertSame(1, $usage->exports_count);
    }

    public function test_duplicate_detection_sets_is_duplicate_when_email_matches(): void
    {
        $first = Lead::factory()->create(['email' => 'same@example.com']);
        $this->assertFalse($first->is_duplicate);

        $second = Lead::factory()->create(['email' => 'same@example.com']);
        $second->refresh();
        $this->assertTrue($second->is_duplicate);
        $this->assertSame($first->id, $second->duplicate_of_lead_id);
    }

    public function test_lead_policy_view_requires_search_leads_permission(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $lead = Lead::factory()->create();

        $response = $this->actingAs($user)->get(route('leads.show', $lead));

        $response->assertForbidden();
    }

    public function test_user_can_update_lead_status(): void
    {
        $user = $this->userWithLeadsPermission();
        $lead = Lead::factory()->create();

        $response = $this->actingAs($user)->patch(route('leads.status.update', $lead), [
            'lead_status' => 'contacted',
        ]);

        $response->assertRedirect(route('leads.show', $lead));
        $lead->refresh();
        $this->assertSame('contacted', $lead->lead_status->value);
    }

    public function test_user_can_add_and_delete_reminder(): void
    {
        $user = $this->userWithLeadsPermission();
        $lead = Lead::factory()->create();
        $remindAt = now()->addDay()->format('Y-m-d\TH:i');

        $response = $this->actingAs($user)->post(route('leads.reminders.store', $lead), [
            'remind_at' => $remindAt,
            'body' => 'Follow up',
        ]);
        $response->assertRedirect(route('leads.show', $lead));
        $this->assertDatabaseHas('lead_reminders', ['lead_id' => $lead->id, 'user_id' => $user->id, 'body' => 'Follow up']);

        $reminder = $lead->reminders()->where('user_id', $user->id)->first();
        $response = $this->actingAs($user)->delete(route('leads.reminders.destroy', $reminder));
        $response->assertRedirect(route('leads.show', $lead));
        $this->assertDatabaseMissing('lead_reminders', ['id' => $reminder->id]);
    }
}
