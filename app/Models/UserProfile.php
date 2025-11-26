<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'school_name',
        'division',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDivisionLabel()
    {
        $labels = [
            'akuntansi' => 'Akuntansi',
            'sekretariat' => 'Sekretariat',
            'anggaran' => 'Anggaran',
            'keuangan' => 'Keuangan',
            'perbendaharaan' => 'Perbendaharaan',
        ];
        return $labels[$this->division] ?? $this->division;
    }
}
