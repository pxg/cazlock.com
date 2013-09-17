<?php
/**
 * Error controller class
 *
 * This class defines all action methods for the controller of any kind of errors.
 */
class ErrorController extends Zend_Controller_Action
{
    /**
     * Display the error page
     */
    public function errorAction()
    {
		$this->getResponse()->clearBody();
    	$errors = $this->_getParam('error_handler');
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
        //        $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');
                $this->view->statusCode = 404;
        //        break;
            default:
                // application error
        //        $this->getResponse()->setRawHeader('HTTP/1.1 500 Internal Server Error');
                $this->view->statusCode = 500;
        //        break;
        }
        
        // set response code
        $this->getResponse()->setHttpResponseCode($this->view->statusCode);
             
        // Log exception, if logger available
        if ($log = $this->getLog()) {
        	$log->log($errors->exception->getMessage(), Zend_Log::ERR);
        }

		// redirect to home page
    	$this->_redirect("/");
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}