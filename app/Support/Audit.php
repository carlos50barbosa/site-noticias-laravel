<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Audit
{
    /**
     * Registra uma ação administrativa (fire-and-forget: nunca interrompe a ação).
     */
    public static function log(string $action, ?string $entity = null, int|string|null $entityId = null): void
    {
        try {
            $user = Auth::user();

            AuditLog::create([
                'user_id' => $user?->id,
                'user_email' => $user?->email,
                'action' => $action,
                'entity' => $entity,
                'entity_id' => $entityId !== null ? (string) $entityId : null,
            ]);
        } catch (Throwable) {
            // silencioso por design
        }
    }
}
