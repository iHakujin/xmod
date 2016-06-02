<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

@require_once(dirname(__FILE__).'/xobject.class.php');

class xDebug extends xObject{
    
    public $log     =   null;
    public $level   =   0;
    public $info    =   array();

    public function __construct(\xMOD $xmod) {
        parent::__construct($xmod);
        
        // Создаем объект xLOG (debug лог)
        $this->log = $this->xmod->newObject('xLog');
        if (is_object($this->log) && ($this->log instanceof xLog)){
            $this->log->initialize($xmod->config['pathes']['core'].'/logs/debug.log');
        }
        
        $this->level = (int)$xmod->config['debug']['level'];
        
        $this->initDebug($this->level);
    }
    
    protected function initDebug($level = 0){
        // Пишем, что лог запущен, дату и время
        //$this->log->add('--xDebug started ( level: '.$level.')--'); 
        switch ($level) {
            case 5 : {
                
            }
            case 4 : {
                
            }
            case 3 : {
                
            }
            case 2 : {
                
            }
            case 1 : {
                
            }
            default : {
                
            }
        }
        return true;
    }
    
}