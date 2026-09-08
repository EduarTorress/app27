<table id="tabla_transportista" class="table table-bordered table-hover table-sm small">
    <thead>
        <tr>
            <th>Empresa</th>
            <th>Placa</th>
            <th>RUC</th>
            <th class="text-center">Seleccionar</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($lista['lista']['items'] as $item) : ?>
            <tr>
                <td><?php echo $item['razon'] ?></td>
                <td><?php echo $item['placa'] ?></td>
                <td><?php echo $item['ructr'] ?></td>
                <td class="text-center" id="iniciartra">
                    <?php
                    $parametro1 = $item['idtra'];
                    $parametro2 = $item['razon'];
                    $parametro3 = $item['nombr'];
                    $parametro4 = $item['placa'];
                    $parametro5 = $item['ructr'];
                    $parametro6 = $item['constancia'];
                    $parametro7 = $item['breve'];
                    $parametro8 = $item['tipot'];
                    $parametro9 = $item['marca'];
                    $parametro10 = $item['placa1'];
                    $parametros = compact('parametro1', 'parametro2', 'parametro3', 'parametro4', 'parametro5', 'parametro6', 'parametro7', 'parametro8', 'parametro9', 'parametro10');
                    $cadena_json = json_encode($parametros);
                    ?>
                    <button id="<?php echo "agregartra" . $parametro1 ?>" class="btn btn-success" onclick='seleccionarTransportista(<?php echo $cadena_json ?>);'><i href="" style="color:white;" class="fas fa-plus-circle"></i></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        var w = screen.width;
        if (w <= 768) {
            focustablacelular('#tabla_transportista')
        } else {
            focustablatransportista('#tabla_transportista')
        }
    });
</script>