<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Documents</h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4">
        <form method="POST" action="{{ route('documents.store') }}" class="mb-6">
            @csrf
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + New Document
            </button>
        </form>
        <form method="POST" action="{{ route('documents.upload') }}" enctype="multipart/form-data" class="mb-8 flex items-center gap-2">
            @csrf
            <input type="file" name="file" accept=".txt,.md" required class="text-sm">
            <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-800 text-sm">
                Upload as Document
            </button>
        </form>
        @error('file')
            <div class="bg-red-100 text-red-800 p-2 rounded mb-4 text-sm">{{ $message }}</div>
        @enderror

        <h3 class="font-semibold text-lg mb-2">Owned by me</h3>
        <ul class="mb-8 space-y-2">
            @forelse($owned as $doc)
                <li class="bg-white p-3 rounded shadow flex justify-between items-center">
                    <a href="{{ route('documents.edit', $doc) }}" class="text-blue-600 hover:underline">
                        {{ $doc->title }}
                    </a>
                    <span class="text-xs text-gray-400">Owner</span>
                </li>
            @empty
                <li class="text-gray-500">No documents yet.</li>
            @endforelse
        </ul>

        <h3 class="font-semibold text-lg mb-2">Shared with me</h3>
        <ul class="space-y-2">
            @forelse($shared as $doc)
                <li class="bg-white p-3 rounded shadow flex justify-between items-center">
                    <a href="{{ route('documents.edit', $doc) }}" class="text-blue-600 hover:underline">
                        {{ $doc->title }}
                    </a>
                    <span class="text-xs text-gray-400">Shared</span>
                </li>
            @empty
                <li class="text-gray-500">Nothing shared with you yet.</li>
            @endforelse
        </ul>
    </div>
</x-app-layout>
