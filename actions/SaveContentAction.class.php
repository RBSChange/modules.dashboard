<?php
class dashboard_SaveContentAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		// Save the page content get as XML string from the page editor :	
		$user = users_persistentdocument_user::getInstanceById($request->getParameter('cmpref'));
		if ($request->hasParameter('content'))
		{
			$content = self::normalizeContent($request->getParameter('content'));
			$page = dashboard_DashboardService::getInstance()->getTemporaryPageFromUser($user);
			website_PageService::getInstance()->updatePageContent($page, $content);		
			$profile = dashboard_DashboardprofileService::getInstance()->getByAccessorId($user->getId());
			if ($profile === null)
			{
				$profile = dashboard_DashboardprofileService::getInstance()->getNewDocumentInstance();
				$profile->setAccessor($user);
			}
			$profile->setDashboardcontent($page->getContent());
			$profile->save();
			$this->logAction($user);
		}
		return $this->sendJSON(array('id' => $user->getId(), 'documentversion' => $user->getDocumentversion(), 'lang' => RequestContext::getInstance()->getLang()));
	}
	
	/**
	 * Normalize the given XML content.
	 *
	 * @param string $content
	 * @return string
	 */
	public static function normalizeContent($content)
	{
		$content = trim(stripslashes($content));
		$content = str_replace('class="preview"', '', $content);
		return $content;
	}
}