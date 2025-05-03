<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function receiveSigned(Request $request)
    {
        // Validasi Auth jika pakai Basic atau Bearer
        $authorization = $request->header('Authorization');
        if (!$this->isAuthorized($authorization)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validasi input
        $request->validate([
            'signpdf' => 'required|file|mimes:pdf',
            'kode' => 'required|string',
        ]);

        // Simpan file
        $file = $request->file('signpdf');
        $filename = 'signed_' . $request->kode . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('signed_docs', $filename);

        return response()->json(['message' => 'Dokumen berhasil diterima', 'path' => $path], 200);
    }

    private function isAuthorized($authorization)
    {
        // Contoh Basic Auth manual
        $expected = 'Basic ' . base64_encode('username:password');
        return $authorization === $expected;
    }
}
