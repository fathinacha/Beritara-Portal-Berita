<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Beritara - Portal Berita</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-full flex flex-col">
    <!-- Header -->
    <x-public.header :categories="$categories" />

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Trending Section -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6">Trending</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($news->where('is_featured', true)->take(6) as $article)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        @if($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" 
                                 alt="{{ $article->title }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                {{ $article->category->name }}
                            </span>
                            <h3 class="text-xl font-bold mt-2 mb-2">{{ $article->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-3">
                                {{ Str::limit(strip_tags($article->content), 150) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Berita Harian Section -->
        <section>
            <h2 class="text-2xl font-bold mb-6">Berita Harian</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($news as $article)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <a href="{{ route('news.detail', $article->slug) }}">
                            @if($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" 
                                     alt="{{ $article->title }}"
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No Image</span>
                                </div>
                            @endif
                            <div class="p-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $article->category->name }}
                                </span>
                                <h3 class="text-xl font-bold mt-2 mb-2">{{ $article->title }}</h3>
                                <p class="text-gray-600 text-sm line-clamp-3">
                                    {{ Str::limit(strip_tags($article->content), 150) }}
                                </p>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500">Tidak ada artikel yang tersedia.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    <!-- Footer -->
    <x-public.footer />
</body>
</html>