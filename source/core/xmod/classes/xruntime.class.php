<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class xRuntime extends xObject{
    public $startTime       = 0;
    
    public $processor       = null;
    
    public $content_type    = '';
    
    public $ext             = '';
    
    protected $document     = null;
    
    public function __construct(\xMOD $xmod){
        parent::__construct($xmod);
    }
    
    public function run($t){
        $xmod = &$this->xmod;
        $this->startTime = $t;        
        
        
        $this->content_type = 'html';        
        $ct_ext = &$xmod->config['extentions'][$this->content_type];

        $this->ext              = (isset($ct_ext) ? $ct_ext : '.htm');
        // Ищем подходящий процессор
        $this->processor = $xmod->newObject('x'.$this->content_type.'Processor');  
        
        // Если не нашли, грузим дефолтный
        if ( !is_object($this->processor) ) {
            $this->processor = $xmod->newObject('xDefaultProcessor');
        }

        if (!is_a($this->processor, 'xDefaultProcessor')) {
            return false;
        }          

        
        
        $data = array_merge(array('_SERVER' => $_SERVER), $xmod->config);
        $data['db']['passwd'] = 'hidden for secure';
        
        
        // Load web document
        if ( !is_object($this->document) ) {
            $this->document = $this->xmod->newObject('xDocument');
        }
        //$template = $this->xmod->newObject('xTemplate');
        //$source = $template->getObject(false)->get('source');
        // 
        //print_r($data);
        // Временное
        $source = implode("", file($this->xmod->config['pathes']['core'].'xmod/templates/default.template.html'));
       $this->processor->addSource($source);
        
        
        $st = explode(" ",$this->startTime);
        $st = $st[0]+$st[1];
        $et = explode(" ",  microtime());
        $et = $et[0]+$et[1];
        
        $xmod->xdebug->info['run']['time'] = sprintf("%f", ($et - $st));
        
        if (!$this->processor->process($data, '++', '')){
            $xmod->log('Unable to process document', __FILE__.'('.__LINE__.')', XLOG::CRITICAL);
            return false;
        }
        
        echo $this->processor->getResult();
        
        return true;
    }
}