<?php

namespace Framework;
use Framework\ServiceProviders\ServiceProviderInterface;
use Framework\ServiceProviders\ConfigServiceProvider;
use Framework\ServiceProviders\DatabaseServiceProvider;

class Application
{
    private array $container;

    private static $app;

    /**
     * @var ServiceProviderInterface[]
     */
    private $serviceProviders = [
        ConfigServiceProvider::class,
        DatabaseServiceProvider::class,
    ];

    public function __construct()
    {
        $this->registerServiceProviders();

        self::$app = $this;
    }

    public static function getApp() {
        return self::$app;
    }

    /**
     * @return array
     */
    public function getContainer(): array
    {
        return $this->container;
    }

    public function add(string $name, $dependency) {
        $this->container[$name] = $dependency;
    }

    public function get(string $name) {
        return $this->container[$name];
    }

    private function registerServiceProviders() {
        // ConfigServiceProvider => ServiceProvider
        // DatabaseServiceProvider => ServiceProvider
        foreach ($this->serviceProviders as $providerClass) {
            /**
             * @var $provider ServiceProviderInterface
             */
            $provider = new $providerClass($this);
            $provider->register();
        }
    }

    /**
     * Взять текущий http метод и path
     * и найти для него подходящий handler
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request) : Response {

        $path = $request->getPath();

        $this->add("request", $request);

        $routes = Route::getAllRoutes();

        foreach ($routes as $route => $v) {
            // $route => "/^\/news\/(\d+)$/"
            // /news/13123133
            $matches = [];
           
            $res = preg_match($route, $path, $matches);
    
            if ($res > 0) {
                if (array_key_exists($request->getMethod(), $v)) {
                    $routeData = $v[$request->getMethod()];
                    $handler = $routeData["handler"];

                    list($handlerClass, $handlerMethod) = explode("😋", $handler);

<<<<<<< HEAD
                    $callableClass = new $handlerClass($this);
                 
                    $funcParams = array_splice($matches, 1);          
=======
                    $callableObj = new $handlerClass($this);

                    $funcParams = array_splice($matches, 1);
>>>>>>> 344a1d3957e1d49c21e62e3fa79e7d522f7b9e6a

                    try {
                        /** @var Response $response */
                        $response = call_user_func_array([$callableObj, $handlerMethod], $funcParams);
                    } catch (\ValidateException $e) {
                        // 400 validation error с определеным стилем страницы
                        echo $e->getMessage();
                        die();
                    } catch (\PDOException $e) {
                        // 500 ошибка сервера
                        echo $e->getMessage();
                        die();
                    }              
                    //$response->setLayoutPath();
                    if($response->LayoutPathisNull()){
                       $response->setLayoutPath($this->get("config")->get('app.layout'));
                    }
<<<<<<< HEAD
                        
                   
                   
=======

                    $response->setLayoutPath($this->get("config")->get('app.layout'));

                    if (property_exists($callableObj, "layout")) {
                        if (!empty($callableObj->layout)) {
                            $response->setLayoutPath($callableObj->layout);
                        }
                    }

>>>>>>> 344a1d3957e1d49c21e62e3fa79e7d522f7b9e6a
                    return $response;
                }
            }
        }

        dd("404 not found");

        return new Response("/../app/views/404.php");
    }

    public function terminate()
    {
    }
}