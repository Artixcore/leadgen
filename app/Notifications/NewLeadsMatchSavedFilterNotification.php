<?php

namespace App\Notifications;

use App\Models\Lead;
use App\Models\SavedFilter;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLeadsMatchSavedFilterNotification extends Notification
{
    use Queueable;

    public function __construct(
        public SavedFilter $savedFilter,
        public Lead $lead
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('leads.show', $this->lead);

        return (new MailMessage)
            ->subject(__('New lead matches your saved filter'))
            ->line(__('A new lead matches your saved filter ":name".', ['name' => $this->savedFilter->name]))
            ->line(__('Lead: :name at :company', [
                'name' => $this->lead->full_name ?? $this->lead->email,
                'company' => $this->lead->company_name ?? '—',
            ]))
            ->action(__('View lead'), $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_lead_matches_filter',
            'saved_filter_id' => $this->savedFilter->id,
            'saved_filter_name' => $this->savedFilter->name,
            'lead_id' => $this->lead->id,
            'url' => route('leads.show', $this->lead),
        ];
    }
}
