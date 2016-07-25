<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

@require_once(dirname(__FILE__).'/xcontenttemplate.class.php');

class xChunk extends xContentTemplate{   
    const TAG_KEY       = '$';    
    public function __construct(\xMOD &$xmod) {
        parent::__construct($xmod);
        try{
            
        }catch (Exception $e) {
            $xmod->sendError('xChunk class construct error', array('error_message' => $e->getMessage()));
        }
    }
    

}