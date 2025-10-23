<?php

namespace App\Http\Controllers\Files;

use App\Common\MediaView;
use App\Http\Controllers\Controller;
use App\Models\Files\Media;
use App\Http\Requests\Files\StoreMediaRequest;
use App\Http\Requests\Files\UpdateMediaRequest;
use App\Jobs\Files\ProcessCreateMediaJob;
use App\Jobs\Files\ProcessUpdateMediaJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a paginated list of media files.
     */
    public function index()
    {
        return view(MediaView::getMediaListView());
    }

    /**
     * Show the media upload form.
     */
    public function create()
    {
        return view(MediaView::getMediaCreateOrEditView());
    }

    /**
     * Show the drive manager view.
     */
    public function drive()
    {
        return view(MediaView::getMediaDriveView());
    }

    /**
     * Store a newly uploaded media file.
     */
    public function store(StoreMediaRequest $request)
    {
        try {
            $file = $request->file('file');
            // stocker localement en temp (disk 'local') pour éviter problèmes d'import immédiat
            $localPath = $file->store('uploads/temp', 'local'); // exemple: uploads/temp/xxxxx

            // dispatch job qui va copier vers 'public' et créer l'enregistrement DB
            $userId = Auth::id();
            ProcessCreateMediaJob::dispatch($localPath, $request->validated('title'), $userId); // optionnel: mettre sur queue 'media'

            return redirect()
                ->route('admin.media.index')
                ->with('success', 'File uploaded and queued for processing.');
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
        //
    }

    /**
     * Show the edit form for a media file.
     */
    public function edit(Media $media)
    {
        return view(MediaView::getMediaCreateOrEditView(), compact('media'));
    }

    /**
     * Update an existing media file.
     */
    public function update(UpdateMediaRequest $request, Media $media)
    {
        try {
            if ($request->hasFile('file')) {
                // store new uploaded file first on local disk
                $localPath = $request->file('file')->store('uploads/temp', 'local');

                // dispatch job to handle replacing the file and updating DB
                ProcessUpdateMediaJob::dispatch($media->id, $request->validated('title'), $localPath);

                return redirect()
                    ->route('admin.media.index')
                    ->with('success', 'Media update queued. Waiting for processing to finalize the update.');
            } else {
                // no file: update title synchronously (fast)
                $media->update([
                    'title' => $request->validated('title'),
                ]);

                return redirect()
                    ->route('admin.media.index')
                    ->with('success', 'Media updated successfully.');
            }
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
