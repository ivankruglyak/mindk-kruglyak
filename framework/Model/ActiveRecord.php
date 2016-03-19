<?php

namespace Framework\Model;

use Framework\DI\Service;
use PDO;


abstract class ActiveRecord {

	protected static $db = null;

	/**
	 * Class constructor
	 */
	public function __construct(){

	}

	public function getRules(){
		return [];
	}

	public static function getDBCon(){

		if(empty(self::$db)){
			self::$db = Service::get('db');
		}

		return self::$db;
	}

	public abstract function getTable();

	public static function find($mode = 'all')
	{
		 $db    = self::getDBCon();
		 $table = static::getTable();

		 $sql = "SELECT * FROM " . $table;

		 if(is_numeric($mode)){
			 $sql .= " WHERE id=".(int)$mode;
		 }

		$statement = $db->prepare($sql);
		$result = $statement->execute();
		 // PDO request...

//		var_dump($result); die;
		 return $result;
	}

	protected function getFields(){

		return get_object_vars($this);
	}

	public function save(){

		$fields = $this->getFields();

		$all_rules = $this->getRules();

		foreach($all_rules as $name => $rules){
			if(array_key_exists($name, $fields)){
				foreach($rules as $rule){
					$valid = $rule->isValid($fields[$name]);
				}
			}
		}

		// @TODO: build SQL expression, execute
	}
} 