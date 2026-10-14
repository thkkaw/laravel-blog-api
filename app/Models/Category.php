<?php

namespace App\Models;

use App\Http\Controllers\Api\BlogController;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];

    public function blogs () {
        return $this->hasMany(Blog::class);
    }
}
