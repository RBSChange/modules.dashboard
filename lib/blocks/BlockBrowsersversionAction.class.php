<?php

class dashboard_BlockBrowsersversionAction extends website_BlockAction
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
		try 
		{			
			$request->setAttribute('browsers', Framework::getConfiguration('browsers'));
		}
		catch (Exception $e)
		{
			$request->setAttribute('browsers', false);
			Framework::exception($e);
		}
		return website_BlockView::SUCCESS;
	}
}