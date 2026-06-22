<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class,
        ];
    }

    /**
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    // ---- Papéis / permissões (espelham App\Enums\Role) ----------------------

    public function isAdmin(): bool
    {
        return $this->role->isAdmin();
    }

    public function canManageUsers(): bool
    {
        return $this->role->canManageUsers();
    }

    public function canManageCategories(): bool
    {
        return $this->role->canManageCategories();
    }

    public function canPublish(): bool
    {
        return $this->role->canPublish();
    }

    public function canManageAllPosts(): bool
    {
        return $this->role->canManageAllPosts();
    }

    /**
     * AUTHOR só edita os próprios posts; ADMIN/EDITOR editam qualquer um.
     */
    public function canEditPost(Post $post): bool
    {
        return $this->canManageAllPosts() || $post->author_id === $this->id;
    }
}
