<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

@require_once(dirname(__FILE__).'/xmod.class.php');

// Default, abstract class of xmod-Object
class xObject {
    protected $xmod     = null;
    
    public function __construct(xMOD &$xmod) {
        $this->xmod = $xmod;
    }    
}