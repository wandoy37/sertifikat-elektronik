<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $siswas = Siswa::all();
        return view('dashboard.siswa.index', compact('siswas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.siswa.create');
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
                'nama' => 'required',
                'nomor_induk_siswa' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'jurusan' => 'required',
                'sekolah' => 'required',
            ],
            [
                'nama.required' => 'nama wajib diisi.',
                'nomor_induk_siswa.required' => 'nomor_induk_siswa wajib diisi.',
                'tempat_lahir.required' => 'tempat_lahir wajib diisi.',
                'tanggal_lahir.required' => 'tanggal_lahir wajib diisi.',
                'jurusan.required' => 'jurusan wajib diisi.',
                'sekolah.required' => 'sekolah wajib diisi.',
            ],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            Siswa::create([
                'nama' => $request->nama,
                'nomor_induk_siswa' => $request->nomor_induk_siswa,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jurusan' => $request->jurusan,
                'sekolah' => $request->sekolah,
            ]);
            return redirect()->route('siswa.index')->with('success', 'siswa ' . $request->nama . ' Baru Berhasil Di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('siswa.index')->with('success', 'siswa ' . $request->nama . ' Baru Gagal Di Tambahkan');
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
        $siswa = Siswa::find($id);
        return view('dashboard.siswa.edit', compact('siswa'));
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
                'nama' => 'required',
                'nomor_induk_siswa' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'jurusan' => 'required',
                'sekolah' => 'required',
            ],
            [
                'nama.required' => 'nama wajib diisi.',
                'nomor_induk_siswa.required' => 'nomor_induk_siswa wajib diisi.',
                'tempat_lahir.required' => 'tempat_lahir wajib diisi.',
                'tanggal_lahir.required' => 'tanggal_lahir wajib diisi.',
                'jurusan.required' => 'jurusan wajib diisi.',
                'sekolah.required' => 'sekolah wajib diisi.',
            ],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $siswa = Siswa::find($id);
            $siswa->update([
                'nama' => $request->nama,
                'nomor_induk_siswa' => $request->nomor_induk_siswa,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jurusan' => $request->jurusan,
                'sekolah' => $request->sekolah,
            ]);
            return redirect()->route('siswa.index')->with('success', 'siswa ' . $request->nama . ' Baru Berhasil Di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('siswa.index')->with('success', 'siswa ' . $request->nama . ' Baru Gagal Di Tambahkan');
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
            $siswa = Siswa::find($id);
            $siswa->delete($siswa);
            return redirect()->route('siswa.index')->with('success', 'siswa ' . $siswa->nama . ' Baru Berhasil Di Hapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('siswa.index')->with('success', 'siswa ' . $siswa->nama . ' Baru Gagal Di Hapuss');
        } finally {
            DB::commit();
        }
    }
}
