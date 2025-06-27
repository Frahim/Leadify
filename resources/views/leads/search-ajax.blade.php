<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Live Search Leads') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <!-- Search Input -->
            <input
                type="text"
                id="searchInput"
                class="w-full px-4 py-2 mb-4 border border-gray-300 rounded dark:bg-gray-700 dark:text-white"
                placeholder="Start typing to search leads...">

            <!-- Results Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border border-gray-200 dark:border-gray-700 text-sm" id="resultsTable">
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
                    <tbody id="resultsBody" class="text-gray-800 dark:text-gray-100">
                        <!-- Filled by JS -->
                    </tbody>
                </table>
            </div>

            <div id="noResults" class="mt-4 text-center text-gray-500 dark:text-gray-300 hidden">
                No results found.
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById("searchInput");
        const resultsBody = document.getElementById("resultsBody");
        const noResults = document.getElementById("noResults");

        let timeout;

        searchInput.addEventListener("input", function() {
            clearTimeout(timeout);
            const query = this.value.trim();

            timeout = setTimeout(() => {
                fetch(`/ajax/leads/search?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        resultsBody.innerHTML = "";
                        if (data.length === 0) {
                            noResults.classList.remove("hidden");
                        } else {
                            noResults.classList.add("hidden");
                            data.forEach(lead => {
                                resultsBody.innerHTML += `
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                        <td class="px-4 py-2 border">${lead.name || ''}</td>                                       
                                        <td class="px-4 py-2 border">${lead.designation || ''}</td>
                                        <td class="px-4 py-2 border">${lead.company || ''}</td>
                                        <td class="px-4 py-2 border">${lead.location || ''}</td>
                                        <td class="px-4 py-2 border">${lead.email1 || ''}</td>
                                        <td class="px-4 py-2 border">${lead.email2 || ''}</td>
                                        <td class="px-4 py-2 border">${lead.phone || ''}</td>                                           
                                        <td class="px-4 py-2 border">
                                            ${lead.url ? `<a href="${lead.url}" target="_blank" class="text-indigo-600 hover:underline">View</a>` : 'N/A'}
                                        </td>
                                        <td class="p-4">${ lead.created_at || ''}</td>
                                    </tr>
                                `;
                            });
                        }
                    });
            }, 300); // debounce
        });
    </script>
</x-app-layout>