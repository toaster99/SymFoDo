<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Todo {
  protected $slug;
  protected $tasks;

  function __construct() {
    $this->tasks = new ArrayCollection();
  }
  public function getTasks() {
    return $this->tasks;
  }

  public function addTask(Task $task) {
    $this->tasks->add($task);
  }

  public function removeTask(Task $task) {
    $this->tasks->removeElement($task);
  }

  public function setTasks($tasks) {
    $this->tasks = $tasks;
  }
}
