<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GardenerPortfolioImage extends Model
{
    protected $fillable = ['user_id', 'image_url'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
