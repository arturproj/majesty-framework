<?php

namespace Spacers\Framework;
use Spacers\Framework\Constant\Attribute\Route;
use Spacers\Framework\Exception\NotFoundExcetion;



final class Kernel
{
    public static function init(mixed $callback = null, array $environments = [])
    {
        set_default_environments($environments);

        is_callable($callback) && call_user_func($callback, $environments);
        

        $controllers = self::loadControllerDir();

        if (empty($controllers)) {
            return dump("controllers list is empty");
        }

        $current_route = new Route(
            path: $_SERVER["REQUEST_URI"],
            alias: "client_current_route",
            method: $_SERVER["REQUEST_METHOD"]
        );

        foreach ($controllers as $ControllerClass) {
            /** @var \ReflectionClass $controller */
            $controller = self::getReflectedController($ControllerClass);
            ;

            foreach ($controller->getMethods(\ReflectionMethod::IS_PUBLIC) as $key => $action) {
                foreach ($action->getAttributes() as $key => $attribute) {

                    if (
                        $attribute->newInstance() instanceof Route
                        &&
                        $attribute->newInstance()->path === $current_route->path
                        &&
                        $attribute->newInstance()->method === $current_route->method
                    ) {
                        return $ControllerClass::getInstance()->{$action->name}();
                    }
                }
            }
        }

        throw new NotFoundExcetion("Requested route '{$current_route->method}:{$current_route->path}' unknown");
    }

    private static function loadControllerDir(): array
    {
        $SPACERS_PROJECT_DIR = getenv("SPACERS_PROJECT_DIR");

        $directory = new \RecursiveDirectoryIterator("$SPACERS_PROJECT_DIR/src/Controller");
        $iterator = new \RecursiveIteratorIterator($directory);
        $matches = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        $controllers = array();
        foreach ($matches as $value) {
            $controllers[] = str_replace(
                // search string to replace
                ["$SPACERS_PROJECT_DIR/src", "/", ".php"],
                // with this
                ["\\App", "\\", ""],
                // string value
                $value[0]
            );

        }

        return $controllers;
    }

    /**
     * @param object::class|string
     * @return \ReflectionClass
     */
    private static function getReflectedController(object|string $controller)
    {
        try {
            return new \ReflectionClass($controller);
        } catch (\ReflectionException $th) {
            throw new \Exception($th->getMessage(), 0, $th);
        }
    }
}