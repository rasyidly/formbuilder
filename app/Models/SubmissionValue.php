<?php

namespace App\Models;

use App\Enums\FormFieldType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubmissionValue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'form_field_id',
        'submission_id',
        'field_label',
        'field_type',
        'value',
        'files_metadata',
    ];


    protected function casts(): array
    {
        return [
            'field_type' => FormFieldType::class,
            'files_metadata' => 'array',
        ];
    }

    /**
     * Get the form field that this value belongs to.
     */
    public function formField(): BelongsTo
    {
        return $this->belongsTo(FormField::class);
    }

    /**
     * Get the submission that this value belongs to.
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Scope a query to only include file upload values.
     */
    public function scopeFiles($query)
    {
        return $query->whereIn('field_type', [FormFieldType::File->value, FormFieldType::IMAGE->value]);
    }

    /**
     * Check if this value is for a file upload field.
     */
    public function isFileUpload(): bool
    {
        return $this->field_type->isFileUpload();
    }

    /**
     * Get the file metadata for file uploads.
     */
    public function getFileMetadata(): array
    {
        return $this->files_metadata ?? [];
    }

    /**
     * Get the original filename for file uploads.
     */
    public function getOriginalFilename(): ?string
    {
        $metadata = $this->getFileMetadata();

        return $metadata['original_name'] ?? null;
    }

    /**
     * Get the file size for file uploads.
     */
    public function getFileSize(): ?int
    {
        $metadata = $this->getFileMetadata();

        return $metadata['size'] ?? null;
    }

    /**
     * Get the file path for file uploads.
     */
    public function getFilePath(): ?string
    {
        $metadata = $this->getFileMetadata();

        return $metadata['path'] ?? $this->value;
    }

    /**
     * Get the formatted value for display.
     */
    public function getDisplayValue(): string
    {
        if ($this->isFileUpload()) {
            return $this->getOriginalFilename() ?? $this->value ?? 'File uploaded';
        }

        if ($this->field_type === FormFieldType::CheckboxList && is_array($this->value)) {
            return implode(', ', $this->value);
        }

        return (string) $this->value;
    }

    /**
     * Get the raw value, properly cast based on field type.
     */
    public function getRawValue(): mixed
    {
        return match ($this->field_type) {
            FormFieldType::Number => is_numeric($this->value) ? (float) $this->value : $this->value,
            FormFieldType::CheckboxList => is_string($this->value) ? json_decode($this->value, true) : $this->value,
            default => $this->value,
        };
    }
}
