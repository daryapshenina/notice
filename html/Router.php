<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 22.12.17
 * Time: 14:39
 */
class Router
{
    private $controller = 'main_controller';
    private $action = 'main';
    private $params = Array();

    function __construct() {


        if (!empty($_SERVER['PATH_INFO'])) {
            list($controller, $action) = explode('--', $_SERVER['PATH_INFO']);
            $this->controller = (file_exists(__APPDIR__ . 'app/controllers/' . strtolower(preg_replace('/[^a-zA-Z]/', '', $controller)) . '_controller.php')) ? strtolower(preg_replace('/[^a-zA-Z]/', '', $controller)) . '_controller' : $this->controller;

            $all_pulic_method = get_class_methods($this->controller);

            foreach ($all_pulic_method as $method) {
                if ($method == preg_replace('/[^a-zA-Z]/', '', $action))
                    $this->action = preg_replace('/[^a-zA-Z]/', '', $action);
            }
        }

        if ((!isset($_SESSION['user_id'])) && (empty(App::$registry['sessionId'])) && (strtolower($this->action) != 'loginaccess')) {
            $open = array( "openjson", "monitoring", "versions", "docinfo", "upload", "reportsjson", "cron", "referencejson", "dchjson", "stats", "status", "version", "crashreport", "xlsstats", "delo", "notify", "session", "test", "newxlsstats", "xlsloginstats", "order");
            if (!in_array(trim($controller, "/"), $open)) {
                $this->action = 'login';
            }
        }

        $this->params = $_REQUEST;

        $this->sortParams();
    }

    function exec() {

            $controller = new $this->controller;

            call_user_func_array(array($controller, $this->action), $this->params);

    }

    private function sortParams() {

            $refl = new ReflectionMethod($this->controller, $this->action);
            $method_params = $refl->getParameters();

            if (!!$method_params) {
                $sorted_params = array();
                foreach ($method_params as $param) {
                    foreach ($this->params as $name => $value) {
                        if ($param->getName() == $name) {
                            if (!$param->allowsNull() and $value == NULL) {
                                throw new MyException("Parameter '{$param->getName()}' of method '{$this->action}' in class '{$this->controller}' can not be empty");
                            } else {
                                $sorted_params[$param->getName()] = $value;
                            }
                        }
                    }
                    if (!array_key_exists($param->getName(), $sorted_params)) {
                        if ($param->isDefaultValueAvailable()) {
                            $sorted_params[$param->getName()] = $param->getDefaultValue();
                        } else {
                            throw new MyException("Parameter '{$param->getName()}' of method '{$this->action}' in class '{$this->controller}' not given");
                        }
                    }
                }
                foreach ($method_params as $p) {
                    $pn = $p->getName();
                    $sp[$pn] = $sorted_params[$pn];
                }
                $this->params = $sp;
            }
        }


}