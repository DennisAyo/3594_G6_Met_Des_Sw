<?php
require 'functions.php';

$permisos = ['Administrador','Profesor'];
permisos($permisos);
//consulta de grados
$grados = $conn->prepare("select * from grados");
$grados->execute();
$grados = $grados->fetchAll();

//consulta las secciones
$secciones = $conn->prepare("select * from secciones");
$secciones->execute();
$secciones = $secciones->fetchAll();

//consulta los alumnos para el listaddo de alumnos
$alumnos = $conn->prepare("select a.id, a.num_lista, a.nombres, a.apellidos, a.genero, b.nombre as grado, c.nombre as seccion from alumnos as a inner join grados as b on a.id_grado = b.id inner join secciones as c on a.id_seccion = c.id order by a.apellidos");
$alumnos->execute();
$alumnos = $alumnos->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<title>Listado de Alumnos | Registro de Notas</title>
    <meta name="description" content="Registro de Notas de la Unidad Educativa Marques Selva Alegre" />
    <link rel="stylesheet" href="css/style.css" />
    

</head>
<body>
<div class="header">
        <h1>Unidad Educativa "Marqués Selva Alegre"</h1>
        <h3>Usuario:  <?php echo $_SESSION["username"] ?></h3>
</div>
<nav>
    <ul>
        <li><a href="inicio.view.php">Inicio</a> </li>
        <li><a href="alumnos.view.php">Registro de Alumnos</a> </li>
        <li class="active"><a href="listadoalumnos.view.php">Listado de Alumnos</a> </li>
        <li><a href="notas.view.php">Registro de Notas</a> </li>
        <li><a href="listadonotas.view.php">Consulta de Notas</a> </li>
        <li class="right"><a href="logout.php">Salir</a> </li>

    </ul>
</nav>

<div class="body">
    <div class="panel">
            <h4>Nomina de Alumnos</h4>
            <?php
            if(!isset($_GET['consultar'])){
            ?>
            <p>Seleccione el curso, y la sección</p>
            <form method="get" class="form" action="listadoalumnos.view.php">
                <label>Seleccione el Curso</label><br>
                <select name="grado" required>
                    <?php foreach ($grados as $grado):?>
                        <option value="<?php echo $grado['id'] ?>"><?php echo $grado['nombre'] ?></option>
                    <?php endforeach;?>
                </select>

                <br><br>
                <label>Seleccione la Sección</label><br><br>

                <?php foreach ($secciones as $seccion):?>
                    <input type="radio" name="seccion" required value="<?php echo $seccion['id'] ?>">Sección <?php echo $seccion['nombre'] ?>
                <?php endforeach;?>

                <br><br>
                <button type="submit" name="consultar" value="1">Consultar Nomina</button></a>
                <br><br>
            </form>
            <?php
        }
        ?>
        <?php
        if(isset($_GET['consultar'])){
            $id_grado = $_GET['grado'];
            $id_seccion = $_GET['seccion'];

            ?>
            <br>
            <a href="listadoalumnos.view.php"><strong><< Volver</strong></a>
            <br>
            <br>


            <table class="table" cellspacing="0" cellpadding="0">
                <tr>
                    <th>No de<br>lista</th><th>Apellidos</th><th>Nombres</th><th>Género</th><th>Curso</th><th>Sección</th>
                    <th>Editar</th><th>Eliminar</th>
                </tr>
                <?php foreach ($alumnos as $alumno) :?>
                <tr>
                    <td align="center"><?php echo $alumno['num_lista'] ?></td><td><?php echo $alumno['apellidos'] ?></td>
                    <td><?php echo $alumno['nombres'] ?></td><td align="center"><?php echo $alumno['genero'] ?></td>
                    <td align="center"><?php echo $alumno['grado'] ?></td><td align="center"><?php echo $alumno['seccion'] ?></td>
                    <td><a href="alumnoedit.view.php?id=<?php echo $alumno['id'] ?>">Editar</a> </td>
                    <td><a href="alumnodelete.php?id=<?php echo $alumno['id'] ?>">Eliminar</a> </td>
                </tr>
                <?php endforeach;?>
            </table>
                <br><br>

                <a class="btn-link" href="alumnos.view.php">Agregar Alumno</a>
                <br><br>
                <!--mostrando los mensajes que recibe a traves de los parametros en la url-->
                <?php
                if(isset($_GET['err']))
                    echo '<span class="error">Error al almacenar el registro</span>';
                if(isset($_GET['info']))
                    echo '<span class="success">Registro almacenado correctamente!</span>';
                ?>

                <br>
        <?php
        }
        ?>
            
        </div>
</div>

<footer>
    <p>Derechos reservados &copy; 2021</p>
</footer>

</body>

</html>
