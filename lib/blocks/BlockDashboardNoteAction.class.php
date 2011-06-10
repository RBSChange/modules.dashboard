<?php
/**
 * dashboard_BlockDashboardNoteAction
 * @package modules.dashboard.lib.blocks
 */
class dashboard_BlockDashboardNoteAction extends website_BlockAction
{	
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	function execute($request, $response)
	{
		if ($this->isInBackofficeEdition())
		{
			return website_BlockView::DUMMY;
		}
		
		$user = users_UserService::getInstance()->getCurrentBackEndUser();
		if ($user !== null && $user->hasMeta('modules.dashboard.noteContent'))
		{
			$request->setAttribute('noteContent', $user->getMeta('modules.dashboard.noteContent'));
		}
		return website_BlockView::SUCCESS;
	}
}