<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Preview Header -->
                    <div class="mb-6 flex justify-between items-center">
                        <h1 class="text-3xl font-bold">Preview Mode</h1>
                        <div class="space-x-2">
                            <a href="{{ route('news.edit', $news) }}" 
                               class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                Edit
                            </a>

                            <!-- Publish/Unpublish Button -->
                            <form action="{{ route('news.update.status', $news) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" 
                                       value="{{ $news->status === 'published' ? 'draft' : 'published' }}">
                                <button type="submit" 
                                        onclick="return confirm('Apakah Anda yakin ingin {{ $news->status === 'published' ? 'membatalkan publikasi' : 'mempublikasikan' }} berita ini?')"
                                        class="{{ $news->status === 'published' 
                                            ? 'bg-yellow-600 hover:bg-yellow-700' 
                                            : 'bg-green-600 hover:bg-green-700' }} text-white px-4 py-2 rounded-md">
                                    {{ $news->status === 'published' ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Article Content -->
                    <div class="prose max-w-none">
                        <!-- Category & Date -->
                        <div class="flex items-center gap-4 mb-6">
                            <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded">
                                {{ $news->category->name }}
                            </span>
                            <span class="text-gray-500">
                                {{ $news->created_at->format('M d, Y') }}
                            </span>
                        </div>

                        <!-- Image -->
                        @if($news->image)
                            <img src="{{ asset('storage/' . $news->image) }}" 
                                 alt="{{ $news->title }}"
                                 class="w-full h-96 object-cover rounded-lg mb-6">
                        @endif

                        <!-- Title -->
                        <h1 class="text-4xl font-bold mb-6">{{ $news->title }}</h1>

                        <!-- Content -->
                        <div class="prose max-w-none">
                            {!! $news->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>