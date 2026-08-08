<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function index()
    {
        $owned = Document::where('user_id', Auth::id())->latest()->get();
        $shared = Auth::user()->sharedDocuments()->latest()->get();

        return view('documents.index', compact('owned', 'shared'));
    }

    public function store(Request $request)
    {
        $document = Document::create([
            'title' => 'Untitled Document',
            'content' => '',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('documents.edit', $document);
    }

    public function edit(Document $document)
    {
        $this->authorizeAccess($document);

        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $this->authorizeAccess($document);

        if ($document->user_id !== Auth::id()) {
            return response()->json(['error' => 'Only the owner can edit this document.'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|nullable|string',
        ]);

        $document->update($validated);

        return response()->json(['message' => 'Saved', 'document' => $document]);
    }

    public function share(Request $request, Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            return back()->withErrors(['error' => 'Only the owner can share this document.']);
        }

        $validated = $request->validate(['email' => 'required|email']);

        $user = \App\Models\User::where('email', $validated['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No user found with that email.']);
        }

        $document->sharedWith()->syncWithoutDetaching([$user->id]);

        return back()->with('success', 'Document shared successfully.');
    }

    private function authorizeAccess(Document $document)
    {
        $isOwner = $document->user_id === Auth::id();
        $isShared = $document->sharedWith()->where('user_id', Auth::id())->exists();

        abort_unless($isOwner || $isShared, 403);
    }

    public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:txt,md|max:2048',
    ]);

    $content = file_get_contents($request->file('file')->getRealPath());
    $title = pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME);

    // Convert plain text to basic HTML paragraphs so Quill can display it
    $htmlContent = '<p>' . nl2br(e($content)) . '</p>';

    $document = Document::create([
        'title' => $title,
        'content' => $htmlContent,
        'user_id' => Auth::id(),
    ]);

    return redirect()->route('documents.edit', $document);
}
}