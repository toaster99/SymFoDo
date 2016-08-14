<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AppBundle\Entity\Task;
use AppBundle\Entity\Todo;

use AppBundle\Form\Type\TodoType;

use AppBundle\EntityManager\TaskManager;

// class DefaultController extends Controller
// {
//     /**
//      * @Route("/", name="homepage")
//      */
//     public function indexAction(Request $request)
//     {
//       $todo = new Todo();
//       $taskList = TaskManager::getAllTasks($this->get('session'));
//       if(isset($taskList)) {
//         $todo->setTasks($taskList);
//       }
//       else {
//         $taskList = $todo->getAllTasks();
//       }

//       $form = $this->createForm(TodoType::class, $todo);

//       $form->handleRequest($request);

//       if($form->isValid())
//       {
//         $todo = $form->getData();
//         $taskList = $todo->getTasks();

//         TaskManager::setTasks($this->get('session'), $taskList);
//       }

//       return $this->render('default/index.html.twig', array(
//         'form' => $form->createView(),
//       ));
//     }

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
      $todo = new Todo();
      $taskList = TaskManager::getAllTasks($this->get('session'));
      if(isset($taskList)) {
        $todo->setTasks($taskList);
      }
      else {
        $taskList = $todo->getAllTasks();
      }

      return $this->render('default/index.html.twig', array(
        'tasks' => $taskList,
      ));
    }



    /**
    * @Route("/active", name="active-todo")
    */
    public function activeTodoAction(Request $request) {
      $todo = new Todo();
      $taskList = TaskManager::getAllTasks($this->get('session'));
      if(isset($taskList)) {
        $todo->setTasks($taskList);
      }
      else {
        $taskList = $todo->getTasks();
      }

      $form = $this->createForm(TodoType::class, $todo);

      $form->handleRequest($request);

      if($form->isValid())
      {
        $todo = $form->getData();
        $taskList = $todo->getTasks();

        TaskManager::setTasks($this->get('session'), $taskList);
      }

      return $form->createView();
    }

    /**
    ** Grabs the tasks from the TaskManager
    **
    */
    private function getTasks($option) {

    }
}
