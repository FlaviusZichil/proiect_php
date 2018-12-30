<?php

namespace Framework;

class Controller
{
    private $twig;

    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../app/Views');
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => __DIR__ . '/../storage/cache/views',
        ));
    }

    public function view(string $viewFile, array $params = [])
    {
        try {
            echo $this->twig->render($viewFile, $params);

        } catch (\Twig_Error_Loader $e)
        {
            echo $e->getMessage();
        }
        catch (\Twig_Error_Runtime $e)
        {
            echo $e->getMessage();
        }
        catch (\Twig_Error_Syntax $e)
        {
            echo $e->getMessage();
        }
    }
}