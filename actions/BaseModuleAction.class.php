<?php
class dashboard_BaseModuleAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
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
		return change_View::NONE;
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
	 * @return users_persistentdocument_user
	 */
	protected function getBackEndUser()
	{
		 return users_UserService::getInstance()->getCurrentBackEndUser();
	}
	
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 * @return string
	 */
	protected function getContent($context, $request)
	{
		return '...';
	}
	
	/**
	 * @param string $title
	 * @param string $icon
	 * @param string $content
	 */
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
	
	// Deprecated.
	
	/**
	 * @deprected use change_TemplateLoader directly
	 */
	protected final function createNewTemplate($package, $name, $mimeType = 'xml')
	{
		list($ptype, $pname) = explode('_', $package);
		return change_TemplateLoader::getNewInstance()->setExtension($mimeType)->load($ptype, $pname, 'template', $name);
	}
}