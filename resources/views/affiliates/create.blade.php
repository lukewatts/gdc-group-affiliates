<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <ul>
                        @foreach ($affiliates as $affiliate)
                            <li class="flex flex-row max-w-[250px] justify-between">
                                <span class="text-xl font-bold">ID:</span>
                                <span class="text-lg">{{ $affiliate->affiliate_id }}</span>
                            </li>
                            <li class="flex flex-row max-w-[250px] justify-between">
                                <span class="text-xl font-bold">Name:</span>
                                <span class="text-lg">{{ $affiliate->name }}</span>
                            </li>
                            <li class="flex flex-row max-w-[250px] justify-between">
                                <span class="text-xl font-bold">Latitude:</span>
                                <span class="text-lg">{{ $affiliate->latitude }}</span>
                            </li>
                            <li class="flex flex-row max-w-[250px] justify-between">
                                <span class="text-xl font-bold">Longitude:</span>
                                <span class="text-lg">{{ $affiliate->longitude }}</span>
                            </li>
                            <li class="flex flex-row max-w-[250px] justify-between">
                                <span class="text-xl font-bold">Distance:</span>
                                <span class="text-lg">{{ $affiliate->distance }}</span>
                            </li>
                            @if (!$loop->last)
                                <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
