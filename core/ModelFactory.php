<?php
class ModelFactory {
    public static function create($type) {
        switch ($type) {
            case 'post':
                require_once 'models/Post.php';
                return new Post();
            case 'user':
                require_once 'models/User.php';
                return new User();
            default:
                throw new Exception("Unknown model type: " . $type);
        }
    }
}
