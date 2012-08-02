<?php
/**
 * dashboard_patch_0360
 * @package modules.dashboard
 */
class dashboard_patch_0360 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$template = theme_PagetemplateService::getInstance()->getByCodeName('tplNewDashboard');
		if ($template instanceof theme_persistentdocument_pagetemplate)
		{
			$this->log("Detele tplNewDashboard...");
			$template->delete();
		}
	}
}