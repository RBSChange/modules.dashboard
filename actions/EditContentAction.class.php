<?php
class dashboard_EditContentAction extends f_action_BaseAction
{
    /**
     * @param Context $context
     * @param Request $request
     */
    public function _execute ($context, $request)
    {
        try
        {
            $document = users_UserService::getInstance()->getCurrentBackEndUser();
            if (!$document->getDashboardcontent())
            {
               $document->setDashboardcontent(dashboard_DashboardService::getInstance()->getDefaultContent());
            }
            $request->setAttribute('document', $document);
        } 
        catch (Exception $e)
        {
            Framework::exception($e);
            
            $request->setAttribute('error', $e->getMessage());
            if (isset($document) && $document)
            {
                $request->setAttribute('document', $document);
            }
            return View::ERROR;
        }
        return View::SUCCESS;
    }
}