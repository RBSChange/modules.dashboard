<?php
class dashboard_GetEditContentStylesheetsAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		header("Expires: " . gmdate("D, d M Y H:i:s", time()+28800) . " GMT");
		header('Content-type: text/css');
	    $rq = RequestContext::getInstance();
        $rq->beginI18nWork($rq->getUILang());
        $this->renderBindings();
		$rq->endI18nWork();		
		return View::NONE;
	}
	
	private function renderBindings()
	{
		$styleArray = array('modules.generic.frontoffice', 'modules.generic.richtext');
        $modules = array('generic', 'uixul', 'dashboard', 'website');
        foreach ($modules as $module)
        {
        	$styleArray[] = 'modules.' . $module . '.backoffice';
        	$styleArray[] = 'modules.' . $module . '.bindings';
        }
        
        $styleArray[] = 'modules.dashboard.dashboard';
      	$styleArray[] = 'modules.uixul.EditContent';
        
		$ss = StyleService::getInstance();
		foreach ($styleArray as $stylename)
		{
			echo $ss->getCSS($stylename, $ss->getFullEngineName('xul'));
		}
	}
}