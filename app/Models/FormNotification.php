<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'recipients',
        'subject',
        'body',
        'field_key_id',
    ];

    protected function casts(): array
    {
        return [
            'recipients' => 'array',
        ];
    }

    /**
     * Get the form that owns this notification.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the form field that contains the email for submitter notification.
     */
    public function fieldKey(): BelongsTo
    {
        return $this->belongsTo(FormField::class, 'field_key_id');
    }

    /**
     * Check if this notification is for form submitters.
     */
    public function isForSubmitters(): bool
    {
        return $this->field_key_id !== null;
    }

    /**
     * Check if this notification has custom recipients.
     */
    public function hasCustomRecipients(): bool
    {
        return !empty($this->recipients);
    }

    /**
     * Get all recipients as a flat array.
     */
    public function getAllRecipients(): array
    {
        $recipients = [];

        // Add custom recipients
        if ($this->hasCustomRecipients()) {
            $recipients = array_merge($recipients, $this->recipients);
        }

        return array_unique($recipients);
    }

    /**
     * Replace placeholders in the given text with actual submission values.
     */
    public function replacePlaceholders(string $text, array $submissionData = []): string
    {
        // Replace TipTap editor mentions format: @[label](id)
        $text = preg_replace_callback('/@\[([^\]]+)\]\((\d+)\)/', function ($matches) use ($submissionData) {
            $fieldLabel = $matches[1];

            // Check if the placeholder exists in submission data
            if (isset($submissionData[$fieldLabel])) {
                return $submissionData[$fieldLabel];
            }

            // Return the field label if no replacement found
            return $fieldLabel;
        }, $text);

        // Also handle legacy %placeholder% format for backward compatibility
        return preg_replace_callback('/%([^%]+)%/', function ($matches) use ($submissionData) {
            $placeholder = $matches[1];

            // Check if the placeholder exists in submission data
            if (isset($submissionData[$placeholder])) {
                return $submissionData[$placeholder];
            }

            // Return the original placeholder if no replacement found
            return $matches[0];
        }, $text);
    }

    /**
     * Get the subject with placeholders replaced.
     */
    public function getProcessedSubject(array $submissionData = []): string
    {
        return $this->replacePlaceholders($this->subject ?? '', $submissionData);
    }

    /**
     * Get the body with placeholders replaced.
     */
    public function getProcessedBody(array $submissionData = []): string
    {
        return tiptap_converter()->asHTML($this->body ?? '');
    }
}
