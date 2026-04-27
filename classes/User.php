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

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }
    public function getInfo() {
        return "Emri: $this->name, Email: $this->email, Roli: $this->role";
    }
}
class AdminUser extends User {
    private $permissions;

    public function __construct($id, $name, $email, $role, $permissions) {
        parent::__construct($id, $name, $email, $role);
         $this->permissions = $permissions;
        
   }
     public function getInfo() {
        return parent::getInfo() . ", Lejet: " . implode(", ", $this->permissions);
   }
}

    
