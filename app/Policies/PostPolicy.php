<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Qualquer usuário autenticado vê a listagem (AUTHOR é filtrado na query).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    /**
     * AUTHOR edita só os próprios; ADMIN/EDITOR editam qualquer post.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->canEditPost($post);
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->canEditPost($post);
    }

    /**
     * Publicar/agendar e mover na fila de revisão exige EDITOR ou ADMIN.
     */
    public function publish(User $user): bool
    {
        return $user->canPublish();
    }
}
