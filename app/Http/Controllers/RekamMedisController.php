<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class RekamMedisController extends Controller
{
    public function index()
    {
        // ambil data rekam Medis
        $data = Kunjungan::with(['pasien.user', 'dokter'])
            ->whereDate('created_at', Carbon::today())
            ->get();

        return view('rekamMedis.index', compact('data'));
    }

    public function create($id)
    {
        // Note: harus ambil data sesuai model
        // ambil pasien id
        // parameter $id adalah dari rekamMedis->pasien->user_id pasien user_id
        $pasien = Pasien::findOrFail($id);
        $pasienId = $pasien->id;
        $pasienName = $pasien->user->name;
        // Ambil data kunjungan pertama
        $kunjungan = Kunjungan::where('pasien_id', $pasien->id)->first();

        // Validasi apakah data kunjungan ditemukan
        if ($kunjungan) {
            $kunjunganId = $kunjungan->id;
            $dokterId = $kunjungan->dokter_id;
            $kunjungan->save();
        } else {
            // Tangani jika kunjungan tidak ditemukan
            return redirect()->back()->with('error', 'pasien belum melakukan Kujungan!');
        }

        // Update status menjadi dipanggil dokter
        $kunjungan->status_antrian = 'dipanggil';
        return view('rekamMedis.create', compact(['pasienName', 'pasienId', 'dokterId', 'kunjunganId']));
    }

    public function edit($id)
    {
        $rekamMedisById = RekamMedis::find($id);
        return view('rekamMedis.edit', compact('rekamMedisById'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'kunjungan_id' => 'required|string',
            'pasien_id' => 'required|string',
            'dokter_id' => 'required|string',
            'keluhan' => 'required|string',
            'riwayat_penyakit' => 'required|string',
            // 'diagnosa' => 'required|string',
            'resep' => 'required|string',
            // 'status' => 'required|string',
        ]);
        try {
            DB::transaction(function () use ($validated) {
                $rekamMedis = RekamMedis::where('kunjungan_id', $validated['kunjungan_id'])->first();
                if (!$rekamMedis || $rekamMedis == null) {
                    throw new Exception('Data Kunjungan tidak ditemukan');
                }
                $rekamMedis->update([
                    'keluhan' => $validated['keluhan'],
                    'riwayat_penyakit' => $validated['riwayat_penyakit'],
                    // 'diagnosa' => $validated['diagnosa'],
                    'resep' => $validated['resep'],
                    'status' => 'selesai'
                ]);
            });
            Alert::success('Sukses', 'Data Rekam Medis Berhasil ditambahkan');
            return redirect()->route('rekamMedis.index');
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kunjungan_id' => 'required|string',
            'pasien_id' => 'required|string',
            'dokter_id' => 'required|string',
            'keluhan' => 'required|string',
            'riwayat_penyakit' => 'required|string',
            // 'diagnosa' => 'required|string',
            'resep' => 'required|string',
            // 'status' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($validated, $id) {
                $rekamMedis = RekamMedis::find($id);
                $rekamMedis->update([
                    'kunjungan_id' => $validated['kunjungan_id'],
                    'pasien_id' => $validated['pasien_id'],
                    'dokter_id' => $validated['dokter_id'],
                    'keluhan' => $validated['keluhan'],
                    'riwayat_penyakit' => $validated['riwayat_penyakit'],
                    'diagnosa' => $validated['diagnosa'],
                    'resep' => $validated['resep'],
                    'status' => $validated['status']
                ]);
            });

            Alert::success('Sukses', 'Data Rekam Medis Berhasil diupdate');
            return redirect()->route('rekamMedis.index');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    public function destroy($id)
    {
        RekamMedis::delete($id);
        Alert::succes('Sukses', 'Data berhasil dihapus');
        return redirect()->back();
    }
}
