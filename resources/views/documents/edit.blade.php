<x-app-layout>
    <x-slot name="header">
    <div class="flex items-center gap-4">
        <a href="{{ route('documents.index') }}" class="text-gray-500 hover:text-gray-800 text-sm">← Documents</a>
        <input id="title" value="{{ $document->title }}" class="font-semibold text-xl text-gray-800 border-b border-transparent focus:border-gray-300 focus:outline-none" />
    </div>
</x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4">

        <div class="flex justify-between items-center mb-4">
            <span id="save-status" class="text-sm text-gray-500"></span>

            @if($document->user_id === auth()->id())
            <form method="POST" action="{{ route('documents.share', $document) }}" class="flex gap-2">
                @csrf
                <input type="email" name="email" placeholder="Share with email" required class="border rounded px-2 py-1 text-sm">
                <button type="submit" class="bg-gray-700 text-white px-3 py-1 rounded text-sm hover:bg-gray-800">Share</button>
            </form>
            @endif
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4 text-sm">{{ session('success') }}</div>
        @endif
        @error('email')
            <div class="bg-red-100 text-red-800 p-2 rounded mb-4 text-sm">{{ $message }}</div>
        @enderror

        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
        <div id="editor" style="min-height: 400px; background: white;">{!! $document->content !!}</div>

        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
        <script>
            const isOwner = {{ $document->user_id === auth()->id() ? 'true' : 'false' }};

            const quill = new Quill('#editor', {
                theme: 'snow',
                readOnly: !isOwner,
                modules: {
                    toolbar: isOwner ? [
                        ['bold', 'italic', 'underline'],
                        [{ header: [1, 2, 3, false] }],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                    ] : false,
                },
            });

            let saveTimeout;
            const status = document.getElementById('save-status');

            function saveDocument() {
                status.textContent = 'Saving...';
                fetch("{{ route('documents.update', $document) }}", {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        title: document.getElementById('title').value,
                        content: quill.root.innerHTML,
                    }),
                })
                .then(res => res.json())
                .then(() => { status.textContent = 'Saved'; setTimeout(() => status.textContent = '', 1500); })
                .catch(() => { status.textContent = 'Error saving'; });
            }

            if (isOwner) {
                quill.on('text-change', () => {
                    clearTimeout(saveTimeout);
                    saveTimeout = setTimeout(saveDocument, 800);
                });
                document.getElementById('title').addEventListener('input', () => {
                    clearTimeout(saveTimeout);
                    saveTimeout = setTimeout(saveDocument, 800);
                });
            }
        </script>
    </div>
</x-app-layout>