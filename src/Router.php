<?php
namespace Framework;

class Router
{
    protected $routes;
    private $error_message = "Resource not found";

    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    private function checkGuard(string $route): void
    {
        if (isset($this->routes[$route]['guard']))
        {
            $guard = "\\App\\Guards\\".$this->routes[$route]['guard'];
            (new $guard)->handle();
	    }
    }

    private function applyActionFromController($uri, $id): void
    {
        $this->checkGuard($uri);

        $controller = "App\\Controllers\\".$this->routes[$uri]['controller'];
        $action = $this->routes[$uri]['action'];

        $controller = new $controller();
        $controller->$action($id[0]);
    }

    public function getResourceFromUri(): void
    {
        if (preg_match('/\d+/', $_SERVER['REQUEST_URI'], $id))
        {
            $dynamic_uri = preg_replace('/[0-9]+/', '{id}', $_SERVER['REQUEST_URI']);

            if(isset($this->routes[$dynamic_uri]))
            {
                $this->applyActionFromController($dynamic_uri, $id);
            }
            else
            {
                echo $this->error_message;
            }
        } else {

            $static_uri = $_SERVER['REQUEST_URI'];

            if(isset($this->routes[$static_uri]))
            {
                $this->applyActionFromController($static_uri, null);
            }
            else
            {
                echo $this->error_message;
            }
        }
    }
}