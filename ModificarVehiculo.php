<html>
<head>
<link rel="stylesheet" href="estilos.css">
<title> Modificar Vehiculo </title>
<meta charset="utf-8"/>
</head>
<body class="FondoInicio">
<?php
	include_once ("php/conection.php"); // conectar y seleccionar la base de datos
	$link = conectar();
	include "Header.php";
	include "MenuBarra.php";
?>
<div class= "registrar">
<h1 style="color:white;text-align:center;font-family:Arial;font-weight:750;text-shadow:5px 5px 5px #aaa;"> Modificar Vehiculo </h1> 
<form method="POST" action="crearcuenta.php" class="input" onsubmit="return validar()">
	<label class="LabelFormularios"> Vehiculo </label>
	<select id="nombre" name="nombre" class="FormularioVehiculos"></select>
	<label class="LabelFormularios"> Marca </label>
	<input type="text" id="nombre" name="nombre" class="FormularioVehiculos">
	<label class="LabelFormularios"> Modelo </label>
	<input type="text" id="apellido" name="apellido" class="FormularioVehiculos">
	<label class="LabelFormularios"> Año </label>
	<input type="text" id="nombreusuario" name="nombreusuario" class="FormularioVehiculos">
	<label class="LabelFormularios"> Patente </label>
	<input type="text" id="email" name="email" class="FormularioVehiculos">
	<label class="LabelFormularios"> Color </label>
	<input type="text" id="clave1" name="password" class="FormularioVehiculos">
	<label class="LabelFormularios"> Asientos </label>
	<input type="text" id="clave2" name="password2" class="FormularioVehiculos">
	<div><input type="submit" class="BotonRegistrar" value="Registrar"></div>
</form>
</div>
</body>
</html>