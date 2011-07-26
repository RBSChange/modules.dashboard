<?php
/**
 * @package module.dashboard
 */
class dashboard_ListAvailablemodulesService extends BaseService
{
	/**
	 * @var dashboard_ListAvailableModulesService
	 */
	private static $instance;

	/**
	 * @return dashboard_ListAvailableModulesService
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * @return array<list_Item>
	 */
	public final function getItems()
	{
		$items = array();
		
		$user = users_UserService::getInstance()->getCurrentBackEndUser();
		if ($user)
		{		
			$fullAccess = $user->getIsroot();
			$ms = ModuleService::getInstance();
			$ps = f_permission_PermissionService::getInstance();
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
	 * @return String
	 */
	public final function getDefaultId()
	{
		return 'website';
	}
}