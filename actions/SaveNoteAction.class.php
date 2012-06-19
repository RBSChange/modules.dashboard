<?php
class dashboard_SaveNoteAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public final function _execute($context, $request)
	{
		$user = users_UserService::getInstance()->getCurrentUser();
		if ($user !== null)
		{
			try 
			{
				$dashboardProfile = dashboard_DashboardprofileService::getInstance()->getByAccessorId($user->getId());
				if ($dashboardProfile === null)
				{
					$dashboardProfile = dashboard_DashboardprofileService::getInstance()->getNewDocumentInstance();
					$dashboardProfile->setAccessor($user);
				}
				$noteContent = $request->getParameter('noteContent');
				if (f_util_StringUtils::isEmpty($noteContent)) 
				{
					$noteContent = null;
				}
				$dashboardProfile->setNoteContent($noteContent);
				$dashboardProfile->save();
			}
			catch (Exception $e)
			{
				Framework::exception($e);
				$this->sendJSONError($e->getMessage());
			}			
		}
		else 
		{
			return $this->sendJSONError('No user !');
		}
		return $this->sendJSON(array());
	}
}