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
		if (users_UserService::getInstance()->getCurrentBackEndUser()->getIsroot())
		{
			$packages = array('framework' => 'Framework');
			foreach (ModuleService::getInstance()->getPackageVersionList() as $packageName => $version)
			{
				$moduleName = substr($packageName, 8);
				$packages[$moduleName] = f_Locale::translate('&modules.' . $moduleName . '.bo.general.Module-name;');
			}
			$request->setAttribute('packages', $packages);
			$request->setAttribute('canEditLocale', true);
		}
		return website_BlockView::SUCCESS;
	}
}