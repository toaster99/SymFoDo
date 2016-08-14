<?php

namespace AppBundle\Entity;

class Task {
  protected $name;
  protected $completed;

  function __construct() {
    $this->completed = false;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function isCompleted() {
    return $this->completed;
  }

  public function setCompleted($completed) {
    $this->completed = $completed;
  }
}
