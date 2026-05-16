<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PasienController extends Controller
{
    public function index()
    {
        $pasien = Pasien::with('user')->get();
        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('pasien.index', compact('pasien'));
    }
    public function create()
    {
        return view('pasien.create');
    }

    public function store(Request $request, Pasien $pasien)
    {
        // dd($request->all());
        $validated = $request->validate([
            'nik' => 'required|digits:16|unique:pasiens,nik',
            'no_bpjs' => 'required|digits:13|unique:pasiens,no_bpjs',
            'nama_pasien' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:pria,wanita',
            'no_telepon' => 'nullable|regex:/^[0-9]+$/|min:10|max:15',
            'alamat' => 'required|string|max:255',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'nama_pasien.required' => 'Kolom Nama Wajib diisi'
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['nama_pasien'],
                'role' => 'pasien'
            ]);

            $pasien->create([
                "nik" => $validated['nik'],
                "user_id" => $user->id,
                "no_bpjs" => $validated['no_bpjs'],
                "jenis_kelamin" => $validated['jenis_kelamin'],
                "tanggal_lahir" => $validated['tanggal_lahir'],
                "no_telepon" => $validated['no_telepon'],
                "alamat" => $validated['alamat']
            ]);

            DB::commit();
            Alert::success('Sukses', 'Data Pasien Berhasil Ditambahkan');

            return redirect()->route('pasien.index');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function show($idPasien)
    {
        return 'Saya Tidak mengerti untuk apa fungsi show 😢';
    }

    public function update(Request $request, Pasien $pasien)
    {
        // dd($request->all());
        $user = User::find($pasien->user_id);
        // return redirect()->route('pasien.index');
        $validated = $request->validate([
            //nik
            //no_bpjs
            "nama_pasien" => 'required|string',
            "no_bpjs" => 'required|string',
            "jenis_kelamin" => 'required|in:pria,wanita',
            "tanggal_lahir" => 'required|date|before:tomorrow',
            "no_telepon" => 'required|numeric',
            "alamat" => 'required|string',
        ]);

        // Update Data User dan Pasien
        try {
            DB::beginTransaction();
            //User
            $user->update([
                'name' => $validated['nama_pasien']
            ]);
            // Pasien
            $pasien->update([
                "nik" => $request->nik,
                "no_bpjs" => $request->no_bpjs,
                "jenis_kelamin" => $validated['jenis_kelamin'],
                "tanggal_lahir" => $validated['tanggal_lahir'],
                "no_telepon" => $validated['no_telepon'],
                "alamat" => $validated['alamat']
            ]);

            DB::commit();
            Alert::success('Sukses', 'Data Pasien Berhasil Diupdate');
            return redirect()
                ->route('pasien.index');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function destroy(Pasien $pasien)
    {
        $pasien->delete();
        Alert::success('Sukses', 'Data Pasien Berhasil Dihapus');
        return redirect()->back();
    }

    public function edit(String $idPasien)
    {
        $pasien = Pasien::find($idPasien);
        $userId = $pasien->user_id;
        $nama_pasien = User::find($userId)->name;
        // dd($nama_pasien);
        return view('pasien.edit', compact('pasien', 'nama_pasien'));
    }
}
