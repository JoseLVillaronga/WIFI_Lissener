<?php
/**
 * @Author : José Luis Villaronga
 * @copyright : 2014
 */
class ControlCalidad
{
	/**
	 * Propiedades
	 */
	private $id;
	public $articulo;
	private $categoria=10;
	public $obi;
	private $observaciones;
	private $rechazo="0";
	private $terminado="0";
	public $usuario;
	public $nivelAceptacion;
	public $nivelInspeccion;
	private $fechaInicio;
	private $fechaFinal;
	private $nmuestras;
	private $infor;
	private $evaluado=null;
	public $loteCC;
	/**
	 * Array con error de la ultima transacción (INSERT,UPDATE), se puede imprimir con "print_r($this->errorSql)" ...
	 */
	public $errorSql=array();
	/**
	 * String con el ID de la ultima fila insertada (INSERT) ...
	 */
	public $uFilaIns;
	/**
	 * Array con los errores de los setters ...
	 */
	public $errores=array();
	/**
	 * Metodo constructor ...
	 */
	public function __construct($g){
		$query = "SELECT * FROM control_calidad WHERE cca_id = $g";
		if(empty($g)){
			$this->articulo=new Articulo(256);
			$this->categoria=10;
			$this->obi=new TipoCC("1");
			$this->usuario=new Usuario();
			$this->nivelAceptacion=new NivelAceptacion("1");
			$this->nivelInspeccion=new NivelInspeccion("1");
			$this->loteCC=new LoteCC();
			$this->evaluado=null;
			return $this;
		}
		$res=Db::listar($query);
		foreach ($res as $fila){
			$this->id=$fila['cca_id'];
			$this->articulo=new Articulo($fila['art_id']);
			$this->categoria=$fila['cat_id'];
			$this->obi=new TipoCC($fila['cca_obi_final']);
			$this->observaciones=$fila['cca_observaciones'];
			$this->rechazo=$fila['cca_rechazo'];
			$this->terminado=$fila['cca_terminado'];
			$this->usuario=new Usuario($fila['cli_usuario']);
			$this->nivelAceptacion=new NivelAceptacion($fila['naql_id']);
			$this->nivelInspeccion=new NivelInspeccion($fila['nins_id']);
			$this->fechaInicio=$fila['cca_fecha_inicio'];
			$this->fechaFinal=$fila['cca_fecha_final'];
			$this->nmuestras=$fila['cca_nro_muestras'];
			$this->infor=$fila['cca_informe'];
			$this->evaluado=$fila['cca_usuario'];
		}
		$this->loteCC=new LoteCC($g);
		return $this;
	}
	/**
	 * Getters ..
	 */
	public function getId(){
	 	return $this->id;
	}
	public function getArticuloId(){
		return $this->articulo->getId();
	}
	public function getCategoria(){
		return $this->categoria;
	}
	public function getTipoCCId(){
		return $this->obi->getId();
	}
	public function getObservaciones(){
		return $this->observaciones;
	}
	public function getRechazo(){
		return $this->rechazo;
	}
	public function getTerminado(){
		return $this->terminado;
	}
	public function getUsuario(){
		return $this->usuario->getUsuario();
	}
	public function getNivelAceptacionId(){
		return $this->nivelAceptacion->getId();
	}
	public function getNivelInspeccionId(){
		return $this->nivelInspeccion->getId();
	}
	public function getFechaInicio(){
		return $this->fechaInicio;
	}
	public function getFechaFinal(){
		return $this->fechaFinal;
	}
	public function getNMuestras(){
		return $this->nmuestras;
	}
	public function getInfor(){
		return $this->infor;
	}
	public function getEvaluado(){
		return $this->evaluado;
	}
	public function getCliente(){
		$query="SELECT rep_id FROM lote_cc WHERE cca_id = $this->id LIMIT 1";
		foreach (Db::listar($query) as $fila){
			$em=$fila['rep_id'];
		}
		$cl=new Reparacion($em);
		return $cl->empresa->getRazonSocial();
	}
	/**
	 * Setters ...
	 */
	public function setId($id){
		$this->id=$id;
	}
	public function setArticulo($id){
		$query="SELECT art_id FROM articulos WHERE art_id <> 256";
		foreach (Db::listar($query) as $fila){
			$check[]=$fila['art_id'];
		}
		if(in_array($id, $check)){
			$this->articulo=new Articulo($id);
			unset($check);
		}else{
			$this->errores['articulo']="No es un Articulo válido ...";
			$this->errores['gen'] = "harError";
			unset($check);
		}
	}
	public function setCategoria($nC){
		if($this->articulo->getId()=="256"){
			if(empty($nC)){
				$this->errores['gen'] = "harError";
				$this->errores['categoria'] = "Hay que seleccionar Categoria ...";
			}else{
				$this->categoria=$nC;
			}
		}else{
			$this->categoria=$nC;
			if(empty($nC)){$this->categoria=10;}
		}
	}
	public function setObi($id){
		$query="SELECT cca_obi_final FROM tipo_cc";
		foreach (Db::listar($query) as $fila){
			$check[]=$fila['cca_obi_final'];
		}
		if(in_array($id, $check) AND $id != "1"){
			$this->obi=new TipoCC($id);
			unset($check);
		}else{
			$this->errores['obi']="Seleccione tipo de inspección válido ...";
			$this->errores['gen'] = "harError";
			unset($check);
		}
	}
	public function setObservaciones($obs){
		if(strlen($obs) < 250){
			$this->observaciones=$obs;
		}else{
			$this->errores['observaciones']="Máximo 250 caracteres ...";
			$this->errores['gen'] = "harError";
		}
	}
	public function setRechazo($rech){
		$this->rechazo=$rech;
	}
	public function setTerminado($nTerm){
		if(!empty($nTerm)){
			$this->terminado=$nTerm;
			$this->setFechaFinal();
		}else{
			$this->terminado="0";
			$this->fechaFinal="";
		}
	}
	public function setUsuario($usu){
		$query="SELECT cli_usuario FROM clientes WHERE cli_grupo = -1 OR cli_admin = 2";
		foreach (Db::listar($query) as $fila){
			$check[]=$fila['cli_usuario'];
		}
		if(in_array($usu, $check)){
			$this->usuario=new Usuario($usu);
			unset($check);
		}else{
			$this->errores['usuario']="Seleccione usuario válido ...";
			$this->errores['gen'] = "harError";
			unset($check);
		}
	}
	public function setNivelAceptacion($naql){
		$query="SELECT naql_id FROM nivel_aceptacion";
		foreach (Db::listar($query) as $fila){
			$check[]=$fila['naql_id'];
		}
		if(in_array($naql, $check) AND $naql!= "1"){
			$this->nivelAceptacion=new NivelAceptacion($naql);
			unset($check);
		}else{
			$this->errores['nivelAceptacion']="Seleccione Nivel de Aceptación válido ...";
			$this->errores['gen'] = "harError";
			unset($check);
		}
	}
	public function setNivelInspeccion($nins){
		$query="SELECT nins_id FROM nivel_inspeccion";
		foreach (Db::listar($query) as $fila){
			$check[]=$fila['nins_id'];
		}
		if(in_array($nins, $check) AND $nins != "1"){
			$this->nivelInspeccion=new NivelInspeccion($nins);
			unset($check);
		}else{
			$this->errores['nivelInspeccion']="Seleccione Nivel de Inspección válido ...";
			$this->errores['gen'] = "harError";
			unset($check);
		}
	}
	public function setFechaInicio($fIni){
		if(empty($fIni)){
			$this->fechaInicio=date("Y-m-d H:i:s");
		}else{
			$this->fechaInicio=$fIni;
		}
	}
	public function setFechaFinal($fFin){
		if(empty($fFin)){
			$this->fechaFinal=date("Y-m-d H:i:s");
		}else{
			$this->fechaFinal=$fFin;
		}
	}
	public function setNMuestras($nM){
		$this->nmuestras=$nM;
	}
	public function setInfor($nInfor){
		$this->infor=$nInfor;
	}
	public function setEvaluado($nE){
		if($this->articulo->getId()=="256"){
			if(empty($nE)){
				$this->errores['gen'] = "harError";
				$this->errores['evaluado'] = "Hay que ingresar Usuario ...";
			}else{
				$this->evaluado=$nE;
			}
		}else{
			$this->evaluado=$nE;
		}
	}
	/**
	 * Actualiza la tabla "control_calidad" con las propiedades actuales del objeto ...
	 */
	public function actualizaDb(){
		if(empty($this->fechaFinal)){
			$con=Conexion::conectar();
			$usu=$this->usuario->getUsuario();
			$art=$this->articulo->getId();
			$obi=$this->obi->getId();
			$aql=$this->nivelAceptacion->getId();
			$nins=$this->nivelInspeccion->getId();
			$query = "UPDATE `control_calidad`
						SET
						`cli_usuario` = :usuario,
						`cca_rechazo` = :rechazo,
						`cca_terminado` = :terminado,
						`cca_observaciones` = :observaciones,
						`art_id` = :articulo,
						`cat_id` = :categoria,
						`cca_fecha_inicio` = :fechaInicio,
						`cca_fecha_final` = null,
						`cca_obi_final` = :obi,
						`naql_id` = :nivelAceptacion,
						`nins_id` = :nivelInspeccion,
						`cca_nro_muestras` = :nmuestras,
						`cca_informe` = :infor,
						`cca_usuario` = :evaluado
						WHERE 
						`cca_id` = :id";
			$stmt = $con -> prepare($query);
			$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
			$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
			$stmt->bindParam(':rechazo', $this->rechazo, PDO::PARAM_STR);
			$stmt->bindParam(':terminado', $this->terminado, PDO::PARAM_STR);
			$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
			$stmt->bindParam(':articulo', $art, PDO::PARAM_STR);
			$stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_STR);
			$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
			$stmt->bindParam(':obi', $obi, PDO::PARAM_STR);
			$stmt->bindParam(':nivelAceptacion', $aql, PDO::PARAM_STR);
			$stmt->bindParam(':nivelInspeccion', $nins, PDO::PARAM_STR);
			$stmt->bindParam(':nmuestras', $this->nmuestras, PDO::PARAM_INT);
			$stmt->bindParam(':infor', $this->infor, PDO::PARAM_STR);
			$stmt->bindParam(':evaluado', $this->evaluado, PDO::PARAM_STR);
			$stmt -> execute();
			$this->errorSql = $stmt->errorInfo();
		}else{
			$con=Conexion::conectar();
			$usu=$this->usuario->getUsuario();
			$art=$this->articulo->getId();
			$obi=$this->obi->getId();
			$aql=$this->nivelAceptacion->getId();
			$nins=$this->nivelInspeccion->getId();
			$query = "UPDATE `control_calidad`
						SET
						`cli_usuario` = :usuario,
						`cca_rechazo` = :rechazo,
						`cca_terminado` = :terminado,
						`cca_observaciones` = :observaciones,
						`art_id` = :articulo,
						`cat_id` = :categoria,
						`cca_fecha_inicio` = :fechaInicio,
						`cca_fecha_final` = :fechaFinal,
						`cca_obi_final` = :obi,
						`naql_id` = :nivelAceptacion,
						`nins_id` = :nivelInspeccion,
						`cca_nro_muestras` = :nmuestras,
						`cca_informe` = :infor,
						`cca_usuario` = :evaluado
						WHERE 
						`cca_id` = :id";
			$stmt = $con -> prepare($query);
			$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
			$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
			$stmt->bindParam(':rechazo', $this->rechazo, PDO::PARAM_STR);
			$stmt->bindParam(':terminado', $this->terminado, PDO::PARAM_STR);
			$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
			$stmt->bindParam(':articulo', $art, PDO::PARAM_STR);
			$stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_STR);
			$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
			$stmt->bindParam(':fechaFinal', $this->fechaFinal, PDO::PARAM_STR);
			$stmt->bindParam(':obi', $obi, PDO::PARAM_STR);
			$stmt->bindParam(':nivelAceptacion', $aql, PDO::PARAM_STR);
			$stmt->bindParam(':nivelInspeccion', $nins, PDO::PARAM_STR);
			$stmt->bindParam(':nmuestras', $this->nmuestras, PDO::PARAM_INT);
			$stmt->bindParam(':infor', $this->infor, PDO::PARAM_STR);
			$stmt->bindParam(':evaluado', $this->evaluado, PDO::PARAM_STR);
			$stmt -> execute();
			$this->errorSql = $stmt->errorInfo();
		}
	}
	/**
	 * Inserta nuevo registro a la tabla "control_calidad" usando las propiedades del Objeto ...
	 */
	public function agregaADb(){
		$con=Conexion::conectar();
		$usu=$this->usuario->getUsuario();
		$art=$this->getArticuloId();
		$obi=$this->obi->getId();
		$aql=$this->getNivelAceptacionId();
		$nins=$this->getNivelInspeccionId();
		$query="INSERT INTO `control_calidad`
				(`cca_id`,
				`cli_usuario`,
				`cca_rechazo`,
				`cca_terminado`,
				`cca_observaciones`,
				`art_id`,
				`cat_id`,
				`cca_fecha_inicio`,
				`cca_fecha_final`,
				`cca_obi_final`,
				`naql_id`,
				`nins_id`,
				`cca_nro_muestras`,
				`cca_usuario`)
				VALUES
				(null,
				:usuario,
				:rechazo,
				:terminado,
				:observaciones,
				:articulo,
				:categoria,
				:fechaInicio,
				:fechaFinal,
				:obi,
				:nivelAceptacion,
				:nivelInspeccion,
				0,
				:evaluado)";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':usuario', $usu, PDO::PARAM_STR);
		$stmt->bindParam(':rechazo', $this->rechazo, PDO::PARAM_STR);
		$stmt->bindParam(':terminado', $this->terminado, PDO::PARAM_STR);
		$stmt->bindParam(':observaciones', $this->observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':articulo', $art, PDO::PARAM_STR);
		$stmt->bindParam(':categoria', $this->categoria, PDO::PARAM_STR);
		$stmt->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
		$stmt->bindParam(':fechaFinal', $this->fechaFinal, PDO::PARAM_STR);
		$stmt->bindParam(':obi', $obi, PDO::PARAM_STR);
		$stmt->bindParam(':nivelAceptacion', $aql, PDO::PARAM_STR);
		$stmt->bindParam(':nivelInspeccion', $nins, PDO::PARAM_STR);
		$stmt->bindParam(':evaluado', $this->evaluado, PDO::PARAM_STR);
		$stmt -> execute();
		$this->errorSql = $stmt->errorInfo();
		$this->uFilaIns=$con->lastInsertId();
		$this->setId($this->uFilaIns);
	}
	/**
	 * Borra registro de la tabla "control_calidad" filtrado por ID ...
	 */
	public function borraPorId($ID){
		if(!isset($ID)){
			return $this;
		}
		$con=Conexion::conectar();
		$query = "DELETE FROM control_calidad WHERE cca_id = :id";
		$stmt = $con -> prepare($query);
		$stmt->bindParam(':id', $ID, PDO::PARAM_STR);
		$stmt -> execute();
		$this->uFilaIns=$con->lastInsertId();
	}
}