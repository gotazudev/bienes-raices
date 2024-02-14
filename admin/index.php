<?php
    require '../includes/funciones.php';
    $auth = estaAutenticado();

    if(!$auth){
        header('Location: /');
    }
 
//? Importamos la DB:
 
//* 1. Importar la conexión:
require '../includes/config/database.php'; /* Exportamos la conexión */
$db = conectarDB();  /* Llamamos la función base de datos */
 
//* 2. Escribir el Query:
$query = "SELECT * FROM propiedades";
 
//* 3. Consultar la DB.
$resultadoDB = mysqli_query($db, $query);
 
 
//* 4. Mostrar los resultados.
//* 5. Cerrar la conexión. (opcional porque php detecta cuando no esta en uso y se cierra)
 
    
 
//? Mostrar mensaje adicional:
 
$mensaje = $_GET['mensaje'] ?? null; //* Con esta variable global podemos enviar todo tipo de datos por medio de la URL. Con este placeholder de ?? lo que hace básciamente es buscar el valor y sino esta lo declara null (es una forma nueva, antes usamos el isset).
 
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id']; 
    $id = filter_var($id, FILTER_VALIDATE_INT);
 
    if($id){
 
        //? Delete Files
        $query = "SELECT imagen FROM propiedades WHERE id = " . $id;
 
        $resultadoDelete = mysqli_query($db, $query);
        $propiedad = mysqli_fetch_assoc($resultadoDelete);
 
        unlink('../imagenes/' . $propiedad['imagen']);
 
        //? Delete propierti
        $query = "DELETE FROM propiedades WHERE id = " . $id;
        
        $resultadoDelete = mysqli_query($db, $query);
 
        if($resultadoDelete){
            header('Location: /admin?mensaje=3'); 
        }
    }
    
}
 
//? Incluir template:
 /* Sirve para exportar funciones o código mas complejo. Include es mas para templates. */
 
$inicio = true; /* Para agregar la clase de incio creamos esta variable y se agrega autamitncament al include */
incluirTemplate('header');
 
?>
 
<main class="contenedor seccion">
 
 
    <h1>Administrador de bienes raices</h1> 
    <!-- Validamos si la creación fue correcta para dar un mensaje al usuario. -->
    <?php if( intval($mensaje) === 1) :?>     <!--  La función intval nos permite convertir de String a int -->
        <p class="alerta exito">¡Anuncio Creado Correctamente!</p>
    <?php elseif( intval($mensaje) === 2) :?>  
        <p class="alerta exito">¡Anuncio Actualizado Correctamente!</p>
    <?php elseif( intval($mensaje) === 3) :?>  
        <p class="alerta error">¡Anuncio Eliminado Correctamente!</p>
    <?php endif; ?>
 
    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva propiedad</a>
 
 
    <!-- Creamos la tabla para mostrar los anuncios creados -->
    <table class="propiedades">
        <thead> <!-- Con esta etiqueta. Podemos diferncia el encabezado de una tabla. -->
            <tr>
                <th>ID</th>
                <th>Titulo propiedad</th>
                <th>Precio</th>
                <th>Imagen</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
 
        <tbody> <!-- Con esta etiqueta. Podemos diferncia el cuerpo de una tabla. 4. Mostrar los resultados. -->
        <?php while( $propiedad = mysqli_fetch_assoc($resultadoDB)): ?>
            <tr>
                <td> <?php echo $propiedad['id']; ?> </td>
                <td> <?php echo $propiedad['titulo']; ?> </td>
                <td>$<?php echo $propiedad['precio']; ?> </td>
                <td> <img src="/imagenes/<?php echo $propiedad['imagen']; ?>" alt="" class="imagen-tabla"> </td> <!--  Recordar que las imagenes nos guarda en BD, se guarda el nombre del archivo por eso primero apuntamos a la carpeta donde se guardó y luego al nombre de la imagen. -->
                <td> <?php echo $propiedad['descripcion']; ?> </td>
                <td>
 
                <!-- Utlizamos un form para el input de eliminar nos envie los datos via POST la información. -->
                <form method="POST" class="w-100">
                    <input type="hidden" name="id" value="<?php echo $propiedad['id']; ?>"> <!-- Estos input tipo hidden no se pueden ver, pero si inspeccionamos el código só los podemos ver. No usamos tipo TEXT porque los usarios pueden modificarlo. -->
 
                    <input type="submit" class="boton-rojo-block" value="Eliminar">
                </form>
 
                    <a href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" class="boton-amarillo-block">Actualizar</a> <!-- Con este QueryString podremos mostrar por url el id de la propiedad a actualizar y esto nos ayudará a traernos la info de cada propiedad. -->
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>
 
<?php
    //* 5. Cerrar la conexión:
    mysqli_close($db);
    incluirTemplate('footer');
?>