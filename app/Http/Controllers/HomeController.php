<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard(){
        if (!session()->has('login')) {
            $token = Cookie::get('remember_token');
            if ($token) {
                $petugas = Petugas::where('remember_token', $token)->first();
                if ($petugas) {
                    session(['login' => true, 'id_petugas' => $petugas->id_petugas]);
                } else {
                    return redirect('/login');
                }
            } else {
                return redirect('/login');
            }
        }

        return view('dashboard'); // atau halaman utamamu
    }

}
