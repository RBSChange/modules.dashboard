<?php

class dashboard_BlockHeaderAction extends website_BlockAction
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
		$request->setAttribute('isRoot', $this->getBackEndUser()->getIsroot());	
		$userName = $this->getBackEndUser()->getFullname();
		if (preg_match('/^[aeyuio]/i', f_util_StringUtils::strip_accents($userName)))
		{
			$request->setAttribute('username', f_Locale::translateUI("&modules.dashboard.bo.general.DashboardOf-voyel;") . $userName);
		}
		else
		{
			$request->setAttribute('username', f_Locale::translateUI("&modules.dashboard.bo.general.DashboardOf;") . $userName);
		}
		
		return website_BlockView::SUCCESS;
	}
	
	/**
	 * @return users_persistentdocument_user
	 */
	private function getBackEndUser()
	{
		return users_UserService::getInstance()->getCurrentBackEndUser();
	}
}