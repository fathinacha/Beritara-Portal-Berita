<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $article->title }} - Beritara</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-full flex flex-col">
    <x-public.header :categories="$categories" />

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <article class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($article->image)
                <img src="{{ asset('storage/' . $article->image) }}" 
                     alt="{{ $article->title }}"
                     class="w-full h-96 object-cover">
            @endif
            
            <div class="p-8">
                <div class="flex items-center space-x-4 mb-6">
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded">
                        {{ $article->category->name }}
                    </span>
                    <span class="text-gray-500">
                        {{ $article->created_at->format('d M Y') }}
                    </span>
                </div>

                <h1 class="text-4xl font-bold mb-6">{{ $article->title }}</h1>
                
                <div class="prose max-w-none">
                    {!! $article->content !!}
                </div>
            </div>
        </article>

        <!-- Related Articles -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Artikel Terkait</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedArticles as $related)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <a href="{{ route('news.detail', $related->slug) }}">
                            @if($related->image)
                                <img src="{{ asset('storage/' . $related->image) }}" 
                                     alt="{{ $related->title }}"
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No Image</span>
                                </div>
                            @endif
                            <div class="p-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $related->category->name }}
                                </span>
                                <h3 class="text-lg font-bold mt-2">{{ $related->title }}</h3>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <x-public.footer />
</body>
</html>