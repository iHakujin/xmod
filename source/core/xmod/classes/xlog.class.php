<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

@require_once(dirname(__FILE__).'/xobject.class.php');

class xLog extends xObject{
    
    const MESSAGE   =   0x1;
    const NOTICE    =   0x2;
    const WARNING   =   0x3;
    const ERROR     =   0x4;
    const CRITICAL  =   0x5;
    
    protected $conf =   array();
      
    private $handle =   null;
       
    public function __construct(\xMOD &$xmod) {
        parent::__construct($xmod);
        
        $this->conf = &$xmod->config['log'];
    }
    
    public function initialize($file = ''){
        $file = (empty($file)) ? $this->xmod->config['pathes']['core'].'/logs/default.log' : $file;       
        if (!$this->handle = fopen($file, "a")){
            return false;
        }
        return true;
    }

    public function stop(){
        fclose($this->handle); 
        return true;
    }
    
    
    static function getStringType($index){
        switch($index) {
            case 0x1 : {return 'MESSAGE';}
            case 0x2 : {return 'NOTICE';}
            case 0x3 : {return 'WARNING';}
            case 0x4 : {return 'ERROR';}
            case 0x5 : {return 'CRITICAL';}
            default  : {return 'UNKNOWN';}
        }
    }
    
    public function add($msg, $title = '', $type = XLOG::NOTICE) {
        $time = date($this->conf['dateformat'], time());

        $message = "[{$time}] ".self::getStringType($type)." > {$title} {$msg}\r\n";
        
        // Записываем $somecontent в наш открытый файл.
        if (fwrite($this->handle, $message) === FALSE) {
            return false;
        }    
        
        return true;
    }
}