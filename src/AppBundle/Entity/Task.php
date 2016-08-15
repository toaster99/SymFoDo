<?php

namespace AppBundle\Entity;

  /**
  * Purpose: Class for a single task
  */
class Task {
  protected $name;
  protected $completed;

  /**
  * Purpose: Constructor for a task, defaults completed to false
  */
  function __construct() {
    $this->completed = false;
  }

  /**
  * Purpose: Retrieves the name property
  */
  public function getName() {
    return $this->name;
  }

  /**
  * Purpose: Sets the name property
  */
  public function setName($name) {
    $this->name = $name;
  }

  /**
  * Purpose: Indicates if the task is completed
  */
  public function isCompleted() {
    return $this->completed;
  }

  /**
  * Purpose: Sets the completed property
  */
  public function setCompleted($completed) {
    $this->completed = $completed;
  }
}
