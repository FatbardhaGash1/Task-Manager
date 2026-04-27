<?php

class Task {
    private $title;
    private $description;
    private $status;
    private $assignedTo;

    public function __construct($title, $description, $status, $assignedTo) {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->assignedTo = $assignedTo;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getAssignedTo() {
        return $this->assignedTo;
    }
}
