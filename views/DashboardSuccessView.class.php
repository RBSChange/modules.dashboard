<?php
class dashboard_DashboardSuccessView extends f_view_BaseView
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$this->setTemplateName('Compatibility', K::HTML);
		$this->setAttribute('contents', $request->getAttribute('contents'));
	}
}
