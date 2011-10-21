<?php
class dashboard_EditContentAction extends change_Action
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		
		$document = users_UserService::getInstance()->getCurrentBackEndUser();
		$request->setAttribute('document', $document);
		
		return change_View::SUCCESS;
	}
}