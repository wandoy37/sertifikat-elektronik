<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategoris = Kategori::latest()->get();
        return view('dashboard.kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.kategori.create');
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
                'title' => 'required|unique:kategoris',
                'template' => 'required',
            ],
            [
                'title.required' => 'Title wajib diisi.',
                'title.unique' => $request->title . ' telah terdaftar.',
                'template.required' => 'Template wajib diisi.',
            ],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            // Upload File
            if ($request['template']) {
                // file upload
                $fileName = Str::slug($request->title, '-') . '-' . time() . '.' . $request->file('template')->extension();
                $request->file('template')->move(public_path('uploads/template'), $fileName);
            }

            Kategori::create([
                'title' => $request->title,
                'slug' => $request->title,
                'template' => $fileName,
            ]);
            return redirect()->route('kategori.index')->with('success', 'Kategori ' . $request->title . ' Baru Berhasil Di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('kategori.index')->with('success', 'Kategori ' . $request->title . ' Baru Gagal Di Tambahkan');
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
        $kategori = Kategori::find($id);
        return response()->file(public_path() . '/uploads/template/' . $kategori->template);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kategori = Kategori::find($id);
        return view('dashboard.kategori.edit', compact('kategori'));
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
        $kategori = Kategori::find($id);
        // Validator
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|unique:kategoris,title,' . $kategori->id,
            ],
            [
                'title.required' => 'Title wajib diisi.',
                'title.unique' => $request->title . ' telah terdaftar.',
            ],
        );

        // If validator fails.
        if ($validator->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            // Upload File
            if ($request['template']) {
                // delete old template
                $path = public_path() . '/uploads/template/';
                File::delete($path . $kategori->template);
                // file upload
                $fileName = Str::slug($request->title, '-') . '-' . time() . '.' . $request->file('template')->extension();
                $request->file('template')->move(public_path('uploads/template'), $fileName);
            }

            $kategori->update([
                'title' => $request->title,
                'slug' => $request->title,
                'template' => $fileName ?? $kategori->template,
            ]);
            return redirect()->route('kategori.index')->with('success', 'Kategori ' . $request->title . ' Baru Berhasil Di Update');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('kategori.index')->with('success', 'Kategori ' . $request->title . ' Baru Gagal Di Update');
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
            $kategori = Kategori::find($id);
            $path = public_path() . '/uploads/template/';
            File::delete($path . $kategori->template);
            $kategori->delete($kategori);

            return redirect()->route('kategori.index')->with('success', 'Kategori ' . $kategori->title . ' Baru Berhasil Di Hapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('kategori.index')->with('success', 'Kategori ' . $kategori->title . ' Baru Gagal Di Hapus');
        } finally {
            DB::commit();
        }
    }
}
