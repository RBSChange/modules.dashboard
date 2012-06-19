<?php

class dashboard_BlockModuledefaultAction extends website_BlockAction
{	
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return string
	 */
	function execute($request, $response)
	{			
		$widget = $this->getConfiguration()->getPackage();
		if (empty($widget))
		{
			return website_BlockView::NONE;
		}
		
		if ($this->isInBackofficeEdition())
		{
		   $modulename = substr($widget, 8);
		   $dummy = array('package' => $widget, 'modulename' => $modulename, 'title' => ModuleService::getInstance()->getLocalizedModuleLabel($modulename));

		   $request->setAttribute('widget', $dummy);
		   return website_BlockView::DUMMY;
		}
		
		$request->setAttribute('widget', $widget);
		return website_BlockView::SUCCESS;
	}
}