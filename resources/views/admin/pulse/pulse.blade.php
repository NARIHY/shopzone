<x-layouts.app :title="__('Application Monitoring')">
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-slate-50 to-white dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 text-slate-800 dark:text-slate-100 transition-colors duration-500">
        
        <!-- Header -->
        <header class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/20 via-blue-500/10 to-transparent dark:from-indigo-400/20 dark:via-indigo-300/10 blur-3xl opacity-60"></div>
            <div class="relative mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div>
                        <h1 class="text-4xl font-extrabold tracking-tight text-indigo-700 dark:text-indigo-400">
                            {{ __('Application Monitoring') }}
                        </h1>
                        <p class="mt-2 text-slate-600 dark:text-slate-400 text-sm">
                            {{ __('Real-time overview of your infrastructure and app performance') }}
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <button class="rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 shadow-md transition-all duration-300 hover:shadow-lg">
                            <i class="fa-solid fa-rotate-right mr-1"></i> {{ __('Refresh') }}
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Grid -->
        <main class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
            <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 auto-rows-[minmax(200px,auto)]">

                @php
                    $panelClasses = 'rounded-2xl bg-white/70 dark:bg-slate-800/70 shadow-lg backdrop-blur-md border border-slate-200/50 dark:border-slate-700/50 
                                     p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1';
                @endphp

                <!-- Servers -->
                <div class="col-span-full {{ $panelClasses }}">
                    <livewire:pulse.servers cols="full" />
                </div>

                <!-- Usage -->
                <div class="col-span-2 lg:col-span-1 {{ $panelClasses }}">
                    <livewire:pulse.usage cols="4" rows="2" />
                </div>

                <!-- Queues -->
                <div class="col-span-2 lg:col-span-1 {{ $panelClasses }}">
                    <livewire:pulse.queues cols="4" />
                </div>

                <!-- Cache -->
                <div class="col-span-2 lg:col-span-1 {{ $panelClasses }}">
                    <livewire:pulse.cache cols="4" />
                </div>

                <!-- Slow Queries -->
                <div class="col-span-full lg:col-span-2 {{ $panelClasses }}">
                    <livewire:pulse.slow-queries cols="8" />
                </div>

                <!-- Exceptions -->
                <div class="col-span-full md:col-span-2 {{ $panelClasses }}">
                    <livewire:pulse.exceptions cols="6" />
                </div>

                <!-- Slow Requests -->
                <div class="col-span-full md:col-span-2 {{ $panelClasses }}">
                    <livewire:pulse.slow-requests cols="6" />
                </div>

                <!-- Slow Jobs -->
                <div class="col-span-full md:col-span-2 {{ $panelClasses }}">
                    <livewire:pulse.slow-jobs cols="6" />
                </div>

                <!-- Outgoing Requests -->
                <div class="col-span-full md:col-span-2 {{ $panelClasses }}">
                    <livewire:pulse.slow-outgoing-requests cols="6" />
                </div>

                <div class="col-span-full">
                    <livewire:reverb.connections cols="full" />
                    <livewire:reverb.messages cols="full" />
                </div>

            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-200/50 dark:border-slate-700/50 py-6 text-center text-xs text-slate-500 dark:text-slate-500">
            <p>© {{ date('Y') }} — Laravel Pulse Dashboard. Crafted with ❤️ by <span class="text-indigo-500 font-semibold">{{__('NERKALY') }}</span>.</p>
        </footer>
    </div>
</x-layouts.app>
