<?php
/**
 * @package modules.dashboard
 * @method dashboard_DashboardService getInstance()
 */
class dashboard_DashboardService extends change_BaseService
{
	/**
	 * @var string
	 */
	private $defaultContent = null;
	
	/**
	 * @return array<String> The names of the modules that have a Dashboard action.
	 */
	public function getDashboardWidgets()
	{
		$widgetArray = array();
		$moduleNameArray = ModuleService::getInstance()->getPackageNames();
		foreach ($moduleNameArray as $moduleName)
		{
			if ($moduleName != 'modules_uixul' && $moduleName != 'modules_dashboard' && change_Controller::getInstance()->actionExists(substr($moduleName, 8), 'Dashboard'))
			{
				$widgetArray[] = $moduleName;
			}
		}
		return $widgetArray;
	}
	
	/**
	 * @return string
	 */
	public function getDefaultContent()
	{
		if ($this->defaultContent === null)
		{
			$filename = change_FileResolver::getNewInstance()->getPath('modules', 'dashboard', 'templates', 'defaultdashbord.xml');
			$this->defaultContent = file_get_contents($filename);
		}
		return $this->defaultContent;
	}
	
	/**
	 * @param users_persistentdocument_user $user
	 * @return website_persistentdocument_page
	 */
	public function getTemporaryPageFromUser($user)
	{
		$profile = dashboard_DashboardprofileService::getInstance()->getByAccessorId($user->getId());
		$content = ($profile !== null) ? $profile->getDashboardcontent() : null;
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