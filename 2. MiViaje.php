<?php
    include_once "php/conection.php"; // conectar y seleccionar la base de datos
    $link = conectar();
	include_once "php/classLogin.php";
	$usuario= new usuario();
	$usuario -> session ($usuarioID, $admin);
?>
<html>
<head>
	<link rel="stylesheet" href="estilos.css">
	<link rel="stylesheet" href="2. Estilos.css">
	<title> Mis Viajes </title>
	<meta charset="utf-8"/>
</head>
<body background="Imagenes/FondoColores.jpg">
<div class= "div_body">
	<?php
	include "Header.php";
	include "MenuBarra.php";
	?>	
	<div>
	<?php 
	try {
		$usuario -> iniciada($usuarioID);
	?>
		<?php
		$viaje_id = $_GET['id'];
		//Trae de base de datos la informacion de los viajes
		$q = "SELECT * FROM viajes where id= $viaje_id ";
		$result = mysqli_query($link,$q);
		$row = mysqli_fetch_array($result);
		$fecha = $row['fecha'];
		$duracion = $row['duracionHoras'];
		$duracionMinutos = $row['duracionMinutos'];
		$horaPartida = $row['hora'];
		$minutosPartida = $row['minuto'];
		$precio = $row['precio'];
		$texto = $row['texto'];
		$estado =$row['idEstado'];
		$origen = $row['idOrigen'];
		$destino = $row['idDestino'];
		$id_Vehiculo = $row['idVehiculo'];
		// AGREGO QUE MUESTRE El Destino---------------*/
		$consultaDestino = "SELECT * FROM ciudades where id=$destino";
		$resultadoConsultaDest = mysqli_query($link,$consultaDestino);
		$rowCiudadDest = mysqli_fetch_array($resultadoConsultaDest);
		$destinoViaje = $rowCiudadDest['ciudad'];
		//---------------AGREGO QUE MUESTRE El Origen---------------
		$consultaOrigen = "SELECT * FROM ciudades where id=$origen";
		$resultadoConsulta = mysqli_query($link,$consultaOrigen);
		$rowCiudad = mysqli_fetch_array($resultadoConsulta);
		$origenViaje = $rowCiudad['ciudad'];
		//---------------AGREGO QUE MUESTRE Los asientos del vehiculo---------------
		$consultaVehiculo = "SELECT * FROM vehiculos where id=$id_Vehiculo";
		$resultadoConsultaVehiculo = mysqli_query($link,$consultaVehiculo);
		$rowVehiculo = mysqli_fetch_array($resultadoConsultaVehiculo);
		$vehiculoViaje = $rowVehiculo['modelo'] . ' ' .$rowVehiculo['patente'];
		$vehiculoViaje = $rowVehiculo['modelo'];
		$asientosDisponibles = $rowVehiculo['asientos'];
		?>
		<div>
			<p class="p_titulo"> Detalles del viaje </p>
			<div class="div_body_detalle">
				<div class="div_vertical" id="div_left_corner" >
					<span> <?php echo "Origen: " . utf8_encode($origenViaje)?> </span>
					<br><br>
					<span class="span_detalle"> <?php echo "Destino: " .utf8_encode($destinoViaje)?> </span>
				</div>
				<div class="div_vertical">
					<span class="span_detalle"> <?php echo "Horario de salida: " .utf8_encode($horaPartida)?><?php echo":" . utf8_encode($minutosPartida); if ($minutosPartida == 0) { echo 0;}?>
					</span>
					<br><br>
					<?php 
					if ($duracionMinutos != 0){
					?>
						<span> <?php echo "Duracion aproximada: " . utf8_encode($duracion)?><?php echo ":" . utf8_encode($duracionMinutos)?> Hs </span>
					<?php
					} else {
					?>
						<span> <?php echo "Duracion aproximada: " . utf8_encode($duracion)?> Hs </span>
					<?php
					}
					?>
				</div>
				<div class="div_vertical">
					<span class="span_detalle"> <?php echo "Precio total: $" . utf8_encode($precio)?> </span>
					<br><br>
					<span class="span_detalle"> Precio por persona: $
					<?php echo(round($precio/$asientosDisponibles));?>
					</span>
				</div>
				<div class="div_vertical">
					<span class="span_detalle"> Lugares totales: <?php echo($asientosDisponibles) ?> </span>
					<br><br>
					<span class="span_detalle"> Lugares disponibles:
					<?php
					$query3= "SELECT * FROM postulaciones WHERE idViaje = $viaje_id AND idEstado = 1";
					$result3= mysqli_query ($link, $query3) or die ('Consuluta query1 fallida: ' .mysqli_error($link));
					$asientosOcupados= 0;
					while ($postulacion = mysqli_fetch_array ($result3)){
						$asientosOcupados ++;
					}
					echo($asientosDisponibles - $asientosOcupados);
					$lugaresDisponibles = ($asientosDisponibles - $asientosOcupados)
					?>
					</span>
				</div>
				<div class="div_vertical" id="div_right_corner">
					<span class="span_detalle"> <?php echo "Fecha: " . utf8_encode($fecha)?> </span>
					<br><br>
					<span class="span_detalle"> <?php echo "Vehiculo: " . utf8_encode($vehiculoViaje)?> </span>
				</div>
				<br><br><br><br>
			</div>	
			<div>
				<br> <hr>
				<p class="p_titulo"> Postulaciones Aceptadas </p>
					<?php
				$query1= "SELECT * FROM postulaciones WHERE idViaje = $viaje_id AND idEstado = 1 ORDER BY fecha DESC";
				$result1= mysqli_query ($link, $query1) or die ('Consuluta query1 fallida: ' .mysqli_error($link));
				?>
				<div class="div_lista_postulaciones">
					<?php
					$hayPostulaciones= false;
					while ($postulacion = mysqli_fetch_array ($result1)) {
						$hayPostulaciones= true;
					?>
						<div class="div_both_corners" >
							<span class="span_detalle" > Fecha: <?php	echo $postulacion ['fecha']?> </span> 
							<span class="span_detalle" > Usuario: <?php 
								$usuarioID = $postulacion ['idUsuario'];
								$query2 = "SELECT * FROM usuarios WHERE id = $usuarioID";
								$result2 = mysqli_query ($link, $query2) or die ('Consuluta query1 fallida: ' .mysqli_error($link));
								$usuarioNombre = mysqli_fetch_array ($result2);
								?>
								<a href= "verPerfilUsuario.php?id=<?php echo $usuarioID?>"> <?php
									echo ($usuarioNombre['nombre'] . ' ' . $usuarioNombre['apellido']);
								?>
								</a>
							</span>
							<span class="span_detalle" style="text-align: center." > Estado: <?php 
								$estado = $postulacion ['idEstado'];
								$query3 = $query1= "SELECT * FROM estadospostulacion WHERE id = $estado";
								$result3 = mysqli_query ($link, $query3) or die ('Consuluta query1 fallida: ' .mysqli_error($link));
								$estadoPostulacion = mysqli_fetch_array ($result3);
								echo ($estadoPostulacion['estado']);
								?>
							</span>
							<span class="span_detalle_button">
								<?php
								$estadoPostulacion= $postulacion ['idEstado'];
								if ($estadoPostulacion == 1){ //Aceptada
								?>
								<input type="submit" value="Dar de baja postulación" class= "boton_postulacion">
								<?php
								} elseif ($estadoPostulacion == 2) { //Rechazada
									echo 'Usted ha rechazado esta postulacion';
								} elseif ($estadoPostulacion == 3) { //Cancelada
									echo 'El usuario ha cancelado esta postulacion';
								} 
								?>
							</span>
						</div>
					<?php	
					}
					if ($hayPostulaciones == false) {
					?>
						<br> <p class="p_informacion_empty"> No hay postulaciones aceptadas para este viaje </p>
					<?php
					}
					?>
				<br>
				<p class="p_titulo"> Postulaciones Pendientes </p>
				<?php
				$query1= "SELECT * FROM postulaciones WHERE idViaje = $viaje_id AND idEstado = 5 ORDER BY fecha DESC";
				$result1= mysqli_query ($link, $query1) or die ('Consuluta query1 fallida: ' .mysqli_error($link));
				?>
				<div class="div_lista_postulaciones">
					<?php
					$hayPostulaciones= false;
					while ($postulacion = mysqli_fetch_array ($result1)) {
						$hayPostulaciones= true;
						$idPostulacion= $postulacion ['id'];
					?>
						<div class="div_both_corners" >
							<span class="span_detalle" > Fecha: <?php	echo $postulacion ['fecha']?> </span> 
							<span class="span_detalle" > Usuario: <?php 
								$usuarioID = $postulacion ['idUsuario'];
								$query2 = "SELECT * FROM usuarios WHERE id = $usuarioID";
								$result2 = mysqli_query ($link, $query2) or die ('Consuluta query1 fallida: ' .mysqli_error($link));
								$usuarioNombre = mysqli_fetch_array ($result2);
								?>
								<a href= "verPerfilUsuario.php?id=<?php echo $usuarioID?>"> <?php
									echo ($usuarioNombre['nombre'] . ' ' . $usuarioNombre['apellido']);
								?>
								</a>
							</span>
							<span class="span_detalle" style="text-align: center." > Estado: <?php 
								$estado = $postulacion ['idEstado'];
								$query3 = $query1= "SELECT * FROM estadospostulacion WHERE id = $estado";
								$result3 = mysqli_query ($link, $query3) or die ('Consuluta query1 fallida: ' .mysqli_error($link));
								$estadoPostulacion = mysqli_fetch_array ($result3);
								echo ($estadoPostulacion['estado']);
								?>
							</span>
							<span class="span_detalle_button">
								<?php
								$estadoPostulacion= $postulacion ['idEstado'];
								if ($estadoPostulacion == 1){ //Aceptada
								?>
								<input type="submit" value="Dar de baja postulación" class= "boton_postulacion">
								<?php
								} elseif ($estadoPostulacion == 2) { //Rechazada
									echo 'Usted ha rechazado esta postulacion';
								} elseif ($estadoPostulacion == 3) { //Cancelada
									echo 'El usuario ha cancelado esta postulacion';
								} elseif ($estadoPostulacion == 5) { //Pendiente
									if ($lugaresDisponibles != 0 ) {								
								?>
										<form name="formulario" method="post" >
											<input type="hidden" name="idVehiculo" value="<?php echo $id_Vehiculo ?>">
											<input type="hidden" name="idViaje" value="<?php echo $viaje_id ?>">
											<input type="hidden" name="idPostulacion" value="<?php echo $idPostulacion ?>">
											<input type="submit" value="Aceptar postulación" class= "boton_postulacion" formaction="php/2. aceptarPostulacion.php" >
											<input type="submit" value="Rechazar postulación" class= "boton_postulacion" formaction="php/2. rechazarPostulacion.php">
										</form>
								<?php
									} else {
									?>
										<span> No puede aceptar mas postulaciones, no hay asientos disponibles </span>
									<?php 
									}
								}
								?>
							</span>
						</div>
					<?php	
					}
					if ($hayPostulaciones == false) {
					?>
						<br> <p class="p_informacion_empty"> No hay postulaciones para este viaje </p>
					<?php
					}
					?>
				<br>
				<p class="p_titulo"> Postulaciones Canceladas </p>
				<?php
				$query1= "SELECT * FROM postulaciones WHERE idViaje = $viaje_id AND (idEstado = 3 OR idEstado = 2)  ORDER BY fecha DESC";
				$result1= mysqli_query ($link, $query1) or die ('Consuluta query1 fallida: ' .mysqli_error($link));
				?>
				<div class="div_lista_postulaciones">
					<?php
					$hayPostulaciones= false;
					while ($postulacion = mysqli_fetch_array ($result1)) {
						$hayPostulaciones= true;
					?>
						<div class="div_both_corners" >
							<span class="span_detalle" > Fecha: <?php	echo $postulacion ['fecha']?> </span> 
							<span class="span_detalle" > Usuario: <?php 
								$usuarioID = $postulacion ['idUsuario'];
								$query2 = "SELECT * FROM usuarios WHERE id = $usuarioID";
								$result2 = mysqli_query ($link, $query2) or die ('Consuluta query1 fallida: ' .mysqli_error($link));
								$usuarioNombre = mysqli_fetch_array ($result2);
								?>
								<a href= "verPerfilUsuario.php?id=<?php echo $usuarioID?>"> <?php
									echo ($usuarioNombre['nombre'] . ' ' . $usuarioNombre['apellido']);
								?>
								</a>
							</span>
							<span class="span_detalle" style="text-align: center." > Estado: <?php 
								$estado = $postulacion ['idEstado'];
								$query3 = $query1= "SELECT * FROM estadospostulacion WHERE id = $estado";
								$result3 = mysqli_query ($link, $query3) or die ('Consuluta query1 fallida: ' .mysqli_error($link));
								$estadoPostulacion = mysqli_fetch_array ($result3);
								echo ($estadoPostulacion['estado']);
								?>
							</span>
							<span class="span_detalle_button">
								<?php
								$estadoPostulacion= $postulacion ['idEstado'];
								if ($estadoPostulacion == 1){ //Aceptada
								?>
								<input type="submit" value="Dar de baja postulación" class= "boton_postulacion">
								<?php
								} elseif ($estadoPostulacion == 2) { //Rechazada
									echo 'Usted ha rechazado esta postulacion';
								} elseif ($estadoPostulacion == 3) { //Cancelada
									echo 'El usuario ha cancelado esta postulacion';
								}
								?>
							</span>
						</div>
					<?php	
					}
					if ($hayPostulaciones == false) {
					?>
						<br> <p class="p_informacion_empty"> No hay canceladas postulaciones en este viaje </p>
					<?php
					}
					?>
				</div>
			</div>
		</div>		
	<?php	
	}		
	catch (Exception $e) {
	?>
		<div>
			<br><br>
			<p> Usted no ha iniciado sesion </p>
			<p> Por favor 
			<a href="Inicio_Sesion.php"> inicie sesion </a>
			o
			<a href="Bienvenida.php"> registrese </a>
			para ver este contenido
		</div>
	<?php
	}
	?>
</div>
</body>
</html>