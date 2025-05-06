<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\NewsRequest;

class NewsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index(Request $request)
    {
        $query = News::with('category');

        // Pencarian berdasarkan judul
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Pengurutan
        $sort = $request->get('sort', 'latest');
        if ($sort === 'latest') {
            $query->latest();
        } elseif ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'title_asc') {
            $query->orderBy('title', 'asc');
        } elseif ($sort === 'title_desc') {
            $query->orderBy('title', 'desc');
        }

        $news = $query->paginate(9);
        $categories = Category::all();
        
        return view('news.index', compact('news', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('news.create', compact('categories'));
    }

    public function store(NewsRequest $request)
    {
        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
            'status' => $request->status
        ];
    
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $data['image'] = $path;
        }
    
        $news = News::create($data);
    
        // Jika status draft, redirect ke preview
        if ($request->status === 'draft') {
            return redirect()->route('news.preview', $news)
                ->with('success', 'Berita berhasil disimpan sebagai draft.');
        }
    
        return redirect()->route('news.index')
            ->with('success', 'Berita berhasil dipublikasikan.');
    }

    public function edit(News $news)
    {
        $categories = Category::all();
        return view('news.edit', compact('news', 'categories'));
    }

    public function update(NewsRequest $request, News $news)
    {
        // Jika request hanya mengubah status
        if ($request->has('status') && !$request->has('title')) {
            $news->update(['status' => $request->status]);
            
            $message = $request->status === 'published' 
                ? 'Berita berhasil dipublikasikan.'
                : 'Berita berhasil diubah menjadi draft.';
                
            return redirect()->route('news.index')
                ->with('success', $message);
        }

        // Jika update penuh (dari halaman edit)
        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'category_id' => $request->category_id,
            'status' => $request->status
        ];

        if ($request->hasFile('image')) {
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            
            $path = $request->file('image')->store('news', 'public');
            $data['image'] = $path;
        }

        $news->update($data);

        return redirect()->route('news.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function updateStatus(Request $request, News $news)
    {
        $request->validate([
            'status' => 'required|in:draft,published'
        ]);

        $news->update(['status' => $request->status]);
        
        $message = $request->status === 'published' 
            ? 'Berita berhasil dipublikasikan.'
            : 'Berita berhasil diubah menjadi draft.';
            
        return redirect()->route('news.index')
            ->with('success', $message);
    }

    public function destroy(News $news)
    {
        if ($news->image) {
            Storage::disk('public')->delete($news->image);
        }
        
        $news->delete();

        return redirect()->route('news.index')
            ->with('success', 'Article deleted successfully.');
    }

    public function preview(News $news)
    {
        // Pastikan hanya admin yang bisa preview
        if ($news->user_id !== auth()->id()) {
            abort(403);
        }
    
        return view('news.preview', compact('news'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $news = News::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->with('category')
            ->latest()
            ->paginate(12);
        
        return view('news.search', [
            'news' => $news,
            'query' => $query,
            'categories' => Category::all()
        ]);
    }
}
