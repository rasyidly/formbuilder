<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'settings',
        'status',
        'published_at',
        'archived_at',
        'created_by',
    ];

    protected $casts = [
        'settings' => 'array',
        'published_at' => 'datetime',
        'archived_at' => 'datetime',
    ];

    /**
     * Get the user who created this form.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all fields for this form.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class)->orderBy('sequence');
    }

    /**
     * Get all active fields for this form.
     */
    public function activeFields(): HasMany
    {
        return $this->hasMany(FormField::class)->where('is_active', true)->orderBy('sequence');
    }

    /**
     * Get all submissions for this form.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Scope a query to only include published forms.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    /**
     * Scope a query to only include draft forms.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include archived forms.
     */
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * Check if the form is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at !== null;
    }

    /**
     * Check if the form is archived.
     */
    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    /**
     * Publish the form.
     */
    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    /**
     * Archive the form.
     */
    public function archive(): void
    {
        $this->update([
            'archived_at' => now(),
        ]);
    }
}
