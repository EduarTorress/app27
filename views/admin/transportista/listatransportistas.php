<div class="card-body">
    <table id="tabla_transportistas" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th>Razon social</th>
                <th >RUC</th>
                <th >Placa 01</th>
                <th>Transportista</th>
                <th>Brevete</th>
                <th>Placa 02</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lista['lista']['items'] as $item) : ?>
                <tr>
                    <td><?php echo $item['razon'] ?></td>
                    <td><?php echo $item['ructr'] ?></td>
                    <td><?php echo $item['placa'] ?></td>
                    <td><?php echo $item['nombr'] ?></td>
                    <td><?php echo $item['breve'] ?></td>
                    <td><?php echo $item['placa1'] ?></td>
                    <td class="text-center" id="iniciar">
                        <?php
                        $parametro1 = $item['idtra'];
                        $parametro2 = $item['placa'];
                        $parametro3 = $item['razon'];
                        $parametro4 = $item['ructr'];
                        $parametro5 = $item['dirtr'];
                        $parametro6 = $item['breve'];
                        $parametro7 = $item['marca'];
                        $parametro8 = $item['constancia'];
                        $parametro9 = $item['tipot'];
                        $parametro10 = $item['placa1'];
                        $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro7', 'parametro8', 'parametro9', 'parametro10');
                        $cadena_json = json_encode($parametros);
                        ?>
                        <button onclick='modalEdit("<?php echo $parametro1 ?>")' class="btn btn-warning">Editar</button>
                        <!-- <button onclick='darBaja("<?php echo $parametro1 ?>")' class="btn btn-danger">Eliminar</button> -->
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        focustabla('#tabla_transportistas')
    });
</script>