<?php

// Пришлось назвать класс так-же, как в modx, потому, что система именования здесь,
// предполагает название, начинающееся с "x"

// !! SINGLETON !!
class xPDO {
    protected $xmod         = null;
    protected $handle       = null;
    protected $connected    = false;
    protected static $_instance; 
    protected $stmt         = null;

        private function __construct() {        
    }
    
    public static function getInstance(\xMOD &$xmod) {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
            self::$_instance->xmod = $xmod;
            
            self::connect();
        }
        
        return self::$_instance;
    }
    
    public function getHandle(){
        return $this->handle;
    }
  
    private function __clone() {
    }

    private function __wakeup() {
    }
    
    protected function connect(){
        
        // Инициализируем системные переменные 
        $obj=self::$_instance;
        $user   = $obj->xmod->config['db']['user'];
        $pass   = $obj->xmod->config['db']['passwd'];
        $host   = $obj->xmod->config['db']['host'];
        $dbname = $obj->xmod->config['db']['dbname'];

        try {
            switch ($obj->xmod->config['db']['type']){  
                case 'mysql' : {
                    # MySQL через PDO_MYSQL  
                    $obj->handle = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                    $obj->connected = true;
                break;
                }
                case 'mssql' : {
                    # MS SQL Server через PDO_DBLIB   
                    $obj->handle = new PDO("mssql:host=$host;dbname=$dbname", $user, $pass);
                    $obj->connected = true;
                break;
                }
                case 'sybase' : {
                    # Sysbase через PDO_DBLIB  
                    $obj->handle = new PDO("sybase:host=$host;dbname=$dbname", $user, $pass);
                    $obj->connected = true;
                break;
                }
                case 'sqlite' : {
                    # SQLite  
                    $obj->handle = new PDO("sqlite:my/database/path/database.db");
                    $obj->connected = true;
                break;
                }
                default : {
                    $obj->xmod->log('Unknown DB Type, failed to create PDO object', __FILE__.'('.__LINE__.') : ', XLOG::ERROR);
                }
            }
        }catch(PDOException $e) {  
            $obj->xmod->log($e->getMessage(), __FILE__.'('.__LINE__.') : ', XLOG::ERROR);
        }
    }
    
    public function isConnected() {
        $obj=self::$_instance;
        return $obj->connected;
    }
    
    /*
    public function query($statement) {
        $this->stmt = $this->handle->query($statement);
    }
    
    public function fetch($fetch_style = null, $cursor_orientation = 'PDO::FETCH_ORI_NEXT', $cursor_offset = 0){
        $this->xmod->xdebug->info['queries']['count']++;
        return $this->stmt->fetch($fetch_style, $cursor_orientation, $cursor_offset);
    }
     
     */
}