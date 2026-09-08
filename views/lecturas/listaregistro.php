<div class="card">
    <div class="card-header">
        Lecturas x Surtidor y Lado:
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table" class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th data-sortable="true">Producto</th>
                        <th data-sortable="true" style="text-align: center;">Surtidor</th>
                        <th data-sortable="true" style="text-align: center;">Lado</th>
                        <th data-sortable="true" style="text-align: center; width: 10px;">Cantidad</th>
                        <th data-sortable="true" style="text-align: center; width: 10px;">Monto S/</th>
                        <th data-sortable="true" style="text-align: center; width: 10px;">Precio</th>
                        <th style="display:none;" class="idart">idart</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listado as $item) : ?>
                        <tr>
                            <td class="producto"><?php echo $item['descri'] ?></td>
                            <td class="surtidor" style="text-align: center;"><?php echo $item['surtidor'] ?></td>
                            <td class="lado" style="text-align: center;"><?php echo $item['lado'] ?></td>
                            <td class="cantidad" style="text-align: center;">
                                <input type="text" style="text-align: end;" onkeypress="return isNumber(event);" onclick="$(this).focus();$(this).select();" value="<?php echo $item['cantidad'] ?>">
                            </td>
                            <td class="monto" style="text-align: center;">
                                <input type="text" style="text-align: end;" onkeypress="return isNumber(event);" onclick="$(this).focus();$(this).select();" value="<?php echo $item['monto'] ?>">
                            </td>
                            <td class="precio" style="text-align: center;">
                                <input type="text" style="text-align: end;" onkeypress="return isNumber(event);" onclick="$(this).focus();$(this).select();" value="<?php echo $item['precio'] ?>">
                            </td>
                            <td class="idart"><?php echo $item['codigo']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                <button id="btnregistrar" class="btn btn-success" onclick="registrarlecturas()">Registrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(".idart").css("display", "none");
    // reportetablebt("#table");
    document.querySelector('#table tbody td:nth-child(4) input').click();

    function navegarMontos() {
        const filas = document.querySelectorAll('#table tbody tr');
        filas.forEach((fila, index) => {
            const cantidad = fila.querySelector('.cantidad input');
            const monto = fila.querySelector('.monto input');
            const precio = fila.querySelector('.precio input');
            // Enter en CANTIDAD → Monto
            cantidad.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    monto.focus();
                    monto.select();
                }
            });
            // Enter en MONTO → Precio
            monto.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    precio.focus();
                    precio.select();
                }
            });
            // Enter en PRECIO → Cantidad de siguiente fila
            precio.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const siguienteFila = filas[index + 1];
                    if (siguienteFila) {
                        const siguienteCantidad = siguienteFila.querySelector('.cantidad input');
                        siguienteCantidad.focus();
                        siguienteCantidad.select();
                    } else {
                        document.getElementById('btnregistrar').click();
                    }
                }
            });
        });
    }

    navegarMontos();
</script>