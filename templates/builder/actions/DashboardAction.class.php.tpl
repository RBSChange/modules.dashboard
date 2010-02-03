<?php
/**
 * <{$module}>_<{$blockName}>Action
 * @package modules.<{$module}>.actions
 */
class <{$module}>_<{$blockName}>Action extends dashboard_BaseModuleAction
{
	/**
	 * @see dashboard_BaseModuleAction::getIcon()
	 * @return string
	 */
	protected function getIcon()
	{
		return '<{$icon}>';
	}
	
	/**
	 * @see dashboard_BaseModuleAction::getTitle()
	 * @return string
	 */
	protected function getTitle()
	{
		return f_Locale::translate('&modules.<{$module}>.bo.blocks.<{$blockName}>.Title;');
	}
	
	/**
	 * @see dashboard_BaseModuleAction::getContent()
	 * @param Context $context
	 * @param Request $request
	 * @return string
	 */
	protected function getContent($context, $request)
	{
		$widget = array();
		
		// Write your code here.
			
		$templateObject = $this->createNewTemplate('modules_<{$module}>', '<{$module|capitalize}>-Action-<{$blockName}>-Success', 'html');
		$templateObject->setAttribute('widget', $widget);
		return $templateObject->execute();
	}
}