<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Application;

/*
|--------------------------------------------------------------------------
| User Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /api/application/users
|
*/
Route::prefix('/users')->group(function () {
    Route::get('/', [Application\Users\UserController::class, 'index'])->name('api.application.users');
    Route::get('/{user:id}', [Application\Users\UserController::class, 'view'])->name('api.application.users.view');
    Route::get('/external/{external_id}', [Application\Users\ExternalUserController::class, 'index'])->name('api.application.users.external');

    Route::post('/', [Application\Users\UserController::class, 'store']);
    Route::patch('/{user:id}', [Application\Users\UserController::class, 'update']);

    Route::patch('/{user:id}/roles/assign', [Application\Users\UserController::class, 'assignRoles']);
    Route::patch('/{user:id}/roles/remove', [Application\Users\UserController::class, 'removeRoles']);

    Route::delete('/{user:id}', [Application\Users\UserController::class, 'delete']);
});

/*
|--------------------------------------------------------------------------
| Node Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /api/application/nodes
|
*/
Route::prefix('/nodes')->group(function () {
    Route::get('/', [Application\Nodes\NodeController::class, 'index'])->name('api.application.nodes');
    Route::get('/deployable', Application\Nodes\NodeDeploymentController::class);
    Route::get('/{node:id}', [Application\Nodes\NodeController::class, 'view'])->name('api.application.nodes.view');
    Route::get('/{node:id}/configuration', Application\Nodes\NodeConfigurationController::class);

    Route::post('/', [Application\Nodes\NodeController::class, 'store']);
    Route::patch('/{node:id}', [Application\Nodes\NodeController::class, 'update']);

    Route::delete('/{node:id}', [Application\Nodes\NodeController::class, 'delete']);

    Route::prefix('/{node:id}/allocations')->group(function () {
        Route::get('/', [Application\Nodes\AllocationController::class, 'index'])->name('api.application.allocations');
        Route::post('/', [Application\Nodes\AllocationController::class, 'store']);
        Route::delete('/{allocation:id}', [Application\Nodes\AllocationController::class, 'delete'])->name('api.application.allocations.view');
    });
});

/*
|--------------------------------------------------------------------------
| Server Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /api/application/servers
|
*/
Route::prefix('/servers')->group(function () {
    Route::get('/', [Application\Servers\ServerController::class, 'index'])->name('api.application.servers');
    Route::get('/{server:id}', [Application\Servers\ServerController::class, 'view'])->name('api.application.servers.view');
    Route::get('/external/{external_id}', [Application\Servers\ExternalServerController::class, 'index'])->name('api.application.servers.external');

    Route::patch('/{server:id}/details', [Application\Servers\ServerDetailsController::class, 'details'])->name('api.application.servers.details');
    Route::patch('/{server:id}/build', [Application\Servers\ServerDetailsController::class, 'build'])->name('api.application.servers.build');
    Route::patch('/{server:id}/startup', [Application\Servers\StartupController::class, 'index'])->name('api.application.servers.startup');

    Route::post('/', [Application\Servers\ServerController::class, 'store']);
    Route::post('/{server:id}/suspend', [Application\Servers\ServerManagementController::class, 'suspend'])->name('api.application.servers.suspend');
    Route::post('/{server:id}/unsuspend', [Application\Servers\ServerManagementController::class, 'unsuspend'])->name('api.application.servers.unsuspend');
    Route::post('/{server:id}/reinstall', [Application\Servers\ServerManagementController::class, 'reinstall'])->name('api.application.servers.reinstall');
    Route::post('/{server:id}/transfer', [Application\Servers\ServerManagementController::class, 'startTransfer'])->name('api.application.servers.transfer');
    Route::post('/{server:id}/transfer/cancel', [Application\Servers\ServerManagementController::class, 'cancelTransfer'])->name('api.application.servers.transfer.cancel');

    Route::delete('/{server:id}', [Application\Servers\ServerController::class, 'delete']);
    Route::delete('/{server:id}/{force?}', [Application\Servers\ServerController::class, 'delete']);

    // Database Management Endpoint
    Route::prefix('/{server:id}/databases')->group(function () {
        Route::get('/', [Application\Servers\DatabaseController::class, 'index'])->name('api.application.servers.databases');
        Route::get('/{database:id}', [Application\Servers\DatabaseController::class, 'view'])->name('api.application.servers.databases.view');

        Route::post('/', [Application\Servers\DatabaseController::class, 'store']);
        Route::post('/{database:id}/reset-password', [Application\Servers\DatabaseController::class, 'resetPassword']);

        Route::delete('/{database:id}', [Application\Servers\DatabaseController::class, 'delete']);
    });
});

/*
|--------------------------------------------------------------------------
| Egg Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /api/application/eggs
|
*/
Route::prefix('/eggs')->group(function () {
    Route::get('/', [Application\Eggs\EggController::class, 'index'])->name('api.application.eggs.eggs');
    Route::get('/{egg:id}', [Application\Eggs\EggController::class, 'view'])->name('api.application.eggs.eggs.view');
});

/*
|--------------------------------------------------------------------------
| Database Host Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /api/application/database-hosts
|
*/
Route::prefix('/database-hosts')->group(function () {
    Route::get('/', [Application\DatabaseHosts\DatabaseHostController::class, 'index'])->name('api.application.databasehosts');
    Route::get('/{database_host:id}', [Application\DatabaseHosts\DatabaseHostController::class, 'view'])->name('api.application.databasehosts.view');

    Route::post('/', [Application\DatabaseHosts\DatabaseHostController::class, 'store']);

    Route::patch('/{database_host:id}', [Application\DatabaseHosts\DatabaseHostController::class, 'update']);

    Route::delete('/{database_host:id}', [Application\DatabaseHosts\DatabaseHostController::class, 'delete']);
});

/*
|--------------------------------------------------------------------------
| Mount Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /api/application/mounts
|
*/
Route::prefix('mounts')->group(function () {
    Route::get('/', [Application\Mounts\MountController::class, 'index'])->name('api.application.mounts');
    Route::get('/{mount:id}', [Application\Mounts\MountController::class, 'view'])->name('api.application.mounts.view');

    Route::post('/', [Application\Mounts\MountController::class, 'store']);
    Route::post('/{mount:id}/eggs', [Application\Mounts\MountController::class, 'addEggs'])->name('api.application.mounts.eggs');
    Route::post('/{mount:id}/nodes', [Application\Mounts\MountController::class, 'addNodes'])->name('api.application.mounts.nodes');

    Route::patch('/{mount:id}', [Application\Mounts\MountController::class, 'update']);

    Route::delete('/{mount:id}', [Application\Mounts\MountController::class, 'delete']);
    Route::delete('/{mount:id}/eggs/{egg_id}', [Application\Mounts\MountController::class, 'deleteEgg']);
    Route::delete('/{mount:id}/nodes/{node_id}', [Application\Mounts\MountController::class, 'deleteNode']);
});

/*
|--------------------------------------------------------------------------
| Role Controller Routes
|--------------------------------------------------------------------------
|
| Endpoint: /api/application/roles
|
*/
Route::prefix('/roles')->group(function () {
    Route::get('/', [Application\Roles\RoleController::class, 'index'])->name('api.application.roles');
    Route::get('/{role:id}', [Application\Roles\RoleController::class, 'view'])->name('api.application.roles.view');

    Route::post('/', [Application\Roles\RoleController::class, 'store']);

    Route::patch('/{role:id}', [Application\Roles\RoleController::class, 'update']);

    Route::delete('/{role:id}', [Application\Roles\RoleController::class, 'delete']);
});
