<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $publishedCount = News::where('status', 'published')->count();
        $draftCount = News::where('status', 'draft')->count();
        $categoryCount = Category::count();
        $totalViews = News::sum('views');
        
        $popularArticles = News::where('status', 'published')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();
        
        $recentActivities = News::latest('updated_at')
            ->take(5)
            ->get();
        
        return view('dashboard', compact(
            'publishedCount',
            'draftCount',
            'categoryCount',
            'totalViews',
            'popularArticles',
            'recentActivities'
        ));
    }
}