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
   * Purpose: Handles calls to the index of the application. Returns
   * the index page and passes the tasks along to twig.
   *
   * @Route("/", name="homepage")
   */
    public function indexAction(Request $request)
    {
      return $this->render('default/index.html.twig', array(
        'tasks' => $this->getTasks(),
      ));
    }

 /**
  * Purpose: Route used to get all the tasks and returns the rendered task
  * view.
  *
  * @Route("/todo/all", name="all-tasks")
  */
    public function allTasksAction(Request $request) {
      return $this->render('default/tasklist.html.twig', array(
        'tasks' => $this->getTasks("all"),
      ));
    }

  /**
  * Purpose: Route used to get only the completed tasks and returns the rendered task
  * view.
  *
  * @Route("/todo/completed", name="completed-tasks")
  */
    public function completedTasksAction(Request $request) {
      return $this->render('default/tasklist.html.twig', array(
        'tasks' => $this->getTasks("completed"),
      ));
    }

    /**
    * Purpose: Route used to get only the active tasks and returns the rendered task
    * view.
    *
    * @Route("/todo/active", name="active-tasks")
    */
    public function activeTasksAction(Request $request) {
      return $this->render('default/tasklist.html.twig', array(
        'tasks' => $this->getTasks("active"),
      ));
    }

    /**
    * Purpose: Updates an existing task and saves it. 
    *
    * Parameters:
    *   - index: The task to update
    *   - name (POST): The name of the task
    *   - completed (POST): Boolean indicating if the task is complete
    *
    * Returns:
    *   - The updated task JSON encoded
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
    * Purpose: Deletes an existing task.
    *
    * Parameters:
    *   - index: The task to delete
    *
    * Returns:
    *   - Empty array
    * @Route("/todo/delete/{index}", name="delete-task")
    */
    public function deleteTaskAction($index, Request $request) {

      TaskManager::deleteTask($this->get('session'), $index);

      $response = new Response("{}", Response::HTTP_OK);
      $response->headers->set('Content-Type', 'application/json');

      return $response;
    }

   /**
    * Purpose: Adds a new task and saves it
    *
    * Parameters:
    *   - index: The task to update
    *   - name (POST): The name of the task
    *
    * Returns:
    *   - The updated task JSON encoded
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
    * Purpose: Loads tasks from the TaskManager and optionally
    * filters them
    *
    * Parameters:
    *   - filter: The filter to apply, can be "all", "active", or "completed"
    *
    * Returns:
    *   - The array of task objects
    *
    */
    private function getTasks($filter="all") {
      $taskList = TaskManager::getAllTasks($this->get('session'));

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
