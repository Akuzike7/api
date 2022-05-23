<?php
namespace models;

use core\data\Model;

class Fault extends Model{
    public string $table = "faults";
    public string $description;
    public string $location;
    public string $status;
    public string $phone;
    public string $user_id;
    public string $category_id;
    
}