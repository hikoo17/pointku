<?php

namespace App\Services;

use App\Models\SuratPanggilan;
use App\Models\SuratPanggilanHistory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SuratPanggilanWorkflowService
{
    private const TRANSITIONS = [
        'draft' => ['diajukan', 'dibatalkan'],
        'diajukan' => ['disetujui', 'perlu_revisi', 'dibatalkan'],
        'perlu_revisi' => ['diajukan', 'dibatalkan'],
        'disetujui' => ['dicetak', 'dibatalkan'],
        'dicetak' => ['dikirim'],
        'dikirim' => ['selesai'],
        'selesai' => [], 'dibatalkan' => [],
    ];

    public function transition(SuratPanggilan $surat, string $next, User $user, ?string $catatan = null): SuratPanggilan
    {
        if (! in_array($next, self::TRANSITIONS[$surat->status] ?? [], true)) {
            throw ValidationException::withMessages(['status' => "Transisi {$surat->status} ke {$next} tidak valid."]);
        }

        if ($next === 'diajukan' && ! $user->hasRole('Guru BK')) {
            abort(403);
        }
        if (in_array($next, ['disetujui', 'perlu_revisi'], true) && ! $user->hasRole('Kesiswaan')) {
            abort(403);
        }
        if (in_array($next, ['dicetak', 'dikirim', 'selesai'], true) && ! $user->hasRole('Kesiswaan')) {
            abort(403);
        }

        return DB::transaction(function () use ($surat, $next, $user, $catatan) {
            $previous = $surat->status;
            $data = ['status' => $next, 'catatan_revisi' => $next === 'perlu_revisi' ? $catatan : $surat->catatan_revisi];

            if ($next === 'diajukan') $data += ['diajukan_oleh' => $user->id, 'diajukan_pada' => now()];
            if ($next === 'disetujui') $data += ['disetujui_oleh' => $user->id, 'disetujui_pada' => now(), 'nomor_surat' => $this->number($surat)];
            if ($next === 'dicetak') $data['dicetak_pada'] = now();
            if ($next === 'dikirim') $data['dikirim_pada'] = now();
            if ($next === 'selesai') $data['selesai_pada'] = now();

            $surat->update($data);
            SuratPanggilanHistory::create([
                'surat_panggilan_id' => $surat->id, 'user_id' => $user->id,
                'status_sebelumnya' => $previous, 'status_baru' => $next, 'catatan' => $catatan,
            ]);
            return $surat->fresh(['siswa.user', 'aturanThreshold', 'histories.user']);
        });
    }

    private function number(SuratPanggilan $surat): string
    {
        return sprintf('SP/%s/%04d/%s', now()->format('Y'), $surat->id, now()->format('m'));
    }
}
