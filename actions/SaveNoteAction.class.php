<?php
class dashboard_SaveNoteAction extends change_JSONAction
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public final function _execute($context, $request)
	{
		$user = users_UserService::getInstance()->getCurrentBackEndUser();
		if ($user !== null)
		{
			try 
			{
				$user->setMeta('modules.dashboard.noteContent', $request->getParameter('noteContent'));
				$user->saveMeta();
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