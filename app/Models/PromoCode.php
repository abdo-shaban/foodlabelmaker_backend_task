<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'user_ids' => 'array'
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_promo_code')->withTimestamps();
    }
}
