<?php
/**
 * @author : José Luis Villaronga
 * @copyright : 2014
 */
class Paginator 
{
	private $_query;
	private $totalFilas;
	private $filasPPagina;
	private $totalPaginas;
	private $nPagina;
	private $_queryLimit;
	private $fetch;
	private $fetch2;
	/**
	 * Metodo Constructor
	 */
	public function __construct($query,$numeroPagina,$filasPPagina){
		$this->_query=$query;
		$this->nPagina=$numeroPagina;
		$con = Conexion::conectar();
		$stmt = $con->prepare($query);
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute();
		$res=$stmt->fetchALL();
		$this->fetch2=$res;
		$this->totalFilas=count($res);
		$this->filasPPagina=$filasPPagina;
		$coef=intval($this->totalFilas / $filasPPagina);
		if($this->totalFilas > $coef*$this->filasPPagina){
			$this->totalPaginas=intval($this->totalFilas / $filasPPagina) + 1;
		}else{
			$this->totalPaginas=intval($this->totalFilas / $filasPPagina);
		}
		if($this->nPagina > $this->totalPaginas){
			$this->nPagina=$this->totalPaginas;
		}elseif($this->nPagina < 1){
			$this->nPagina=1;
		}
		$queryF=$this->_query;
		if($this->nPagina > "0" AND $this->nPagina <= $this->totalPaginas){
			$i=$this->getNumeroPagina()-1;
			$ii=$i * $filasPPagina;
			$this->_queryLimit=$query." LIMIT ".$ii.",".$this->filasPPagina;
			$this->fetch=Db::listar($this->_queryLimit);
		}
	}
	/**
	 * Getters ...
	 */
	public function getQuery(){
		return $this->_query;
	}
	public function getTotalFilas(){
		return $this->totalFilas;
	}
	public function getFilasPPagina(){
		return $this->filasPPagina;
	}
	public function getTotalPaginas(){
		return $this->totalPaginas;
	}
	public function getNumeroPagina(){
		if($this->nPagina > $this->totalPaginas){
			return $this->totalPaginas;
		}else{
			return $this->nPagina;
		}
	}
	public function getFetch(){
		return $this->fetch;
	}
	public function getQueryLimit(){
		return $this->_queryLimit;
	}
	public function atrasCSS(){
		if($this->nPagina < 2){
			echo " style='display: none;'";
		}else{
			echo " style='display: auto;'";
		}
	}
	public function adelanteCSS(){
		if($this->nPagina >= $this->totalPaginas){
			echo " style='display: none;'";
		}else{
			echo " style='display: auto;'";
		}
	}
	public function menuPaginator($nPagina){

		
	}
	/**
	 * Setters ...
	 */
	public function setFilasPPagina($nFPP){
		$this->filasPPagina=$nFPP;
	}
}
