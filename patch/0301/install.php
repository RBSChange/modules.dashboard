<?php
/**
 * dashboard_patch_0301
 * @package modules.dashboard
 */
class dashboard_patch_0301 extends patch_BasePatch
{
//  by default, isCodePatch() returns false.
//  decomment the following if your patch modify code instead of the database structure or content.
    /**
     * Returns true if the patch modify code that is versionned.
     * If your patch modify code that is versionned AND database structure or content,
     * you must split it into two different patches.
     * @return Boolean true if the patch modify code that is versionned.
     */
//	public function isCodePatch()
//	{
//		return true;
//	}
 
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$template = theme_PagetemplateService::getInstance()->getByCodeName('tplNewDashboard');
    	if ($template)
    	{
    		$template->setJs('modules.dashboard.lib.js.dashboard,modules.uixul.lib.dashboardext,modules.uixul.lib.wToolkit,modules.dashboard.lib.js.dashboardwidget');
    		$template->save();		
    		$this->execChangeCommand('clear-webapp-cache', array());
    	}
	}

	/**
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'dashboard';
	}

	/**
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0301';
	}
}