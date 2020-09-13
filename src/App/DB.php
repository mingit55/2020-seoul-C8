<?php
namespace App;

class DB {
    static $conn = null;
    static function getConnection(){
        if(self::$conn == null){
            self::$conn = new \PDO("mysql:host=localhost;dbname=seoul_7;charset=utf8mb4", "root", "", [
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);
        }
        return self::$conn;
    }

    static function query($sql, $data = []){
        $q = self::getConnection()->prepare($sql);
        $q->execute($data);
        return $q;
    }

    static function fetch($sql, $data = []){
        return self::query($sql, $data)->fetch();
    }

    static function fetchAll($sql, $data = []){
        return self::query($sql, $data)->fetchAll();
    }

    static function find($table, $id){
        return self::fetch("SELECT * FROM `$table` WHERE id = ?", [$id]);
    }
    
    static function who($user_email){
        return self::fetch("SELECT * FROM users WHERE user_email = ?", [$user_email]);
    }

    static function lastInsertId(){
        return self::getConnection()->lastInsertId();
    }
}