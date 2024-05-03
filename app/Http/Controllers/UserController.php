<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //index
    public function index()
    {
        //search by name, pagination 10
        $users = User::select('users.id', 'users.name', 'users.email', 'users.phone', 'positions.title', 'users.created_at')
            ->leftJoin('employees', 'employees.id', '=', 'users.employees_id')
            ->leftJoin('positions', 'positions.id', '=', 'employees.positions_id')
            ->where('users.name', 'like', '%' . request('name') . '%')
            ->orderByDesc('users.id')
            ->paginate(10);
        // dd($users);
        return view('pages.users.index', compact('users'));
    }

    //create
    public function create()
    {
        return view('pages.users.create');
    }

    //store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'employees_id' => $request->employee,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            // 'position' => $request->position,
            // 'department' => $request->department,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    //edit
    public function edit($id)
    {
        $user = User::select('users.id', 'users.name', 'users.email', 'users.phone', 'positions.title', 'users.created_at', 'employees.name as employee_name', 'users.role')
            ->leftJoin('employees', 'employees.id', '=', 'users.employees_id')
            ->leftJoin('positions', 'positions.id', '=', 'employees.positions_id')
            ->where('users.id', $id)->first();
        $employees = Employee::all();

        return view('pages.users.edit', compact('user','employees'));
    }

    //update
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'employee_id' => $request->employee,
            'role' => $request->role,
        ]);

        //if password filled
        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    //destroy
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
