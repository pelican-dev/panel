<?php

namespace App\Contracts\Http;

use App\Enums\SubuserPermission;

interface ClientPermissionsRequest
{
    /**
     * Returns the permissions string indicating which permission should be used to
     * validate that the authenticated user has permission to perform this action against
     * the given resource (server).
     */
    public function permission(): SubuserPermission|string;
}
