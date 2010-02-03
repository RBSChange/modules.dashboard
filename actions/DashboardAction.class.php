<?php
class dashboard_DashboardAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
        try
        {
        	controller_ChangeController::setNoCache();
            $userDocument = users_UserService::getInstance()->getCurrentBackEndUser();
            $page = dashboard_DashboardService::getInstance()->getTemporaryPageFromUser($userDocument);
            website_PageService::getInstance()->render($page);
        }
        catch (Exception $e)
        {  
        	Framework::exception($e);     
        }
		return View::NONE;
	}
}