<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ArticleMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
    ];

    public function article():HasOne{
        return $this->hasOne(Article::class);
    }
}
