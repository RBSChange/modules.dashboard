<?php
class dashboard_SaveContentAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		// Save the page content get as XML string from the page editor :
		$user = $this->getDocumentService()->getDocumentInstance($request->getParameter(K::COMPONENT_ID_ACCESSOR));
		if ($request->hasParameter('content'))
		{
			$content = self::normalizeContent($request->getParameter('content'));
			$page = dashboard_DashboardService::getInstance()->getTemporaryPageFromUser($user);
			website_PageService::getInstance()->updatePageContent($page, $content);
			$user->setDashboardcontent($page->getContent());
			$user->save();
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