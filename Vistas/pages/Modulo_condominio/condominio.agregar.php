<?php
session_start();
require '../../../Datos/config.php';
require "../../../Datos/sidebar.php";
if(isset($_SESSION['loggedin'])){
    if(isset($_SESSION['perfil'])){
        $perfil = $_SESSION['perfil'];
        switch ($perfil) {
            case '-1':
                $msg = "Usuario Maestro";
            break;
            case '1':
                $msg = "Residente";
            break;
            case '2':
                $msg = "Conserje";
            break;
            case '3':
                $msg = "Mayordomo";
            break;
            case '4':
                $msg = "Administrador de condominio";
            break;
            case '5':
                $msg = "Conserje y Residente";
            break;
            case '6':
                $msg = "Mayordomo y Residente";
            break;
            case '7':
                $msg = "Administrador y Residente";
            break;
        }

        if ($_SESSION['perfil'] == 4 || $_SESSION['perfil'] == 7 || $_SESSION['perfil'] == -1) {

        }else{
            echo "<script>alert('No tienes privilegios para acceder al módulo'); window.location.href = '../index.php'</script>";

        }
    }else{
        if(isset($_SESSION['id_usuario'])){
            $usuario = $_SESSION['id_usuario'];
            $msg = "Usuario Maestro";
        }else{
            echo "<script>alert('Sólo el usuario maestro tiene acceso a este módulo'); window.location.href = '../../../Clases/Login/class.logout.php'</script>";
        }
    }
}else{
    echo "<script>alert('Está página es solo para usuarios registrados'); window.location.href = '../login.html'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>CONDOPRO</title>
		<!-- Bootstrap Core CSS -->
		<link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- MetisMenu CSS -->
		<link href="../../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
		<!-- Custom CSS -->
		<link href="../../dist/css/sb-admin-2.css" rel="stylesheet">
		<!-- Custom Fonts -->
		<link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<script language="javascript" src="../../vendor/jquery/jquery.min.js"></script>
		<!-- Llenado de comunas a partir de la region -->
		<script type="text/javascript">
			$(document).ready(function(){
				$("#region").change(function(){
					$("#region option:selected").each(function(){
						id_region = $(this).val();
						$.post("model.regiones.php", { id_region: id_region }, function(data){
							$("#comuna").html(data);
						})
					});
				});
			});
		</script>
	</head>
	<body>
		<div id="wrapper">
			<!-- Navigation -->
			<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="../index.php">C O N D O P R O</a>
				</div>
				<!-- /.navbar-header -->
				<ul class="nav navbar-top-links navbar-right">
					<?php if(isset($_SESSION['condominio'])){ ?>
					<b>Usted se encuentra en <?php
					$id = $_SESSION['condominio'];
					$consulta = "SELECT nombre_condominio FROM condominios WHERE id_condominio = $id";
					$resultado = mysqli_query($conexion, $consulta);
					while($fila = $resultado->fetch_assoc()){
					$nombre = $fila['nombre_condominio'];
					}
					echo $nombre;
					?>&nbsp;<a href="../../../Clases/Condominio/class.cambiar.php">Cambiar</a></b>
					<?php }else{ ?>
					<b>No ha seleccionado condominio &nbsp;<a href="../../../Clases/Condominio/class.cambiar.php">Seleccionar</a></b>
					<?php } ?>
					<!-- /.dropdown -->
					<li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-user fa-fw"></i> <?php echo $_SESSION['username'];?> <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-users fa-fw"></i> <?php echo $msg; ?></a>
                        </li>
                        <li class="divider"></li>
                            <li><a href="../Modulo_usuario/usuario.perfil.php"><i class="fa fa-user fa-fw"></i> Perfil</a>
                        </li>
                        <?php
                            if(isset($perfil)){
                                if($perfil != -1){
                                    echo "<li><a href='Modulo_favorito/favorito.index.php'><i class='fa fa-gear fa-fw'></i> Favoritos</a></li>";
                                }
                            }
                        ?>
                    <li class="divider"></li>
                    <li><a href="../../../Clases/Login/class.logout.php"><i class="fa fa-sign-out fa-fw"></i> Desconectar</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
		<!-- /.dropdown -->
	</ul>
	<!-- /.navbar-top-links -->
	<div class="navbar-default sidebar" role="navigation">
		<div class="sidebar-nav navbar-collapse">
			<ul class="nav" id="side-menu">
                <?php 
                    if (isset($usuario)) {
                        switch ($usuario) {
                            case '0':
                                echo "<li>
                                        <a href='Modulo_condominio/condominio.index.php'><i class='fa fa-table fa-fw'></i> Condominios</a>
                                    </li>";
                            break;
                        }
                    }else{
                        if(isset($perfil)){
                            echo MostrarNavegadorSecundario($perfil);
                        }
                    }
                ?>
			</ul>
		</div>
		<!-- /.sidebar-collapse -->
	</div>
	<!-- /.navbar-static-side -->
</nav>
<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Gestión de Condominios</h1>
		</div>
	</div>
	<!-- /.row -->
	<div class="row">
		<div class="panel panel-primary">
			<div class="panel-heading">
				Formulario de condominio
			</div>
			<br>
			<!-- /.panel-heading -->
			<form action="../../../Clases/Condominio/class.agregar.php" method="POST" accept-charset="utf-8">
				<div class="row">
					<div class="col-md-12">
						<div style="display: none;">
							<input type="text" name="userCreacion" value="<?php echo $_SESSION['username']; ?>">
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Rut</label>
								<div class="row">
									<div class="col-md-8">
										<input type="text" class="form-control" placeholder="Rut" name="rut" maxlength="8" required />
									</div>
									<div class="col-md-4">
										<input type="text" class="form-control" placeholder="Digito" name="dv" maxlength="1" required />
									</div>
								</div>
							</div>
							<div class="form-group">
								<label>Nombre condominio</label>
								<input type="text" class="form-control" placeholder="Nombre condominio" name="condominio" required />
							</div>
							<div class="form-group">
								<label>Dirección</label>
								<input type="text" class="form-control" placeholder="Dirección" name="direccion" required />
							</div>
							<div class="form-group">
								<label for="region">Región</label>
								<select class="form-control" name="region" id="region" required="">
									<?php
									$sql = "SELECT id_region, region FROM regiones ORDER BY id_region ASC";
									$resultado = mysqli_query($conexion, $sql);
									while ($fila = $resultado->fetch_assoc()) {?>
									<option value="<?php echo $fila['id_region']; ?>"><?php echo $fila['region']; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label>Comuna</label>
								<select name="comuna" id="comuna" class="form-control" required>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Cantidad sectores</label>
								<input type="number" class="form-control" placeholder="Cantidad sectores" name="sectores" required />
							</div>
							<div class="form-group">
								<label>Cantidad pisos habitables</label>
								<input type="number" class="form-control" placeholder="Cantidad pisos habitables" name="cantidad_piso" required />
							</div>
							<div class="form-group">
								<label>Unidades por piso</label>
								<input type="number" class="form-control" placeholder="Unidades por piso" name="unidad_piso" required />
							</div>
							<div class="form-group">
								<label>Primer piso habitable</label>
								<input type="number" class="form-control" placeholder="Primer piso habitable" name="primer_piso" required />
							</div>
							<div class="form-group">
								<label>Activo</label>
								<div class="radio">
									<label>
										<input type="radio" name="activo" id="activo1" value="1" checked>Si
									</label>&nbsp;&nbsp;&nbsp;
									<label>
										<input type="radio" name="activo" id="activo2" value="0">No
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-6">
							<div class="form-group">
								<input type="submit" class="btn btn-block btn-primary btn-lg" value="Registrar">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<a href="condominio.index.php" class="btn btn-block btn-warning btn-lg">Volver</a>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<!-- /.panel-body -->
	</div>
	<!-- /.col-lg-12 -->
</div>
</div>
<!-- /#wrapper -->
<!-- jQuery -->
<!-- Bootstrap Core JavaScript -->
<script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="../../vendor/metisMenu/metisMenu.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="../../dist/js/sb-admin-2.js"></script>
</body>
</html>