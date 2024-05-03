<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Positions;
use App\Models\Jabatans;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'birthplace',
        'birthdate',
        'positions_id',
        'jabatans_id',
    ];

    public function position()
    {
        return $this->belongsTo(Positions::class, 'positions_id');
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatans::class, 'jabatans_id');
    }
}
