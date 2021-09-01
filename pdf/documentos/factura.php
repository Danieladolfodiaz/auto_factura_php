<?php

	 ob_start();
	session_start();
	/* Conectar a Base de datos*/
	include("../../config/db.php");
	include("../../config/conexion.php");
	$session_id= session_id();
	$sql_count=mysqli_query($con,"select * from tmp ");
	$count=mysqli_num_rows($sql_count);
	if ($count==0)
	{
	echo "<script>alert('No hay productos agregados a la factura proforma')</script>";
	echo "<script>window.close();</script>";
	exit;
	}

	require_once(dirname(__FILE__).'/../html2pdf.class.php');
		
	//Variables por GET
	$cliente=intval($_GET['cliente']);
	$descripcion=mysqli_real_escape_string($con,(strip_tags($_REQUEST['descripcion'], ENT_QUOTES)));
	$anio=intval($_GET['anio']);
	$marca=mysqli_real_escape_string($con,(strip_tags($_REQUEST['marca'], ENT_QUOTES)));
	$modelo=mysqli_real_escape_string($con,(strip_tags($_REQUEST['modelo'], ENT_QUOTES)));
	$color=mysqli_real_escape_string($con,(strip_tags($_REQUEST['color'], ENT_QUOTES)));
	$millas=intval($_GET['millas']);
	
	//Fin de variables por GET
	$sql=mysqli_query($con, "select LAST_INSERT_ID(id) as last from facturas order by id desc limit 0,1 ");
	$rw=mysqli_fetch_array($sql);
	$numero=$rw['last']+1;	
	$perfil=mysqli_query($con,"select * from perfil limit 0,1");//Obtengo los datos de la emprea
	$rw_perfil=mysqli_fetch_array($perfil);
	
	$sql_cliente=mysqli_query($con,"select * from clientes where id='$cliente' limit 0,1");//Obtengo los datos del proveedor
	$rw_cliente=mysqli_fetch_array($sql_cliente);
    // get the HTML
    
     include(dirname('__FILE__').'/res/factura_html.php');
    $content = ob_get_clean();
	
	$nombre_archivo="factura_".$numero.'.pdf';

    try
    {
        // init HTML2PDF
        $html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
        // display the full page
        $html2pdf->pdf->SetDisplayMode('fullpage');
        // convert
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        // send the PDF
        $html2pdf->Output($nombre_archivo);
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
