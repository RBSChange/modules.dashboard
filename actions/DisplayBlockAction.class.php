<?php
class dashboard_DisplayBlockAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$componentId = $request->getParameter(K::COMPONENT_ID_ACCESSOR);
		$componentLang = $request->getParameter(K::COMPONENT_LANG_ACCESSOR);
		$pageId = $request->getParameter('pageid');
		$blocType = $request->getParameter('type');
		$package = explode('_', $blocType);
		
		$displayParam = $request->getParameter('display');
		if (!is_array($displayParam)) {$displayParam = array();}
		
		try
		{
			$blockClassName = block_BlockService::getInstance()->getBlockActionClassNameByType($blocType);
			if ($blockClassName === null)
			{
				throw new Exception("Block $bt not found");
			}
			$blockClass = new ReflectionClass($blockClassName);
			
			
			$blockController = website_BlockController::getInstance();
			$page = dashboard_DashboardService::getInstance()->getTemporaryPageFromUser(DocumentHelper::getDocumentInstance($pageId));
			$blockController->setPage($page);
			$blockController->getContext()->setAttribute(website_BlockAction::BLOCK_BO_MODE_ATTRIBUTE, true);
			$blockInstance = $blockClass->newInstance($blocType);
			
			foreach ($displayParam as $name => $value)
			{
				$blockInstance->setConfigurationParameter($name, $value);
			}	
			
			$blockController->process($blockInstance, f_mvc_HTTPRequest::getInstance());
			$blockContent = $blockController->getResponse()->getWriter()->getContent();	

			$request->setAttribute('id', $componentId);
			$request->setAttribute('lang', $componentLang);
			if (empty($blockContent))
			{
				try
				{
					$blockContent = "<strong>" . f_Locale::translate(block_BlockService::getInstance()->getBlockLabelFromBlockName($blocType)) . "</strong>";
				}
				catch (Exception $e)
				{
					Framework::exception($e);
					$blockContent = "";
				}
			}
			else
			{
				$search = array('/&([a-z0-9\[\]%]+)=/i', '/&&/i', '/&(\W)/i', '/<script[^>]+\/>/i', '/<script.*<\/script>/is', '/change:([a-z0-9]+=")/is');
				$replace = array('&amp;$1=', '&amp;&', '&amp;$1', '', '', 'change_$1');
				$blockContent = preg_replace($search, $replace, $blockContent);
			}
		}
		catch (Exception $e)
		{
			Framework::exception($e);
			return $this->sendJSONException($e);
		}	
		return $this->sendJSON(array('message' => $blockContent));
	}
}
