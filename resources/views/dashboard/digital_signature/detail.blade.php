@extends('dashboard.layouts.app')
@section('title', 'Detail Kegiatan')

@section('content')
    <div class="page-inner">
        {{-- Notify --}}
        @if (session('success'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                        {{ session('success') }}
                    </div>
                    <script>
                        setTimeout(function() {
                            document.getElementById('success-alert').style.display = 'none';
                        }, 6000); // Hilang setelah 3 detik
                    </script>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                        {{ session('error') }}
                    </div>
                    <script>
                        setTimeout(function() {
                            document.getElementById('error-alert').style.display = 'none';
                        }, 6000); // Hilang setelah 3 detik
                    </script>
                </div>
            </div>
        @endif
        {{-- ====== --}}

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('digital.signature.index') }}" class="btn btn-outline-primary mb-4">
                    <i class="fas fa-undo"></i>
                    Kembali
                </a>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="fw-bold">Detail Kegiatan</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Kegiatan</label>
                            <input type="text" class="form-control text-capitalize" placeholder="Nama Kegiatan"
                                value="{{ $kegiatan->judul_kegiatan }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Kategori Kegiatan</label>
                            <input type="text" class="form-control text-uppercase" placeholder="Nama Kegiatan"
                                value="{{ $kegiatan->kategori->title }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Penyelenggara Kegiatan</label>
                            <input type="text" class="form-control text-capitalize" placeholder="Penyelenggara Kegiatan"
                                value="{{ $kegiatan->penyelenggara_kegiatan }}" disabled>
                        </div>
                        <div class="form-group">
                            <label>Periode Kegiatan</label>
                            <input type="text" class="form-control" placeholder="Nama Kegiatan"
                                value="{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai_kegiatan)->isoFormat('D MMM') . ' - ' . \Carbon\Carbon::parse($kegiatan->tanggal_akhir_kegiatan)->isoFormat('D MMM Y') }}"
                                disabled>
                        </div>
                        <div class="form-group">
                            <label>Penandatangan Kegiatan</label>
                            <input type="text" class="form-control" placeholder="Nama Kegiatan"
                                value="{{ $kegiatan->penandatangan->nama }}" disabled>
                        </div>


                        @if (!Auth::user()->hasRole('penandatangan'))
                            <div class="form-group">
                                <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" class="btn btn-primary">
                                    <i class="fas fa-pen"></i>
                                    Edit
                                </a>
                            </div>
                        @endif



                    </div>
                </div>
            </div>
            @if ($kegiatan->status == 'unsigned')
                <div class="col-lg-6">
                    @include('dashboard.digital_signature.form_passphrase')
                </div>
            @endif
            <div class="col-lg-12">
                @include('dashboard.kegiatan.daftar_partisipasi')
                @include('dashboard.kegiatan.daftar_narasumber')
            </div>
        </div>


    </div>

    @push('scripts')
        <script>
            $('#basic-datatables').DataTable();
            $('#narsum-datatables').DataTable();

            // Notify
            var flash = $('#success').data('flash');
            if (flash) {
                $.notify({
                    // options
                    icon: 'fas fa-check',
                    title: 'Berhasil',
                    message: '{{ session('success') }}',
                }, {
                    // settings
                    type: 'success',
                });
            }
            var flash = $('#fails').data('flash');
            if (flash) {
                $.notify({
                    // options
                    icon: 'fas fa-ban',
                    title: 'Gagal',
                    message: '{{ session('fails') }}',
                }, {
                    // settings
                    type: 'danger',
                });
            }

            function btnDelete(id) {
                swal({
                    title: 'Apa anda yakin?',
                    text: "Data tidak dapat di kembalikan setelah ini !!!",
                    type: 'warning',
                    buttons: {
                        confirm: {
                            text: 'Ya, hapus sekarang',
                            className: 'btn btn-success'
                        },
                        cancel: {
                            visible: true,
                            className: 'btn btn-danger'
                        }
                    }
                }).then((Delete) => {
                    if (Delete) {
                        $('#form-delete-' + id).submit();
                    } else {
                        swal.close();
                    }
                });
            }
        </script>
        <script>
            $('#basic').select2({
                theme: "bootstrap"
            });

            $('#selectNarasumber').select2({
                theme: "bootstrap"
            });
        </script>
    @endpush
@endsection
