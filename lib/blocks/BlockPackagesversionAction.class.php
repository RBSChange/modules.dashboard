<?php
class dashboard_BlockPackagesversionAction extends website_BlockAction
{	
	/**
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	function execute($request, $response)
	{				
		$request->setAttribute('packageVersion', ModuleService::getInstance()->getPackageVersionInfos());
		return website_BlockView::SUCCESS;
	}
}