<?php
/**
 * @package modules.dashboard
 * @method dashboard_ListAvailablemodulesService getInstance()
 */
class dashboard_ListModuledashboardService extends change_BaseService implements list_ListItemsService
{
	/**
	 * @return list_Item[]
	 */
	public function getItems()
	{
		$items = array();

		$widgets = dashboard_DashboardService::getInstance()->getDashboardWidgets();
		foreach ($widgets as $widget) 
		{
			$modulename = substr($widget, 8);
			$items[] = new list_Item(ModuleService::getInstance()->getLocalizedModuleLabel($modulename), $widget);
		}		
		return $items;
	}

}