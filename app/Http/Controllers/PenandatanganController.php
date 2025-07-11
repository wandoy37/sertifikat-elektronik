<?php

namespace App\Http\Controllers;

use App\Models\Penandatangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
        $penandatangans = Penandatangan::all();
        return view('dashboard.penandatangan.create', compact('penandatangans'));
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
            ],
            [
                'nama.required' => 'nama wajib diisi.',
                'nip.required' => 'nip wajib diisi.',
                'pangkat_golongan.required' => 'pangkat/golongan wajib diisi.',
                'jabatan.required' => 'jabatan wajib diisi.',
            ],
        );

        // if request or update passphrase
        if (request('passphrase')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'passphrase' => 'required|confirmed|min:6',
                ],
                [
                    'passphrase.required' => 'passphrase wajib diisi',
                    'passphrase.confirmed' => 'Konfirmasi passphrase tidak cocok',
                    'passphrase.min' => 'passphrase minimal 6 huruf',
                ],
            );
        }

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        // If validator success
        DB::beginTransaction();
        try {
            $data = [
                'user_id' => $request->user_id,
                'nama' => $request->nama,
                'nip' => $request->nip,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan' => $request->jabatan,
            ];

            if ($request->passphrase) {
                $data['passphrase'] = Hash::make($request->passphrase);
            }

            Penandatangan::create($data);
            return redirect()->route('user.edit', auth()->user()->username)->with('success', 'Biodata telah di update.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('user.edit', auth()->user()->username)->with('fails', 'Biodata gagal di update.');
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
        $penandatangans = Penandatangan::all();
        return view('dashboard.penandatangan.edit', compact('penandatangan', 'penandatangans'));
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
        $user = User::where('id', $request->user_id)->first();
        $penandatangan = Penandatangan::find($id);

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

        // if request or update passphrase
        if (request('passphrase')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'passphrase' => 'required|confirmed|min:6',
                ],
                [
                    'passphrase.required' => 'passphrase wajib diisi',
                    'passphrase.confirmed' => 'Konfirmasi passphrase tidak cocok',
                    'passphrase.min' => 'passphrase minimal 6 huruf',
                ],
            );
        }

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        // If validator success
        DB::beginTransaction();
        try {
            // New passphrase
            if (request('passphrase')) {
                $newpassphrase = Hash::make($request->passphrase);
                $penandatangan->passphrase = $newpassphrase;
            }

            $penandatangan->update([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan' => $request->jabatan,
                'tanda_tangan_stempel' => $fileName ?? $penandatangan->tanda_tangan_stempel,
            ]);
            return redirect()->route('user.edit', $user->username)->with('success', 'Biodata berhasil di update.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('user.edit', $user->username)->with('fails', 'Biodata gagal di update.');
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
