<?php

namespace App\Models;

use App\Enums\FormFieldType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormField extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'form_id',
        'sequence',
        'label',
        'type',
        'placeholder',
        'help_text',
        'options',
        'validation_rules',
        'conditional_logic',
        'settings',
        'is_required'
    ];

    protected function casts(): array
    {
        return [
            'type' => FormFieldType::class,
            'options' => 'array',
            'validation_rules' => 'array',
            'conditional_logic' => 'array',
            'settings' => 'array',
            'is_required' => 'boolean'
        ];
    }

    /**
     * Get the form that owns this field.
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * Get all submission values for this field.
     */
    public function submissionValues(): HasMany
    {
        return $this->hasMany(SubmissionValue::class);
    }

    /**
     * Scope a query to only include required fields.
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope a query to order fields by sequence.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sequence');
    }

    /**
     * Check if the field is a file upload field.
     */
    public function isFileUpload(): bool
    {
        return $this->type->isFileUpload();
    }

    /**
     * Check if the field has multiple options (select, radio, checkbox).
     */
    public function hasOptions(): bool
    {
        return $this->type->hasOptions() && ! empty($this->options);
    }

    /**
     * Get the validation rules for Laravel validation.
     */
    public function getValidationRules(): array
    {
        $rules = $this->validation_rules ?? [];

        if ($this->is_required) {
            $rules[] = 'required';
        }

        return $rules;
    }

    /**
     * Get the field options as key-value pairs.
     */
    public function getOptionsArray(): array
    {
        if (!$this->hasOptions()) {
            return [];
        }

        return collect($this->options)->pluck('label', 'value')->toArray();
    }
}
