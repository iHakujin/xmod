<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
@require_once(dirname(__FILE__).'/xpdo.class.php');
@require_once(dirname(__FILE__).'/xspecifiedobject.class.php');

abstract class xPDOObject extends xSpecifiedObject {
    
    protected $xpdo             = null;
    
    // Поля таблицы объекта 
    protected $map              = null;
    
    // Данные объекта
    protected $data             = null;
    
    public function __construct(\xMOD &$xmod) {
         parent::__construct($xmod);
         
        // Инициализируем датапровайдер
        $this->xpdo = xPDO::getInstance($xmod)->getHandle();
        $this->makeMap(); // Строим карту полей объекта 
    }
    
    protected function isConnected() {
        $offline = $this->xmod->config['db']['offline'];
        if ($offine || !($this->xpdo instanceof xPDO) || !xPDO::isConnected()){
            return false;
        }
        return true;
    }
    
    public function getTableName(){
        return $this->xmod->config['db']['prefix'].strtolower(get_class($this));
    }
    
    public function load($name){
        $this->xmod->xdebug->log->add('Trying to load xPDO Object "'.$name.'"', '', XLOG::MESSAGE);
        if (!$this->isConnected()) {
            $this->xmod->xdebug->log->add('DB is not connected? offline mode', '', XLOG::WARNING);
            return false;
        }
    }
    
    protected function makeMap($remake = false){
        if (!xPDO::isConnected()) return false;
        
         // Если карта есть в файле, то пробуем применить ее
        $exist = false;    
            // TODO:
            // Make section
            
        if ($remake || !$exist) {
            // Если кэшируемый, то пробуем получить данные из кэша
            
            if ($cached){ // Если получили
                echo 'Кэша еще нет';  
            } else {

                // Пока получаем только основной объект (TODO: прикрутить связанные)
                $stmt = $this->xpdo->query('show columns from '.$this->getTableName());
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
            }
        }
        
        // Name of mapped object 
        $this->map['object'] = get_class($this);
        
        // Prepare fields array
        $this->map['fields'] = array();
        
        foreach ((array)$data as $item) {
             $this->map['fields'][$item['Field']] = $item;
        }
        return $this->map;
    }
    
    public function getObjectRawData($cached = null){
        if (!xPDO::isConnected()) return false;
        $this->cached = (isset($cached)) ? $cached : $this->cached;
        
        // Если кэшируемый, то пробуем получить данные из кэша
        if ($this->cached){
          echo 'Кэша еще нет';  
        } else {

            // Добавить ключ, для выбора нужной записи
            $stmt = $this->xpdo->query('select * from '.$this->getTableName());
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->xmod->xdebug->info['queries']['count']++;
        }
        
        return $data;
    }
    
    protected function getComposites($cached = null){
        return null;
    }    
    
    // Возвращает загруженный и заполненный данными объект
    public function getObject($cached = true, $remap = false){
        // Make Object map if not maked or remap requested
        if ($remap || !isset($this->map) || ($this->map['object'] !== get_class($this))){
            $this->makeMap(true);   
        }
        
        $data = $this->getObjectRawData($cached);

        
        foreach ($this->map['fields'] as $key => $field) {
            $this->data[$key] = $this->processField($data[$key]);
        }
        
        return $this;
    }
    
    protected function processField($field) {
        // TODO:
        // Подключаем нужный процессор и преобразуем поле так, как нужно нам
        $result = $field;
        return $result;
    }
   
    public function get($k) {
        return $this->data[$k];
    }
    
}