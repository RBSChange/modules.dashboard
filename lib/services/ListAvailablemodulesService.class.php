<?php
/**
 * @package modules.dashboard
 * @method dashboard_ListAvailablemodulesService getInstance()
 */
class dashboard_ListAvailablemodulesService extends change_BaseService implements list_ListItemsService
{
	/**
	 * @return list_Item[]
	 */
	public final function getItems()
	{
		$items = array();
		
		$user = users_UserService::getInstance()->getCurrentBackEndUser();
		if ($user)
		{		
			$fullAccess = $user->getIsroot();
			$ms = ModuleService::getInstance();
			$ps = change_PermissionService::getInstance();
			foreach ($ms->getPackageNames() as $packageName)
			{
				$moduleName = substr($packageName, 8);
				$module = $ms->getModule($moduleName);
				if ($module->isVisible() && $moduleName != 'dashboard' && ($fullAccess || $ps->hasPermission($user, $packageName . '.Enabled', $module->getRootFolderId())))
				{
					$label = $module->getUILabel();
					$items[$label] = new list_Item($label, $moduleName);
				}
			}
		}
		
		ksort($items);
		return array_values($items);
	}

	/**
	 * @return string
	 */
	public final function getDefaultId()
	{
		return 'website';
	}
}