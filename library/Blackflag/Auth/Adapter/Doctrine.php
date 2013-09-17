<?php
/**
 * Auth adapter for Doctrine user class.
 *
 * Heavily based on the adapter described here:
 * @link http://palo-verde.us/?blog/2009/08/16/zend_auth-adapter-with-doctrine.html
 * 
 * @package Blackflag
 * @author Alex Gemmell
 * @version 1.0
 */
class Blackflag_Auth_Adapter_Doctrine implements Zend_Auth_Adapter_Interface
{
    public $username;
    public $password;
    public $user;
    
    const NOT_FOUND_MESSAGE = "The username you entered could not be found.";
    const CREDENTIALS_MESSAGE = "The password you entered is not correct.";
    const UNKNOWN_FAILURE = "Authentication failed for an unknown reason.";

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /** 
     * Zend_Auth hook
     */
    public function authenticate()
    {
        try {
            $this->user = User::authenticate($this->username, $this->password);
        } catch (Exception $e) {
            
            if ($e->getMessage() == User::NOT_FOUND) {
                return $this->notFound();
            }
            
            if ($e->getMessage() == User::PASSWORD_MISMATCH) {
                return $this->passwordMismatch();
            }
            
            return $this->failed($e);
        }
        
        return $this->success();
    }

    /**
     * Factory for Zend_Auth_Result
     *
     * @param integer    The Result code, see Zend_Auth_Result
     * @param mixed      The Message, can be a string or array
     * @return Zend_Auth_Result
     */
    public function result($code, $messages = array())
    {
        if (!is_array($messages)) {
            $messages = array(
                $messages
            );
        }
        
        return new Zend_Auth_Result($code, $this->user, $messages);
    }

    /**
     * "User not found" wrapper for $this->result()
     */
    public function notFound()
    {
        return $this->result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, self::NOT_FOUND_MESSAGE);
    }

    /**
     * "Password does not match" wrapper for $this->result()
     */
    public function passwordMismatch()
    {
        return $this->result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND, self::CREDENTIALS_MESSAGE);
    }

    /**
     * General or Unknown failure wrapper for $this->result()
     */
    public function failed(Exception $e)
    {
        return $this->result(Zend_Auth_Result::FAILURE, self::UNKNOWN_FAILURE);
    }

    /**
     * "Success" wrapper for $this->result()
     */
    public function success()
    {
        return $this->result(Zend_Auth_Result::SUCCESS);
    }
}
?>