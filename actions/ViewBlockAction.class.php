<?php
class dashboard_ViewBlockAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$user = $this->getBackEndUser();
		if (!$user)
		{
			return change_View::NONE;
		}
		$blocType = $request->getModuleParameter('dashboard', 'type');
		$package = explode('_', $blocType);
		$moduleName = $package[0];

		$displayParam = $request->getModuleParameter('dashboard', 'display');
		if (!is_array($displayParam))
		{
			$displayParam = array();
		}

		try
		{
			$bt = 'modules_'. $moduleName . '_'.$package[1];
			$blockClassName = block_BlockService::getInstance()->getBlockActionClassNameByType($bt);
			if ($blockClassName === null)
			{
				throw new Exception("Block $bt not found");
			}
			$blockClass = new ReflectionClass($blockClassName);
				
			$moduleParams = $request->getParameter($moduleName.'Param', array());
			$moduleParams['refresh'] = true;
			$request->setParameter($moduleName.'Param', $moduleParams);
				
			$blockController = website_BlockController::getInstance();
			$page = dashboard_DashboardService::getInstance()->getTemporaryPageFromUser($user);
			$blockController->setPage($page);
			$blockController->getContext()->setAttribute(website_BlockAction::BLOCK_BO_MODE_ATTRIBUTE, false);
			$blockInstance = $blockClass->newInstance($bt);
			foreach ($displayParam as $name => $value)
			{
				$blockInstance->setConfigurationParameter($name, $value);
			}
			$blockController->process($blockInstance, change_Controller::getInstance()->getRequest());
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

		change_Controller::setNoCache();
		header('Content-Type' . ':' . 'text/xml');
		$this->write($title, $icon, $blockContent, $openModule);
		return change_View::NONE;
	}

	/**
	 * @return users_persistentdocument_user
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
		$output->startCdata();
		$output->text($content);
		$output->endCdata();
		$output->endElement(); //content

		$output->endElement(); //dashboard-widget
		$output->endDocument(); //DOCUMENT
		echo $output->outputMemory(true);
	}
}