@props(['categories'])

<header class="bg-white shadow-sm sticky top-0 z-50" x-data="{ categoryOpen: false, scroll: 0 }">
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
                <form action="{{ route('search') }}" method="GET" class="flex">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Search..." 
                        class="px-4 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        value="{{ request('q') }}"
                    >
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700">
                        Search
                    </button>
                </form>
                <button 
                    @click="categoryOpen = !categoryOpen"
                    class="px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 hover:bg-gray-50"
                >
                    Kategori
                </button>
            </div>
        </div>
    </div>

    <!-- Category Sub-header -->
    <div 
        x-show="categoryOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="border-t bg-white"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 relative">
            <!-- Tombol Navigasi Kiri -->
            <button 
                @click="scroll = Math.max(scroll - 200, 0)"
                class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-white shadow-md rounded-full p-2 hover:bg-gray-50 z-10"
                x-show="scroll > 0"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <!-- Kategori Container -->
            <div class="overflow-hidden relative">
                <div 
                    class="flex space-x-4 justify-center transition-transform duration-300 ease-in-out"
                    :style="{ transform: `translateX(-${scroll}px)` }"
                >
                    @foreach($categories as $category)
                        <a href="#" 
                           class="text-gray-600 hover:text-indigo-600 hover:bg-gray-50 px-4 py-2 rounded-md text-sm whitespace-nowrap"
                        >
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Tombol Navigasi Kanan -->
            <button 
                @click="scroll = Math.min(scroll + 200, $refs.categoryContainer.scrollWidth - $refs.categoryContainer.clientWidth)"
                class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-white shadow-md rounded-full p-2 hover:bg-gray-50 z-10"
                x-show="$refs.categoryContainer.scrollWidth > $refs.categoryContainer.clientWidth"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</header>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('header', () => ({
            categoryOpen: false
        }))
    })
</script>
