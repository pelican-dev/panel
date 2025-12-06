<?php

namespace App\Contracts\Http;

use App\Enums\SubuserPermission;

interface ClientPermissionsRequest
{
    /**
     * Returns the permission used to validate that the authenticated user may perform
     * this action against the given resource (server).
     */
    public function permission(): SubuserPermission|string;
}
