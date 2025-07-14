<?php

namespace App\Http\Controllers;

use App\Models\Meme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MemeController extends Controller
{
    public function index(Request $request)
    {
        $query = Meme::with('user');

        // Author filter
        if ($request->has('author_filter') && $request->author_filter) {
            if ($request->author_filter === 'me') {
                $query->where('user_id', Auth::id());
            } elseif ($request->author_filter === 'others') {
                $query->where('user_id', '!=', Auth::id());
            }
        }
        
        // Username search
        if ($request->has('username') && $request->username) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('username', 'like', '%' . $request->username . '%');
            });
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'scheduled':
                    $query->upcoming();
                    break;
                case 'overdue':
                    $query->overdue();
                    break;
                case 'no_schedule':
                    $query->whereNull('tanggal_upload_sosmed');
                    break;
            }
        }

        // Sort by date
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'upload_date':
                    $query->orderBy('tanggal_upload_sosmed', 'asc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $memes = $query->paginate(12);

        return view('memes.index', compact('memes'));
    }

    public function create()
    {
        return view('memes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'source' => 'nullable|string|max:255',
            'konteks' => 'nullable|string',
            'penjelasan' => 'nullable|string',
            'tanggal_upload_sosmed' => 'nullable|date|after:now',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('gambar')) {
            $filename = $request->file('gambar')->hashName();
            $path = $request->file('gambar')->storeAs('memes', $filename, 'public');
            $data['gambar'] = $filename;
        }

        Meme::create($data);

        return redirect()->route('memes.index')
                        ->with('success', 'Meme berhasil ditambahkan!');
    }

    public function show(Meme $meme)
    {
        return view('memes.show', compact('meme'));
    }

    public function edit(Meme $meme)
    {
        if ($meme->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('memes.edit', compact('meme'));
    }

    public function update(Request $request, Meme $meme)
    {
        if ($meme->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'source' => 'nullable|string|max:255',
            'konteks' => 'nullable|string',
            'penjelasan' => 'nullable|string',
            'tanggal_upload_sosmed' => 'nullable|date|after:now',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('gambar')) {
            // Delete old image
            if ($meme->gambar && Storage::disk('public')->exists('memes/' . $meme->gambar)) {
                Storage::disk('public')->delete('memes/' . $meme->gambar);
            }

            $filename = $request->file('gambar')->hashName();
            $path = $request->file('gambar')->storeAs('memes', $filename, 'public');
            $data['gambar'] = $filename;
        }

        $meme->update($data);

        return redirect()->route('memes.index')
                        ->with('success', 'Meme berhasil diperbarui!');
    }

    public function destroy(Meme $meme)
    {
        if ($meme->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete image file
        if ($meme->gambar && Storage::disk('public')->exists('memes/' . $meme->gambar)) {
            Storage::disk('public')->delete('memes/' . $meme->gambar);
        }

        $meme->delete();

        return redirect()->route('memes.index')
                        ->with('success', 'Meme berhasil dihapus!');
    }

    public function removeReminder(Meme $meme)
    {
        if ($meme->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $meme->update(['tanggal_upload_sosmed' => null]);

        return redirect()->back()
                        ->with('success', 'Reminder berhasil dihapus!');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // User's statistics
        $totalMemes = $user->memes()->count();
        $scheduledMemes = $user->memes()->upcoming()->count();
        $overdueMemes = $user->memes()->overdue()->count();
        
        // User's recent memes
        $recentMemes = $user->memes()->orderBy('created_at', 'desc')->take(5)->get();
        $upcomingMemes = $user->memes()->upcoming()->orderBy('tanggal_upload_sosmed', 'asc')->take(5)->get();

        return view('dashboard', compact(
            'totalMemes',
            'scheduledMemes',
            'overdueMemes',
            'recentMemes',
            'upcomingMemes'
        ));
    }
}
