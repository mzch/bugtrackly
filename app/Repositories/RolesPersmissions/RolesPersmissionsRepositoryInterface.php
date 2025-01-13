<?php

namespace App\Repositories\RolesPersmissions;

interface RolesPersmissionsRepositoryInterface
{
    public function getAllRoles();
    public function getAllRolesWithPermissions();

    public function getRoleByIdWithPermissions($roleId):array|null;
}
