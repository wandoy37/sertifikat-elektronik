<?php

namespace App\Http\Controllers;

use App\Models\Narasumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\returnSelf;

class NarasumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $narasumbers = Narasumber::all();
        return view('dashboard.narasumber.index', compact('narasumbers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.narasumber.create');
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
                'nip' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'pangkat_golongan' => 'required',
                'jabatan' => 'required',
                'instansi' => 'required',
            ],
            [
                'nama.required' => 'nama wajib diisi.',
                'nip.required' => 'nomor_induk_siswa wajib diisi.',
                'tempat_lahir.required' => 'tempat_lahir wajib diisi.',
                'tanggal_lahir.required' => 'tanggal_lahir wajib diisi.',
                'pangkat_golongan.required' => 'pangkat golongan wajib diisi.',
                'jabatan.required' => 'jabatan wajib diisi.',
                'instansi.required' => 'instansi wajib diisi.',
            ],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            Narasumber::create([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan' => $request->jabatan,
                'instansi' => $request->instansi,
            ]);
            return redirect()->route('narasumber.index')->with('success', 'Narasumber ' . $request->nama . ' Baru Berhasil Di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('narasumber.index')->with('success', 'Narasumber ' . $request->nama . ' Baru Gagal Di Tambahkan');
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
        $narasumber = Narasumber::find($id);
        return view('dashboard.narasumber.edit', compact('narasumber'));
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
                'nip' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'pangkat_golongan' => 'required',
                'jabatan' => 'required',
                'instansi' => 'required',
            ],
            [
                'nama.required' => 'nama wajib diisi.',
                'nip.required' => 'nomor_induk_siswa wajib diisi.',
                'tempat_lahir.required' => 'tempat_lahir wajib diisi.',
                'tanggal_lahir.required' => 'tanggal_lahir wajib diisi.',
                'pangkat_golongan.required' => 'pangkat golongan wajib diisi.',
                'jabatan.required' => 'jabatan wajib diisi.',
                'instansi.required' => 'instansi wajib diisi.',
            ],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $narasumber = Narasumber::find($id);
            $narasumber->update([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan' => $request->jabatan,
                'instansi' => $request->instansi,
            ]);
            return redirect()->route('narasumber.index')->with('success', 'Narasumber ' . $request->nama . ' Baru Berhasil Di Update');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('narasumber.index')->with('success', 'Narasumber ' . $request->nama . ' Baru Gagal Di Update');
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
            $narasumber = Narasumber::find($id);
            $narasumber->delete($narasumber);
            return redirect()->route('narasumber.index')->with('success', 'Narasumber ' . $narasumber->nama . ' Baru Berhasil Di Hapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('narasumber.index')->with('success', 'Narasumber ' . $narasumber->nama . ' Baru Gagal Di Hapus');
        } finally {
            DB::commit();
        }
    }
}
