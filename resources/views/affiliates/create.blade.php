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
                    <table class="responsive-table">
                        <thead class="bg-indigo-700 border-b">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Distance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($affiliates as $affiliate)
                                <tr>
                                    <td data-title="ID">{{ $affiliate->affiliate_id }}</td>
                                    <td data-title="Name">{{ $affiliate->name }}</td>
                                    <td data-title="Latitude">{{ $affiliate->latitude }}</td>
                                    <td data-title="Longitude">{{ $affiliate->longitude }}</td>
                                    <td data-title="Distance">{{ $affiliate->distance }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
