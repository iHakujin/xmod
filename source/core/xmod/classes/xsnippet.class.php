<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

@require_once(dirname(__FILE__).'/xspecifiedobject.class.php');

class xSnippet extends xSpecifiedObject {
    const TAG_KEY       = '';     
    public function __construct(\xMOD &$xmod) {
        parent::__construct($xmod);
    }
}