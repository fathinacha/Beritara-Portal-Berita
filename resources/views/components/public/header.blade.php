@props(['categories'])

<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <a href="/" class="flex items-center">
                    <img src="{{ asset('images/beritaraLogo.png') }}" 
                         alt="Beritara Logo" 
                         class="h-8 w-auto mr-2">
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <form class="flex">
                    <input type="text" placeholder="Search..." class="px-4 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700">
                        Search
                    </button>
                </form>
                <select class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</header>