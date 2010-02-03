<?php
/**
 * dashboard_patch_0300
 * @package modules.dashboard
 */
class dashboard_patch_0300 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		parent::execute();
		
		// Import lists.
		$scriptReader = import_ScriptReader::getInstance();
		$scriptReader->executeModuleScript('dashboard', 'init.xml');
	}

	/**
	 * Returns the name of the module the patch belongs to.
	 *
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'dashboard';
	}

	/**
	 * Returns the number of the current patch.
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0300';
	}
}