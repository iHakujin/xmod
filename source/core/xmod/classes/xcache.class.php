<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(dirname(dirname(__FILE__)).'/xobject.class.php');

class xCache extends xObject {
    
    protected $directory    =   '/core/cache/';
    protected $repository   =   array();
    
    public function __construct(\xMOD &$xmod) {
        parent::__construct($xmod);
        // Устанавлииваем директорию для кэша
        $this->directory = (!empty($xmod->config['pathes']['cache'])) ? $xmod->config['pathes']['cache'] : $this->directory;
    }
    
    public function put(&$obj) {
        // Если объект не подходит для кэширования, то нах его
        if (!is_a($obj, 'xSpecifiedObject')) return false;
        
        $this->reposiory[get_class($obj)][$obj->getName()][$obj->getToken()] = $obj;
        
        return true;
    }
    
    public function get($classname, $name, $token = 0) {
        $this->load($classname);
        return $this->reposiory[$classname][$name][$token];
    } 
    
    public function load($class = null){

    }
    
    public function save(){
        
    }
}