<?php
class dashboard_DashboardBlockGenerator extends builder_BlockGenerator
{
	/**
	 * Generate a block : blocks.xml, blockAction, success template, tag, locales.
	 *
	 * @param string $blockName
	 * @param boolean $genTag
	 * @param string $icon
	 * @return string the path of the generated PHP file
	 */
	public function generateBlock($blockName, $genTag, $icon)
	{
		$blockName = 'Dashboard'.ucfirst($blockName);
		$actionPath = $this->_generateBlockAction($blockName, $icon);

		$this->_generateBlocksxml($blockName, $icon);
		block_BlockService::getInstance()->compileBlocksForPackage("modules_".$this->name);
		return $actionPath;
	}
	
	/**
	 * @return string[] [$folder, $tplName]
	 */
	protected function getBlockTemplateInfo()
	{
		return array('blocks', 'DashboardBlockAction.class.php.tpl');
	}
	
	/**
	 * @return string[] [$folder, $tplName]
	 */
	protected function getBlockSuccessViewInfo()
	{
		return array('blocks', 'DashboardBlockActionSuccess.html.tpl');
	}
	
	protected function _getTpl($folder, $tpl, $blockName, $icon = null, $additionalParams = null)
	{
		$templateDir = f_util_FileUtils::buildProjectPath('modules', 'dashboard', 'templates', 'builder', $folder);
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