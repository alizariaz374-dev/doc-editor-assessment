<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4">
        @php
            $ownedCount = auth()->user()->documents()->count();
            $sharedCount = auth()->user()->sharedDocuments()->count();
        @endphp

        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded shadow text-center">
                <div class="text-2xl font-bold">{{ $ownedCount + $sharedCount }}</div>
                <div class="text-sm text-gray-500">Total Documents</div>
            </div>
            <div class="bg-white p-4 rounded shadow text-center">
                <div class="text-2xl font-bold">{{ $ownedCount }}</div>
                <div class="text-sm text-gray-500">Owned by Me</div>
            </div>
            <div class="bg-white p-4 rounded shadow text-center">
                <div class="text-2xl font-bold">{{ $sharedCount }}</div>
                <div class="text-sm text-gray-500">Shared with Me</div>
            </div>
        </div>

        <a href="{{ route('documents.index') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Go to My Documents →
        </a>
    </div>
</x-app-layout>