<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\RekamMedis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str as SupportStr;

class KunjunganController extends Controller
{
    public function kunjunganMandiri()
    {
        $pasiens = Pasien::with(['user'])
            ->whereHas('user', function ($query) {
                $query->where('role', '=', 'pasien');
            })
            ->get();

        return view('kunjungan.create', compact(['pasiens']));
    }
    public function showQueue(Kunjungan $kunjungan)
    {
        $kunjungan->load([
            'pasien.user',
            'dokter'
        ]);

        return view('kunjungan.show-queue', compact('kunjungan'));
    }
    // keperluan dari JQUERY
    public function getPasien($id)
    {
        // return response()->json(['data' => $request->all()]);
        // $validated = $request->validate([
        //     'user_id' => 'required'
        // ]);

        if (! $id) {
            return response()->json([
                'status' => 'fail',
                'message' => 'ID Pasien tidak ditemukan',
            ]);
        }

        $result = Pasien::with('user')
            ->where('id', $id)
            ->first();

        if (! $result) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Pasien tidak terdaftar',
            ]);
        }

        return response()->json([
            'status' => 'Succes',
            'message' => 'Pasien terdaftar',
            'data' => $result,
        ]);
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'periode' => 'nullable|in:hari_ini,mingguan,bulanan,semua_tanggal',
            'poli' => 'nullable|string|max:255',
            'jaminan' => 'nullable|string|max:255',
            'status' => 'nullable|in:menunggu,dipanggil,menunggu_obat,selesai',
        ]);

        $periode = $validated['periode'] ?? 'hari_ini';
        $today = Carbon::today();

        $query = Kunjungan::with(['pasien.user', 'dokter.user']);

        if ($periode === 'mingguan') {
            $query->whereBetween('created_at', [
                $today->copy()->startOfWeek(Carbon::MONDAY),
                $today->copy()->endOfWeek(Carbon::SUNDAY),
            ]);
        } elseif ($periode === 'bulanan') {
            $query->whereBetween('created_at', [
                $today->copy()->startOfMonth(),
                $today->copy()->endOfMonth(),
            ]);
        } else {
            $query->whereDate('created_at', $today);
        }

        $query
            ->when($validated['poli'] ?? null, function ($query, $poli) {
                $query->where('poli', $poli);
            })
            ->when($validated['jaminan'] ?? null, function ($query, $jaminan) {
                $query->where('jaminan', $jaminan);
            })
            ->when($validated['status'] ?? null, function ($query, $status) {
                $query->where('status_antrian', $status);
            });

        $data = $query->latest()->get();

        if ($request->ajax()) {
            return view('kunjungan._table', compact('data'));
        }

        $poliOptions = Kunjungan::query()
            ->whereNotNull('poli')
            ->distinct()
            ->orderBy('poli')
            ->pluck('poli');

        $jaminanOptions = Kunjungan::query()
            ->whereNotNull('jaminan')
            ->distinct()
            ->orderBy('jaminan')
            ->pluck('jaminan');

        return view('kunjungan.index', compact(
            'data',
            'periode',
            'poliOptions',
            'jaminanOptions'
        ));
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
        $validated = $request->validate([
            'pasien_id' => 'required|exists:pasiens,id',
            'poli' => 'required|in:Umum,Gigi',
            'jaminan' => 'required|in:Asuransi,Pribadi',
            'keluhan' => 'required|string',
        ]);

        $kunjungan = DB::transaction(function () use ($validated) {
            $prefix = $validated['poli'] === 'Gigi' ? 'G' : 'U';
            $lastNumber = Kunjungan::whereDate('created_at', Carbon::today())
                ->where('poli', $validated['poli'])
                ->lockForUpdate()
                ->orderByDesc('no_antrian')
                ->first();

            $number = $lastNumber
                ? intval(SupportStr::substr($lastNumber->no_antrian, 1)) + 1
                : 1;

            $dokter = Dokter::whereHas('poli', function ($query) use ($validated) {
                $query->where('nama', $validated['poli']);
            })->first();

            $kunjungan = Kunjungan::create([
                'dokter_id' => $dokter?->id ?? ($validated['poli'] === 'Gigi' ? 2 : 1),
                'pasien_id' => $validated['pasien_id'],
                'poli' => $validated['poli'],
                'jaminan' => $validated['jaminan'],
                'keluhan' => $validated['keluhan'],
                'no_antrian' => $prefix . str_pad($number, 3, '0', STR_PAD_LEFT),
                'status_antrian' => 'menunggu',
            ]);

            RekamMedis::create([
                'kunjungan_id' => $kunjungan->id,
                'pasien_id' => $kunjungan->pasien_id,
                'dokter_id' => $kunjungan->dokter_id,
                'status' => 'menunggu',
            ]);

            return $kunjungan;
        });

        $kunjungan->load('pasien.user');

        session()->flash('queue_ticket', [
            'id' => $kunjungan->id,
            'number' => $kunjungan->no_antrian,
            'patient_name' => $kunjungan->pasien?->user?->name ?? '-',
            'clinic' => $kunjungan->poli,
            'schedule' => $kunjungan->created_at
                ->locale('id')
                ->translatedFormat('d F Y, H:i'),
            'qr_url' => route('antrian.show', $kunjungan->id),
        ]);

        return redirect()->route('kunjungan.create');
    }

    public function show()
    {
        $antrian = Kunjungan::with('user')->get();

        return view('antrian.show', compact('antrian'));
    }
}
