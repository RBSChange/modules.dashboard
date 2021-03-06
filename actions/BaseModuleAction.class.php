<?php
class dashboard_BaseModuleAction extends f_action_BaseAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public final function _execute($context, $request)
	{
        try
        {   
            $title = $this->getTitle();
            $icon = MediaHelper::getIcon($this->getIcon(), MediaHelper::SMALL);
            $content = $this->getContent($context, $request);  
        }
        catch (Exception $e)
        {  
        	Framework::exception($e);     
        	$title = 'Error';
        	$icon = MediaHelper::getIcon("document", MediaHelper::SMALL);
        	$content = $e->getMessage();
        }
        
		header('Content-Type' . ':' . 'text/xml');		
		$this->write($title, $icon, $content);
		return View::NONE;
	}

	/**
	 * @return string
	 */
	protected function getTitle()
	{
		return "Widget";
	}

	/**
	 * @return string
	 */
	protected function getIcon()
	{
		return "document";
	}
	
	/**
	 * @return users_persistentdocument_backenduser
	 */
	protected function getBackEndUser()
	{
		 return users_UserService::getInstance()->getCurrentBackEndUser();
	}
	
	/**
	 * @param Context $context
	 * @param Request $request
	 * @return string
	 */
	protected function getContent($context, $request)
	{
		return '...';
	}
	
	/**
	 * @param string $package
	 * @param string $name
	 * @param string $mimeType
	 * @return TemplateObject
	 */
	protected final function createNewTemplate($package, $name, $mimeType = 'xml')
	{
		return TemplateLoader::getInstance()->setPackageName($package)->setMimeContentType($mimeType)->load($name);
	}
	
	private function write($title, $icon, $content)
	{
		$output = new XMLWriter();
		$output->openMemory();
		$output->startDocument('1.0', 'UTF-8');
		$output->startElement('dashboard-widget');
		
		$output->writeElement('title', $title);
		
		$output->startElement('icon');
		$output->writeAttribute('image', $icon);	
		$output->endElement(); //icon
		
		$output->startElement('content');
		$output->writeCData($content);
		$output->endElement(); //content
		
		$output->endElement(); //dashboard-widget
		$output->endDocument(); //DOCUMENT
		echo $output->outputMemory(true);		
	}
}