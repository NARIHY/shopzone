<?php

namespace Tests\Feature\Files;

use App\Common\MediaView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();        
        $this->actingAs(User::factory()->create());
    }

    /** @test */
    public function test_it_displays_the_media_index_page()
    {
        $response = $this->get(route('admin.media.index'));

        $response->assertStatus(200);
        $response->assertViewIs(\App\Common\MediaView::getMediaListView());
    }

    /** @test */
    public function test_it_displays_the_media_create_page()
    {
        $response = $this->get(route('admin.media.create'));
        $response->assertStatus(200);
        $response->assertViewIs(\App\Common\MediaView::getMediaCreateOrEditView());
    }

    /* 
      Media::create([
                'title'         => $request->title,
                'path'          => $path,
                'disk'          => 'public',
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
                'original_name' => $file->getClientOriginalName(),
            ]);

    */
    public function test_it_can_store_a_new_media()
    {
        Storage::fake('public'); // Mock the 'public' storage disk
        $file = UploadedFile::fake()->image('test-image.jpg');

        $data = [
            'title' => 'Test Media',
            'file' => $file
        ];

        // Act: Make a POST request to the store route
        $response = $this->post(route('admin.media.store'), $data);

        // Assert: Check the response and database
        $response->assertRedirect(route('admin.media.index')); // Verify redirect to index
        $response->assertSessionHas('success', 'File uploaded successfully.'); 

        // Verify the Media model was created in the database
        $this->assertDatabaseHas('media', [
            'title' => $data['title']
        ]);
    }
    /** @test */
    public function test_it_displays_the_media_edit_page()
    {
        $media = \App\Models\Files\Media::factory()->create();

        $response = $this->get(route('admin.media.edit', $media));

        $response->assertViewIs(\App\Common\MediaView::getMediaCreateOrEditView());
        $response->assertViewHas('media', $media);
    }

    /** @test */
    public function test_it_can_update_a_media()
    {
        Storage::fake('public');
        $media = \App\Models\Files\Media::factory()->create();
        $file = UploadedFile::fake()->image('updated-image.jpg');

        $data = [
            'title' => 'Updated Media Title',
            'file' => $file,
        ];

        $response = $this->put(route('admin.media.update', $media), $data);

        $response->assertRedirect(route('admin.media.index'));
        $response->assertSessionHas('success', 'Media updated successfully.');

        $this->assertDatabaseHas('media', [
            'id' => $media->id,
            'title' => $data['title'],
        ]);
    }

    /** @test */
    public function test_it_fails_to_update_media_with_invalid_data()
    {
        $media = \App\Models\Files\Media::factory()->create();

        $data = [
            'title' => '',
            'file' => null,
        ];

        $response = $this->put(route('admin.media.update', $media), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function test_it_can_delete_a_media()
    {
        Storage::fake('public');
        $media = \App\Models\Files\Media::factory()->create();

        $response = $this->delete(route('admin.media.destroy', $media));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Media deleted successfully.');

        $this->assertDatabaseMissing('media', [
            'id' => $media->id,
        ]);
    }

    /** @test */
    public function test_it_fails_to_store_media_with_invalid_data()
    {
        $data = [
            'title' => 'Test Media',
            'file' => null,
        ];

        $response = $this->post(route('admin.media.store'), $data);

        $response->assertRedirectBack();
        $response->assertSessionHasErrors(['file']);
    }
}
