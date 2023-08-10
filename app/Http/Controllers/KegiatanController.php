<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Kegiatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kegiatans = Kegiatan::latest()->get();
        return view('dashboard.kegiatan.index', compact('kegiatans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategories = Kategori::latest()->get();
        return view('dashboard.kegiatan.create', compact('kategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validator
        $validator = Validator::make(
            $request->all(),
            [
                'kode_kegiatan' => 'required',
                'judul_kegiatan' => 'required',
                'kategori_id' => 'required',
                'tahun_kegiatan' => 'required',
                'tanggal_mulai_kegiatan' => 'required',
                'tanggal_akhir_kegiatan' => 'required',
                'total_jam_kegiatan' => 'required',
            ],
            [],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            Kegiatan::create([
                'kode_kegiatan' => $request->kode_kegiatan,
                'judul_kegiatan' => $request->judul_kegiatan,
                'slug' => Str::slug($request->judul_kegiatan, '-'),
                'kategori_id' => $request->kategori_id,
                'tahun_kegiatan' => $request->tahun_kegiatan,
                'tanggal_mulai_kegiatan' => $request->tanggal_mulai_kegiatan,
                'tanggal_akhir_kegiatan' => $request->tanggal_akhir_kegiatan,
                'total_jam_kegiatan' => $request->total_jam_kegiatan,
            ]);
            return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $request->judul_kegiatan . ' Baru Berhasil Di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $request->judul_kegiatan . ' Baru Gagal Di Tambahkan');
        } finally {
            DB::commit();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kategories = Kategori::latest()->get();
        $kegiatan = Kegiatan::find($id);
        return view('dashboard.kegiatan.edit', compact('kegiatan', 'kategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validator
        $validator = Validator::make(
            $request->all(),
            [
                'kode_kegiatan' => 'required',
                'judul_kegiatan' => 'required',
                'kategori_id' => 'required',
                'tahun_kegiatan' => 'required',
                'tanggal_mulai_kegiatan' => 'required',
                'tanggal_akhir_kegiatan' => 'required',
                'total_jam_kegiatan' => 'required',
            ],
            [],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $kegiatan = Kegiatan::find($id);

            $kegiatan->update([
                'kode_kegiatan' => $request->kode_kegiatan,
                'judul_kegiatan' => $request->judul_kegiatan,
                'slug' => Str::slug($request->judul_kegiatan, '-'),
                'kategori_id' => $request->kategori_id,
                'tahun_kegiatan' => $request->tahun_kegiatan,
                'tanggal_mulai_kegiatan' => $request->tanggal_mulai_kegiatan,
                'tanggal_akhir_kegiatan' => $request->tanggal_akhir_kegiatan,
                'total_jam_kegiatan' => $request->total_jam_kegiatan,
            ]);
            return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $request->judul_kegiatan . ' Berhasil Di Updae');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $request->judul_kegiatan . ' Gagal Di Updae');
        } finally {
            DB::commit();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $kegiatan = Kegiatan::find($id);

            $kegiatan->delete($kegiatan);
            return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $kegiatan->judul_kegiatan . ' Berhasil Di Hapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('kegiatan.index')->with('success', 'Kegiatan ' . $kegiatan->judul_kegiatan . ' Gagal Di Hapus');
        } finally {
            DB::commit();
        }
    }
}
