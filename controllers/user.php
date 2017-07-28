<?php
namespace TonicApp;

//include controllers
require_once($_SERVER['DOCUMENT_ROOT'].'/tonic3-dev-test/app/interfaces/api.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/tonic3-dev-test/app/controllers/_db.php');

//include models
require_once($_SERVER['DOCUMENT_ROOT'].'/tonic3-dev-test/app/models/user.php');

class UserAPI extends APIClass
{
    protected $User;

    public function __construct($request) {
        parent::__construct($request);

        $User = new User();
        $this->User = $User;
    }

    /**
    * Endpoints
    **/

    protected function insert($args) {
        if ($this->method == 'POST') {
            $User = new User();
            $data = array();
            parse_str($this->request, $data);

            $User->_load($data);
            $User->creation_date = date("Y-m-d H:i:s");
            $User->_save();
            return array("message" => "Sucessfully added user.", "status" => "200");
        } else {
            return "Only accepts POST requests";
        }
    }

    protected function get() {
        if ($this->method == 'GET') {
            $_DB = new _DB();
            $sql = "SELECT * FROM `users`";
            $result = $_DB->returnQuery($sql);

            return $result;
        } else {
            return "Only accepts GET requests";
        }
    }

    protected function getUser($args) {
        $uid = $args[0];
        if ($this->method == 'GET') {
            $_DB = new _DB();
            $sql = "SELECT * FROM `users` WHERE `id` = '".$uid."' LIMIT 1";
            $result = $_DB->returnQuery($sql);

            return $result[0];
        } else {
            return "Only accepts GET requests";
        }
    }

    protected function search($args) {
        $keyword = $args[0];

        if ($this->method == 'GET') {
            $_DB = new _DB();
            $sql = "SELECT * FROM `users` WHERE `firstname` LIKE \"%".$keyword."%\" OR `surname` LIKE \"%".$keyword."%\"";
            $result = $_DB->returnQuery($sql);
            return $result;
        } else {
            return "Only accepts GET requests";
        }
    }

    protected function update($args) {
        $uid = $args[0];
        if ($this->method == 'PUT') {
            $User = new User($uid);
            $data = array();
            parse_str($this->request, $data);

            if (empty($data['password'])) {
                $data['password'] = $User->password;
            }

            $User->_load($data);
            $User->_save();
            
            return array("status"=>"200");
        } else {
            return "Only accepts PUT requests";
        }
    }

    protected function delete($args) {
        $uid = $args[0];
        if ($this->method == 'DELETE') {
            $_DB = new _DB();
            $sql = "DELETE FROM `users` WHERE `id` = '".$uid."' LIMIT 1";
            $result = $_DB->runQuery($sql);

            return array("status"=>"200");
        } else {
            return "Only accepts DELETE requests";
        }
    }
 }