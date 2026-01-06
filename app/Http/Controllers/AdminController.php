<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil ditambahkan!');
    }

    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalTransactions = Transaction::count();
        $totalVolume = Transaction::sum('amount');
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalUsers', 'totalTransactions', 'totalVolume', 'recentUsers'));
    }

    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroyUser($id)
    {
        if (Auth::id() == $id) {
            return redirect()->back()->with('error', 'Dilarang menghapus akun sendiri! Silakan minta Admin lain jika ingin resign.');
        }
        
        $user = User::findOrFail($id);
        if ($user->avatar && file_exists(storage_path('app/public/' . $user->avatar))) {
            unlink(storage_path('app/public/' . $user->avatar));
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus dari sistem.');
    }
}
