<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(dirname(__FILE__).'/xdefaultprocessor.class.php');
require_once(dirname(dirname(__FILE__)).'/xcontenttemplate.class.php');

class xHTMLProcessor extends xDefaultProcessor {
    
    protected $template     =   null;
    protected $keys         =   ['$', '#', '%', '*', '!', '+', '++', '~', '~~', '?', '^'];
    
    public function __construct(\xMOD &$xmod) {
        parent::__construct($xmod);
    }

    /*
    public function getChunk($tpl, $props = [], $prefix = ''){
        
        $tpl = 'IM VERY CHUNK IOGOGO';
        
        
            if (substr($tpl, 0, 7) == '@INLINE') {
                $tpl = substr($tpl, 7);   
            }elseif(substr($tpl, 0, 5) == '@FILE'){
                $filename = substr($tpl, 5);
                $tpl = implode('', file($filename));
            }else{
                $tpl = $this->modx->getChunk($tpl, $props);
            }
        
            $chunk = $this->newObject('xChunk');
            if (!($chunk instanceof xChunk)) return false;
            
            $chunk->setCacheable();
            $chunk->setPrefix($prefix);

            $chunk->process($tpl, $props);
            
        return $chunk;
    } 
    */
       
    public static function dot($array, $prepend = '')
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value) && ! empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }
        return $results;
    }    
    
    private function processTags($source, $data = [], $key, $prefix){
        $matches = array();

        $exp  = "/(".preg_quote(xContentTemplate::OPENING_SYM, '/')."(\W+)([\w]+[\w\.]*[\w]+)(.*?)".preg_quote(xContentTemplate::TRAILING_SYM, '/').")/i";
        
        if ( ($count = preg_match_all( $exp, $source, $matches, PREG_SET_ORDER)) === false ){
            $this->xmod->log('xTags processing failed: ', __FILE__.'('.__LINE__.')', XLOG::ERROR);
        }
          
        if ($count) {
            // Подготавливаем данные
            $prepared_data = self::dot($data);
        }
        
        foreach((array)$matches as $match){          
            $source = str_replace($match[0], $prepared_data[$match[3]], $source);   
            //xContentTemplate::OPENING_SYM.$match[2].$match[3].$match[4].xContentTemplate::TRAILING_SYM
        }
        return $source;
    }
    
    
    public function process($data, $key = '*', $prefix = ''){
        $source = implode("\n", $this->source);
        $this->source = $this->processTags($source, $data, $key, $prefix);
/*
        if ($this->xmod->xdebug->level > 0) {
            $this->source = $this->processTags($this->source, (array)$this->xmod->xdebug->info, '^', '');
        }
*/
        return true;
    }
}