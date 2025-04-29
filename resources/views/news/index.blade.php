<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('News Management') }}
            </h2>
            <a href="{{ route('news.create') }}" 
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                Add New Article
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('news.index') }}" method="GET" class="flex flex-wrap gap-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <input type="text" 
                                   name="search" 
                                   placeholder="Cari berita..." 
                                   value="{{ request('search') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Category Filter -->
                        <div class="w-48">
                            <select name="category" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort Options -->
                        <div class="w-48">
                            <select name="sort" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul (A-Z)</option>
                                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Judul (Z-A)</option>
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div>
                            <button type="submit" 
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Cari
                            </button>
                            @if(request()->has('search') || request()->has('category') || request()->has('sort'))
                                <a href="{{ route('news.index') }}" 
                                   class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($news as $article)
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
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $article->category->name }}
                                        </span>
                                        <span class="text-xs font-medium px-2.5 py-0.5 rounded {{ $article->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $article->status === 'published' ? 'Published' : 'Draft' }}
                                        </span>
                                    </div>
                                    
                                    <h3 class="text-xl font-bold mb-2">{{ $article->title }}</h3>
                                    <p class="text-gray-600 mb-4 line-clamp-3">
                                        {{ Str::limit(strip_tags($article->content), 150) }}
                                    </p>
                                    
                                    <div class="flex justify-end space-x-2">
                                        <!-- Preview Button -->
                                        <a href="{{ route('news.preview', $article) }}" 
                                           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                            Preview
                                        </a>

                                        <!-- Publish/Unpublish Button -->
                                        <form action="{{ route('news.update.status', $article) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" 
                                                   value="{{ $article->status === 'published' ? 'draft' : 'published' }}">
                                            <button type="submit" 
                                                    onclick="return confirm('Apakah Anda yakin ingin {{ $article->status === 'published' ? 'membatalkan publikasi' : 'mempublikasikan' }} berita ini?')"
                                                    class="{{ $article->status === 'published' 
                                                        ? 'bg-yellow-500 hover:bg-yellow-600' 
                                                        : 'bg-green-500 hover:bg-green-600' }} text-white px-3 py-1 rounded text-sm">
                                                {{ $article->status === 'published' ? 'Unpublish' : 'Publish' }}
                                            </button>
                                        </form>

                                        <!-- Edit Button -->
                                        <a href="{{ route('news.edit', $article) }}" 
                                           class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-sm">
                                            Edit
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('news.destroy', $article) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"
                                                    onclick="return confirm('Are you sure you want to delete this article?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-gray-500">
                                No articles found.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $news->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>