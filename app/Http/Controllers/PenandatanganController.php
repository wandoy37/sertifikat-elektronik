<?php

namespace App\Http\Controllers;

use App\Models\Penandatangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PenandatanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $penandatangans = Penandatangan::latest()->get();
        return view('dashboard.penandatangan.index', compact('penandatangans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.penandatangan.create');
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
                'pangkat_golongan' => 'required',
                'jabatan' => 'required',
                'tanda_tangan_stempel' => 'required',
            ],
            [
                'nama.required' => 'nama wajib diisi.',
                'nip.required' => 'nip wajib diisi.',
                'pangkat_golongan.required' => 'pangkat/golongan wajib diisi.',
                'jabatan.required' => 'jabatan wajib diisi.',
                'tanda_tangan_stempel.required' => 'tanda tangan dan stempel wajib diisi.',
            ],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        // If validator success
        DB::beginTransaction();
        try {
            // Upload File
            if ($request['tanda_tangan_stempel']) {
                // file upload
                $fileName = time() . '.' . $request->file('tanda_tangan_stempel')->extension();
                $request->file('tanda_tangan_stempel')->move(public_path('uploads/tanda_tangan_stempel'), $fileName);
            }

            Penandatangan::create([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan' => $request->jabatan,
                'tanda_tangan_stempel' => $fileName,
            ]);
            return redirect()->route('penandatangan.index')->with('success', $request->nama . ' telah di tambahkan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('penandatangan.index')->with('fails', $request->nama . ' gagal di tambahkan.');
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
        $penandatangan = Penandatangan::find($id);
        return view('dashboard.penandatangan.edit', compact('penandatangan'));
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
                'pangkat_golongan' => 'required',
                'jabatan' => 'required',
            ],
            [
                'nama.required' => 'nama wajib diisi.',
                'nip.required' => 'nip wajib diisi.',
                'pangkat_golongan.required' => 'pangkat/golongan wajib diisi.',
                'jabatan.required' => 'jabatan wajib diisi.',
            ],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        // If validator success
        DB::beginTransaction();
        try {
            $penandatangan = Penandatangan::find($id);

            // Upload File
            if ($request['tanda_tangan_stempel']) {
                // delete old tanda_tangan_stempel
                $path = public_path() . 'uploads/tanda_tangan_stempel/';
                File::delete($path . $penandatangan->tanda_tangan_stempel);
                // file upload
                $fileName = time() . '.' . $request->file('tanda_tangan_stempel')->extension();
                $request->file('tanda_tangan_stempel')->move(public_path('uploads/tanda_tangan_stempel'), $fileName);
            }

            $penandatangan->update([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan' => $request->jabatan,
                'tanda_tangan_stempel' => $fileName ?? $penandatangan->tanda_tangan_stempel,
            ]);
            return redirect()->route('penandatangan.index')->with('success', $request->nama . ' telah di update.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('penandatangan.index')->with('fails', $request->nama . ' gagal di update.');
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
        $penandatangan = Penandatangan::find($id);
        DB::beginTransaction();
        try {
            $penandatangan = Penandatangan::find($id);
            $path = public_path() . 'uploads/tanda_tangan_stempel/';
            File::delete($path . $penandatangan->tanda_tangan_stempel);
            $penandatangan->delete($penandatangan);
            return redirect()->route('penandatangan.index')->with('success', $penandatangan->nama . ' telah di hapus.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('penandatangan.index')->with('fails', $penandatangan->nama . ' gagal di hapus.');
        } finally {
            DB::commit();
        }
    }
}
