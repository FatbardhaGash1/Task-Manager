<?php

class User {
    private $id;
    private $name;
    private $email;
    private $role;

    public function __construct($id, $name, $email, $role) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }
}
