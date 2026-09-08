<div id="divkardexgeneral">
    <table id="tablekardexgeneral" class="table table-bordered table-hover table-sm small" data-page-size="5">
        <thead>
            <tr>
                <th>Codigo</th>
                <th>Producto</th>
                <th class="text-end">Stock Inicial</th>
                <th class="text-end">Ventas</th>
                <th class="text-end">Compras</th>
                <th class="text-end">Stock Final</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listado as $item) : ?>
                <tr>
                    <td><?php echo $item['Coda'] ?></td>
                    <td><?php echo $item['Descri'] ?></td>
                    <td><?php echo $item['si'] ?></td>
                    <td><?php echo $item['ventas'] ?></td>
                    <td><?php echo $item['compras'] ?></td>
                    <td><?php echo $item['stock'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<style>
    #tablekardexgeneral {
        zoom: 70%;
    }
</style>
<script>
    // $(document).ready(function() {
    //     var table = $("#table").DataTable({
    //         paging: true,
    //         lengthChange: false,
    //         searching: false,
    //         info: false,
    //         keys: true,
    //         autoWidth: false,
    //         responsive: true,
    //         fnCreatedRow: function(rowEl, data) {
    //             $(rowEl).attr("id", data[0]);
    //         },
    //     });
    //     $('thead').css({
    //         color: "white",
    //         "background-color": "#03326a"
    //     });
    //     $(table.row().node()).addClass("selected");
    //     $("#iniciar").trigger("click");
    //     $("#table").on("key-focus.dt", function(e, datatable, cell) {
    //         $(table.row(cell.index().row).node()).addClass("selected");
    //     });
    //     $("#table").on("key-blur.dt", function(e, datatable, cell) {
    //         $(table.row(cell.index().row).node()).removeClass("selected");
    //     });
    //     $("#table").on("key.dt", function(e, datatable, key, cell, originalEvent) {
    //         if (key === 13) {
    //             var data = table.row(cell.index().row).data();
    //             arr = data[2].split('"');
    //             const cmd = "#" + arr[1];
    //             document.querySelector(cmd).click();
    //         }
    //     });
    // });

    reportetablebt("#tablekardexgeneral");
    // $("#divkardexgeneral #btnExportexcel").remove();
    // $("#divkardexgeneral #btnExportpdf").remove();

    $('#tablekardexgeneral').on('click-row.bs.table', function(e, row, $element, field) {
        // Tu lógica aquí
        // console.log('Fila seleccionada:', row);
        searchkardexxproducto(row[0])
        $("#lbltitulokardexxproducto").html("Kardex x Producto: <b>"+row[1]+"</b>");
    });
</script>