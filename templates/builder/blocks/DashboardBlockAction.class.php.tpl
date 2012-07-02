<?php
/**
 * @package modules.<{$module}>
 * @method <{$module}>_Block<{$blockName}>Configuration getConfiguration()
 */
class <{$module}>_Block<{$blockName}>Action extends dashboard_BlockDashboardAction
{	
	/**
	 * @see dashboard_BlockDashboardAction::setRequestContent()
	 *
	 * @param f_mvc_Request $request
	 * @param boolean $forEdition
	 */
	protected function setRequestContent($request, $forEdition)
	{
		if ($forEdition) {return;}
		
		//$request->setAttribute('data', 'Content here');
	}
}