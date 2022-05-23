<?php
namespace models;

use core\data\Model;

class FaultTechnician extends Model{
    public string $table = "fault_technician";
    public string $fault_id;
    public string $technician;
    
}