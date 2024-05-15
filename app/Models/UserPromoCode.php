<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserPromoCode extends Pivot
{
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
