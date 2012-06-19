<?php
/**
 * @package modules.dashboard
 * @method dashboard_DashboardprofileService getInstance()
 */
class dashboard_DashboardprofileService extends users_ProfileService
{
	/**
	 * @return dashboard_persistentdocument_dashboardprofile
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_dashboard/dashboardprofile');
	}

	/**
	 * Create a query based on 'modules_dashboard/dashboardprofile' model.
	 * Return document that are instance of dashboard_persistentdocument_dashboardprofile,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_dashboard/dashboardprofile');
	}
	
	/**
	 * Create a query based on 'modules_dashboard/dashboardprofile' model.
	 * Only documents that are strictly instance of dashboard_persistentdocument_dashboardprofile
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_dashboard/dashboardprofile', false);
	}

	/**
	 * @param integer $accessorId
	 * @param boolean $required
	 * @return dashboard_persistentdocument_dashboardprofile || null
	 */
	public function getByAccessorId($accessorId, $required = false)
	{
		return parent::getByAccessorId($accessorId, $required);
	}
	
	/**
	 * @param dashboard_persistentdocument_dashboardprofile $document
	 * @param string[] $propertiesName
	 * @param array $datas
	 * @param integer $accessorId
	 */
	public function addFormProperties($document, $propertiesName, &$datas, $accessorId = null)
	{
		if ($document->isNew()) {$datas['id'] = 0;}
		$userPreferences = $document->getUserPreferences();
		if (!empty($userPreferences))
		{
			//{"toolbarActionView":"menu","documentEditorDefaultPanel":"properties","documenteditorHelp":"hidden","useLeftTreeDblClick":"true"}
			$prefs = JsonService::getInstance()->decode($userPreferences);
			foreach ($prefs as $n => $v) 
			{
				$datas[$n] = $v;
			}
		}
	}
}