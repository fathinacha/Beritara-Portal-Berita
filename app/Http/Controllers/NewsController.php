<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        $news = News::with('category')
                    ->latest()
                    ->paginate(9);
        return view('news.index', compact('news'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'category_id' => $request->category_id,
            'user_id' => auth()->id()
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $data['image'] = $path;
        }

        News::create($data);

        return redirect()->route('news.index')
            ->with('success', 'Article created successfully.');
    }

    public function edit(News $news)
    {
        $categories = Category::all();
        return view('news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'category_id' => $request->category_id
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($news->image) {
                Storage::disk('public')->delete($news->image);
            }
            
            $path = $request->file('image')->store('news', 'public');
            $data['image'] = $path;
        }

        $news->update($data);

        return redirect()->route('news.index')
            ->with('success', 'Article updated successfully.');
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
} 