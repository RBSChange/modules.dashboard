<?php
/**
 * dashboard_BlockDashboardAction
 * @package modules.dashboard.lib.blocks
 */
abstract class dashboard_BlockDashboardAction extends website_BlockAction
{
	/**
	 * @return string
	 */
	public function getIcon()
	{
		return MediaHelper::getIcon($this->getBlockInfo()->getIcon(), MediaHelper::SMALL);
	}
	
	/**
	 * @return string
	 */
	public function getTitle()
	{
		return f_Locale::translateUI($this->getBlockInfo()->getLabel());
	}
	
	/**
	 * @return boolean
	 */
	public function getOpenModule()
	{
		return true;
	}

	/**
	* @param f_mvc_Request $request
	* @param boolean $forEdition
	 */
	protected function buildRefreshUrl($request, $forEdition)
	{
		if (!$request->hasAttribute('title')) {$request->setAttribute('title', $this->getTitle());}
		if (!$request->hasAttribute('icon')) {$request->setAttribute('icon', $this->getIcon());}
		if (!$request->hasAttribute('hideGotoModule')) {$request->setAttribute('hideGotoModule', $this->getOpenModule() ? 'false' : 'true');}
		if ($forEdition) 
		{
			return;
		}
		$modulename = $this->getModuleName();
		$dashboardParam = array('type' => $modulename . '_' . $this->getName(), 'display' => $this->getConfigurationParameters());
		$link = LinkHelper::getUIActionLink('dashboard', 'ViewBlock');
		if ($modulename == 'dashboard')
		{
			$link->setQueryParameter('dashboardParam', array_merge($dashboardParam, $request->getParameters()));
		}
		else
		{
			$link->setQueryParameter('dashboardParam', $dashboardParam);
			$link->setQueryParameter($modulename . 'Param', $request->getParameters());
		}
		$request->setAttribute('refreshURL', $link->getUrl());
	}
	
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	function execute($request, $response)
	{
		$forEdition = $this->isInBackofficeEdition();
		if ($forEdition)
		{
			$request->setAttribute('forEdition', true);
		}
		$this->setRequestContent($request, $forEdition);		
		$this->buildRefreshUrl($request, $forEdition);
		return website_BlockView::SUCCESS;
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @param boolean $forEdition
	 */
	abstract protected function setRequestContent($request, $forEdition);
}