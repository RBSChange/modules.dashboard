<?php
class dashboard_ViewBlockAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$user = $this->getBackEndUser();	
		if (!$user) {return View::NONE;}
		$blocType = $request->getModuleParameter('dashboard', 'type');
		$package = explode('_', $blocType);
		$moduleName = $package[0];
		
		$displayParam = $request->getModuleParameter('dashboard', 'display');
		if (!is_array($displayParam)) {$displayParam = array();}
		
		$blockClassName = $moduleName . '_Block' . ucfirst($package[1]) . 'Action';
		$blockClass = new ReflectionClass($blockClassName);
		try
		{
			$moduleParams = $request->getParameter($moduleName.'Param', array());
			$moduleParams['refresh'] = true;
			$request->setParameter($moduleName.'Param', $moduleParams);
			
			$blockController = website_BlockController::getInstance();
			$page = dashboard_DashboardService::getInstance()->getTemporaryPageFromUser($user);
			$blockController->setPage($page);
			$blockController->getContext()->setAttribute(website_BlockAction::BLOCK_BO_MODE_ATTRIBUTE, false);
			$blockInstance = $blockClass->newInstance();			
			foreach ($displayParam as $name => $value)
			{
				$blockInstance->setConfigurationParameter($name, $value);
			}	
			$blockController->process($blockInstance, f_mvc_HTTPRequest::getInstance());
			$blockContent = $blockController->getResponse()->getWriter()->getContent();
			
			$title = $blockInstance->getTitle();
			$icon = $blockInstance->getIcon();
			$openModule = $blockInstance->getOpenModule();
		}
		catch (Exception $e)
		{
			Framework::exception($e);
			$blockContent = $e->getMessage();
			$title = $blocType;
			$icon = '';
			$openModule = false;
		}
		
		controller_ChangeController::setNoCache();
		header('Content-Type' . ':' . 'text/xml');	
		$this->write($title, $icon, $blockContent, $openModule);
		return View::NONE;
	}
	
	/**
	 * @return users_persistentdocument_backenduser
	 */
	protected function getBackEndUser()
	{
		 return users_UserService::getInstance()->getCurrentBackEndUser();
	}
	
	/**
	 * @param string $title
	 * @param string $icon
	 * @param string $content
	 */
	private function write($title, $icon, $content, $openModule)
	{
		$output = new XMLWriter();
		$output->openMemory();
		$output->startDocument('1.0', 'UTF-8');
		$output->startElement('dashboard-widget');
		if (!$openModule)
		{
			$output->writeAttribute('hideGotoModule', 'true');
		}
		$output->writeElement('title', $title);
		
		$output->startElement('icon');
		$output->writeAttribute('image', $icon);	
		$output->endElement(); //icon
		
		$output->startElement('content');
                $output->text($content);
                $output->endElement(); //content

		$output->endElement(); //content
		
		$output->endElement(); //dashboard-widget
		$output->endDocument(); //DOCUMENT
		echo $output->outputMemory(true);		
	}
}
