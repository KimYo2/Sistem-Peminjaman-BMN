<?php

namespace App\Http\Controllers\Concerns;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsAudit
{
    protected function logAudit(string $action, string $entity, ?int $entityId = null, array $meta = []): void
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'entity' => $entity,
                'entity_id' => $entityId,
                'meta' => $meta,
            ]);
        } catch (\Throwable $e) {
            // Avoid breaking user flow on audit failure
        }
    }
}
