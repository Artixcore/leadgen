<?php

namespace App\Notifications;

use App\Models\LeadImportRun;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImportRunFailedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public LeadImportRun $run
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
        $url = route('admin.import-runs.show', $this->run);
        $source = $this->run->leadSource?->name ?? 'Unknown';

        return (new MailMessage)
            ->subject(__('Lead import failed'))
            ->line(__('A lead source sync has failed.'))
            ->line(__('Source: :name', ['name' => $source]))
            ->line(__('Error: :message', ['message' => $this->run->error_message ?? 'Unknown']))
            ->action(__('View import run'), $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'import_run_failed',
            'lead_import_run_id' => $this->run->id,
            'lead_source_name' => $this->run->leadSource?->name,
            'error_message' => $this->run->error_message,
            'url' => route('admin.import-runs.show', $this->run),
        ];
    }
}
