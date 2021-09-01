<?php
// conectamos a base de datos
include("../config/db.php");		
 $con=@mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
$search = strip_tags(trim($_GET['q'])); 
// hacemos una consulta preparada
$query = mysqli_query($con, "SELECT * FROM clientes WHERE nombre LIKE '%$search%' LIMIT 40");
// Utiliza fetchall para encontrar los datos 
$list = array();
while ($list=mysqli_fetch_array($query)){
	$data[] = array('id' => $list['id'], 'text' => $list['nombre'],'email' => $list['email'],'telefono' => $list['telefono'],'direccion' => $list['direccion']);
}
// return el resultado en json
echo json_encode($data);
?>