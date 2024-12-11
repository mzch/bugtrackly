<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Storage path for the profile photo
    |--------------------------------------------------------------------------
    |
    */
    'storage_path'         => env('USER_AVATAR_STORAGE_PATH', 'profile-photos'),

    /*
    |--------------------------------------------------------------------------
    | Storage disk for the profile photo
    |--------------------------------------------------------------------------
    |
    */
    'profile_photo_disk'   => env('USER_AVATAR_STORAGE_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Should we use the gravatar service ?
    |--------------------------------------------------------------------------
    |
    */
    'use_gravatar_service' => env('USER_AVATAR_USE_GRAVATAR', false),

    /*
    |--------------------------------------------------------------------------
    | Default avatar path
    |--------------------------------------------------------------------------
    |
    */
    'default_avatar_path'  => env('USER_AVATAR_DEFAULT_PATH', 'default.png'),
];
