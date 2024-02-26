<?php

namespace App\Http\Controllers;

use App\Models\Orang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orangs = Orang::all();
        return view('dashboard.orang.index', compact('orangs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.orang.create');
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
                'nik' => 'required',
            ],
            [],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            Orang::create([
                'nama' => $request->nama,
                'nik' => $request->nik,
            ]);
            return redirect()->route('orang.index')->with('success', 'Orang baru berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('orang.index')->with('success', 'Orang baru gagal ditambahkan');
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
        $orang = Orang::find($id);
        return view('dashboard.orang.edit', compact('orang'));
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
        $orang = Orang::find($id);
        // Validator
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'nik' => 'required',
            ],
            [],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $orang->update([
                'nama' => $request->nama,
                'nik' => $request->nik,
            ]);
            return redirect()->route('orang.index')->with('success', 'Update orang berhasil');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('orang.index')->with('success', 'Update orang gagal');
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
        $orang = Orang::find($id);
        DB::beginTransaction();
        try {
            $orang->delete($orang);
            return redirect()->route('orang.index')->with('success', 'Orang berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('orang.index')->with('success', 'Orang gagal dihapus');
        } finally {
            DB::commit();
        }
    }
}
