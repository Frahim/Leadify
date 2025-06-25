<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('List') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="container">
      
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <table class="table-wrapper border-collapse border min-w-full">
            <thead>
                <tr>                    
                    <th class="text-left p-4">Name</th>
                    <th class="p-4">C Job Title</th>
                    <th class="p-4">C Company</th>                    
                    <th class="p-4">Location</th>
                    <th class="p-4">Email 1</th>
                    <th class="p-4">Email 2</th>
                    <th class="p-4">Phone</th>
                    <th class="p-4">Linkden URL</th>
                    <th class="p-4">Time</th>
                    <!-- <th class="p-4"> <a target="_blank" class="btn" href="/leads/export">Export</a></th> -->
                </tr>
            </thead>
            <tbody>
                @foreach($leads as $lead)
                    <tr>
                        
                        <td class="p-4 border ">{{ $lead->name }}</td>
                        <td class="p-4 border  ">{{ $lead->designation }}</td>
                        <td class="p-4 border ">{{ $lead->company }}</td>
                        <td class="p-4 border ">{{ $lead->location }}</td>
                        <td class="p-4 border  ">{{ $lead->email1 }}</td>
                        <td class="p-4 border  ">{{ $lead->email2 }}</td>
                        <td class="p-4 border ">{{ $lead->phone }}</td>
                        <td class="p-4 border ">{{ $lead->url }}</td>
                        <th class="p-4">{{ $lead->created_at }}</th>
                        <!-- <td class="p-4 border ">
                            <a href="#">View</a>
                        </td> -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
</x-app-layout>