<?php
/**
 * Class where to put your custom methods for document dashboard_persistentdocument_dashboardprofile
 * @package modules.dashboard.persistentdocument
 */
class dashboard_persistentdocument_dashboardprofile extends dashboard_persistentdocument_dashboardprofilebase
{

	protected function getUserPreferencesObject()
	{
		$pref = $this->getUserPreferences();
		if (!empty($pref))
		{
			return JsonService::getInstance()->decode($pref);	
		}
		return array();
	} 
	
	protected function setUserPreferencesObject($array)
	{
		if (count($array))
		{
			$this->setUserPreferences(JsonService::getInstance()->encode($array));
		}
		else
		{
			$this->setUserPreferences(null);
		}
	} 
	
	public function setToolbarActionView($string)
	{
		$pref = $this->getUserPreferencesObject();
		if (empty($string))
		{
			unset($pref['toolbarActionView']);
		}
		else
		{
			$pref['toolbarActionView'] = $string;
		}
		$this->setUserPreferencesObject($pref);
	}
	
	public function setDocumentEditorDefaultPanel($string)
	{
		$pref = $this->getUserPreferencesObject();
		if (empty($string))
		{
			unset($pref['documentEditorDefaultPanel']);
		}
		else
		{
			$pref['documentEditorDefaultPanel'] = $string;
		}
		$this->setUserPreferencesObject($pref);
	}

	public function setDocumenteditorHelp($string)
	{
		$pref = $this->getUserPreferencesObject();
		if (empty($string))
		{
			unset($pref['documenteditorHelp']);
		}
		else
		{
			$pref['documenteditorHelp'] = $string;
		}
		$this->setUserPreferencesObject($pref);
	}
	
	public function setUseLeftTreeDblClick($string)
	{
		$pref = $this->getUserPreferencesObject();
		if ($string == 'true')
		{
			$pref['useLeftTreeDblClick'] = $string;
		}
		else
		{
			unset($pref['useLeftTreeDblClick']);
		}
		$this->setUserPreferencesObject($pref);
	}
}