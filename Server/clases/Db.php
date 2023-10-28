<?php
/**
 * @author : José Luis Villaronga
 * @copyright : 2014
 */
class Db
{
	/***********************************************************************************
	 * Método constructor "privado" para evitar instancias de esta clase ...           *
	 ***********************************************************************************/
	private function __construct(){}
	/***********************************************************************************
	 * Lista todas las filas del Query que le pase como parametro ...                  *
	 ***********************************************************************************/
	static function listar($query){
		$con = Conexion::conectar();
		$sql = "SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;".$query."; SET SESSION TRANSACTION ISOLATION LEVEL REPEATABLE READ;";
		$stmt = $con->prepare($query);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute();
		return $stmt->fetchALL();
	}
}
