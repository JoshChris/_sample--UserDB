<?php
namespace TonicApp;

abstract class APIClass
{
    // request method - are we making a GET, POST, PUT or DELETE request?
    protected $method = '';

    // request endpoint
    protected $endpoint = '';

    // endpoint action
    protected $action = '';

    protected $request = array();

    // additional URI components after the endpoint and action have been removed 
    protected $args = Array();

    // put request file
    protected $file = Null;

    public function __construct($request)
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        $this->args = explode('/', rtrim($request, '/'));

        $this->endpoint = array_shift($this->args);

        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }

        switch($this->method) :
            case 'DELETE':
            case 'POST':
                $_POST = file_get_contents('php://input');
                $this->request = _clean($_POST);
                break;
            case 'GET':
                $this->request = _clean($_GET);
                break;
            case 'PUT':
                $_POST = file_get_contents('php://input');
                $this->request = _clean($_POST);
                break;
            default:
                $this->_response('Invalid Method', 405);
                break;
        endswitch;
    }

    public function process()
    {
        if (method_exists($this, $this->endpoint)) {
            return $this->_response($this->{$this->endpoint}($this->args));
        }
        return $this->_response("No Endpoint: $this->endpoint", 404);
    }

    private function _response($data, $status = 200)
    {
        header("HTTP/1.1 " . $status . " " . $this->_status($status));
        return json_encode($data);
    }

    private function _status($code)
    {
        $status = array(  
            200 => 'OK',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ); 
        return ($status[$code])?$status[$code]:$status[500]; 
    }
}