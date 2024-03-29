<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image as ResizeImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pesertas = Peserta::latest()->get();
        return view('dashboard.peserta.index', compact('pesertas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($username)
    {
        $user = User::where('username', $username)->first();
        return view('dashboard.peserta.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $username)
    {
        $user = User::where('username', $username)->first();
        // Validator
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'nomor_identitas' => 'required',
                'thumbnail' => 'image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'nama.required' => 'Nama wajib diisi.',
                'nomor_identitas.required' => 'Nomor Identitas wajib diisi.',
            ],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        // If validator success
        DB::beginTransaction();
        try {
            if ($request['foto']) {
                $path = public_path('foto_peserta/');
                !is_dir($path) &&
                    mkdir($path, 0777, true);

                // Process Uploads
                $name = time() . '.' . $request->foto->extension();
                ResizeImage::make($request->file('foto'))
                    ->resize(354, 472)
                    ->save($path . $name);
            }
            Peserta::create([
                'nama' => $request->nama,
                'nomor_identitas' => $request->nomor_identitas,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'foto' => $name ?? null,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan' => $request->jabatan,
                'instansi' => $request->instansi,
                'user_id' => $user->id,
            ]);
            return redirect()->route('dashboard.index')->with('success', 'profil berhasil di update');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('dashboard.index')->with('fails', 'profil berhasil di update');
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
    public function show($username)
    {
        $user = User::where('username', $username)->first();
        $peserta = Peserta::where('user_id', $user->id)->first();
        return view('dashboard.peserta.show', compact('peserta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($username)
    {
        $user = User::where('username', $username)->first();
        $peserta = Peserta::where('user_id', $user->id)->first();
        return view('dashboard.peserta.edit', compact('peserta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $username)
    {
        // Validator
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'nomor_identitas' => 'required',
                'thumbnail' => 'image|mimes:jpeg,png,jpg|max:2048',
            ],
            [
                'nama.required' => 'Nama wajib diisi.',
                'nomor_identitas.required' => 'Nomor identitas wajib diisi.',
            ],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        // If validator success
        DB::beginTransaction();
        try {
            $user = User::where('username', $username)->first();
            $peserta = Peserta::where('user_id', $user->id)->first();

            if ($request['foto']) {
                $path = public_path('foto_peserta/');
                !is_dir($path) &&
                    mkdir($path, 0777, true);

                // Process delete old thumbnail
                $oldFoto = $peserta->foto;
                File::delete($path . $oldFoto);

                // Process Uploads
                $name = time() . '.' . $request->foto->extension();
                ResizeImage::make($request->file('foto'))
                    ->resize(354, 472)
                    ->save($path . $name);
            }

            $peserta->update([
                'nama' => $request->nama,
                'nomor_identitas' => $request->nomor_identitas,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'foto' => $name ?? $peserta->foto,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan' => $request->jabatan,
                'instansi' => $request->instansi,
                'user_id' => $user->id,
            ]);

            return redirect()->route('dashboard.index')->with('success', 'profil berhasil di update');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('dashboard.index')->with('fails', 'profil gagal di update');
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
    public function destroy($username)
    {
        DB::beginTransaction();
        try {
            $user = User::where('username', $username)->first();
            $peserta = Peserta::where('user_id', $user->id)->first();
            $peserta->delete($peserta);
            $user->delete($user);
            return redirect()->route('user.index')->with('success', $user->username . ' berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('fails', $user->username . ' gagal dihapus');
        } finally {
            DB::commit();
        }
    }
}
