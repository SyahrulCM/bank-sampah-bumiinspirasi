<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function role(){
        return view('layout.role');
    }

    public function getRole(){
        $data = Role::all();
        return view('layout.role', [
            'data' => $data,
        ]);
    }

    public function inputRole(Request $request){

        $request->validate([
            'id_role' => 'nullable',
            'nama_role' => 'required|string|max:255',
        ]);

        $input = [
            'id_role' => $request->id_role ,
            'nama_role' => $request->nama_role,
        ];
        Role::create($input);
        
        return redirect()->back()->with('sukses', 'Data Role berhasil disimpan.');
    }
}
