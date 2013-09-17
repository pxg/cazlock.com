<?php
/**
 * Controller to handle Logins and outs of Admin module
 */
class Admin_UserController extends Zend_Controller_Action
{
	/**
     * Login action - validates against db table using Zend_Auth
     * @return void;
     */
    public function loginAction()
    {
        $form = new Admin_Form_User_Login;
        $this->view->login = true;
        
        if ($this->_hasParam('logout')) {
        	$this->view->loggedOut = true;
        }

        if (!$this->getRequest()->isPost()) {
            $this->view->loginForm = $form;
            return;
        } elseif (!$form->isValid($_POST)) {
            $this->view->failedValidation = true;
            $this->view->loginForm = $form;
            return;
        }

        $values = $form->getValues();

        // Setup DbTable adapter
        $adapter = new Zend_Auth_Adapter_DbTable(
            Zend_Db_Table::getDefaultAdapter() // set earlier in Bootstrap
        );
        $adapter->setTableName('admin_users');
        $adapter->setIdentityColumn('username');
        $adapter->setCredentialColumn('password');
        $adapter->setIdentity($values['name']);
        $adapter->setCredential(
            hash('SHA256', $values['password'])
        );

        // authentication attempt
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($adapter);

        // authentication succeeded
        if ($result->isValid()) {
            $auth->getStorage()
                ->write($adapter->getResultRowObject(null, 'password'));
            $this->view->passedAuthentication = true;        
            $this->_redirect('/admin/index/');
        } else {
        	// failed, about to login page
            $this->view->failedAuthentication = true;
            $this->view->loginForm = $form; 
        }
    }
    
	/**
     * Logout action - clears the Zend_Auth identity
     * @return void;
     */
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('user', 'login', 'admin', array('logout' => 1));
    }

}