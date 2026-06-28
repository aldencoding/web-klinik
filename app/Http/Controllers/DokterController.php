<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Kunjungan;
use App\Models\Poli;
use App\Models\Pasien;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class DokterController extends Controller
{
    public function getJadwal()
    {
        $jadwal = JadwalDokter::with('dokter.user')->get();
        return view('dokter.jadwal', compact('jadwal'));
    }
    public function getKunjungan()
    {
        $today = Carbon::today()->toDateString();
        $kunjungans = Kunjungan::with(['pasien.user'])
            ->where('status_antrian', "menunggu")
            ->where('dokter_id', auth()->user()->dokter->id)
            ->whereDate('created_at', $today)
            ->get();
        return view('dokter.kunjungan', compact('kunjungans'));
    }
    public function getRekamMedisPasien(Request $request)
    {
        $antrian = Pasien::with(['user'])
            ->where('user_id', $request->id)
            ->get();
        return view('dokter.antrianKunjungan', compact('antrian'));
    }
    public function postRekamMedisPasien(Request $request)
    {
        $antrian = Kunjungan::find($request->id);
        $antrian->update([
            'status_antrian' => 'Selesai',
            'posisi_antrian' => 'Selesai'
        ]);
        Alert::success('Sukses', 'Data Berhasil Dikirim');
        return redirect()->back();
    }
    public function index()
    {
        $dokter = Dokter::with(['user'])->get();
        $poli = Poli::get();
        // ini adalah fungsi dari sweet alert
        // dd($dokter);
        $title = 'Hapus data Dokter!';
        $text = 'Apakah anda yakin?';
        confirmDelete($title, $text);
        return view('dokter.index', compact(['dokter', 'poli']));
    }
    public function create()
    {
        $poli = Poli::get();
        return view('dokter.create', compact(['poli']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokter' => 'required|string|max:255',
            'email' => 'required',
            'password' => 'required',
            'poli' => 'required|exists:poli,id',
            'jenis_kelamin' => 'required|in:pria,wanita',
            'no_telepon' => 'required|regex:/^[0-9]+$/|min:10|max:15',
            'alamat' => 'required|string|max:255',
        ]);

        // Hash Password
        $dokterPassword = Hash::make($request->password);
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['nama_dokter'],
                'email' => $validated['email'],
                'password' => $dokterPassword,
                'role' => 'dokter'
            ]);

            Dokter::create([
                'user_id' => $user->id,
                'poli_id' => $validated['poli'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'no_telepon' => $validated['no_telepon'],
                'alamat' => $validated['alamat'],
            ]);

            DB::commit();
            Alert::success('Sukses', 'Data Dokter Berhasil Ditambahkan');

            return redirect()->route('dokter.index');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function show($idPasien)
    {
        return 'TAMPILAN DETAIL DOKTER';
    }

    public function update(Request $request, Dokter $dokter)
    {
        $validated = $request->validate([
            'nama_dokter' => 'required|string|max:255',
            'email' => 'required',
            'jenis_layanan' => 'required|exists:layanans,id',
            'jenis_kelamin' => 'required|in:pria,wanita',
            'no_telepon' => 'nullable|regex:/^[0-9]+$/|min:10|max:15',
            'hari' => 'required|string',
            'jam' => 'required|string',
        ]);
        try {
            DB::transaction(function () use ($validated, $dokter) {

                // update user
                $dokter->user->update([
                    'nama' => $validated['nama_dokter'],
                    'email' => $validated['email'],
                ]);

                // update dokter
                $dokter->update([
                    'layanan_id' => $validated['jenis_layanan'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'no_telepon' => $validated['no_telepon'],
                ]);

                // update / create jadwal BERDASARKAN DOKTER
                $dokter->jadwal_dokter()->updateOrCreate(
                    ['dokter_id' => $dokter->id],
                    [
                        'hari' => $validated['hari'],
                        'jam' => $validated['jam'],
                    ]
                );
            });

            Alert::success('Sukses', 'Data Dokter Berhasil Diupdate');
            return redirect()->route('dokter.index');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
    }

    public function destroy(Dokter $dokter)
    {
        $dokter->delete();
        Alert::success('Sukses', 'Data Dokter Berhasil Dihapus');
        return redirect()->back();
    }

    public function edit(Dokter $dokter)
    {
        $data_dokter = $dokter;
        $layanan = Poli::get();

        return view('dokter.edit', compact('dokter', 'layanan'));
    }
}
