<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(dirname(__FILE__).'/xobject.class.php');

class xRuntime extends xObject{

    public $processor       = null;
    
    public $content_type    = '';
    
    public $ext             = '';
    
    public function __construct(\xMOD $xmod){
        parent::__construct($xmod);
    }
    
    public function run(){
        $xmod = &$this->xmod;
        
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
        
        //print_r($data);
        
        $source = implode("\r\n", file($this->xmod->config['pathes']['core'].'xmod/templates/default.template.html'));
        $this->processor->addSource($source);
        if (!$this->processor->process($data, '++', '')){
            $xmod->log('Unable to process document', __FILE__.'('.__LINE__.')', XLOG::CRITICAL);
            return false;
        }
        
        echo $this->processor->getResult();
        
        return true;
    }
}

class xMOD {
    public $startTime       = 0;
    
    public $config          = null;
    
    public $classDepot      = [];
    
    // Данные Query string и POST
    private $request_data   = [];
    
    public $xlog           = null;
    
    public $xdebug         = null;
    
    public $runtime        = null;
    
    
    public function __construct($configuration) {
        $this->config = $configuration;
        $this->classDepot[] = dirname(__FILE__).'/';
        
        //обрабатываем запрос
        $this->handleRequest();        

        try{
            // Добавляем хранилища классов
            $this->addClassDepot(dirname(__FILE__).'/processors/');
            $this->addClassDepot(dirname(__FILE__).'/controllers/');
            
        }catch (Exception $e) {
            $this->sendError('unavailable', array('error_message' => $e->getMessage()));
        }   
    }
    
    public function initialize() {
        // Задаем данные о версии системы
        $this->config['engine']['name']      = 'XMOD';
        $this->config['engine']['version']['number']      = '0.0.1';
        $this->config['engine']['version']['postfix']     = 'alpha';
        
        
        
        // Создаем объект xLOG (логгирование событий)
        $this->xlog = $this->newObject('xLog');
        if (is_object($this->xlog) && ($this->xlog instanceof xLog)){
            if (!$this->xlog->initialize()){
                return false;
            }
        }
        
        $this->xdebug = $this->newObject('xDebug');
        if (!($this->xdebug instanceof xDebug)){
            return false;
        }                 
        
        return true;
    }
    
    public function addClassDepot($directory){
        if (!in_array($directory, $this->classDepot)){
            $this->classDepot[] = $directory;
            return true;
        }
        return false;
    }
    
    public function log($msg, $title = '', $type = XLOG::NOTICE){
        if ($this->xlog instanceof xLog){
            $this->xlog->add($msg, $title, $type);
            if ($type  > XLOG::WARNING) {
                $this->xdebug->log->add($msg, $title, $type);
            }
            return true;
        }
        return false;
    }
    
    public function loadClass($classname){
        if (!class_exists($classname)){
            foreach($this->classDepot as $place){
                $file = $place.strtolower($classname).'.class.php';
                if (file_exists($file)){
                    @require_once($file);
                    return true;
                }
            }
        }
              
        return false;
    }
    
    public function newObject($classname){
            try{
                if (class_exists($classname) || $this->loadClass($classname)) {                    
                    $obj = new $classname($this);
                    if ($obj instanceof $classname) return $obj; 
                }
                               
                
                if (is_object($this->xlog) && ($this->xlog instanceof xLog)){
                    $this->log('Unable to create object "'.$classname.'", class not loaded.', $title = __FILE__.'('.__LINE__.') : ', $type = XLOG::ERROR);
                    return null;
                }
                   
                throw Exception('xMOD  is not initialized, log can not write correctly', 0, null);
            }catch (Exception $e) {
                $this->sendError('Object error', array('error_message' => $e->getMessage()));
            }                   
        return null; // Если не вкатило
    }
    
    public function sendError($message){
        
    }
    
    public function invokeEvent($name){

    return true;    
    }
    
    // HTTP Request
    private function handleRequest(){       
        $this->request_data['assets']['query_id'] = md5(uniqid());
        
        $this->request_data['cookie']   = $_COOKIE;
        $this->request_data['get']      = $_GET;
        $this->request_data['post']    = $_POST;
               
    }
    
    public function request($key, $separated = false){
        if ($separated){
            return $this->request_data[$separated][$key];
        }
        
        $data = [];
        foreach($this->request_data as $r){
            $data = array_merge((array)$data, (array)$r);
        }
        return $data[$key];
    }
    
    public function start($t){
        /* Set the actual start time */
        $this->startTime = $t;        
    
        
        $this->runtime = $this->newObject('xRuntime');
        $this->runtime->content_type = 'html';
        return $this->runtime->run();
    }
}
