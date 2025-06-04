<?php
class Router {
    private static $instance = null;

    private function __construct() {} 

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    public function handleRequest() {
        $route = $_GET['route'] ?? 'home';

        switch ($route) {
            case 'post':
                (new PostController())->show($_GET['id']);
                break;
            case 'create':
                (new PostController())->create();
                break;
            case 'login':
                (new UserController())->login();
                break;
            case 'profile':
                (new UserController())->profile();
                break;
            default:
                (new PostController())->index();
        }
    }
}
