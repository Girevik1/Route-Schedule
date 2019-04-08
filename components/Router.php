<?php

/**
 * Класс Router
 * Компонент для работы с маршрутами
 */
class Router
{
    /**
     * Свойство для хранения массива роутов
     * @var array
     */
    private $routes;

    /**
     * Конструктор
     */
    public function __construct()
    {
        $routesPath = ROOT . '/config/routes.php';
        $this->routes = include($routesPath);
    }

    /**
     * Возвращает строку запроса
     */
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            $url = trim($_SERVER['REQUEST_URI'], '/');

            if (strrpos($url, '?') !== false) {
                // Убираем все что после ? (query) так как это нам не нужно для роутинга
                return substr($url, 0, strrpos($url, '?'));
            }

            return $url;
        }

        return '';
    }

    /**
     * Метод для обработки запроса
     */
    public function run()
    {
        $uri = $this->getURI();

        $isRouteProcessed = false;

        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("~$uriPattern~", $uri)) {
                // Получаем внутренний путь из внешнего согласно правилу.

                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                $segments = explode('/', $internalRoute);

                $controllerName = array_shift($segments) . 'Controller';
                $controllerName = ucfirst($controllerName);


                $actionName = 'action' . ucfirst(array_shift($segments));

                $parameters = $segments;

                $controllerFile = ROOT . '/controllers/' . $controllerName . '.php';
                if (file_exists($controllerFile)) {
                    include_once($controllerFile);
                }

                $controllerObject = new $controllerName;

                if (method_exists($controllerObject, $actionName)) {
                    $result = call_user_func_array(array($controllerObject, $actionName), $parameters);

                    if ($result != null) {
                        $isRouteProcessed = true;
                        break;
                    }
                }
            }
        }

        if (!$isRouteProcessed) {
            echo 'Not Found';
        }
    }
}