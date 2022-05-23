<?php
namespace models;

use core\data\Model;


class Verification extends Model {
    public string $email;
    public string $hashedCode;
    public string $status;
    public string $table = "verification_codes";
}