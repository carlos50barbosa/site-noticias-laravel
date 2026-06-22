<?php

namespace Tests\Unit;

use App\Enums\Role;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class PermissionsTest extends TestCase
{
    public function test_admin_has_full_permissions(): void
    {
        $role = Role::ADMIN;

        $this->assertTrue($role->isAdmin());
        $this->assertTrue($role->canManageUsers());
        $this->assertTrue($role->canManageCategories());
        $this->assertTrue($role->canPublish());
        $this->assertTrue($role->canManageAllPosts());
    }

    public function test_editor_manages_content_but_not_users(): void
    {
        $role = Role::EDITOR;

        $this->assertFalse($role->isAdmin());
        $this->assertFalse($role->canManageUsers());
        $this->assertTrue($role->canManageCategories());
        $this->assertTrue($role->canPublish());
        $this->assertTrue($role->canManageAllPosts());
    }

    public function test_author_has_no_management_permissions(): void
    {
        $role = Role::AUTHOR;

        $this->assertFalse($role->canManageUsers());
        $this->assertFalse($role->canManageCategories());
        $this->assertFalse($role->canPublish());
        $this->assertFalse($role->canManageAllPosts());
    }

    public function test_can_edit_post_respects_ownership(): void
    {
        $author = new User();
        $author->id = 5;
        $author->role = Role::AUTHOR;

        $editor = new User();
        $editor->id = 7;
        $editor->role = Role::EDITOR;

        $own = new Post();
        $own->author_id = 5;

        $other = new Post();
        $other->author_id = 99;

        $this->assertTrue($author->canEditPost($own));
        $this->assertFalse($author->canEditPost($other));
        $this->assertTrue($editor->canEditPost($other));
    }
}
