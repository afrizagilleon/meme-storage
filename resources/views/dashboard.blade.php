@extends('layouts.app')

@section('title', 'Dashboard - Meme Storage')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $totalMemes }}</h4>
                        <p>Total Meme</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-images fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $scheduledMemes }}</h4>
                        <p>Terjadwal</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $overdueMemes }}</h4>
                        <p>Terlewat</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $totalMemes - $scheduledMemes }}</h4>
                        <p>Tidak Terjadwal</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-question-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Meme Terbaru Saya</h5>
            </div>
            <div class="card-body">
                @forelse($recentMemes as $meme)
                    <div class="d-flex mb-3">
                        <img src="{{ asset('storage/memes/' . $meme->gambar) }}"
                             alt="{{ $meme->nama }}"
                             class="me-3"
                             style="width: 80px; height: 60px; object-fit: cover; border-radius: 5px;">
                        <div class="flex-grow-1">
                            <h6>{{ $meme->nama }}</h6>
                            <small class="text-muted">{{ $meme->created_at->diffForHumans() }}</small>
                        </div>
                        <div>
                            <a href="{{ route('memes.show', $meme) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Belum ada meme.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Reminder Mendatang</h5>
            </div>
            <div class="card-body">
                @forelse($upcomingMemes as $meme)
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h6>{{ $meme->nama }}</h6>
                            <small class="text-muted">{{ $meme->tanggal_upload_sosmed->format('d/m/Y H:i') }}</small>
                        </div>
                        <span class="badge bg-primary">{{ $meme->time_remaining }}</span>
                    </div>
                @empty
                    <p class="text-muted">Tidak ada reminder.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
