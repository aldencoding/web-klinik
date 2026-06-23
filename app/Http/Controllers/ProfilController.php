<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    //index
    public function index($userId)
    {
        $userProfile = User::findOrFail($userId); // Ubah nama variabelnya di sini
        return view('profil.index', compact('userProfile')); // Cukup panggil string namanya
    }
}
