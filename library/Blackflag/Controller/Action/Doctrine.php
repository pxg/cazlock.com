<?php
/**
 * Controller to handle clients
 * Like Blackflag_Controller_Action but implements Doctrine models
 */
class Blackflag_Controller_Action_Doctrine extends Zend_Controller_Action
{
    /**
     * Name of the main model used by the controller
     * @var string
     */
    protected $_primaryModel = '';
    
    /**
     * FlashMessenger
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger = null;
    
    /**
     * Logger to use
     * @var Zend_Log
     */
    protected $_logger = null;
    
    /**
     * Admin config
     * @var Zend_Config
     */
    protected $_adminConfig = null;

    /**
     * Constructor
     * @return void;
     */
    public function init()
    {
        // get messenger
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        
        // get the logger
        $this->_logger = Zend_Registry::get('logger');
        
        // get admin config from application config
        // @todo Too specific to the Admin module - move in there? 
        $this->_adminConfig = $this->getInvokeArg('bootstrap')->getOption('admin');
    }

    /**
     * Index action - list records
     * @return void;
     */
    public function indexAction()
    {
        $this->view->items = Doctrine::getTable($this->_primaryModel)->findAll();
    }

    /**
     * Delete action - delete record
     * @return void;
     */
    public function deleteAction()
    {
        // delete record
        if ($this->_hasParam('id')) {
            $q = Doctrine_Query::create()->delete($this->_primaryModel . ' x')->where('x.id = ?', $this->_getParam('id'));
            $q->execute();
        }
    }

    /**
     * Override updateAction
     * @return void;
     */
    public function updateAction()
    {
    
    }
}