<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pasien;
use App\Models\Kunjungan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str as SupportStr;
use RealRashid\SweetAlert\Facades\Alert;

class KunjunganController extends Controller
{
    private function generateNomorKunjungan() {}

    //keperluan dari JQUERY
    public function getNikPasien(Request $request)
    {
        $validated = $request->validate(['nik' => 'required']);
        $result = Pasien::with('user')->where('nik', $validated['nik'])->first();
        if (!$result) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Nik tidak terdaftar'
            ]);
        }

        return response()->json([
            'status' => 'Succes',
            'message' => 'Nik terdaftar',
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
        return view('kunjungan.create');
    }

    public function store(Request $request)
    {
        //property
        // dd($request->all());
        $today = Carbon::today()->toDateString();

        $validated = $request->validate([
            'nik' => 'required',
            // 'pasien' => 'required',
            'pasien_id' => 'required',
            'poli' => 'required',
            'jaminan' => 'required',
            'keluhan' => 'required',
        ]);

        //query
        $pasien = Pasien::where("nik", $validated['nik'])
            ->get()->firstOrFail();
        if (!$pasien) {
            Alert::error("error", "NIK tidak terdaftar");
            return redirect()
                ->back();
        }
        $lastNumber = Kunjungan::whereDate("created_at", $today)
            ->where("poli", $validated['poli'])
            ->orderByDesc("no_antrian")->first();

        // no antrian
        if (!$lastNumber) {
            if ($validated['poli'] == 'Gigi') {
                Kunjungan::create([
                    'dokter_id' => 2,
                    'pasien_id' =>  $validated['pasien_id'],
                    'poli' =>  $validated['poli'],
                    'jaminan' =>  $validated['jaminan'],
                    'keluhan' =>  $validated['keluhan'],
                    'no_antrian' => 'G001',
                    'status_antrian' => 'Menunggu',
                ]);
            } elseif ($validated['poli'] == 'Umum') {
                Kunjungan::create([
                    'dokter_id' => 1,
                    'pasien_id' =>  $validated['pasien_id'],
                    'poli' =>  $validated['poli'],
                    'jaminan' =>  $validated['jaminan'],
                    'keluhan' =>  $validated['keluhan'],
                    'no_antrian' => 'U001',
                    'status_antrian' => 'Menunggu',
                ]);
            }
            Alert::success('Sukses', 'Data Kunjungan Berhasil ditambahkan');
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

        Kunjungan::create([
            'dokter_id' => $dokterId,
            'pasien_id' =>  $validated['pasien_id'],
            'poli' =>  $validated['poli'],
            'jaminan' =>  $validated['jaminan'],
            'keluhan' =>  $validated['keluhan'],
            'no_antrian' => $final,
            'status_antrian' => 'Menunggu',
        ]);

        Alert::success('Sukses', 'Data Kunjungan Berhasil Ditambahkan');
        return redirect()->back();
    }

    public function show()
    {
        $antrian = Kunjungan::with('user')->get();

        return view('antrian.show', compact('antrian'));
    }
}
