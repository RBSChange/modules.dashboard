<?php
class dashboard_EditContentAction extends change_Action
{
    /**
     * @param change_Context $context
     * @param change_Request $request
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
            return change_View::ERROR;
        }
        return change_View::SUCCESS;
    }
}