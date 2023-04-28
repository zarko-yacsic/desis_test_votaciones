<?php
include('conexion_bd.php');

if($_GET['listado']){

	// Obtener listado de regiones...
	if($_GET['listado'] == 'regiones'){
		$sql = "SELECT * FROM vtc_regiones ORDER BY id ASC";
		$rs = mysqli_query($_conn, $sql);
		if(mysqli_num_rows($rs) > 0){
			echo '<option value="">-Seleccionar-</option>';
			while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)){
				echo '<option value="' . $row['id'] . '">' . $row['region'] . '</option>';
			}
		}
	}

	// Obtener comunas por región seleccionada...
	if($_GET['listado'] == 'comunas'){
		if($_GET['region_id']){
			$region_id = $_GET['region_id'];
			if(!is_numeric($region_id)){
				echo '<option value="">No se encontraron comunas</option>';
			}
			else{
				$sql = "SELECT cm.id AS comuna_id, cm.comuna, cm.provincia_id AS provincia_id, rg.region 
						FROM vtc_comunas AS cm 
						INNER JOIN vtc_provincias AS pr ON cm.provincia_id = pr.id 
						INNER JOIN vtc_regiones AS rg ON pr.region_id = rg.id 
						WHERE rg.id = " . $region_id . " ORDER BY cm.comuna ASC";
				$rs = mysqli_query($_conn, $sql);
				if(mysqli_num_rows($rs) > 0){
					echo '<option value="">-Seleccionar-</option>';
					while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)){
						echo '<option value="' . $row['comuna_id'] . '" data-provincia_id="' . $row['provincia_id'] . '">' . $row['comuna'] . '</option>';
					}
				}
			}
		}
	}

	// Obtener listado de candidatos...
	if($_GET['listado'] == 'candidatos'){
		$sql = "SELECT * FROM vtc_candidatos ORDER BY id ASC";
		$rs = mysqli_query($_conn, $sql);
		if(mysqli_num_rows($rs) > 0){
			echo '<option value="">-Seleccionar-</option>';
			while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)){
				echo '<option value="' . $row['id'] . '">' . $row['nombre'] . ' ' . $row['apellido'] . '</option>';
			}
		}
	}
}
?>