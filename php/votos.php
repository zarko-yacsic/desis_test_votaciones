<?php
include('conexion_bd.php');

if($_GET['rnd']){
	// Obtener listado de votaciones por candidato...
	$sql = "SELECT CONCAT(cn.nombre, ' ', cn.apellido) AS candidato, 
				COUNT(vt.id) AS votos 
				FROM vtc_candidatos AS cn 
				LEFT JOIN vtc_votaciones AS vt 
				ON cn.id = vt.candidato_id 
				GROUP BY cn.id ORDER BY votos DESC;";
	$rs = mysqli_query($_conn, $sql);
	
	if(mysqli_num_rows($rs) > 0){
		echo '<table border="0" cellspacing="0"">
			<thead>
				<tr>
					<th>Candidato</th>
					<th class="texto_centro">Votos</th>
				</tr>
			</thead>
			<tbody>';
		while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)){
			echo '<tr>
					<td>' . $row['candidato'] . '</td>
					<td class="texto_centro">' . $row['votos'] . '</td>
				</tr>';
		}
		echo '<tbody>
			</table>';
	}

	else{
		echo '* No existen votaciones hasta ahora.';
	}
}
?>

