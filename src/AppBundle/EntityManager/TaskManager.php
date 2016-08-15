<?php
namespace AppBundle\EntityManager;

class TaskManager {
	public static function addTask($session,$task) {
		$taskList = TaskManager::getAllTasks($session);

		array_push($taskList, $task);
		$session->set('tasks', $taskList);
	}

	public static function deleteTask($session, $index) {
		$taskList = TaskManager::getAllTasks($session);

		unset($taskList[$index]);
		$session->set('tasks', $taskList);
	}

	public static function getAllTasks($session) {
		$taskList = $session->get('tasks');

		if(!isset($taskList)) {
			$taskList = array();
		}

		return $taskList;
	}

	public static function setTasks($session, $tasks) {
		$session->set('tasks', $tasks);
	}

	public static function updateTask($session,$task, $key) {
		$taskList = TaskManager::getAllTasks($session);
		
		$taskList[$key] = $task;
		$session->set('tasks', $taskList);
	}
}