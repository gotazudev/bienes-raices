<?php 

    //Base de datos
    require '../../includes/config/database.php'; 
    $db = conectarDB();

    //Consultar para obtener vendedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db,$consulta);

    //Arreglo con mensajes de errores
    $errores=[];

    $titulo = '';
    $precio = '';
    $descripcion = '';
    $habitaciones= '';
    $wc = '';
    $estacionamiento = '';
    $vendedorId = '';   
    $creado = date('Y/m/d');   

    //Ejecutar el codigo despues de que el usuario envie el formulario
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        // echo "<pre>";
        // var_dump($_POST);   
        // echo "</pre>";

        $titulo = mysqli_real_escape_string($db, $_POST["titulo"]);
        $precio = mysqli_real_escape_string($db, $_POST["precio"]);
        $descripcion = mysqli_real_escape_string($db, $_POST["descripcion"]);
        $habitaciones= mysqli_real_escape_string($db, $_POST["habitaciones"]);
        $wc = mysqli_real_escape_string($db, $_POST["wc"]);
        $estacionamiento = mysqli_real_escape_string($db, $_POST["estacionamiento"]);
        $vendedorId = mysqli_real_escape_string($db, $_POST["vendedor"]);

        if(!$titulo){
            $errores[] = "Debes añadir un titulo";
        }
        if(!$precio){
            $errores[] = "Debes añadir un precio";
        }
        if(strlen($descripcion) <50){
            $errores[] = "Debes añadir un descripcion y debes tener almenos 50 caracteres";
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

        // echo "<pre>";
        // var_dump($errores);
        // echo "</pre>";

        //Revisar que el arreglo de errores este vacio
        if(empty($errores)){

        //Insertar en BD
        $query = "INSERT INTO propiedades (titulo, precio, descripcion, habitaciones, wc, estacionamiento, creado, vendedores_id ) VALUES ('$titulo', '$precio', '$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$creado', '$vendedorId')";
        // echo $query;
        $resultado = mysqli_query($db,$query);

            if($resultado){
                // Redirecciona al usuario
                header('Location: /admin');
            }
        }
    }   
    


    require '../../includes/funciones.php';
    incluirTemplate('header');
 ?>
    <main class="contenedor seccion">
        <h1>Crear</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form class="formulario" method="POST" action="/admin/propiedades/crear.php" >
            <fieldset>
                <legend>Informacion general</legend>

                <label for="">Titulo:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo propiedad" value="<?php echo $titulo ?>">
                <label for="">Precio:</label>
                <input type="number" id="precio" name="precio" placeholder="Precio propiedad" value="<?php echo $precio ?>">
                <label for="">Imagen:</label>
                <input type="file" id="imagen" accept="image/jpeg, image/png">
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

            <input type="submit" value="Crear propiedad" class="boton boton-verde">
        </form>

    </main>

    <?php 
    incluirTemplate('footer');
 ?>