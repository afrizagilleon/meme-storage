@extends('layouts.app')

@section('title', $meme->nama . ' - Meme Storage')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>{{ $meme->nama }}</h4>
                <div class="btn-group">
                    <a href="{{ route('memes.edit', $meme) }}" class="btn btn-outline-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="{{ route('memes.destroy', $meme) }}"
                          style="display: inline;"
                          onsubmit="return confirm('Yakin ingin menghapus meme ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/memes/' . $meme->gambar) }}"
                         alt="{{ $meme->nama }}"
                         class="img-fluid rounded"
                         style="max-height: 500px;">
                </div>

                @if($meme->konteks)
                    <div class="mb-3">
                        <h6><i class="fas fa-info-circle"></i> Konteks</h6>
                        <p>{{ $meme->konteks }}</p>
                    </div>
                @endif

                @if($meme->penjelasan)
                    <div class="mb-3">
                        <h6><i class="fas fa-comment-dots"></i> Penjelasan</h6>
                        <p>{{ $meme->penjelasan }}</p>
                    </div>
                @endif

                @if($meme->source)
                    <div class="mb-3">
                        <h6><i class="fas fa-link"></i> Source</h6>
                        <p>{{ $meme->source }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-info"></i> Informasi</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Ditambahkan:</strong></td>
                        <td>{{ $meme->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Diupdate:</strong></td>
                        <td>{{ $meme->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @if($meme->tanggal_upload_sosmed)
                        <tr>
                            <td><strong>Jadwal Upload:</strong></td>
                            <td>{{ $meme->tanggal_upload_sosmed->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if($meme->tanggal_upload_sosmed->isPast())
                                    <span class="badge bg-danger">Terlewat</span>
                                @else
                                    <span class="badge bg-success">{{ $meme->time_remaining }}</span>
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        @if($meme->tanggal_upload_sosmed)
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="fas fa-bell"></i> Reminder</h5>
                </div>
                <div class="card-body">
                    <p>Meme ini dijadwalkan untuk diupload ke sosial media pada:</p>
                    <p class="h6">{{ $meme->tanggal_upload_sosmed->format('d/m/Y H:i') }}</p>

                    <form method="POST" action="{{ route('memes.remove-reminder', $meme) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-bell-slash"></i> Hapus Reminder
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('memes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Meme
    </a>
</div>
@endsection
