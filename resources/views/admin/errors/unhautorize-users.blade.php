<x-layouts.app :title="__('Access Denied')">
    <div class="flex items-center justify-center min-h-screen bg-gray-50 dark:bg-gray-900 px-6">
        <div class="text-center max-w-lg bg-white dark:bg-gray-800 p-10 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
            
            {{-- Illustration ou icône --}}
            <div class="mb-6">
                <svg class="mx-auto h-20 w-20 text-red-500 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
                </svg>
            </div>

            {{-- Titre principal --}}
            <h1 class="text-4xl font-extrabold text-gray-800 dark:text-gray-100 mb-2">403</h1>
            <h2 class="text-xl text-gray-600 dark:text-gray-300 mb-4">Oops! Access Denied</h2>

            {{-- Message amical --}}
            <p class="text-gray-500 dark:text-gray-400 mb-4">
                You do not have permission to access this page. It might be restricted or require special permissions.
            </p>
            
            {{-- Message supplémentaire --}}
            <p class="text-gray-500 dark:text-gray-400 mb-6 font-medium">
                Please contact your administrator if you believe this is an error.
            </p>

            {{-- Actions utiles --}}
            <div class="flex flex-col sm:flex-row justify-center gap-3">
                <a href="" class="px-6 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                    Go Back Home
                </a>
                <a href="" class="px-6 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg shadow hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Contact Support
                </a>
            </div>

            {{-- Astuce supplémentaire --}}
            <p class="mt-6 text-sm text-gray-400 dark:text-gray-500">
                If you think this is an error, please contact support with the link above.
            </p>
        </div>
    </div>
</x-layouts.app>
