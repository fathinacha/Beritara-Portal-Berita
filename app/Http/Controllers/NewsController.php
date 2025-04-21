<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $news = News::with(['category', 'user'])->latest()->get();
        return response()->json($news);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'category_id' => $request->category_id,
            'user_id' => Auth::id()
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/news', $imageName);
            $data['image'] = 'news/' . $imageName;
        }

        $news = News::create($data);

        return response()->json([
            'message' => 'News created successfully',
            'news' => $news
        ], 201);
    }

    public function show(News $news)
    {
        $news->load(['category', 'user']);
        return response()->json($news);
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'category_id' => $request->category_id
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($news->image) {
                Storage::delete('public/' . $news->image);
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/news', $imageName);
            $data['image'] = 'news/' . $imageName;
        }

        $news->update($data);

        return response()->json([
            'message' => 'News updated successfully',
            'news' => $news
        ]);
    }

    public function destroy(News $news)
    {
        // Delete image if exists
        if ($news->image) {
            Storage::delete('public/' . $news->image);
        }

        $news->delete();

        return response()->json([
            'message' => 'News deleted successfully'
        ]);
    }
} 