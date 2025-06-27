<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-2xl text-gray-900 dark:text-white leading-tight">
                {{ __('My Leads') }}
            </h2>
            <a href="{{ route('leads.import.form') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 border border-red-700 rounded">Import Leads</a>
        </div>

    </x-slot>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">

            @if(session('success'))
            <div class="mb-4 text-green-700 bg-green-100 border border-green-300 rounded-lg p-4 dark:bg-green-800 dark:text-green-100">
                {{ session('success') }}
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border border-gray-300 dark:border-gray-700 text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-2 border">Name</th>
                            <th class="px-4 py-2 border">Job Title</th>
                            <th class="px-4 py-2 border">Company</th>
                            <th class="px-4 py-2 border">Location</th>
                            <th class="px-4 py-2 border">Email 1</th>
                            <th class="px-4 py-2 border">Email 2</th>
                            <th class="px-4 py-2 border">Phone</th>
                            <th class="px-4 py-2 border">LinkedIn URL</th>
                            <th class="px-4 py-2 border">Time</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100">
                        @forelse($leads as $lead)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-2 border">{{ $lead->name }}</td>
                            <td class="px-4 py-2 border">{{ $lead->designation }}</td>
                            <td class="px-4 py-2 border">{{ $lead->company }}</td>
                            <td class="px-4 py-2 border">{{ $lead->location }}</td>
                            <td class="px-4 py-2 border">{{ $lead->email1 }}</td>
                            <td class="px-4 py-2 border">{{ $lead->email2 }}</td>
                            <td class="px-4 py-2 border">{{ $lead->phone }}</td>
                            <td class="px-4 py-2 border">
                                @if($lead->url)
                                <a href="{{ $lead->url }}" target="_blank" class="text-indigo-600 hover:underline">View</a>
                                @else
                                N/A
                                @endif
                            </td>
                            <td class="px-4 py-2 border">{{ $lead->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center px-4 py-6 text-gray-500 dark:text-gray-300">
                                No leads found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $leads->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>