<?php

namespace App\Repositories\RolesPersmissions;

interface RolesPersmissionsRepositoryInterface
{
    public function getAllRolesWithPermissions();

    public function getRoleByIdWithPermissions($roleId):array|null;
}
