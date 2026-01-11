<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;

class Outlet extends Model
{
    use HasFactory;

    protected $table = 'Outlets';
    protected $primaryKey = 'OutletID';

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    protected $fillable = [
        'OutletCode',
        'OutletName',
        'IPAddress',
        'City',
        'District',
        'OpeningDate',
        'ClosingDate',
        'Status',
        'DatabaseName',
        'DatabaseUser',
        'DatabasePassword',
        'CreatedAt',
        'CreatedBy',
        'UpdatedAt',
        'UpdatedBy',
        'IsDeleted'
    ];

    protected $casts = [
        'OpeningDate' => 'date',
        'ClosingDate' => 'date',
        'IsDeleted' => 'boolean',
    ];

    protected $hidden = [
        'DatabasePassword',
        'IsDeleted',
    ];

    protected static function booted()
    {
        static::addGlobalScope('notDeleted', function (Builder $builder) {
            $builder->where('IsDeleted', 0);
        });
    }

    public function setDatabasePasswordAttribute($value)
    {
        if ($value && !empty($value)) {
            if (!str_starts_with($value, 'eyJ')) {
                $this->attributes['DatabasePassword'] = Crypt::encryptString($value);
            } else {
                $this->attributes['DatabasePassword'] = $value;
            }
        }
    }

    public function getDecryptedPassword(): ?string
    {
        $password = $this->attributes['DatabasePassword'] ?? null;
        if ($password) {
            try {
                return Crypt::decryptString($password);
            } catch (\Exception $e) {
                return $password;
            }
        }
        return null;
    }

    public function scopeActive($query)
    {
        return $query->where('Status', 'Active')->where('IsDeleted', 0);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('Status', $status);
    }

    public function scopeByCity(Builder $query, string $city): Builder
    {
        return $query->where('City', $city);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('OutletCode', 'LIKE', "%{$term}%")
                ->orWhere('OutletName', 'LIKE', "%{$term}%")
                ->orWhere('City', 'LIKE', "%{$term}%")
                ->orWhere('District', 'LIKE', "%{$term}%")
                ->orWhere('IPAddress', 'LIKE', "%{$term}%");
        });
    }

    public function hasCredentials(): bool
    {
        return !empty($this->DatabaseName) && !empty($this->DatabaseUser);
    }

    public function isActive(): bool
    {
        return $this->Status === 'Active';
    }
}
