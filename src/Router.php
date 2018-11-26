<?php
class Router
{
    protected $routes;

    public function __construct($routes)
    {
        $this->routes = $routes;
    }

    public function route_url()
    {
        if (preg_match('/\d+/', $_SERVER['REQUEST_URI'], $id))
        {
            $dynamic_uri = preg_replace('/[0-9]+/', '{id}', $_SERVER['REQUEST_URI']);

            if(isset($this->routes[$dynamic_uri]))
            {
                $controller = $this->routes[$dynamic_uri]['controller'];
                $action = $this->routes[$dynamic_uri]['action'];

                $controller = new $controller();
                $controller->$action($id);
            }
            else
            {
                echo "Resource not found";
            }

        } else {

            $static_uri = $_SERVER['REQUEST_URI'];

            if(isset($this->routes[$static_uri]))
            {
                $controller = $this->routes[$static_uri]['controller'];
                $action = $this->routes[$static_uri]['action'];

                $controller = new $controller();
                $controller->$action();
            }
            else
            {
                echo "Resource not found";
            }
        }
    }
}