<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
            {{ __('Import Leads') }}
        </h2>
    </x-slot>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
            <div class="max-w-3xl mx-auto mt-8 p-6 bg-white dark:bg-gray-800 shadow-lg rounded-xl">
                @if (session('success'))
                <div class="mb-4 p-4 text-green-700 bg-green-100 rounded dark:bg-green-200 dark:text-green-900">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="mb-4 p-4 text-red-700 bg-red-100 rounded dark:bg-red-200 dark:text-red-900">
                    {{ session('error') }}
                </div>
                @if ($errors->any())
                <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                    @foreach (session('errors')->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @endif
                @endif

                <form method="POST" action="{{ route('leads.import') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Choose Excel/CSV File</label>
                        <input id="file" name="file" type="file" required
                            class="mt-1 block w-full text-sm text-gray-900 bg-gray-100 border border-gray-300 rounded-lg cursor-pointer dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600">
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Supported formats: .xls, .xlsx, .csv. Max size: 10MB.</p>
                        @error('file')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            Import Leads
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-sm text-gray-600 dark:text-gray-400">
                    <p><strong>⚠️ Important:</strong> Your file must contain headers like:</p>
                    <p><code class="bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">name, email1, email2, phone, url, designation, company, location</code></p>
                    <p class="mt-2">💡 Tip: Export some leads first to generate a template format.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>