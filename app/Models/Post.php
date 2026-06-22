<?php

namespace App\Models;

use App\Enums\PostStatus;
use App\Support\Html;
use App\Support\Youtube;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'title',
    'slug',
    'excerpt',
    'content',
    'cover_image_url',
    'status',
    'review_note',
    'view_count',
    'pinned',
    'author_id',
    'category_id',
    'published_at',
])]
class Post extends Model
{
    protected function casts(): array
    {
        return [
            'status' => PostStatus::class,
            'pinned' => 'boolean',
            'view_count' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsToMany<Tag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * @return HasMany<Comment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // ---- Escopos / apresentação -------------------------------------------

    /**
     * Apenas notícias publicadas (visíveis ao público).
     *
     * @param  Builder<Post>  $query
     * @return Builder<Post>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PostStatus::PUBLISHED);
    }

    /**
     * Imagem de capa: a explícita, ou a miniatura do 1º vídeo do YouTube no conteúdo.
     */
    public function coverImage(): ?string
    {
        if ($this->cover_image_url) {
            return $this->cover_image_url;
        }

        $id = Youtube::id($this->content);

        return $id ? Youtube::thumbnail($id) : null;
    }

    /**
     * Resumo para cards/SEO: o excerpt informado ou um trecho do conteúdo.
     */
    public function excerptText(int $max = 160): string
    {
        $base = $this->excerpt ?: Html::strip($this->content);

        return Str::limit($base, $max, '…');
    }
}
