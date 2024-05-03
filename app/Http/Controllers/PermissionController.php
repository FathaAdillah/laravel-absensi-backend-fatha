<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    //index
    public function index(Request $request)
    {
        // $permissions = Permission::with('user')
        //     ->when($request->input('name'), function ($query, $name) {
        //         $query->whereHas('user', function ($query) use ($name) {
        //             $query->where('name', 'like', '%' . $name . '%');
        //         });
        //     })->orderBy('id', 'desc')->paginate(10);
        $permissions = Permission::select('permissions.*', 'users.name as uname', 'departments.title as departments', 'positions.title as positions')
            ->leftJoin('users', 'permissions.user_id', '=', 'users.id')
            ->leftJoin('employees', 'users.employees_id', '=', 'employees.id')
            ->leftJoin('positions', 'employees.positions_id', '=', 'positions.id')
            ->leftJoin('departments', 'employees.departments_id', '=', 'departments.id')
            ->when($request->input('name'), function ($query, $name) {
                $query->whereHas('user', function ($query) use ($name) {
                    $query->where('name', 'like', '%' . $name . '%');
                });
            })
            ->orderByDesc('permissions.id')
            ->paginate(10);
        return view('pages.permission.index', compact('permissions'));
    }

    //view
    public function show($id)
    {
        // $permission = Permission::with('user')->find($id);
        $permissions = Permission::select('permissions.*', 'users.name as uname','users.phone as phone', 'departments.title as departments', 'positions.title as positions')
            ->leftJoin('users', 'permissions.user_id', '=', 'users.id')
            ->leftJoin('employees', 'users.employees_id', '=', 'employees.id')
            ->leftJoin('positions', 'employees.positions_id', '=', 'positions.id')
            ->leftJoin('departments', 'employees.departments_id', '=', 'departments.id')
            ->find($id);
        return view('pages.permission.show', compact('permissions'));
    }

    //edit
    public function edit($id)
    {
        $permission = Permission::find($id);
        return view('pages.permission.edit', compact('permission'));
    }

    //update
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        $permission->is_approved = $request->is_approved;
        $permission->save();
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully');
    }
}
