<?php

namespace QuetzalStudio\Maple;

use Illuminate\Contracts\Routing\Registrar as Router;
use QuetzalStudio\Maple\Http\Controllers\API\UserController as UserAPIController;
use QuetzalStudio\Maple\Http\Controllers\UserController;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  \Illuminate\Contracts\Routing\Registrar  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function all()
    {
        $this->users();
    }

    public function users()
    {
        $this->router->group([
            'prefix' => 'users',
            'as' => 'users.',
        ], function ($router) {
            $router->get('/', [
                UserController::class, 'index'
            ])->middleware('can:users.show')->name('index');

            $router->get('/create', [
                UserController::class, 'create'
            ])->middleware('can:users.create')->name('create');

            $router->get('/{id}', [
                UserController::class, 'show'
            ])->middleware('can:users.show')->name('show');

            $router->get('/{id}/edit', [
                UserController::class, 'edit'
            ])->middleware('can:users.edit')->name('edit');
        });

        $this->router->group([
            'prefix' => 'json/users',
            'as' => 'json.users.',
        ], function ($router) {
            $router->get('/', [
                UserAPIController::class, 'index'
            ])->middleware('can:users.show')->name('index');

            $router->post('/', [
                UserAPIController::class, 'store'
            ])->middleware('can:users.create')->name('store');

            $router->put('/{id}', [
                UserAPIController::class, 'update'
            ])->middleware('can:users.edit')->name('update');

            $router->delete('/{id}', [
                UserAPIController::class, 'destroy'
            ])->middleware('can:users.delete')->name('destroy');
        });
    }
}
