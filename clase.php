<?php
class DBControl {
	private $conn;
	
	function __construct() 
	{ 
	$this->conn = $this->conectarDB();
	}
	
	function conectarDB() 
	{
		$conn = mysqli_connect("localhost","root","","larause2");
		return $conn;
	}
	
	function larausequery($query) 
	{
		$resultado = mysqli_query($this->conn,$query);
		while($fila=mysqli_fetch_assoc($resultado)) 
		{
			$obtener_resultado[] = $fila;
		}		
		if(!empty($obtener_resultado))
		{
		return $obtener_resultado;
		}
	}
	
	function nfilas($query) {
		$resultado  = mysqli_query($this->conn,$query);
		$totalfilas = mysqli_num_rows($resultado);
		return $totalfilas;	
	}
}
?>