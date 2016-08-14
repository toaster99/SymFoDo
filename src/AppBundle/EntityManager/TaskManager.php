<?php
namespace AppBundle\EntityManager;

class TaskManager {
	public static function addTask($session,$task) {
		$taskList = $session->get('tasks');
		array_push($taskList, $task);
		$session->set('tasks', $taskList);
	}

	public static function getAllTasks($session) {
		$taskList = $session->get('tasks');
		return $taskList;
	}

	public static function getActiveTasks($session) {
		$taskList = getAllTasks($session);

		
	}

	public static function setTasks($session, $tasks) {
		$session->set('tasks', $tasks);
	}

	public static function updateTask($session,$task, $key) {
		$taskList = $session->get('tasks');
		$taskList[$key] = $task;
		$session->set('tasks', $taskList);
	}
}