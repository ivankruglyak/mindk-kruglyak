<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 10/17/14
 * Time: 12:09 PM
 */

namespace Blog\Model;

use Framework\Model\ActiveRecord;
use PDO;

class User extends ActiveRecord
{
    public $id;
    public $email;
    public $password;
    public $role;

    public static function getTable()
    {
        return 'users';
    }

    public function getRole()
    {
        return $this->role;
    }

    public static function findByEmail($email)
    {
        $db    = self::getDBCon();
        $table = self::getTable();

        $sql = "SELECT * FROM " . $table;

        $sql .= " WHERE email='" . $email . "'";


        $statement = $db->prepare($sql);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_OBJ);
        return $result;
    }
}