<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'profile_image',
        'additional_file',
        'is_merged',
        'merged_into'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_merged' => 'boolean',
        'phone' => 'string',
    ];

    /**
     * Get the custom fields for the contact.
     */
    public function customFields(): HasMany
    {
        return $this->hasMany(ContactCustomField::class);
    }

    /**
     * Get the contacts that were merged into this one.
     */
    public function mergedContacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'merged_into');
    }

    /**
     * Get the master contact this one was merged into.
     */
    public function masterContact()
    {
        return $this->belongsTo(Contact::class, 'merged_into');
    }

    /**
     * Scope a query to only include non-merged contacts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_merged', false);
    }

    /**
     * Scope a query to only include merged contacts.
     */
    public function scopeMerged($query)
    {
        return $query->where('is_merged', true);
    }

    /**
     * Get the URL for the profile image.
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        return asset('images/default-profile.png');
    }

    /**
     * Get the URL for the additional file.
     */
    public function getAdditionalFileUrlAttribute()
    {
        if ($this->additional_file) {
            return asset('storage/' . $this->additional_file);
        }
        return null;
    }
}
