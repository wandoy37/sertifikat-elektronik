<div class="card">
    <div class="card-header">
        <h4 class="fw-bold">Passphrase</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('signature', $kegiatan->id) }}" method="post">
            @csrf
            <div class="form-group">
                <input type="password" class="form-control text-capitalize" placeholder="Passphrase" name="passphrase">
                <input type="text" name="auth_id" value="{{ Auth::user()->id }}" hidden>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-outline-primary">Bubuhkan Tanda Tangan</button>
            </div>
        </form>
    </div>
</div>
