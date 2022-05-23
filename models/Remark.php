<?php
namespace models;

use core\data\Model;

class Remark extends Model{
    public string $table = "remarks";
    public string $remark;
    public string $user_id;
    public string $fault_id;
    
}