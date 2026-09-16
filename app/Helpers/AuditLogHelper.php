<?php

namespace App\Helpers;

use App\Models\AuditLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class AuditLogHelper
{
    /**
     * Catat satu baris audit log buat perubahan sebuah model.
     *
     * PENTING soal urutan pemanggilan: karena Eloquent nge-sync
     * getOriginal() ke nilai BARU begitu save()/update() sukses,
     * $oldValues harus diambil (mis. $model->only([...])) SEBELUM
     * update() dipanggil, baru $newValues diambil SESUDAHNYA. Contoh:
     *
     *   $oldValues = $tarif->only(['tarif_per_kwh']);
     *   $tarif->update(['tarif_per_kwh' => $baru]);
     *   AuditLogHelper::record($tarif, 'updated', $user, $oldValues, $tarif->only(['tarif_per_kwh']));
     */
    public static function record(
        Model $model,
        string $action,
        ?Authenticatable $user = null,
        array $oldValues = [],
        array $newValues = [],
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $user?->id,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ]);
    }
}