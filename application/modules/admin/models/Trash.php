<?php
class Admin_Model_Trash
{	
	/* vars */
	protected $_modelNames = array();
	
	/*
	* Returns array of Model properties
	* 
	* @return array
	*/
	public function setModels(array $modelNames)
	{
		$this->_modelNames = $modelNames;	
	}

	/*
	 * Get data
	 * 
	 * @return array
	 */
	public function fetchAll()
	{
		$items = array();

		foreach ($this->_modelNames as $modelName) {
			$fileName = explode('_', $modelName);
			$fileName = array_pop($fileName);
			require_once(APPLICATION_PATH.'/models/'.$fileName.'.php');
			$model = new $modelName;
			$items[] = $model->fetchTrashed();
		}
		
		return $items;
	}
	
	/*
	 * Get data
	 * 
	 * @return array
	 */
	public function fetchAllTrashedPaginated($page)
	{
		$items = array();

		foreach ($this->_modelNames as $modelName) {
			$fileName = explode('_', $modelName);
			$fileName = array_pop($fileName);
			require_once(APPLICATION_PATH.'/models/'.$fileName.'.php');
			$model = new $modelName;
			$trashedItems = $model->fetchTrashed()->toArray();
			foreach($trashedItems as $trashedItem) {
				$trashedItem['modelName'] = $modelName;
				$items[] = $trashedItem;
			}
		}
		
		$paginator = Zend_Paginator::factory($items);
		$paginator->setCurrentPageNumber($page);
		$paginator->setItemCountPerPage(50);
		return $paginator;
	}
	
	/*
	 * Get data
	 * 
	 * @return array
	 */
	public function delete($modelName, $id)
	{
		$model = new $modelName;
		$model->deleteOne($id);
	}
	
	/*
	 * Get data
	 * 
	 * @return array
	 */
	public function restore($modelName, $id)
	{
		$model = new $modelName;
		$row = $model->find($id)->current();
		$row->isTrashed = 0;
		$row->save();
	}
}