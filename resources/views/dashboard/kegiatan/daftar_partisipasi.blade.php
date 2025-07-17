<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="fw-bold">Daftar Partisipan</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="basic-datatables" class="display table table-striped table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nomor Sertifikat</th>
                            <th>Nama Lengkap</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @foreach ($sertifikats as $sertifikat)
                            @if ($kegiatan->kategori->title == 'pelatihan')
                                @if ($sertifikat->peserta_id !== '-')
                                    <tr class="text-center">
                                        <td class="text-center" width="25px;">{{ $counter++ }}</td>
                                        <td>{{ $sertifikat->nomor_sertifikat }}</td>
                                        <td>
                                            @php
                                                $peserta_id = $sertifikat->peserta_id;
                                                $url = env('SIMPELTAN_API_DATA_PESERTA') . "/{$sertifikat->peserta_id}";

                                                $ch = curl_init();
                                                curl_setopt($ch, CURLOPT_URL, $url);
                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                                                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
                                                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                                curl_setopt($ch, CURLOPT_USERAGENT, 'Laravel/10.0 (compatible; PHP)');

                                                $response = curl_exec($ch);
                                                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                                $error = curl_error($ch);
                                                curl_close($ch);

                                                if ($error) {
                                                    $data = null;
                                                    // Log error jika perlu
                                                    \Log::error('cURL Error: ' . $error);
                                                } elseif ($httpCode !== 200) {
                                                    $data = null;
                                                    \Log::error('HTTP Error: ' . $httpCode);
                                                } else {
                                                    $data = json_decode($response, true);
                                                }
                                            @endphp
                                            {{ $data[0]['peserta_nama'] }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $sertifikat->status == 'signed' ? 'badge-success' : 'badge-warning' }}">
                                                {{ $sertifikat->status }}
                                            </span>

                                        </td>
                                        <td class="form-inline d-flex justify-content-center">
                                            <a href="{{ route('sertifikat.preview', $sertifikat->id) }}"
                                                class="fw-bold text-dark mr-4 text-capitalize" target="_blank">
                                                <i class="fas fa-search"></i>
                                            </a>
                                            @if (!Auth::user()->hasRole('penandatangan'))
                                                <a href="{{ route('sertifikat.download', $sertifikat->id) }}"
                                                    class="text-dark mr-2" target="_blank">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <form id="form-delete-{{ $sertifikat->id }}"
                                                    action="{{ route('sertifikat.delete', $sertifikat->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="text" name="kegiatan_id" value="{{ $kegiatan->id }}"
                                                        hidden>
                                                </form>
                                                <button type="button" class="btn btn-link text-danger"
                                                    onclick="btnDelete( {{ $sertifikat->id }} )">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                @endif
                            @endif

                            @if ($kegiatan->kategori->title == 'bimtek')
                                @if ($sertifikat->orang_id !== '-')
                                    <tr class="text-center">
                                        <td class="text-center" width="25px;">{{ $counter++ }}</td>
                                        <td>{{ $sertifikat->nomor_sertifikat }}</td>
                                        <td>
                                            @php
                                                $orang = DB::table('orangs')
                                                    ->where('id', '=', $sertifikat->orang_id)
                                                    ->first();
                                            @endphp
                                            {{ $orang->nama }}
                                        </td>
                                        <td>
                                            <span
                                                class="badge {{ $sertifikat->status == 'signed' ? 'badge-success' : 'badge-warning' }}">
                                                {{ $sertifikat->status }}
                                            </span>

                                        </td>
                                        <td class="form-inline d-flex justify-content-center">
                                            <a href="{{ route('sertifikat.preview', $sertifikat->id) }}"
                                                class="fw-bold text-dark mr-4 text-capitalize" target="_blank">
                                                <i class="fas fa-search"></i>
                                            </a>
                                            @if (!Auth::user()->hasRole('penandatangan'))
                                                <a href="{{ route('sertifikat.download', $sertifikat->id) }}"
                                                    class="text-dark mr-2" target="_blank">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <form id="form-delete-{{ $sertifikat->id }}"
                                                    action="{{ route('sertifikat.delete', $sertifikat->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <input type="text" name="kegiatan_id"
                                                        value="{{ $kegiatan->id }}" hidden>
                                                </form>
                                                <button type="button" class="btn btn-link text-danger"
                                                    onclick="btnDelete( {{ $sertifikat->id }} )">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endif

                            @if ($kegiatan->kategori->title == 'pkl')
                                @if ($sertifikat->siswa_id !== '-')
                                    <tr class="text-center">
                                        <td class="text-center" width="25px;">{{ $counter++ }}</td>
                                        <td>{{ $sertifikat->nomor_sertifikat }}</td>
                                        <td>
                                            @php
                                                $siswa = DB::table('siswas')
                                                    ->where('id', '=', $sertifikat->siswa_id)
                                                    ->first();
                                            @endphp
                                            {{ $siswa->nama }}
                                        </td>
                                        <td class="form-inline d-flex justify-content-center">
                                            <a href="{{ route('home.show', $sertifikat->verified_code) }}"
                                                class="fw-bold text-primary mr-3 text-capitalize" target="_blank">
                                                <i class="fas fa-download"></i>
                                                Download
                                            </a>
                                            @if (!Auth::user()->hasRole('penandatangan'))
                                                <a href="{{ route('sertifikat.peserta.generate', $sertifikat->id) }}"
                                                    class="btn btn-info btn-sm" target="_blank">
                                                    <i class="fas fa-certificate"></i>
                                                    Cetak {{ $sertifikat->id }}
                                                </a>
                                                <form id="form-delete-{{ $sertifikat->id }}"
                                                    action="{{ route('sertifikat.peserta.delete', $sertifikat->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" class="btn btn-link text-danger"
                                                    onclick="btnDelete( {{ $sertifikat->id }} )">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
