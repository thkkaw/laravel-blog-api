<?php

namespace App\Models;

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Blog extends Model
{
    protected $fillable = ['category_id', 'title', 'body'];

    public function category () {
        return $this->belongsTo(Category::class);
    }
}
