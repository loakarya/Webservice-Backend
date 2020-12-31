<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "faqs";
    
    public function user() {
        return $this->belongsTo('App\User');
    }
}
