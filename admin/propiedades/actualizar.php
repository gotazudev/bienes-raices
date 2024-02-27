<?php 
    require '../../includes/funciones.php';
    $auth = estaAutenticado();
    
    

    // Validar la URL por ID valido
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    

    //Base de datos
    require '../../includes/config/database.php'; 
    $db = conectarDB();

    // Obtener los datos de la propiedad
    $consulta = "SELECT * FROM propiedades WHERE id = ".$id;
    $resultado = mysqli_query($db,$consulta);
    $propiedad = mysqli_fetch_assoc($resultado);

    //Consultar para obtener vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db,$consulta);

    //Arreglo con mensajes de errores
    $errores=[];

    $titulo = $propiedad['titulo'];
    $precio = $propiedad['precio'];
    $descripcion = $propiedad['descripcion'];
    $habitaciones= $propiedad['habitaciones'];
    $wc = $propiedad['wc'];
    $estacionamiento = $propiedad['estacionamiento'];
    $vendedorId = $propiedad['vendedores_id']; 
    // Por seguridad la imagen no se debe de llenar en actualizar
    $imagenPropiedad = $propiedad['imagen'];  

    $creado = date('Y/m/d');   

    //Ejecutar el codigo despues de que el usuario envie el formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // echo "<pre>";
        // var_dump($_POST);   
        // echo "</pre>";

        // echo "<pre>";
        // var_dump($_FILES);   
        // echo "</pre>";
        // exit;

        $titulo = mysqli_real_escape_string($db, $_POST["titulo"]);
        $precio = mysqli_real_escape_string($db, $_POST["precio"]);
        $descripcion = mysqli_real_escape_string($db, $_POST["descripcion"]);
        $habitaciones= mysqli_real_escape_string($db, $_POST["habitaciones"]);
        $wc = mysqli_real_escape_string($db, $_POST["wc"]);
        $estacionamiento = mysqli_real_escape_string($db, $_POST["estacionamiento"]);
        $vendedorId = mysqli_real_escape_string($db, $_POST["vendedor"]);

        //Asignar files hacia la variable
        $imagen = $_FILES['imagen'];
        

        if(!$titulo){
            $errores[] = "Debes añadir un titulo";
        }
        if(!$precio){
            $errores[] = "Debes añadir un precio";
        }
        if(strlen($descripcion) <10){
            $errores[] = "Debes añadir un descripcion y debes tener almenos 10 caracteres";
        }
        if(!$habitaciones){
            $errores[] = "Debes añadir un habitaciones";
        }
        if(!$wc){
            $errores[] = "Debes añadir un wc";
        }
        if(!$estacionamiento){
            $errores[] = "Debes añadir un estacionamiento";
        }
        if(!$vendedorId){
            $errores[] = "Elige un vendedor";
        }

        // if(!$imagen['name'] || $imagen['error']){
        //     $errores[]= "La imagen es obligatoria";
        // }

        // Validar por tamaño (100 kb maximo)
        $medida = 1000 * 100;
        if($imagen['size'] > $medida){
            $errores[] = "La imagen es muy pesada";
        }

        // echo "<pre>";
        // var_dump($errores);
        // echo "</pre>";

        // Revisar que el arreglo de errores este vacío
        if( empty($errores) ) {

            // // Crear carpeta 
            $carpetaImagenes = '../../imagenes/';
 
            if(!is_dir($carpetaImagenes)){
               mkdir($carpetaImagenes);
             }

            $nombreImagen = '';


            /* SUBIDA DE ARCHIVOS */
            if($imagen['name']){
                // Eliminar la imagen previa
                unlink($carpetaImagenes . $propiedad['imagen']);

                 // Generar imagen nombre unico
                $nombreImagen = md5(uniqid(rand(),true)).".jpg";

                // Subir la imagen
                move_uploaded_file($imagen['tmp_name'], $carpetaImagenes.$nombreImagen);
            } else{
                $nombreImagen = $propiedad['imagen'];
            }           

           
        //Insertar en BD
        $query = "UPDATE propiedades SET titulo = '{$titulo}', precio = {$precio}, imagen='{$nombreImagen}', descripcion = '{$descripcion}', habitaciones = {$habitaciones}, wc = {$wc}, estacionamiento = {$estacionamiento}
        , vendedores_id = {$vendedorId} WHERE id = {$id} ";
        // echo $query;

        $resultado = mysqli_query($db,$query);

            if($resultado){
                // Redirecciona al usuario
                header('Location: /admin?resultado=2');
            }
        }
    }   
    


    incluirTemplate('header');
 ?>
    <main class="contenedor seccion">
        <h1>ACtualizar propiedad</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form class="formulario" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Informacion general</legend>

                <label for="">Titulo:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo propiedad" value="<?php echo $titulo ?>">
                <label for="">Precio:</label>

     <!-- Por seguridad la imagen no se debe de llenar en actualizar -->

                <input type="number" id="precio" name="precio" placeholder="Precio propiedad" value="<?php echo $precio ?>">
                <label for="">Imagen:</label>

                <img src="/imagenes/<?php echo $imagenPropiedad; ?>" class="imagen-small" alt="">

                <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">
                <label for="descripcion">Descripcion:</label>
                <textarea id="descripcion" name="descripcion" cols="30" rows="10" ><?php echo $descripcion ?></textarea>
            </fieldset>
            <fieldset>
                <legend>Informacion propiedad</legend>

                <label for="habitaciones">Habitaciones:</label>
                <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 3" value="<?php echo $habitaciones ?>" min="1" max="9" step="2">
                <label for="wc">Baños:</label>
                <input type="number" id="wc" name="wc" placeholder="Ej: 3" value="<?php echo $wc ?>" min="1" max="9" step="2">
                <label for="estacionamiento">Estacionamiento:</label>
                <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 3" value="<?php echo $estacionamiento ?>" min="1" max="9" step="2">
            </fieldset>
            <fieldset>
                <legend>Vendedor</legend>
                <select name="vendedor" id="">
                    <option value="">----Seleccione-----</option>

                    <?php while($vendedor = mysqli_fetch_assoc($resultado)): ?>
                        <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>"><?php echo $vendedor['nombre']." ".$vendedor['apellido'];?></option>
                    <?php endwhile; ?>

                </select>
            </fieldset>

            <input type="submit" value="Actualizar propiedad" class="boton boton-verde">
        </form>

    </main>

    <?php 
    incluirTemplate('footer');
 ?>