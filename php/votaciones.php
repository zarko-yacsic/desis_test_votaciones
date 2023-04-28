<?php
include('conexion_bd.php');

// Recibir datos enviados para la votación...
if($_POST){
	$nombre = $_POST['txt_nombre'];
	$apellido = $_POST['txt_apellido'];
	$alias = $_POST['txt_alias'];

	// Procesar RUT y obtener dígito verificador...
	$rut = str_replace('.', '', trim($_POST['txt_rut']));
	$rut = explode('-', $rut);
	$rut_dv = strtoupper($rut[1]);
	$rut = intval($rut[0]);

	$email = trim(strtolower($_POST['txt_email']));
	$region_id = $_POST['sel_region'];
	$provincia_id = $_POST['hf_provincia'];
	$comuna_id = $_POST['sel_comuna'];
	$desde_web = (isset($_POST['chk_desde_web'])) ? $_POST['chk_desde_web'] : 0;
	$desde_tv = (isset($_POST['chk_desde_tv'])) ? $_POST['chk_desde_tv'] : 0;
	$desde_rrss = (isset($_POST['chk_desde_rrss'])) ? $_POST['chk_desde_rrss'] : 0;
	$desde_amigo = (isset($_POST['chk_desde_amigo'])) ? $_POST['chk_desde_amigo'] : 0;

	// Verificar por RUT si el votante ya realizó alguna votación previamente...
	$rut_existe = mysqli_query($_conn, "SELECT * FROM vtc_votantes WHERE CONCAT(rut, '-', rut_dv) = CONCAT(" . $rut . ", '-', '" . $rut_dv . "') LIMIT 1;");

	// RUT del votante ya existe. No está habilitado para votar...
	if(mysqli_num_rows($rut_existe) > 0){
		$data = array(
			'status' => 'RUT_EXISTE',
			'rut_existe' => 'El RUT ' . trim(strtoupper($_POST['txt_rut'])) . ' no puede emitir votación nuevamente.'
		);
	}

	// RUT del votante no existe en la BD. Está habilitado para votar...
	else{
		// Guardar la información del votante...
		$sql = "INSERT INTO vtc_votantes (nombre, apellido, alias, rut, rut_dv, email, region_id, provincia_id, comuna_id, 
					desde_web, desde_tv, desde_rrss, desde_amigo) VALUES('" . $nombre . "', '" . $apellido . "', '" . $alias . "', 
					" . $rut . ", '" . $rut_dv . "', '" . $email . "', " . $region_id . ", " . $provincia_id . ", " . $comuna_id . ", 
					" . $desde_web . ", " . $desde_tv . ", " . $desde_tv . ", " . $desde_amigo . ")";
		if(mysqli_query($_conn, $sql)){
			$votante_id = mysqli_insert_id($_conn);

			// Si se ha creado correctamente un 'votante_id', se guarda el candidato seleccionado...
			if($votante_id != 0){
				$candidato_id = $_POST['sel_candidato'];
				if(mysqli_query($_conn, "INSERT INTO vtc_votaciones (votante_id, candidato_id, fecha_hora) VALUES(" . $votante_id . ", " . $candidato_id . ", NOW())")){
					$data['status'] = 'SUCCESS';
				}
			}
			else{
				$data = array(
					'status' => 'ERROR',
					'error' => 'Error al guardar votación para el candidato seleccionado.'
				);
			}
		}
		else{
			$votante_id = 0;
			$data = array(
				'status' => 'ERROR',
				'error' => 'Error al guardar la información del votante.'
			);
		}
	}
}


// Error en el envío de datos $_POST...
else{
	$data = array(
		'status' => 'ERROR',
		'error' => 'No se pudo enviar el formulario con los datos de la votación.'
	);
}


// Imprimir salida JSON...
$json_data = json_encode($data);
echo $json_data;
?>