<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $hidden = ['intervention'];
    
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function featured() {
        return $this->hasOne('App\Models\FeaturedArticle');
    }
}
