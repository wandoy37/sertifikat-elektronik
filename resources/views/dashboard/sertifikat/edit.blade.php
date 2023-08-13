@extends('dashboard.layouts.app')
@section('title', 'Edit Kategori')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Edit Kategori</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="fas fa-tag"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('kategori.index') }}" class="btn btn-outline-primary mb-4">
                    <i class="fas fa-undo"></i>
                    Kembali
                </a>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Edit Kategori</h3>
                    </div>
                    <form action="{{ route('kategori.update', $kategori->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="card-body">
                            <div class="form-group">
                                <label>Title</label>
                                <input id="title" type="text"
                                    class="form-control @error('title') has-error @enderror" name="title"
                                    placeholder="title" value="{{ old('title', $kategori->title) }}">
                                @error('title')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Template</label>
                                <input id="template" type="file" class="form-control" name="template"
                                    placeholder="template" value="{{ old('template') }}">
                                @error('template')
                                    <strong class="text-danger">{{ $message }}</strong>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-action float-right mb-3">
                                <button type="submit" class="btn btn-primary btn-rounded btn-login">
                                    <i class="fas fa-sync"></i>
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            $('#datepicker').datetimepicker({
                format: 'MM/DD/YYYY',
            });
        </script>
    @endpush
@endsection
