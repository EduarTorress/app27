<?php
$directorio = "sysven/";
$dir = opendir($directorio);
if (isset($_FILES['archivo'])) {
    $carpeta=$_GET["carpeta"];
    $dir_subida = 'sysven/'.$carpeta."/";
    $fichero_subido = $dir_subida . basename($_FILES['archivo']['name']);
    move_uploaded_file($_FILES['archivo']['tmp_name'], $fichero_subido);
    header("Location: app18.test/subeArchivo.php");
}
?>
<div class="container">
    <form action="" method="POST" enctype="multipart/form-data">
        <h2>Subir Sysven a la nube</h2><br>
        <label for="">Ingrese la contraseña: </label>
        <input type="password" name="" id="password"><br>
        <select name="" id="carpeta">
            <?php
            while (($file = readdir($dir)) !== false) {
                if ($file != '.' && $file != '..')
                    echo '<option>' . $file . '</option>';
            }
            ?>
        </select>
        <input type="file" name="archivo" id="archivo">
        <input type="submit" value="Enviar" />
    </form>
</div>
<script>
    // window.addEventListener('load', function() {
    //     console.log('La página ha terminado de cargarse!!');
    // });
    select = document.getElementById("carpeta");
    select.addEventListener('change',
        function() {
            carpeta = document.getElementById("carpeta");
            console.log(carpeta.value);
            window.location.href = window.location.href + "?carpeta=" + carpeta.value
        });
</script>