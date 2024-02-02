<?php 
    // Importar la conexion
    require '../includes/config/database.php'; 
    $db = conectarDB();
    
    // Escribir query
    $query = "SELECT * FROM propiedades";

    // Consultar BD
    $resultadoConsulta = mysqli_query($db,$query);


    // Muestra mensaje comdicional
    $resultado = $_GET['resultado'] ?? null;

    // Include template
    require '../includes/funciones.php';
    incluirTemplate('header');
 ?>
    <main class="contenedor seccion">
        <h1>Administrador de bienes raices</h1>
        <?php if(intval($resultado) === 1): ?>
            <p class="alerta exito">Anuncio creado correctamente</p>
        <?php endif; ?>
        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva propiedad</a>
    </main>

    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody><!-- Mostrar resultados-->
            <?php while($propiedad = mysqli_fetch_assoc($resultadoConsulta)): ?>
            <tr>
                <td> <?php echo $propiedad['id']; ?> </td>
                <td> <?php echo $propiedad['titulo']; ?> </td>
                <td><img src="/imagenes/<?php echo $propiedad['imagen']; ?> " class="imagen-tabla"></td>
                <td>S/ <?php echo $propiedad['precio']; ?> </td>
                <td>
                    <a href="#" class="boton-rojo-block">Eliminar</a>
                    <a href="#" class="boton-amarillo-block">Actualizar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php 

    // Cerrar la conexion
    mysqli_close($db);

    incluirTemplate('footer');
 ?>