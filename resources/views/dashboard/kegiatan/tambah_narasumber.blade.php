<div class="card">
    <div class="card-header">
        <h4 class="fw-bold">Tambah Narasumber</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('sertifikat.narasumber.store') }}" method="post">
            @csrf
            <div class="form-group">
                <input type="text" name="kegiatan_id" value="{{ $kegiatan->id }}" hidden>
                <div class="select2-input">
                    <select id="selectNarasumber" name="narasumber_id" class="form-control">
                        <option value="">--Pilih Narasumber--</option>
                        @foreach ($narasumbers as $narasumber)
                            <option value="{{ $narasumber->id }}">{{ $narasumber->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-login">
                    <i class="fas fa-plus"></i>
                    Tambah
                </button>
            </div>
        </form>
    </div>
</div>
