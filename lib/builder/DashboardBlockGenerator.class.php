<?php
class dashboard_DashboardBlockGenerator extends builder_BlockGenerator
{
	/**
	 * Generate a block : blocks.xml, blockAction, success template, tag, locales.
	 *
	 * @param String $blockName
	 * @param Boolean $genTag
	 * @param String $icon
	 * @return String the path of the generated PHP file
	 */
	public function generateBlock($blockName, $genTag, $icon)
	{
		$blockName = 'Dashboard'.ucfirst($blockName);
		$actionPath = $this->_generateBlockAction($blockName, $icon);

		$this->_generateBlocksxml($blockName, $icon);
		block_BlockService::getInstance()->compileBlocks();
		return $actionPath;
	}
	
	/**
	 * @return String[] [$folder, $tplName]
	 */
	protected function getBlockTemplateInfo()
	{
		return array('blocks', 'DashboardBlockAction.class.php.tpl');
	}
	
	/**
	 * @return String[] [$folder, $tplName]
	 */
	protected function getBlockSuccessViewInfo()
	{
		return array('blocks', 'DashboardBlockActionSuccess.html.tpl');
	}
	
	protected function _getTpl($folder, $tpl, $blockName, $icon = null, $additionalParams = null)
	{
		$templateDir = f_util_FileUtils::buildWebeditPath('modules', 'dashboard', 'templates', 'builder', $folder);
		$generator = new builder_Generator();
		$generator->setTemplateDir($templateDir);
		$generator->assign('author', $this->author);
		$generator->assign('blockName', $blockName);
		$generator->assign('module', $this->name);
		$generator->assign('icon', $icon);
		$generator->assign('date', $this->date);
		foreach ($this->getAdditionalTplVariables() as $key => $value)
		{
			$generator->assign($key, $value);
		}
		if ($additionalParams !== null)
		{
			foreach ($additionalParams as $key => $value)
			{
				$generator->assign($key, $value);
			}
		}
		$result = $generator->fetch($tpl);
		return $result;
	}
}