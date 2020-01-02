<?php


class API {
    private $contentType;
    private $registered = [];
    function __construct() {}

    function start() {
        $this->contentType = isset($_SERVER["CONTENT_TYPE"])
            ? trim($_SERVER["CONTENT_TYPE"])
            : '';

        if (strpos($this->contentType, "application/json") !== false) {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);
            if(is_array($decoded) && isset($decoded['key']) && isset($this->registered[$decoded['key']])) {
                $callback = $this->registered[$decoded['key']];
                unset($decoded['key']);
                call_user_func_array($callback, $decoded);
                exit;
            }
        }
    }
    function register($key, $callback) {
        $this->registered[$key] = $callback;
    }
    function json($data) {
        echo json_encode($data);
    }
}
