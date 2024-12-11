<?php

namespace App\Trait\User;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

trait HasProfilePhoto
{
    /**
     * Get the URL to the user's profile photo.
     *
     * @return Attribute
     */
    public function profilePhotoUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            return $this->profile_photo_path
                ? Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path)
                : $this->defaultProfilePhotoUrl();
        });
    }

    /**
     * @return string|null
     */
    private function defaultProfilePhotoUrl(): ?string
    {
        $default_avatar_url = $this->getDefaultProfilePhotoUrl();
        if (config('avatar.use_gravatar_service') && $gravatar_url = $this->get_gravatar_url()) {
            return $gravatar_url;
        }
        return $default_avatar_url;
    }

    /**
     * Retourne l'url gravatar de l'utilisateur ou null s'il n'existe pas.
     * @return string|null
     */
    private function get_gravatar_url(): ?string
    {
        // Créez l'URL du Gravatar
        $email = $this->getAttribute('email');
        $size = 80;
        $gravatarUrl = 'https://www.gravatar.com/avatar/' . hash("sha256", strtolower(trim($email))) . '?d=404' . "&s=" . $size;;
        $headers = @get_headers($gravatarUrl);
        if ($headers && strpos($headers[0], '200')) {
            return $gravatarUrl;
        } else {
            return null;
        }
    }

    protected function getDefaultProfilePhotoUrl(): ?string
    {
        $defaultPath = config('avatar.default_avatar_path');
        $storagePath = config('avatar.storage_path');
        if (is_null($defaultPath)) {
            return null;
        }
        return Storage::disk($this->profilePhotoDisk())->url($storagePath . '/' . $defaultPath);
    }

    protected function profilePhotoDisk()
    {
        return config('avatar.profile_photo_disk', 'public');
    }
}
