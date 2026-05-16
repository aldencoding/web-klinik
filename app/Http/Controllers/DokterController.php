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
        $kunjungan = Kunjungan::with(['user'])
            ->where('created_at', $today)
            ->get();
        return view('dokter.kunjungan', compact('kunjungan'));
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
        $dokter = Dokter::with(['user', 'layanan'])->get();
        // ini adalah fungsi dari sweet alert
        $title = 'Hapus data Dokter!';
        $text = 'Apakah anda yakin?';
        confirmDelete($title, $text);
        return view('dokter.index', compact(['dokter']));
    }
    public function create()
    {
        $layanan = Poli::get();
        return view('dokter.create', compact('layanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokter' => 'required|string|max:255',
            'jenis_layanan' => 'required|exists:layanans,id',
            'jenis_kelamin' => 'required|in:pria,wanita',
            'no_telepon' => 'required|regex:/^[0-9]+$/|min:10|max:15',
            'alamat' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'nama' => $validated['nama_dokter'],
                'role' => 'dokter'
            ]);

            Dokter::create([
                'user_id' => $user->id,
                'layanan_id' => $validated['jenis_layanan'],
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
