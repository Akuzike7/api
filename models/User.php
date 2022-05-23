<?php

namespace models;

use core\data\Model;

class User extends Model {
    public string $uname;
    public string $email;
    public string $password;
    public string $table = "users";
    public string $phone;
}