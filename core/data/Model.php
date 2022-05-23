<?php

namespace core\data;

use Exception;
use PDO;
use PDOException;

class Model
{
    protected string $table = '';

    protected $primary_key = 'id';
    protected $id = 'id';

    private PDO $database;

    public function __construct()
    {
        if (!$this->table) {
            $table = explode('\\', get_class($this));

            $this->table = strtolower(end($table));
        }

        try {
            $config = [
                'dns' => 'mysql:host=localhost;dbname=maintenance-portal;port=3306',
                'user' => 'root',
                'password' => ''
            ];

            $dns = $config['dns'] ?? '';
            $user = $config['user'] ?? '';
            $password = $config['password'] ?? '';

            $this->database = new PDO($dns, $user, $password);

            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function find($data)
    {

        $data = is_object($data) ? (array)$data : $data;

        $attributes = [];

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $attributes[$key] = $value;
            }
        }

        $attribute = array_keys($attributes);

        $sql = implode(" AND ", array_map(fn($key) => "$key = :$key", $attribute));

        $statement = $this->database->prepare("SELECT * FROM `{$this->table}` WHERE $sql");

        foreach ($attributes as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();

        return $statement->fetch(PDO::FETCH_OBJ);
    }

    public function findAll($data = null)
    {
        if($data == null){

            $statement = $this->database->prepare("SELECT * FROM `{$this->table}`");
    
            $statement->execute();
    
            return $statement->fetchAll(PDO::FETCH_OBJ);
        }
        else{
            $data = is_object($data) ? (array)$data : $data;

        $attributes = [];

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $attributes[$key] = $value;
            }
        }

        $attribute = array_keys($attributes);

        $sql = implode(" AND ", array_map(fn($key) => "$key = :$key", $attribute));

        $statement = $this->database->prepare("SELECT * FROM `{$this->table}` WHERE $sql");

        foreach ($attributes as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
        }
    }
    
    
    public function count($condition)
    {

        $condition = is_object($condition) ? (array)$condition : $condition;

        $attributes = [];

        foreach ($condition as $key => $value) {
            if (property_exists($this, $key)) {
                $attributes[$key] = $value;
            }
        }

        $attribute = array_keys($attributes);

        $sql = implode(" AND ", array_map(fn($key) => "$key = :$key", $attribute));

        $statement = $this->database->prepare("SELECT COUNT(*) as count FROM `{$this->table}` WHERE $sql");

        foreach ($attributes as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();

        return $statement->fetch(PDO::FETCH_OBJ);
    }

    public function insert($data)
    {
        $data = is_object($data) ? (array)$data : $data;

        $attributes = [];

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $attributes[$key] = $value;
            }
        }

        $attribute = array_keys($attributes);

        $fields = implode(",", array_map(fn($key) => "`$key`", $attribute));

        $PlaceHolder = implode(",", array_map(fn($key) => ":$key", $attribute));

        $statement = $this->database->prepare("INSERT INTO `{$this->table}`($fields) VALUES($PlaceHolder)");

        foreach ($attributes as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        try{
            $prep = $statement->queryString;

            $statement->execute();

            return $res = [
                'message' => 'inserted successfully',
                'status' => '200'
            ];

        }catch(PDOException $e){
            return $res = [
                'message' => 'failed to insert '.$e->getMessage().'\n'.$prep,
                'status' => '400'
            ];
        }

       
    }

    public function update($data,$condition)
    {
        $data = is_object($data) ? (array)$data : $data;
        $condition = is_object($condition) ? (array)$condition : $condition;

        $attributes = [];
        $cattributes = [];

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $attributes[$key] = $value;
            }
        }

        foreach ($condition as $key => $value) {
            if (property_exists($this, $key)) {
                $cattributes[$key] = $value;
            }
        }

        $attribute = array_keys($attributes);

        $cattribute = array_keys($cattributes);

        $sql = implode(",", array_map(fn($key) => "`$key` = :$key", $attribute));

        $sql2 = implode(" AND ", array_map(fn($key) => "$key = :$key", $cattribute));

        $statement = $this->database->prepare("UPDATE `{$this->table}` SET $sql WHERE $sql2");

        foreach ($attributes as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        foreach ($cattributes as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        try{
            $prep = $statement->queryString;

            $statement->execute();

            return $res = [
                'message' => 'updated successfully',
                'status' => '200'
            ];

        }catch(PDOException $e){
            return $res = [
                'message' => 'failed to update: '.$e->getMessage().'\n'.$prep,
                'status' => '400'
            ];
        }
    }

    public function delete($data)
    {
        $data = is_object($data) ? (array)$data : $data;

        $attributes = [];

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $attributes[$key] = $value;
            }
        }

        $attribute = array_keys($attributes);

        $sql = implode(" AND ", array_map(fn($key) => "`$key` = :$key", $attribute));

        $statement = $this->database->prepare("DELETE * `{$this->table}` WHERE $sql");

        foreach ($attributes as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        try{
            $prep = $statement->queryString;

            $statement->execute();

            return $res = [
                'message' => 'deleted successfully',
                'status' => '200'
            ];

        }catch(PDOException $e){
            return $res = [
                'message' => 'failed to delete: '.$e->getMessage().'\n'.$prep,
                'status' => '400'
            ];
        }
    }
}