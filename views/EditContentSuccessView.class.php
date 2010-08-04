<?php
class dashboard_EditContentSuccessView extends f_view_BaseView
{

	/**
	 * @param Request $request
	 * @return users_persistentdocument_backenduser
	 */
	private function getBackEndUser($request)
	{
		 return $request->getAttribute('document');
	}
	
    /**
	 * @param Context $context
	 * @param Request $request
	 */
    public function _execute($context, $request)
    {
    	$backEndUser = $this->getBackEndUser($request);
    	$document = dashboard_DashboardService::getInstance()->getTemporaryPageFromUser($backEndUser);    	
    	$pageContent = website_PageService::getInstance()->getContentForEdition($document);
    	
    	$this->setTemplateName('Dashboard-EditContent-Success', K::XUL);
        $this->setAttribute('pageContent', $pageContent);
		
        // include stylesheets
 		$link = LinkHelper::getUIChromeActionLink('dashboard', 'GetEditContentStylesheets')
			->setArgSeparator(f_web_HttpLink::ESCAPE_SEPARATOR);
		$this->setAttribute('cssInclusion', '<?xml-stylesheet href="' . $link->getUrl() . '" type="text/css"?>');

		$link = LinkHelper::getUIChromeActionLink('uixul', 'GetAdminJavascripts')
			->setArgSeparator(f_web_HttpLink::ESCAPE_SEPARATOR);
			$this->setAttribute('scriptlibrary', '<script type="application/x-javascript" src="' . $link->getUrl() . '"/>');
			
		// include JavaScript
		$this->getJsService()->registerScript('modules.dashboard.lib.js.editcontent');
		$this->setAttribute('scriptInclusion', $this->getJsService()->executeInline(K::XUL));
		
		
        $this->setAttribute('PAGEID', $backEndUser->getId());
        $this->setAttribute('PAGELANG', RequestContext::getInstance()->getLang());
       	$this->setAttribute('PAGEVERSION', -1);
        $this->setAttribute('PAGEPATH', "");
        $this->setAttribute('pageName', f_util_StringUtils::quoteDouble($backEndUser->getFullname()));
    }
}
