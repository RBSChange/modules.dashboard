<?php
class dashboard_BlockToolsAction extends website_BlockAction
{	
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	function execute($request, $response)
	{		
		$packages = array();
		$canEditLocale = false;
		$user = users_UserService::getInstance()->getCurrentBackEndUser();
		
		if ($user->getIsroot())
		{
			$packages['framework'] = 'Framework';
			foreach (ModuleService::getInstance()->getPackageVersionList() as $packageName => $version)
			{
				$moduleName = substr($packageName, 8);
				$packages[$moduleName] = f_Locale::translateUI('&modules.' . $moduleName . '.bo.general.Module-name;');
			}
			$canEditLocale = true;
		}
		else 
		{
			$ps = f_permission_PermissionService::getInstance();
			$ms = ModuleService::getInstance();
			foreach (ModuleService::getInstance()->getPackageVersionList() as $packageName => $version)
			{
				$moduleName = substr($packageName, 8);
				$prefixConstModulename = strtoupper('mod_'.$moduleName.'_');
				$enabled = constant($prefixConstModulename . 'ENABLED');
				$visible = constant($prefixConstModulename . 'VISIBLE');
				if ($enabled && $visible && $ps->hasPermission($user, $packageName . '.EditLocale', $ms->getRootFolderId($moduleName)))
				{
					$packages[$moduleName] = f_Locale::translateUI('&modules.' . $moduleName . '.bo.general.Module-name;');
					$canEditLocale = true;
				}
			}
		}
		
		$request->setAttribute('packages', $packages);
		$request->setAttribute('canEditLocale', $canEditLocale);
		return website_BlockView::SUCCESS;
	}
}