<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Selamat Datang, ' . Str::before(Auth::user()->name, ' ') . '!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistik Utama -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Artikel</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ $publishedCount }}</p>
                    <p class="text-sm text-gray-500">Artikel Terpublikasi</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Draft</h3>
                    <p class="text-3xl font-bold text-yellow-600">{{ $draftCount }}</p>
                    <p class="text-sm text-gray-500">Artikel Draft</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Kategori</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $categoryCount }}</p>
                    <p class="text-sm text-gray-500">Total Kategori</p>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Views</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $totalViews }}</p>
                    <p class="text-sm text-gray-500">Seluruh Artikel</p>
                </div>
            </div>

            <!-- Artikel Populer -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Artikel Populer</h3>
                <div class="space-y-4">
                    @foreach($popularArticles as $article)
                    <div class="flex items-center justify-between border-b pb-4">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $article->title }}</h4>
                            <p class="text-sm text-gray-500">{{ $article->category->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-indigo-600">{{ $article->views }} views</p>
                            <p class="text-sm text-gray-500">{{ $article->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Aktivitas Terbaru -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-center space-x-4 border-b pb-4">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $activity->title }}</h4>
                            <p class="text-sm text-gray-500">
                                Status: 
                                <span class="px-2 py-1 text-xs rounded-full {{ $activity->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $activity->status }}
                                </span>
                            </p>
                        </div>
                        <p class="text-sm text-gray-500">{{ $activity->updated_at->diffForHumans() }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
