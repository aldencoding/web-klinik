<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pasien;
use App\Models\Kunjungan;
use App\Models\RekamMedis;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str as SupportStr;
use RealRashid\SweetAlert\Facades\Alert;

class KunjunganController extends Controller
{
    private function generateNomorKunjungan() {}

    //keperluan dari JQUERY
    public function getPasien($id)
    {
        // return response()->json(['data' => $request->all()]);
        // $validated = $request->validate([
        //     'user_id' => 'required'
        // ]);

        if (!$id) {
            return response()->json([
                'status' => 'fail',
                'message' => 'ID Pasien tidak ditemukan'
            ]);
        }

        $result = Pasien::with('user')
            ->where('id', $id)
            ->first();

        if (!$result) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Pasien tidak terdaftar'
            ]);
        }

        return response()->json([
            'status' => 'Succes',
            'message' => 'Pasien terdaftar',
            'data' =>  $result
        ]);
    }
    public function index()
    {
        // $today = Carbon::today()->toDateString();
        // 1. Ambil data kunjungan sekaligus dengan data pasien dan user-nya (Nested Eager Loading)
        $data = Kunjungan::with(['pasien.user', 'dokter'])
            ->whereDate('created_at', Carbon::today())
            ->get();

        // 2. Ubah datanya menjadi array menggunakan map() bawaan Laravel Collections
        // $data = $kunjungan->map(function ($k) {
        //     return [
        //         'no_antrian'  => $k->no_antrian,
        //         'dokter' => $k->dokter->user->name, // Mengambil langsung lewat relasi
        //         'pasien' => $k->pasien->user->name, // Mengambil langsung lewat relasi
        //         'poli'        => $k->poli,
        //         'jaminan'     => $k->jaminan,
        //         'keluhan'     => $k->jaminan,
        //         'status'      => $k->status_antrian,
        //     ];
        // })->toArray();
        // dd($data);

        return view('kunjungan.index', compact('data'));
    }

    public function create()
    {
        $pasiens = Pasien::with(['user'])
            ->whereHas('user', function ($query) {
                $query->where('role', '=', 'pasien');
            })
            ->get();
        return view('kunjungan.create', compact(['pasiens']));
    }

    public function store(Request $request)
    {

        $today = Carbon::today()->toDateString();

        $validated = $request->validate([
            // 'pasien' => 'required',
            // 'pasien_id' => 'required',
            // 'nik' => 'required',
            'pasien_id' => 'required',
            'poli' => 'required',
            'jaminan' => 'required',
            'keluhan' => 'required',
        ]);

        //query nik sudah tidak dipakai, diganti dengan id pasien
        // $pasien = Pasien::where("nik", $validated['nik'])
        //     ->get()->firstOrFail();
        // if (!$pasien) {
        //     Alert::error("error", "NIK tidak terdaftar");
        //     return redirect()
        //         ->back();
        // }
        $lastNumber = Kunjungan::whereDate("created_at", $today)
            ->where("poli", $validated['poli'])
            ->orderByDesc("no_antrian")->first();

        // no antrian
        if (!$lastNumber) {
            if ($validated['poli'] == 'Gigi') {
                $kunjungan = Kunjungan::create([
                    'dokter_id' => 2,
                    'pasien_id' =>  $validated['pasien_id'],
                    'poli' =>  $validated['poli'],
                    'jaminan' =>  $validated['jaminan'],
                    'keluhan' =>  $validated['keluhan'],
                    'no_antrian' => 'G001',
                    'status_antrian' => 'Menunggu',
                ]);

                RekamMedis::create([
                    'kunjungan_id' => $kunjungan->id,
                    'pasien_id' => $kunjungan->pasien_id,
                    'dokter_id' => $kunjungan->dokter_id,
                    'status' => 'menunggu'

                ]);
            } elseif ($validated['poli'] == 'Umum') {
                $kunjungan = Kunjungan::create([
                    'dokter_id' => 1,
                    'pasien_id' =>  $validated['pasien_id'],
                    'poli' =>  $validated['poli'],
                    'jaminan' =>  $validated['jaminan'],
                    'keluhan' =>  $validated['keluhan'],
                    'no_antrian' => 'U001',
                    'status_antrian' => 'Menunggu',
                ]);

                RekamMedis::create([
                    'kunjungan_id' => $kunjungan->id,
                    'pasien_id' => $kunjungan->pasien_id,
                    'dokter_id' => $kunjungan->dokter_id,
                    'status' => 'menunggu'

                ]);
            }
            Alert::success('Sukses', 'Pasien berhasil di Daftarkan');
            return redirect()->back();
        }
        // Asumsi $lastNumber->no_antrian adalah "A001"
        $lastKunjungan = $lastNumber->no_antrian;

        // 1. Ambil prefix (huruf depan), misalnya "A"
        $prefix = SupportStr::substr($lastKunjungan, 0, 1);
        $dokterId = null;
        if ($prefix == "U") {
            $dokterId = 1;
        } elseif ($prefix == "G") {
            $dokterId = 2;
        }

        // 2. Ambil angka setelah huruf, ubah ke integer, lalu tambah 1
        $number = intval(SupportStr::substr($lastKunjungan, 1)) + 1;

        // 3. Gabungkan kembali dengan format angka 3 digit (001, 002, dst)
        $final = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);

        // Hasil: "A002"

        $kunjungan = Kunjungan::create([
            'dokter_id' => $dokterId,
            'pasien_id' =>  $validated['pasien_id'],
            'poli' =>  $validated['poli'],
            'jaminan' =>  $validated['jaminan'],
            'keluhan' =>  $validated['keluhan'],
            'no_antrian' => $final,
            'status_antrian' => 'Menunggu',
        ]);

        RekamMedis::create([
            'kunjungan_id' => $kunjungan->id,
            'pasien_id' => $kunjungan->pasien_id,
            'dokter_id' => $kunjungan->dokter_id,
            'status' => 'menunggu'
        ]);

        Alert::success('Sukses', 'Pasien berhasil di Daftarkan');
        return redirect()->back();
    }

    public function show()
    {
        $antrian = Kunjungan::with('user')->get();

        return view('antrian.show', compact('antrian'));
    }
}
