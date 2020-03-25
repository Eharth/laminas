<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usuarios
 *
 * @author imam
 */
class Application_Model_DbTable_Usuario implements Zend_Acl_Role_Interface
{
    private $_userName;
    private $_roleId;
    private $_fullName;
    private $_userID;
    private $_roleName;
 
    public function getUserName()
    {
        return $this->_userName;
    }
 
    public function setUserName($userName)
    {
        $this->_userName = (string) $userName;
    }
 
    public function getFullName()
    {
        return $this->_fullName;
    }
    
    public function setRoleName ($roleName) {
        $this->_roleName = (string) $roleName;
    }
    
    public function getRoleName() {
        return $this->_roleName;
    }
 
    public function setFullName($fullName)
    {
        $this->_fullName = (string) $fullName;
    }
    public function getUserID()
    {
        return $this->_userID ;
    }
    public function setUserID($userID)
    {
       $this->_userID = (string) $userID; 
    }
    
    /**
     *
     */
    public function getRoleId()
    {
        return $this->_roleId;
    }
 
    public function setRoleId($roleId)
    {
        $this->_roleId = (string) $roleId;
    }
}

?>
