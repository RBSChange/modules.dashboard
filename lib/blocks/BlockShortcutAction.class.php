<?php
class dashboard_BlockShortcutAction extends website_BlockAction
{	
	/**
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	function execute($request, $response)
	{	
		$moduleObjects = array();
		$user = users_UserService::getInstance()->getCurrentBackEndUser();
		
		// Bloc title.
		$title = $this->getConfiguration()->getBlocktitle();
		$title = $title ? $title : f_Locale::translate('&modules.uixul.bo.general.Modules;');
		$request->setAttribute('title', $title);
		
		// Modules.
		$modules = $this->getConfiguration()->getModules();
		if ($user && $modules)
		{
			$ms = ModuleService::getInstance();
			$ps = f_permission_PermissionService::getInstance();
			$fullAccess = $user->getIsroot();
			$modules = explode(',', $modules);			
			foreach ($modules as $moduleName)
			{
				$module = $ms->getModule($moduleName);
				if ($module->isVisible() && ($fullAccess || $ps->hasPermission($user, 'modules_' . $moduleName . '.Enabled', $module->getRootFolderId())))
				{
					$moduleObjects[] = $module;
				}
			}
		}
		$request->setAttribute('modules', $moduleObjects);
		
		return website_BlockView::SUCCESS;
	}
}