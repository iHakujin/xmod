<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(dirname(dirname(__FILE__)).'/xobject.class.php');
require_once(dirname(__FILE__).'/xprocessor.interface.php');

class xDefaultProcessor extends xObject implements xProcessor {
    
    protected $data       = [];    
    protected $source     = '';    
    
    // Выходные данные
    protected $result   = '';
    
    public function __construct(\xMOD &$xmod) {
        parent::__construct($xmod);
    }
    
    public function addSource($source) {
        $this->source[] = $source;
    }   
    
    public function addData($data) {
        $this->data[] = $data;
    }       
    
    public function getResult() {
        return $this->source;
    }       
    
    
    public function process($data){
        $this->result = 'default processor';
        
        $this->result =  print_r($this->result, 1);
        return true;
    }
}