<?php

class modelAdmin extends cmsModel{

//============================================================================//
//==========================    КОМПОНЕНТЫ   =================================//
//============================================================================//

    public function getInstalledControllers(){

        return $this->get('controllers', function($item, $model){

            $item['options'] = cmsModel::yamlToArray($item['options']);

            $item['title'] = string_lang($item['name'].'_CONTROLLER', $item['title']);

            return $item;

        });

    }

    public function getInstalledControllersCount(){

        return $this->getCount('controllers');

    }

    public function getControllerInfo($controller_name){
        return $this->getItemByField('controllers', 'name', $controller_name, function($item){
            $item['options'] = cmsModel::yamlToArray($item['options']);
            $item['title'] = string_lang($item['name'].'_CONTROLLER', $item['title']);
            return $item;
        });
    }

//============================================================================//
//============================================================================//

    public function getEvents(){

        $this->limit = false;

        return $this->get('events');

    }

    public function reorderEvents($ids_list){

        $this->reorderByList('events', $ids_list);

        cmsCache::getInstance()->clean('events');

        return true;

    }

    public function addEvent($listener, $event) {

        $id = $this->insert('events', array(
            'listener' => $listener,
            'event'    => $event,
            'ordering' => $this->getNextOrdering('events')
        ));

        cmsCache::getInstance()->clean('events');

        return $id;

    }

    public function deleteEvent($listener, $event) {

        $this->filterEqual('listener', $listener);
        $this->filterEqual('event', $event);
        $this->deleteFiltered('events');

        cmsCache::getInstance()->clean('events');

        return true;

    }

//============================================================================//
//==========================    ПЛАНИРОВЩИК    ===============================//
//============================================================================//

    public function getSchedulerTasksCount(){

        return $this->getCount('scheduler_tasks');

    }

    public function getSchedulerTasks(){

        return $this->get('scheduler_tasks');

    }

    public function getPendingSchedulerTasks(){

        $tasks = $this->filterEqual('is_active', 1)->getSchedulerTasks();
        $pending = array();

        if($tasks){
	        foreach($tasks as $task){

	            if ($task['is_new']) {
	                $pending[] = $task;
	                continue;
	            }

	            $time_last_run = strtotime($task['date_last_run']);
	            $time_now = time();

	            $minutes_ago = floor(($time_now - $time_last_run) / 60);

	            if ($minutes_ago >= $task['period']){
	                $pending[] = $task;
	                continue;
	            }

	        }
        }

        return $pending;

    }

    public function getSchedulerTask($id){

        return $this->getItemById('scheduler_tasks', $id);

    }

    public function addSchedulerTask($task){

        return $this->insert('scheduler_tasks', $task);

    }

    public function updateSchedulerTask($id, $task){

        return $this->update('scheduler_tasks', $id, $task);

    }

    public function updateSchedulerTaskDate($id){

        return $this->updateSchedulerTask($id, array(
            'is_new' => 0,
            'date_last_run' => null
        ));

    }

    public function deleteSchedulerTask($id){

        return $this->delete('scheduler_tasks', $id);

    }

    public function toggleSchedulerPublication($id, $is_active){

     	return $this->update('scheduler_tasks', $id, array(
			'is_active' => $is_active
		));

    }

//============================================================================//
//============================================================================//

}
