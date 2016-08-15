<?php
namespace AppBundle\EntityManager;

/**
* Purpose: Includes static classes for managing tasks.
*/
class TaskManager {
   /**
    * Purpose: Adds a task object to the array and saves to the session.
    *
    * Parameters:
    *   - session: The current session object
    *	- task: The task to add
    *
    */
	public static function addTask($session,$task) {
		$taskList = TaskManager::getAllTasks($session);

		array_push($taskList, $task);
		$session->set('tasks', $taskList);
	}

   /**
    * Purpose: Deletes a task at the specified index and saves to the session.
    *
    * Parameters:
    *   - session: The current session object
    *	- index: The index of the task to delete
    *
    */
	public static function deleteTask($session, $index) {
		$taskList = TaskManager::getAllTasks($session);

		unset($taskList[$index]);
		$session->set('tasks', $taskList);
	}

   /**
    * Purpose: Retrieves all the tasks from the session
    *
    * Parameters:
    *   - session: The current session object
    *
    * Returns:
    *	- A list of task objects
    */
	public static function getAllTasks($session) {
		$taskList = $session->get('tasks');

		if(!isset($taskList)) {
			$taskList = array();
		}

		return $taskList;
	}

   /**
    * Purpose: Sets the tasks list in the session
    *
    * Parameters:
    *   - session: The current session object
    *	- tasks: An array of task objects
    *
    */
	public static function setTasks($session, $tasks) {
		$session->set('tasks', $tasks);
	}

   /**
    * Purpose: Update an existing task and save to the session
    *
    * Parameters:
    *   - session: The current session object
    *	- task: The task information to update with
    *	- key: The task being updates
    *
    */
	public static function updateTask($session,$task, $key) {
		$taskList = TaskManager::getAllTasks($session);
		
		$taskList[$key] = $task;
		$session->set('tasks', $taskList);
	}
}