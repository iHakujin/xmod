<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once(dirname(__FILE__).'/xobject.class.php');

class xSpecifiedObject extends xObject {
    
    // Идентивфикаторы
    protected   $token    =     null;
    protected   $name     =     null;
    
    protected   $cached   =     true;
    
    public function __construct(\xMOD &$xmod) {
        parent::__construct($xmod);
        $this->updateToken();
    }
    
    public function updateToken(){
        $old_token = $this->token;
        $this->token = uniqid();
        return (($old_token != $this->token) ? true : false );
    }
       
    public function getToken(){
        return $this->token;
    }
    
    public function getName(){
        return $this->name;
    }    
    
    public function load($name){
        return true;
    }    
}

