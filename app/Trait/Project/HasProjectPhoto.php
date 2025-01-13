<?php

namespace App\Trait\Project;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasProjectPhoto
{
    /**
     * Update the user's profile photo.
     *
     * @param UploadedFile $photo
     * @param false|string $storagePath
     * @return void
     */
    public function updateProjectPhoto(UploadedFile $photo, false|string $storagePath = false)
    {
        if(!$storagePath){
            $storagePath = config('bugtrackly.project_photo_storage_path');
        }
        tap($this->project_photo_path, function ($previous) use ($photo, $storagePath) {
            $this->forceFill([
                'project_photo_path' => $photo->storePublicly(
                    $storagePath, ['disk' => $this->projectPhotoDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->projectPhotoDisk())->delete($previous);
            }
        });
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return Attribute
     */
    public function projectPhotoUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            return $this->project_photo_path
                ? Storage::disk($this->projectPhotoDisk())->url($this->project_photo_path)
                : null;
        });
    }

    protected function projectPhotoDisk()
    {
        return config('bugtrackly.profile_project_disk', 'public');
    }

    /**
     * Delete the project's photo.
     *
     * @return void
     */
    public function deleteProjectPhoto()
    {
        if (is_null($this->project_photo_path)) {
            return;
        }

        Storage::disk($this->projectPhotoDisk())->delete($this->project_photo_path);

        $this->forceFill([
            'project_photo_path' => null,
        ])->save();
    }
}
