<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Petugas yang bertugas pada kategori ini.
     */
    public function petugas(): HasMany
    {
        return $this->hasMany(User::class)->where('role', User::ROLE_PETUGAS)->where('is_active', true);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
