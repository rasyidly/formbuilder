<?php

namespace App\Listeners;

use App\Events\FormSubmittedEvent;
use App\Models\Form;
use App\Models\FormNotification;
use App\Models\Submission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendFormSubmittedEmail
{
    /**
     * Create the event listener.
     */
    public function __construct(
        public Submission $submission,
    ) {}

    /**
     * Handle the event.
     */
    public function handle(FormSubmittedEvent $event): void
    {
        $form = $event->submission->form;
        $notifications = $form->notifications;

        if ($notifications->isEmpty()) {
            return;
        }

        foreach ($notifications as $notification) {
            $this->sendNotification($notification, $event->submission);
        }
    }

    /**
     * Send a specific notification.
     */
    protected function sendNotification(FormNotification $notification, Submission $submission): void
    {
        $recipients = $this->getRecipientsForNotification($notification, $submission);

        if (empty($recipients)) {
            return;
        }

        $subject = $notification->subject;
        $body = $notification->getProcessedBody($submission);

        foreach ($recipients as $recipient) {
            Mail::send([], [], function ($message) use ($recipient, $subject, $body) {
                $message->to($recipient)
                    ->subject($subject)
                    ->html($body);
            });
        }
    }

    /**
     * Get recipients for a notification.
     */
    protected function getRecipientsForNotification(FormNotification $notification, Submission $submission): array
    {
        $recipients = [];

        // Custom recipients
        if ($notification->hasCustomRecipients()) {
            $recipients = array_merge($recipients, $notification->recipients);
        }

        // Submitter email (thank you email)
        if ($notification->isForSubmitters() && $notification->fieldKey) {
            $submitterEmail = $submission->values()->where('form_field_id', $notification->fieldKey->id)->first();
            if ($submitterEmail) {
                $recipients[] = $submitterEmail->value;
            }
        }

        return array_unique(array_filter($recipients));
    }
}
