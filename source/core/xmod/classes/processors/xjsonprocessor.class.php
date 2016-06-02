<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once(dirname(__FILE__).'/xdefaultprocessor.class.php');

class xJSONProcessor extends xDefaultProcessor {
    
    public function __construct(\xMOD &$xmod) {
        parent::__construct($xmod);
    }
    
    public function process($data){
        $this->result[] = 'All working';
        return json_encode($this->result);
    }
}