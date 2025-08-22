<?php

namespace App\Models;

use App\Enums\SubmissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'form_id',
        'submitter_id', // [QUESTION] we don't need this anymore?
        'submitter_name', // [QUESTION] we don't need this anymore?
        'submitter_email', // [QUESTION] we don't need this anymore?
        'status',
        'notes',
        'metadata',
        'submitter_ip',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'status' => SubmissionStatus::class,
            'metadata' => 'array',
        ];
    }

    /**
     * Get the form that this submission belongs to.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get the user who submitted this form (if authenticated).
     */
    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitter_id');
    }

    /**
     * Get all values for this submission.
     */
    public function values(): HasMany
    {
        return $this->hasMany(SubmissionValue::class);
    }

    /**
     * Scope a query to only include pending submissions.
     */
    public function scopePending($query)
    {
        return $query->where('status', SubmissionStatus::Pending);
    }

    /**
     * Scope a query to only include approved submissions.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', SubmissionStatus::Approved);
    }

    /**
     * Scope a query to only include rejected submissions.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', SubmissionStatus::Rejected);
    }

    /**
     * Scope a query to only include archived submissions.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', SubmissionStatus::Archived);
    }

    /**
     * Approve the submission.
     */
    public function approve(?string $notes = null): void
    {
        $this->update([
            'status' => SubmissionStatus::Approved,
            'notes' => $notes,
        ]);
    }

    /**
     * Reject the submission.
     */
    public function reject(?string $notes = null): void
    {
        $this->update([
            'status' => SubmissionStatus::Rejected,
            'notes' => $notes,
        ]);
    }

    /**
     * Archive the submission.
     */
    public function archive(?string $notes = null): void
    {
        $this->update([
            'status' => SubmissionStatus::Archived,
            'notes' => $notes,
        ]);
    }

    /**
     * Get the submission data as an array of field_label => value.
     */
    public function getSubmissionData(): array
    {
        return $this->values()
            ->get()
            ->pluck('value', 'field_label')
            ->toArray();
    }

    /**
     * Get a specific field value by field name.
     */
    public function getFieldValue(string $fieldName): mixed
    {
        $value = $this->values()
            ->where('field_label', $fieldName)
            ->first();

        return $value ? $value->value : null;
    }

    /**
     * Get the submitter's display name.
     */
    public function getSubmitterDisplayName(): string
    {
        if ($this->submitter) {
            return $this->submitter->name;
        }

        return $this->submitter_name ?? $this->submitter_email ?? 'Anonymous';
    }
}
