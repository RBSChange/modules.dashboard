<?php
/**
 * dashboard_DashboardprofileScriptDocumentElement
 * @package modules.dashboard.persistentdocument.import
 */
class dashboard_DashboardprofileScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return dashboard_persistentdocument_dashboardprofile
	 */
	protected function initPersistentDocument()
	{
		return dashboard_DashboardprofileService::getInstance()->getNewDocumentInstance();
	}
	
	/**
	 * @return dashboard_persistentdocument_dashboardprofilemodel
	 */
	protected function getDocumentModel()
	{
		return f_persistentdocument_PersistentDocumentModel::getInstanceFromDocumentModelName('modules_dashboard/dashboardprofile');
	}
}