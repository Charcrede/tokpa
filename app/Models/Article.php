<?php

namespace App\Models;

use App\Models\ArticleMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'category',
        'boutique_id',
        'article_media_id',
        'commande',
        'images',
    ];

    public function articleMedia() :BelongsTo{
        return $this->belongsTo(ArticleMedia::class);
    }
    public function boutique() :BelongsTo{
        return $this->belongsTo(Boutique::class);
    }
}
