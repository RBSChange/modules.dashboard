<?php
class dashboard_DashboardAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$rc = RequestContext::getInstance();
        $rc->setUILangFromParameter($request->getParameter('uilang'));
        change_Controller::setNoCache();
        try
        {
        	$rc->beginI18nWork($rc->getLang());
            $userDocument = users_UserService::getInstance()->getCurrentBackEndUser();
            $page = dashboard_DashboardService::getInstance()->getTemporaryPageFromUser($userDocument);
            website_PageService::getInstance()->render($page);
            $rc->endI18nWork();
        }
        catch (Exception $e)
        {  
        	$rc->endI18nWork($e);     
        }
		return change_View::NONE;
	}
}