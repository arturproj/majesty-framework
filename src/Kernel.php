<?php

namespace Spacers\Framework;
use Spacers\Framework\Constant\Attribute\Route;

class Kernel
{
    public static function init($callback, array $config = [])
    {
        foreach ($config as $key => $value) {
            putenv("$key=$value");
        }
        $callback();

        $controllers = self::load_controllers();
        $current_route = new Route(path: $_SERVER["REQUEST_URI"], alias: "*", method: $_SERVER["REQUEST_METHOD"]);
        foreach ($controllers as $controller) {
            foreach ($controller->getMethods(\ReflectionMethod::IS_PUBLIC) as $key => $action) {
                foreach ($action->getAttributes() as $key => $attribute) {
                    dump("\\" . $controller->name, $action->name, $attribute->newInstance(), $current_route);
                    //    /**
                    //     * \Spacers\Framework\Controller\AbstractController  $class
                    //     */
                    //    $class = "\\". $controller->name;
                    //    $class::getInstance()->{$action->name}();
                }
            }
        }

    }

    private static function load_controllers(): array
    {
        $SPACERS_PROJECT_DIR = getenv("SPACERS_PROJECT_DIR");

        $directory = new \RecursiveDirectoryIterator("$SPACERS_PROJECT_DIR/src/Controller");
        $iterator = new \RecursiveIteratorIterator($directory);
        $matches = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        $controllers = array();
        foreach ($matches as $value) {
            $class = str_replace(
                // search string to replace
                ["$SPACERS_PROJECT_DIR/src", "/", ".php"],
                // with this
                ["\\App", "\\", ""],
                // string value
                $value[0]
            );
            $controllers[] = self::getReflectedController($class);
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