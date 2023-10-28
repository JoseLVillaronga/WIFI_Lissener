<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
    class Conexion
    {
        //constantes para la conexion
        Static $user   = 'db_user';
        static $password     = 'db_password';
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
                self::$link = new PDO("mysql:host=127.0.0.1;port=3306;dbname=teccam;charset=utf8", self::$user, self::$password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                /*** nos conectamos ***/
                //echo 'conectado a mysql <br />'; 
                return self::$link;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }           
        }
    }
