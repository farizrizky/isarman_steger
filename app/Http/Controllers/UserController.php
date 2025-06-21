<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function login(){
        if(Auth::check()){
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function authentication(Request $request){
        $formRequest = new UserRequest('user_login');
        
        $username = $request->username;
        $password = $request->password;

        if(empty($username) || empty($password)){
            $sweetalert = [
                'state' => 'error',
                'title' => 'Login Gagal',
                'message' => 'Username dan Password tidak boleh kosong'
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $user = [
            'name' => $request->username,
            'password' => $request->password,
            'is_active' => 1
        ];

        if (Auth::attempt($user)){
            return redirect()->intended('dashboard');
        }else{
            $sweetalert = [
                'state' => 'error',
                'title' => 'Login Gagal',
                'message' => 'Username dan Password Salah'
            ];
            return back()->with('sweetalert', $sweetalert);
        }
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function listUser(){
        $user = User::with('roles')->get();
        $data = [
            'user' => $user,
        ];
        return view('user.user-list', $data);
    }

    public function createFormUser(){
        $data = [
            'role' => Role::get()
        ];
        return view('user.user-create-form', $data);
    }

    public function createUser(Request $request){
        $usernameExists = User::where('name', $request->name)->exists();
        $emailExists = User::where('email', $request->email)->exists();

        if($usernameExists){
            $sweetalert = [
                'state' => 'error',
                'title' => 'Pembuatan User Gagal',
                'message' => "Username {$request->name} sudah digunakan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        if($emailExists){
            $sweetalert = [
                'state' => 'error',
                'title' => 'Pembuatan User Gagal',
                'message' => "Email {$request->email} sudah digunakan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }

        $ownerExists = User::role('Owner')->exists();
        $direkturExists = User::role('Direktur')->exists();
        
        if($ownerExists && $request->role == 'Owner' || $direkturExists && $request->role == 'Direktur'){
            $sweetalert = [
                'state' => 'error',
                'title' => 'Terjadi Kesalahan',
                'message' => "Direktur sudah ada, hanya boleh satu direktur"
            ];
            return back()->with('sweetalert', $sweetalert);
        }
        
        $user = User::create([
            'name' => $request->name,
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        if($request->role){
            $user->assignRole($request->role);
        }

        $sweetalert = [
            'state' => 'success',
            'title' => 'Pembuatan User Berhasil',
            'message' => "User {$user->name} berhasil dibuat"
        ];
        return redirect('/user')->with('sweetalert', $sweetalert);
    }

    public function updateFormUser($id){
        $user = User::find($id);
        if(is_null($user)){
            $sweetalert = [
                'state' => 'error',
                'title' => 'Data Tidak Ditemukan',
                'message' => "User dengan ID {$id} tidak ditemukan"
            ];
            return redirect('/user')->with('sweetalert', $sweetalert);
        }
        $data = [
            'user' => $user,
            'role' => Role::get()
        ];
        return view('user.user-update-form', $data);
    }

    public function updateUser(Request $request, $id){
        $user = User::find($id);
        if(is_null($user)){
            $sweetalert = [
                'state' => 'error',
                'title' => 'Data Tidak Ditemukan',
                'message' => "User dengan ID {$id} tidak ditemukan"
            ];
            return redirect('/user')->with('sweetalert', $sweetalert);
        }
        
       if ($request->role == 'Owner') {
            $existingOwner = User::role('Owner')->where('id', '!=', $id)->first();
            if ($existingOwner) {
                return back()->with('sweetalert', [
                    'state' => 'error',
                    'title' => 'Terjadi Kesalahan',
                    'message' => "Owner sudah ada, hanya boleh satu owner"
                ]);
            }
        }

        if ($request->role == 'Direktur') {
            $existingDirektur = User::role('Direktur')->where('id', '!=', $id)->first();
            if ($existingDirektur) {
                return back()->with('sweetalert', [
                    'state' => 'error',
                    'title' => 'Terjadi Kesalahan',
                    'message' => "Direktur sudah ada, hanya boleh satu direktur"
                ]);
            }
        }

        $usernameExists = User::where('name', $request->name)->where('id', '!=', $id)->exists();
        $emailExists = User::where('email', $request->email)->where('id', '!=', $id)->exists();

        if($user->name != $request->name){
             if($usernameExists){
                $sweetalert = [
                    'state' => 'error',
                    'title' => 'Update User Gagal',
                    'message' => "Username {$request->name} sudah digunakan"
                ];
                return back()->with('sweetalert', $sweetalert);
            }
        }

        if($emailExists){
            $sweetalert = [
                'state' => 'error',
                'title' => 'Update User Gagal',
                'message' => "Email {$request->email} sudah digunakan"
            ];
            return back()->with('sweetalert', $sweetalert);
        }
        $dataUser = [
            'name' => $request->name,
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => $request->is_active
        ];

        if(!empty($request->password)){
            $dataUser['password'] = bcrypt($request->password);
        };   

        $user->update($dataUser);

        if($request->role){
            $user->syncRoles($request->role);
        }else{
            $user->syncRoles([]);
        }

        $sweetalert = [
            'state' => 'success',
            'title' => 'Berhasil',
            'message' => "User {$user->name} berhasil diubah"
        ];
        return redirect('/user')->with('sweetalert', $sweetalert);
    }

    public function deleteUser($id){
        $user = User::find($id);
        if(is_null($user)){
            $sweetalert = [
                'state' => 'error',
                'title' => 'Data Tidak Ditemukan',
                'message' => "User dengan ID {$id} tidak ditemukan"
            ];
            return redirect('/user')->with('sweetalert', $sweetalert);
        }

        $user->delete();

        $sweetalert = [
            'state' => 'success',
            'title' => 'Berhasil',
            'message' => "User {$user->name} berhasil dihapus"
        ];
        return redirect('/user')->with('sweetalert', $sweetalert);
    }

    
}
