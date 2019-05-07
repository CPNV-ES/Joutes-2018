<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Config;
use Route;

class HomeController extends Controller
{
    public function index()
    {
        $routes = [];
        $api_prefix = '/' . substr(env('API_PREFIX'),0,3);

        foreach (Route::getRoutes() as $route) {
            if (substr($route->getPath(), 0, strlen($api_prefix)) === $api_prefix) {
                $routes[$route->getName()] =  $route->getPath();
            }
        }
        return [
            'name' => Config::get('app.name'),
            'version' => Config::get('app.version'),
            'api_routes' => $routes
        ];
    }
}
