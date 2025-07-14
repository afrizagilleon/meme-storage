<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Meme extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'gambar',
        'source',
        'konteks',
        'penjelasan',
        'tanggal_upload_sosmed'
    ];

    protected $casts = [
        'tanggal_upload_sosmed' => 'datetime',
    ];

    // Accessor untuk waktu remaining
    public function getTimeRemainingAttribute()
    {
        if (!$this->tanggal_upload_sosmed) {
            return null;
        }

        $now = Carbon::now();
        $uploadTime = Carbon::parse($this->tanggal_upload_sosmed);

        if ($uploadTime->isPast()) {
            return 'Terlewat';
        }

        return $uploadTime->diffForHumans($now);
    }

    // Scope untuk meme yang sudah terlewat
    public function scopeOverdue($query)
    {
        return $query->where('tanggal_upload_sosmed', '<', Carbon::now())
                    ->whereNotNull('tanggal_upload_sosmed');
    }

    // Scope untuk meme yang akan datang
    public function scopeUpcoming($query)
    {
        return $query->where('tanggal_upload_sosmed', '>', Carbon::now())
                    ->whereNotNull('tanggal_upload_sosmed');
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $term)
    {
        return $query->where('nama', 'like', '%' . $term . '%')
                    ->orWhere('konteks', 'like', '%' . $term . '%')
                    ->orWhere('penjelasan', 'like', '%' . $term . '%')
                    ->orWhere('source', 'like', '%' . $term . '%');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
