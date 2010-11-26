<?php
class dashboard_BlockToolsAction extends website_BlockAction
{	
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	function execute($request, $response)
	{		
		$user = users_UserService::getInstance()->getCurrentBackEndUser();
		return website_BlockView::SUCCESS;
	}
}