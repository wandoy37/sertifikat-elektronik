<?php

namespace App\Http\Controllers;

use App\Mail\NotifyRegister;
use App\Mail\NotifySertifikat;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::latest()->get();
        return view('dashboard.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.user.create');
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
                'username' => 'required|unique:users',
                'email' => 'required|unique:users'
                // 'password' => 'required|confirmed|min:6',
            ],
            [
                'username.required' => 'Username wajib diisi',
                'username.unique' => 'Username ' . $request->username . ' sudah dimiliki.',
                'email.required' => 'Email wajib diisi',
                'email.unique' => 'Email ' . $request->email . ' sudah dimiliki.',
                // 'password.required' => 'Password wajib diisi',
                // 'password.confirmed' => 'Konfirmasi password tidak cocok',
                // 'password.min' => 'Password minimal 6 huruf',
            ],
        );

        // kondisi jika validasi gagal dilewati.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        // Message Notify Email
        $data_notify = [
            'subject' => 'Email ada telah terdaftar pada aplikasi Sertifikat Elektronik (UPTD BPPSDMP)',
            'username' => $request->username,
            'password' => $request->username,
            'link' => url('/login'),
        ];

        Mail::to($request->email)->send(new NotifyRegister($data_notify));

        DB::beginTransaction();
        try {
            User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->username),
                'role' => 'peserta',
            ]);

            return redirect()->route('user.index')->with('success', $request->username . ' berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('fails', $request->username . ' gagal ditambahkan');
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
    public function edit($username)
    {
        $user = User::where('username', $username)->first();
        return view('dashboard.user.edit', compact('user'));
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
        $user = User::where('username', $username)->first();

        // Validator
        $validator = Validator::make(
            $request->all(),
            [
                'username' => 'required|unique:users,username,' . $user->id,
                'email' => 'required|unique:users,email,' . $user->id,
            ],
            [
                'username.required' => 'Username wajib diisi',
                'username.unique' => 'Username ' . $request->username . ' sudah dimiliki.',
                'email.required' => 'Email wajib diisi',
                'email.unique' => 'Email ' . $request->email . ' sudah dimiliki.',
            ],
        );

        // if request or update password
        if (request('password')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'password' => 'required|confirmed|min:6',
                ],
                [
                    'password.required' => 'Password wajib diisi',
                    'password.confirmed' => 'Konfirmasi password tidak cocok',
                    'password.min' => 'Password minimal 6 huruf',
                ],
            );
        }

        // kondisi jika validasi gagal dilewati.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            // New password
            if (request('password')) {
                $newPassword = Hash::make($request->password);
            }

            $user->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => $newPassword ?? $user->password,
            ]);
            return redirect()->route('dashboard.index')->with('success', $request->username . ' berhasil diupdate');
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('fails', $request->username . ' gagal ditambahkan');
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
        $user = User::where('username', $username)->first();

        DB::beginTransaction();
        try {

            $user->delete($user);

            return redirect()->route('user.index')->with('success', $user->username . ' berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('fails', $user->username . ' gagal dihapus');
        } finally {
            DB::commit();
        }
    }
}
