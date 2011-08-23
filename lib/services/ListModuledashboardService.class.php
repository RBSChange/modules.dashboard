<?php
class dashboard_ListModuledashboardService extends BaseService 
	implements list_ListItemsService
{
	/**
	 * @var dashboard_ListModuledashboardService
	 */
	private static $instance;


	/**
	 * @return dashboard_ListModuledashboardService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Returns an array of available templates for the website module.
	 *
	 * @return array
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