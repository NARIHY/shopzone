<?php

namespace App\Http\Controllers\Files;

use App\Common\MediaView;
use App\Http\Controllers\Controller;
use App\Models\Files\Media;
use App\Http\Requests\Files\StoreMediaRequest;
use App\Http\Requests\Files\UpdateMediaRequest;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a paginated list of media files.
     */
    public function index()
    {
        return view(MediaView::getCategoryListView());
    }

    /**
     * Show the media upload form.
     */
    public function create()
    {
        return view(MediaView::getCategoryCreateOrEditView());
    }

    /**
     * Store a newly uploaded media file.
     */
    public function store(StoreMediaRequest $request)
    {
        try {
            $file = $request->file('file');
            $path = $file->store('', 'public');
            dd($request);
            Media::create([
                'title'=> $request->validated('title'),
                'path'          => $path,
                'disk'          => 'public',
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
                'original_name' => $file->getClientOriginalName(),
            ]);

            return redirect()
                ->route('admin.media.index')
                ->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['file' => 'File upload failed: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display a single media file.
     */
    public function show(Media $media)
    {
    }

    /**
     * Show the edit form for a media file.
     */
    public function edit(Media $media)
    {
        return view(MediaView::getCategoryCreateOrEditView(), compact('media'));
    }

    /**
     * Update an existing media file.
     */
    public function update(UpdateMediaRequest $request, Media $media)
    {
        try {
            if ($request->hasFile('file')) {
                // Delete old file if it exists
                if ($media->path && Storage::disk($media->disk)->exists($media->path)) {
                    Storage::disk($media->disk)->delete($media->path);
                }

                $file = $request->file('file');
                $path = $file->store('', 'public');

                $media->update([
                    'title'=> $request->validated('title'),
                    'path'          => $path,
                    'disk'          => 'public',
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }

            return redirect()
                ->route('admin.media.index')
                ->with('success', 'Media updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['file' => 'Media update failed: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Delete a media file.
     */
    public function destroy(Media $media)
    {
        try {
            if ($media->path && Storage::disk($media->disk)->exists($media->path)) {
                Storage::disk($media->disk)->delete($media->path);
            }

            $media->delete();

            return redirect()
                ->back()
                ->with('success', 'Media deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to delete media: ' . $e->getMessage());
        }
    }
}
