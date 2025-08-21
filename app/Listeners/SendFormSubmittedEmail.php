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
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FormSubmittedEvent $event): void
    {
        $this->sendNotificationsForSubmission($event->submission);
    }

    /**
     * Send notifications for a form submission.
     */
    protected function sendNotificationsForSubmission(Submission $submission): void
    {
        $form = $submission->form;
        $notifications = $form->notifications;

        if ($notifications->isEmpty()) {
            return;
        }

        $submissionData = $this->getSubmissionDataArray($submission);

        foreach ($notifications as $notification) {
            $this->sendNotification($notification, $submission, $submissionData);
        }
    }

    /**
     * Send a specific notification.
     */
    protected function sendNotification(FormNotification $notification, Submission $submission, array $submissionData): void
    {
        $recipients = $this->getRecipientsForNotification($notification, $submission);

        if (empty($recipients)) {
            return;
        }

        $subject = $notification->getProcessedSubject($submissionData);
        $body = $notification->getProcessedBody($submissionData);

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
            $submitterEmail = $this->getSubmitterEmail($submission, $notification->fieldKey);
            if ($submitterEmail) {
                $recipients[] = $submitterEmail;
            }
        }

        return array_unique(array_filter($recipients));
    }

    /**
     * Get submitter email from submission.
     */
    protected function getSubmitterEmail(Submission $submission, $emailField): ?string
    {
        $submissionValue = $submission->values()
            ->where('form_field_id', $emailField->id)
            ->first();

        return $submissionValue?->value;
    }

    /**
     * Convert submission to array for placeholder replacement.
     */
    protected function getSubmissionDataArray(Submission $submission): array
    {
        $data = [];

        foreach ($submission->values as $value) {
            $field = $value->formField;
            if ($field) {
                $data[$field->label] = $value->value;
            }
        }

        // Add some meta fields
        $data['submission_id'] = $submission->id;
        $data['submission_date'] = $submission->created_at->format('Y-m-d H:i:s');
        $data['form_name'] = $submission->form->name;

        return $data;
    }

    /**
     * Get available placeholders for a form.
     */
    public function getAvailablePlaceholders(Form $form): array
    {
        $placeholders = [];

        foreach ($form->fields as $field) {
            $placeholders[$field->label] = $field->type->getLabel();
        }

        // Add meta placeholders
        $placeholders['submission_id'] = 'Submission ID';
        $placeholders['submission_date'] = 'Submission Date';
        $placeholders['form_name'] = 'Form Name';

        return $placeholders;
    }
}
