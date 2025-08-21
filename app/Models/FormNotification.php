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
     * Get the body with placeholders replaced.
     */
    public function getProcessedBody(Submission $submission): string
    {
        $body = $this->body ?? '';
        // $this->body = {"type":"doc","content":[{"type":"paragraph","attrs":{"class":null,"style":null},"content":[{"type":"mention","attrs":{"id":12,"label":"Your Email","href":null,"type":null,"target":"_blank","data":[]}},{"type":"text","text":" Submitting new form"}]}]}
        // submission is model Submission has values

        // Build submissionData: [field_id => value]
        $values = $submission->values->pluck('value', 'form_field_id')->toArray();

        $doc = is_string($body) ? json_decode($body, true) : $body;

        if (is_array($doc) && isset($doc['content'])) {
            foreach ($doc['content'] as &$block) {
                if (isset($block['content']) && is_array($block['content'])) {
                    foreach ($block['content'] as $i => $node) {
                        if ($node['type'] === 'mention' && isset($node['attrs']['id'])) {
                            $fieldId = $node['attrs']['id'];
                            $value = $values[$fieldId] ?? $node['attrs']['label'];
                            // Replace the node in-place
                            $block['content'][$i] = [
                                'type' => 'text',
                                'text' => $value,
                            ];
                        }
                    }
                }
            }
            unset($block, $node);
            $body = $doc;
        }

        return tiptap_converter()->asHTML($body);
    }
}
