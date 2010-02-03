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
        
        $this->getStyleService()->registerStyle('modules.generic.frontoffice');
        $this->getStyleService()->registerStyle('modules.generic.richtext');
        //$this->getStyleService()->registerStyle('modules.website.frontoffice');
        //$this->getStyleService()->registerStyle('modules.website.richtext');
        
        $modules = array('generic', 'uixul', 'dashboard', 'website');
        $cssInclusion = array();
        foreach ($modules as $module)
        {
            $this->getStyleService()->registerStyle('modules.' . $module . '.backoffice');
            $this->getStyleService()->registerStyle('modules.' . $module . '.bindings');
		    $link = LinkHelper::getUIChromeActionLink('uixul', 'GetStylesheet')->setQueryParametre(K::WEBEDIT_MODULE_ACCESSOR, $module);
            $cssInclusion[] = '<?xml-stylesheet href="' . $link->getUrl() . '" type="text/css"?>';
        }
        
        $this->getStyleService()->registerStyle('modules.dashboard.dashboard');
        $this->getStyleService()->registerStyle('modules.uixul.EditContent');

        $this->setAttribute('cssInclusion', $this->getStyleService()->execute(K::XUL, null) . join("\n", $cssInclusion));

        // include JavaScript
		$this->getJsService()->registerScript('modules.uixul.lib.default');
        $this->setAttribute('scriptInclusion', $this->getJsService()->executeInline(K::XUL));

        $this->setAttribute('PAGEID', $backEndUser->getId());
        $this->setAttribute('PAGELANG', RequestContext::getInstance()->getLang());
       	$this->setAttribute('PAGEVERSION', -1);
        $this->setAttribute('PAGEPATH', "");
        $this->setAttribute('pageName', f_util_StringUtils::quoteDouble($backEndUser->getFullname()));
    }
}
