<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home.index');
    }

    public function show($id)
    {
        $sertifikat = Sertifikat::find($id);
        $pathToFile = public_path('sertifikat/' . 'doc-sertifikat-' . $sertifikat->id . '.' . 'pdf');
        return response()->file($pathToFile);
    }
}
