@extends('layouts.app')

@section('title', 'Semua Meme - Meme Storage')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Semua Meme</h1>
    <a href="{{ route('memes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Meme
    </a>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('memes.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari meme..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <select name="author_filter" class="form-control">
                            <option value="">Semua Author</option>
                            <option value="me" {{ request('author_filter') == 'me' ? 'selected' : '' }}>Meme Saya</option>
                            <option value="others" {{ request('author_filter') == 'others' ? 'selected' : '' }}>Meme Lainnya</option>
                        </select>
                        <input type="text" name="username" class="form-control"
                               placeholder="Atau cari username..." value="{{ request('username') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="filter" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="scheduled" {{ request('filter') == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                        <option value="overdue" {{ request('filter') == 'overdue' ? 'selected' : '' }}>Terlewat</option>
                        <option value="no_schedule" {{ request('filter') == 'no_schedule' ? 'selected' : '' }}>Tidak Terjadwal</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-control">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="upload_date" {{ request('sort') == 'upload_date' ? 'selected' : '' }}>Tanggal Upload</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Meme Grid -->
<div class="row">
    @forelse($memes as $meme)
        <div class="col-md-4 mb-4">
            <div class="card meme-card h-100">
                <div class="position-relative">
                    <img src="{{ asset('storage/memes/' . $meme->gambar) }}"
                         alt="{{ $meme->nama }}"
                         class="card-img-top meme-img">

                    @if($meme->tanggal_upload_sosmed)
                        @if($meme->tanggal_upload_sosmed->isPast())
                            <span class="badge bg-danger status-badge">Terlewat</span>
                        @else
                            <span class="badge bg-success status-badge">{{ $meme->time_remaining }}</span>
                        @endif
                    @endif
                </div>

                <div class="card-body">
                    <h5 class="card-title">{{ $meme->nama }}</h5>

                    @if($meme->konteks)
                        <p class="card-text">{{ Str::limit($meme->konteks, 100) }}</p>
                    @endif

                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-user"></i> 
                            {{ $meme->user ? $meme->user->username : 'Unknown' }}
                            • {{ $meme->created_at->diffForHumans() }}
                        </small>
                    </div>

                    @if($meme->source)
                        <small class="text-muted">
                            <i class="fas fa-link"></i> {{ $meme->source }}
                        </small>
                    @endif
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div class="btn-group">
                            <a href="{{ route('memes.show', $meme) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($meme->user_id === Auth::id())
                                <a href="{{ route('memes.edit', $meme) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('memes.destroy', $meme) }}"
                                      style="display: inline;"
                                      onsubmit="return confirm('Yakin ingin menghapus meme ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                        @if($meme->tanggal_upload_sosmed && $meme->user_id === Auth::id())
                            <form method="POST" action="{{ route('memes.remove-reminder', $meme) }}"
                                  style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-secondary"
                                        title="Hapus Reminder">
                                    <i class="fas fa-bell-slash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                <h4>Belum ada meme</h4>
                <p class="text-muted">Mulai dengan menambahkan meme pertama Anda!</p>
                <a href="{{ route('memes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Meme
                </a>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="d-flex justify-content-center">
    {{ $memes->links() }}
</div>
@endsection
