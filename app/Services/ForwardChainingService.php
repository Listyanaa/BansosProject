<?php
namespace App\Services;

use App\Models\Aturan;
use App\Models\Syarat;
use App\Models\HasilDiagnosa;

class ForwardChainingService
{
    private function loadRules(): array
    {
        return Aturan::orderBy('kode_aturan')->get()->map(function($r){
            return [
                'id'     => $r->id,
                'kode'   => $r->kode_aturan,
                'syarat' => $r->kondisiArray(), 
                'hasil'  => $r->hasil,          
            ];
        })->all();
    }

    public function questionText(string $kode): string
    {
        return Syarat::where('kode',$kode)->value('teks') ?: $kode;
    }

    public function infer(array $facts): ?array
    {
        foreach ($this->loadRules() as $r) {
            $ok = true;
            foreach ($r['syarat'] as $g) {
                if (!isset($facts[$g]) || $facts[$g] !== true) { $ok=false; break; }
            }
            if ($ok) return ['hasil'=>$r['hasil'], 'rule'=>$r['kode']];
        }
        return null;
    }

    public function nextQuestion(array $facts): ?string
    {
        $candidates = [];
        foreach ($this->loadRules() as $r) {
            $gugur = false;
            foreach ($r['syarat'] as $g) {
                if (isset($facts[$g]) && $facts[$g] === false) { $gugur = true; break; }
            }
            if (!$gugur) $candidates[] = $r;
        }

        if (!$candidates) {
           
            $unknown = Syarat::where('aktif',true)->pluck('kode')
                ->filter(fn($k)=>!array_key_exists($k,$facts))->first();
            return $unknown ?: null;
        }

        $freq = [];
        foreach ($candidates as $r) {
            foreach ($r['syarat'] as $g) {
                if (!array_key_exists($g, $facts)) $freq[$g] = ($freq[$g] ?? 0) + 1;
            }
        }
        if (!$freq) return null;

        arsort($freq);
        return array_key_first($freq);
    }

    public function saveHistory(int $penerimaId = 0, string $hasil = '-', array $facts = []): void
    {
        HasilDiagnosa::create([
            'penerima_id' => $penerimaId ?: 0,
            'hasil'       => $hasil,
            'tanggal'     => now(),
            'jejak'       => $facts,
        ]);
    }
}
