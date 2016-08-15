<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use AppBundle\Entity\Task;
use AppBundle\Entity\Todo;

use AppBundle\Form\Type\TodoType;

use AppBundle\EntityManager\TaskManager;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
      return $this->render('default/index.html.twig', array(
        'tasks' => $this->getTasks(),
      ));
    }

 /**
  * Route used to get all the tasks and returns the rendered task
  * view.
  * @Route("/todo/all", name="all-tasks")
  */
    public function allTasksAction(Request $request) {
      return $this->render('default/tasklist.html.twig', array(
        'tasks' => $this->getTasks("all"),
      ));
    }

  /**
  * Route used to get only the completed tasks and returns the rendered task
  * view.
  * @Route("/todo/completed", name="completed-tasks")
  */
    public function completedTasksAction(Request $request) {
      return $this->render('default/tasklist.html.twig', array(
        'tasks' => $this->getTasks("completed"),
      ));
    }

    /**
    * Route used to get only the active tasks and returns the rendered task
    * view.
    * @Route("/todo/active", name="active-tasks")
    */
    public function activeTasksAction(Request $request) {
      return $this->render('default/tasklist.html.twig', array(
        'tasks' => $this->getTasks("active"),
      ));
    }

    /**
    * Update an existing task
    * @Route("/todo/update/{index}", name="active-task")
    */
    public function updateTaskAction($index, Request $request) {
      $taskName = $request->request->get('name');
      $taskCompleted = ($request->request->get('completed') === "true");

      $task = new Task();
      $task->setName($taskName);
      $task->setCompleted($taskCompleted);

      TaskManager::updateTask($this->get('session'), $task, $index);

      $response = new Response(json_encode($task), Response::HTTP_OK);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }

    /**
    * Update an existing task
    * @Route("/todo/delete/{index}", name="delete-task")
    */
    public function deleteTaskAction($index, Request $request) {

      TaskManager::deleteTask($this->get('session'), $index);

      $response = new Response("{}", Response::HTTP_OK);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }

    /**
    * Add a new task to the todo
    * @Route("/todo/add", name="add-task")
    */
    public function addTaskAction(Request $request) {
      $taskName = $request->request->get('name');

      $task = new Task();

      if(isset($taskName)) {
        $task->setName($taskName);
      }

      TaskManager::addTask($this->get('session'), $task);

      $response = new Response(json_encode($task), Response::HTTP_OK);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }



    /**
    ** Grabs the tasks from the TaskManager
    **
    */
    private function getTasks($filter="all") {
      $todo = new Todo();
      $taskList = TaskManager::getAllTasks($this->get('session'));
      if(isset($taskList)) {
        $todo->setTasks($taskList);
      }
      else {
        $taskList = $todo->getTasks();
      }

      if($filter == "active") {
        $taskList = array_filter($taskList, function($task) {
          return !$task->isCompleted();
        });
      }
      elseif ($filter == "completed") {
        $taskList = array_filter($taskList, function($task) {
          return $task->isCompleted();
        });
      }

      return $taskList;
    }
}
