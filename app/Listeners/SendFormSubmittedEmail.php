<?php

namespace App\Listeners;

use App\Events\FormSubmittedEvent;
use App\Mail\FormSubmitted;
use App\Mail\FormSubmissionThankYou;
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
        // Send notification email to admin/recipient
        if (isset($event->submission->form->settings['recipient_emails']) && count($event->submission->form->settings['recipient_emails'])) {
            Mail::to($event->submission->form->settings['recipient_emails'])->send(new FormSubmitted($event->submission));
        }

        // Send thank you email to submitter if they provided email and field has receive_feedback setting
        $emailFields = $event->submission->form->fields->filter(function ($field) {
            return $field->type->value === 'email' &&
                isset($field->settings['receive_feedback']) &&
                $field->settings['receive_feedback'] === true;
        });

        foreach ($emailFields as $emailField) {
            $submissionValue = $event->submission->values->where('form_field_id', $emailField->id)->first();
            if ($submissionValue && !empty($submissionValue->value)) {
                Mail::to($submissionValue->value)->send(new FormSubmissionThankYou($event->submission));
            }
        }
    }
}
