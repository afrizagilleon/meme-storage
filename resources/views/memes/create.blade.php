@extends('layouts.app')

@section('title', 'Tambah Meme - Meme Storage')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Tambah Meme Baru</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('memes.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Meme <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                       id="nama" name="nama" value="{{ old('nama') }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="gambar" class="form-label">Gambar Meme <span class="text-danger">*</span></label>
                <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                       id="gambar" name="gambar" accept="image/*" required>
                @error('gambar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="source" class="form-label">Source</label>
                <input type="text" class="form-control @error('source') is-invalid @enderror"
                       id="source" name="source" value="{{ old('source') }}"
                       placeholder="Contoh: Twitter, Instagram, Reddit">
                @error('source')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="konteks" class="form-label">Konteks</label>
                <textarea class="form-control @error('konteks') is-invalid @enderror"
                          id="konteks" name="konteks" rows="3"
                          placeholder="Konteks atau situasi terkait meme">{{ old('konteks') }}</textarea>
                @error('konteks')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="penjelasan" class="form-label">Penjelasan</label>
                <textarea class="form-control @error('penjelasan') is-invalid @enderror"
                          id="penjelasan" name="penjelasan" rows="3"
                          placeholder="Penjelasan detail tentang meme">{{ old('penjelasan') }}</textarea>
                @error('penjelasan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="tanggal_upload_sosmed" class="form-label">Jadwal Upload ke Sosmed</label>
                <input type="datetime-local" class="form-control @error('tanggal_upload_sosmed') is-invalid @enderror"
                       id="tanggal_upload_sosmed" name="tanggal_upload_sosmed" value="{{ old('tanggal_upload_sosmed') }}">
                @error('tanggal_upload_sosmed')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Opsional: Atur reminder untuk upload ke sosial media</small>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('memes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Meme
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
