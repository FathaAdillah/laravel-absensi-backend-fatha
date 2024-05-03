<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmpController extends Controller
{
    public function all()
    {
        $employees = Employee::select(
            'employees.id AS id',
            'employees.name AS name',
            'employees.birthplace',
            'employees.birthdate',
            'jabatans.title AS jabatan',
            'positions.title AS posisi'
        )
        ->join('jabatans', 'employees.jabatans_id', '=', 'jabatans.id')
        ->join('positions', 'employees.positions_id', '=', 'positions.id')
        ->get();
        return response()->json($employees);
    }
}
