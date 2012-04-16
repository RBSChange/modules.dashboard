<?php
class dashboard_DashboardService
{
	/**
	 * @var dashboard_DashboardService Unique instance of dashboard_DashboardService
	 */
	private static $instance = null;

	private $defaultContent = null;
	
	/**
	  * Private constructor for singleton instance.
	  */
	private function __construct()
	{
	}
	
	/**
	 * @return dashboard_DashboardService Unique instance of dashboard_DashboardService
	 */
	public static function getInstance()
	{
		if ( is_null(self::$instance) )
		{
			self::$instance = new dashboard_DashboardService();
		}
		return self::$instance;
	}

	/**
	 * @return array<String> The names of the modules that have a Dashboard action.
	 */
	public function getDashboardWidgets()
	{
		$widgetArray = array();
		$moduleNameArray = ModuleService::getInstance()->getModules();
		foreach ($moduleNameArray as $moduleName)
		{
			if ($moduleName != 'modules_uixul' && $moduleName != 'modules_dashboard' && Controller::getInstance()->actionExists(substr($moduleName, 8), 'Dashboard'))
			{
				$widgetArray[] = $moduleName;
			}
		}
		return $widgetArray;
	}
	
	public function getDefaultContent()
	{
		if ($this->defaultContent === null)
		{
			$filename = FileResolver::getInstance()->setPackageName('modules_dashboard')
				->setDirectory('templates')
				->getPath('defaultdashbord.xml');
			$this->defaultContent = file_get_contents($filename);
		}
		return $this->defaultContent;
	}
	
	/**
	 * @param users_persistentdocument_backenduser $user
	 * @return website_persistentdocument_page
	 */
	public function getTemporaryPageFromUser($user)
	{
    	$content = $user->getDashboardcontent();
    	if (f_util_StringUtils::isEmpty($content))
    	{
    		$content = $this->getDefaultContent();
    	}
		$page = website_PageService::getInstance()->getNewDocumentInstance();
		$page->setLang(RequestContext::getInstance()->getUILang());
		$page->setLabel('Temporary Dashboard Page');
		$page->setTemplate('defaultTemplate');
    	$page->setContent($content);
    	
    	$template = theme_PagetemplateService::getInstance()->getByCodeName('defaultTemplate');
    	if (!$template)
    	{
    		$template = theme_PagetemplateService::getInstance()->getNewDocumentInstance();
    		$template->setLabel('defaultTemplate');
    		$template->setCodename('defaultTemplate');
    		$template->setDoctype('XHTML 1.0 Strict');
    		$template->setProjectpath('modules/dashboard/templates/defaultTemplate.xml');
    		$template->setUseprojectcss(false);
    		$template->setUseprojectjs(false);
    		
    		$template->setCssscreen('modules.generic.frontoffice,modules.generic.richtext,modules.dashboard.dashboard');
    		$template->setJs('modules.dashboard.lib.js.dashboard,modules.uixul.lib.wCore,modules.dashboard.lib.js.dashboardwidget');
    		$template->save();
    	}
		return $page;
	}
}