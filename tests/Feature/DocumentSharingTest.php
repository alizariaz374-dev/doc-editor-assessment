<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentSharingTest extends TestCase
{
    use RefreshDatabase;

    public function test_shared_user_can_view_but_not_edit_a_document_they_dont_own()
    {
        $owner = User::factory()->create();
        $sharedUser = User::factory()->create();

        $document = Document::create([
            'title' => 'Test Document',
            'content' => '<p>Original content</p>',
            'user_id' => $owner->id,
        ]);

        // Not shared yet — should be forbidden
        $response = $this->actingAs($sharedUser)->get(route('documents.edit', $document));
        $response->assertStatus(403);

        // Owner shares it with the second user
        $document->sharedWith()->attach($sharedUser->id);

        // Now the shared user can view it
        $response = $this->actingAs($sharedUser)->get(route('documents.edit', $document));
        $response->assertStatus(200);

        // But the shared user cannot update it via the API
        $response = $this->actingAs($sharedUser)->putJson(route('documents.update', $document), [
            'content' => '<p>Hacked content</p>',
        ]);
        $response->assertStatus(403);

        // Content should be unchanged
        $this->assertEquals('<p>Original content</p>', $document->fresh()->content);
    }

    public function test_owner_can_edit_their_own_document()
    {
        $owner = User::factory()->create();

        $document = Document::create([
            'title' => 'Test Document',
            'content' => '<p>Original</p>',
            'user_id' => $owner->id,
        ]);

        $response = $this->actingAs($owner)->putJson(route('documents.update', $document), [
            'content' => '<p>Updated</p>',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('<p>Updated</p>', $document->fresh()->content);
    }
}