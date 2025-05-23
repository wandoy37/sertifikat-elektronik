<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <h4 class="fw-bold">Daftar Narasumber</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="narsum-datatables" class="display table table-striped table-hover" cellspacing="0"
                    width="100%">
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
                        @foreach ($sertifikats as $narasumber)
                            @if ($narasumber->narasumber_id !== '-')
                                <tr class="text-center">
                                    <td class="text-center" width="25px;">{{ $counter++ }}</td>
                                    <td>{{ $narasumber->nomor_sertifikat }}</td>
                                    <td>
                                        @php
                                            $narasumber_name = DB::table('narasumbers')
                                                ->where('id', '=', $narasumber->narasumber_id)
                                                ->first();
                                        @endphp
                                        {{ $narasumber_name->nama }}
                                    </td>
                                    <td>
                                        @if ($narasumber->status == 'terbit')
                                            <span class="badge badge-success text-capitalize">
                                                {{ $narasumber->status }}
                                            </span>
                                        @else
                                            <span class="badge badge-count text-capitalize">
                                                {{ $narasumber->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="form-inline d-flex justify-content-center">
                                        <a href="{{ route('sertifikat.narasumber.preview', $narasumber->id) }}"
                                            class="fw-bold text-dark mr-4 text-capitalize" target="_blank">
                                            <i class="fas fa-search"></i>
                                        </a>
                                        <a href="{{ route('sertifikat.narasumber.download', $narasumber->id) }}"
                                            class="text-dark mr-2" target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form id="form-delete-{{ $narasumber->id }}"
                                            action="{{ route('sertifikat.delete', $narasumber->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <input type="text" name="kegiatan_id" value="{{ $kegiatan->id }}" hidden>
                                        </form>
                                        <button type="button" class="btn btn-link text-danger"
                                            onclick="btnDelete( {{ $narasumber->id }} )">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
