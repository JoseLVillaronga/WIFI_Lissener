<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
    class Conexion
    {
        //declaramos estática para que se acceda esta propiedad directamente desde el objeto
        static $link;
        
        // definimos el método constructor simplemente para que no se pueda instanciar
        private function __construct(){}
        
     /**
     * @static
     * @return PDO
     * declaramos estática para que se acceda este método directamente desde el objeto
     */
        static function conectar(){
            try {
                self::$link = new PDO($_SESSION['DB_CONNECTION'].":host=".$_SESSION['DB_HOST'].";port=".$_SESSION['DB_PORT'].";dbname=".$_SESSION['DB_DATABASE'].";charset=utf8", 
		    $_SESSION['DB_USERNAME'], 
		    $_SESSION['DB_PASSWORD'], 
		    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
		);
                /*** nos conectamos ***/
                return self::$link;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }           
        }
    }
