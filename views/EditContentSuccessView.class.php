<?php
class dashboard_EditContentSuccessView extends change_View
{
	/**
	 * @param change_Request $request        	
	 * @return users_persistentdocument_user
	 */
	private function getUser($request)
	{
		return $request->getAttribute('document');
	}
	
	/**
	 * @param change_Context $context        	
	 * @param change_Request $request        	
	 */
	public function _execute($context, $request)
	{
		$user = $this->getUser($request);
		$document = dashboard_DashboardService::getInstance()->getTemporaryPageFromUser($user);
		$pageContent = website_PageService::getInstance()->getContentForEdition($document);
		
		$this->setTemplateName('Dashboard-EditContent-Success', 'xul');
		$this->setAttribute('pageContent', $pageContent);
		$this->setAttribute('editorType', website_PageRessourceService::getInstance()->getTemplateType());
		
		// include stylesheets
		$link = LinkHelper::getUIChromeActionLink('dashboard', 'GetEditContentStylesheets')->setArgSeparator(f_web_HttpLink::ESCAPE_SEPARATOR);
		$this->setAttribute('cssInclusion', '<?xml-stylesheet href="' . $link->getUrl() . '" type="text/css"?>');
		
		$link = LinkHelper::getUIChromeActionLink('uixul', 'GetAdminJavascripts')->setArgSeparator(f_web_HttpLink::ESCAPE_SEPARATOR);
		$this->setAttribute('scriptlibrary', '<script type="application/x-javascript" src="' . $link->getUrl() . '"/>');
		
		// include JavaScript
		$jss = website_JsService::getInstance();
		$jss->registerScript('modules.dashboard.lib.js.editcontent');
		$this->setAttribute('scriptInclusion', $jss->executeInline('xul'));
		
		$this->setAttribute('PAGEID', $user->getId());
		$this->setAttribute('PAGELANG', RequestContext::getInstance()->getLang());
		$this->setAttribute('PAGEVERSION', -1);
		$this->setAttribute('PAGEPATH', "");
		$this->setAttribute('pageName', f_util_StringUtils::quoteDouble($user->getLabel()));
	}
}