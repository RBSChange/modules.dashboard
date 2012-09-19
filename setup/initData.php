<?php
/**
 * @package modules.dashboard
 */
class dashboard_Setup extends object_InitDataSetup
{
	public function install()
	{
		$this->executeModuleScript('init.xml');
	}
}