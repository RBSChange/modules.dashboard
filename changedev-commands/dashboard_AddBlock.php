<?php
/**
 * @package modules.dashboard
 */
class commands_dashboard_AddBlock extends c_ChangescriptCommand
{
	/**
	 * @return String
	 */
	function getUsage()
	{
		return "<moduleName> <blockName> [icon]";
	}

	/**
	 * @return String
	 */
	function getDescription()
	{
		return "initialize a new block for the dashboard.";
	}
	
	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 */
	protected function validateArgs($params, $options)
	{
		return count($params) >= 2;
	}
	
	/**
	 * @param Integer $completeParamCount the parameters that are already complete in the command line
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @return String[] or null
	 */
	function getParameters($completeParamCount, $params, $options, $current)
	{
		if ($completeParamCount == 0)
		{
			$components = array();
			foreach (glob("modules/*", GLOB_ONLYDIR) as $module)
			{
				$components[] = basename($module);
			}
			return $components;
		}
	}
	
	/**
	 * @param String[] $params
	 * @param array<String, String> $options where the option array key is the option name, the potential option value or true
	 * @see c_ChangescriptCommand::parseArgs($args)
	 */
	function _execute($params, $options)
	{
		$this->message("== Add dashboard block ==");

		$moduleName = $params[0];
		$blockName = ucfirst($params[1]);
		$icon = isset($params[2]) ? $params[2] : null;

		$this->loadFramework();
		$moduleGenerator = new dashboard_DashboardBlockGenerator($moduleName);
		$moduleGenerator->setAuthor($this->getAuthor());
		$blockPath = $moduleGenerator->generateBlock($blockName, false, $icon);
		
		$this->quitOk("Block '$blockName' added in module '$moduleName'.
Please now edit ".$blockPath.".");
	}
}