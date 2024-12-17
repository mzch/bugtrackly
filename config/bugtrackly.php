<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Storage path for the profile photo
    |--------------------------------------------------------------------------
    |
    */
    'storage_path'         => env('BUGTRACKLY_USER_AVATAR_STORAGE_PATH', 'profile-photos'),

    /*
    |--------------------------------------------------------------------------
    | Storage disk for the profile photo
    |--------------------------------------------------------------------------
    |
    */
    'profile_photo_disk'   => env('BUGTRACKLY_USER_AVATAR_STORAGE_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Should we use the gravatar service ?
    |--------------------------------------------------------------------------
    |
    */
    'use_gravatar_service' => env('BUGTRACKLY_USER_AVATAR_USE_GRAVATAR', false),

    'use_initiales_as_avatar' => env('BUGTRACKLY_USER_AVATAR_INITIALS', false),

];
