<?php
class dashboard_DashboardSuccessView extends change_View
{
	/**
	 * @param change_Context $context
	 * @param change_Request $request
	 */
	public function _execute($context, $request)
	{
		$this->setTemplateName('Compatibility', 'html');
		$this->setAttribute('contents', $request->getAttribute('contents'));
	}
}