<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\RekamMedis;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class RekamMedisController extends Controller
{
    public function index()
    {
        // ambil data rekam Medis
        $rekamMedis = RekamMedis::with(['pasien', 'dokter'])
            ->get([
                'id',
                'kunjungan_id',
                'pasien_id',
                'keluhan',
                'riwayat_penyakit',
                'status'
            ]);
        // dd($rekamMedis[0]->dokter);

        return view('rekamMedis.index', compact('rekamMedis'));
    }

    public function create($id)
    {
        // Note: harus ambil data sesuai model
        // ambil pasien id
        // parameter $id adalah dari rekamMedis->pasien->user_id pasien user_id
        $pasien = Pasien::findOrFail($id);
        $pasienId = $pasien->id;
        $pasienName = $pasien->user->name;

        $kunjungan = Kunjungan::where('pasien_id', $pasien->id)->first();
        $kunjunganId = $kunjungan->id;
        $dokterId = $kunjungan->dokter_id;
        // dd([
        //     'pasienName' => $pasienName,
        //     'pasienId' => $pasienId,
        //     'kunjunganId' => $kunjunganId,
        //     'dokterId' => $dokterId
        // ]);
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
