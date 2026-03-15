<?php

namespace Tests\Feature;

use App\Models\Export;
use App\Models\Lead;
use App\Models\User;
use Database\Seeders\PlanSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportLoggingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(PlanSeeder::class);
    }

    public function test_every_export_creates_export_record(): void
    {
        $user = User::factory()->completedOnboarding()->create();
        $user->assignRole('user');
        $user->givePermissionTo('export-leads');
        $lead = Lead::factory()->create();

        $this->assertSame(0, Export::count());

        $response = $this->actingAs($user)->post(route('leads.export'), [
            'format' => 'csv',
            'lead_ids' => [$lead->id],
        ]);

        $response->assertRedirect();
        $this->assertSame(1, Export::count());
        $export = Export::first();
        $this->assertSame($user->id, $export->user_id);
        $this->assertSame('csv', $export->type);
        $this->assertSame(1, $export->row_count);
        $this->assertSame('completed', $export->status);
    }
}
