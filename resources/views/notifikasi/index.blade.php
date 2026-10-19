<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Notifikasi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($notifications as $notification)
                    <a
                        href="{{ $notification->data['url'] ?? '#' }}"
                        class="block p-5 hover:bg-gray-50 dark:hover:bg-gray-700/50 {{ $notification->read_at ? '' : 'bg-blue-50/50 dark:bg-blue-900/10' }}"
                    >
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ $notification->data['judul'] ?? 'Notifikasi' }}
                        </p>
                        @if (!empty($notification->data['pesan']))
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                {{ $notification->data['pesan'] }}
                            </p>
                        @endif
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </a>
                @empty
                    <p class="p-6 text-sm text-gray-500 dark:text-gray-400">
                        Belum ada notifikasi.
                    </p>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
