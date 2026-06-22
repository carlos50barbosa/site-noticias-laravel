<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'user_email', 'action', 'entity', 'entity_id'])]
class AuditLog extends Model
{
    // Append-only: só created_at (preenchido pelo banco via useCurrent).
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}
