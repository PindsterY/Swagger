<?php namespace Veebiteenus;
class Application
{
    public $db = null;
    function run()
    {
        // Init vars
        $action = $_SERVER["REQUEST_METHOD"];
        $path_info = trim($_SERVER["PATH_INFO"], '/');
        $path_info = explode('/',$path_info);
        $controller = empty($path_info)? trigger_error('No endpoint given'):array_shift($path_info);
        $parameters = array_shift($path_info);
        $controller_name = "Veebiteenus\\controllers\\". $controller;
        // Check if requested endpoint exists
        if (!class_exists($controller_name)){
            trigger_error("Invalid endpoint", E_USER_ERROR);
        }
        // Run requested action
        $controller = new $controller_name;
        $controller->$action($parameters);
    }
}