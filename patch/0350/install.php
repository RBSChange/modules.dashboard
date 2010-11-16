<?php
/**
 * dashboard_patch_0350
 * @package modules.dashboard
 */
class dashboard_patch_0350 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$template = theme_PagetemplateService::getInstance()->getByCodeName('tplNewDashboard');
		$template->setJs('modules.dashboard.lib.js.dashboard,modules.uixul.lib.dashboardext,modules.uixul.lib.wCore,modules.dashboard.lib.js.dashboardwidget');
		$template->save();
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
		return '0350';
	}
}