<?php
class dashboard_DashboardAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$rc = RequestContext::getInstance();
        $rc->setUILangFromParameter($request->getParameter('uilang'));
        controller_ChangeController::setNoCache();
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
		return View::NONE;
	}
}