<?php
namespace TonicApp;
//include helpers
require_once($_SERVER['DOCUMENT_ROOT'].'/tonic3-dev-test/app/helpers/strings.php');

//include DB Controller
require_once($_SERVER['DOCUMENT_ROOT'].'/tonic3-dev-test/app/controllers/_db.php');

class User
{
	protected $id;

	public $firstname;
	public $surname;
	public $email;
	public $password;
	public $country;
	public $country_code;
	public $phone;
	public $creation_date;

	function __construct($id = NULL)
	{

		if (!empty($id)){
			$_DB = new _DB();
	        $sql = "SELECT * FROM `users` WHERE `id` = '".$id."'";
	        $result = $_DB->returnQuery($sql);

			if (!empty($result[0])) {
				$this->id = (int) $id;
				$this->_load($result[0]);
			} else {
				throw new \Exception("Error: Could not load into User Model.");
			}
		}
	}

	public function _load($data)
	{
		$this->firstname = (string) $data['firstname'];
		$this->surname = (string) $data['surname'];
		$this->email = (string) $data['email'];
		$this->password = (string) $data['password'];
		$this->country = (string) $data['country'];
		$this->country_code = (string) $data['country_code'];
		$this->phone = (string) $data['phone'];
		if(!empty($data['creation_date'])){
			$this->creation_date = (string) $data['creation_date'];
		}
	}

	public function _array($data)
	{
		$data['firstname'] = (string) $this->firstname;
		$data['surname'] = (string) $this->surname;
		$data['email'] = (string) $this->email;
		$data['password'] = (string) $this->password;
		$data['country'] = (string) $this->country;
		$data['country_code'] = (string) $this->country_code;
		$data['phone'] = (string) $this->phone;
		$data['creation_date'] = (string) $this->creation_date;
		return $data;
	}

	public function _save()
	{
		$data = array(
			'firstname' => (string) $this->firstname,
			'surname' => (string) $this->surname,
			'email' => (string) $this->email,
			'password' => (string) $this->password,
			'country' => (string) $this->country,
			'country_code' => (string) $this->country_code,
			'phone' => (string) $this->phone, 
			'creation_date' => (string) $this->creation_date
		);

		if(empty($data)) {
			throw new \Exception("Error: Could not save user.");
		}

		$fields = array();
		$values = array();

		if( !empty($this->id) ){
			$values = array();
			foreach($data as $key => $val){
				$values[] = "`".$key."`='"._clean($val)."'";
			}
			
			$_DB = new _DB();
			$sql  = "UPDATE `users` SET ".implode(', ', $values);
			$sql .= " WHERE `id` = ".$this->id;
	        $result = $_DB->runQuery($sql);
		}else{
			foreach($data as $key => $val){
				$fields[] = '`'.$key.'`';
				$values[] = _clean($val);
			}
			
			$_DB = new _DB();
			$sql = "INSERT INTO `users` (".implode(', ', $fields).") VALUES ('".implode("','", $values)."')";
	        $result = $_DB->runQuery($sql);
		}
	}
}