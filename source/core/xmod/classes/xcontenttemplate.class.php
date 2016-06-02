<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
@require_once(dirname(__FILE__).'/xspecifiedobject.class.php');

class xContentTemplate extends xSpecifiedObject {
   
    const OPENING_SYM       = '[[';
    const TRAILING_SYM      = ']]';
    
    protected $data         = [];
    protected $source       = '';
    protected $prefix       = '';
    protected $cacheable    = false;
    
    public function __construct(\xMOD &$xmod) {
        parent::__construct($xmod);
    }
    
    public function setCacheable($cacheable = true){
        $this->cacheable = $cacheable;
        return $this->cacheable;
    }
    
    public function setSource($source){
        $this->source   = $source;
    }  
    
    public function setData($data){
        $this->data = $data;
    }    
    
    public function setPrefix($prefix ){
        $this->prefix = $prefix;
        return $this->$prefix;
    }
    
    public function process($data = []){
        //$processor->addSource($this->source);
        $processor->addData($this->data);
        
        $processor = $this->xmod->newObject('xHTMLProcessor');
        if (!($processor instanceof xHTMLProcessor)) return null;
        
        $this->source = $processor->process($this->data, '+', $this->prefix);
        return true;
    }
}