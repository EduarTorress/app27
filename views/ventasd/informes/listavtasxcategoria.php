<div class="table-responsive">
    <table id="table" class="table table-bordered table-hover table-sm small">
        <thead>
            <tr>
                <th class="text-center" data-sortable="true">Fecha</th>
                <th class="text-center" data-sortable="true" colspan="100%">Categoria</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listado as $item) : ?>
                <tr>
                    <td><?php echo $item[0]['fech'] ?></td>
                    <?php
                    foreach ($item as $it) { ?>
                        <td class="text-center">
                            <b> <?php echo $it['dcat']; ?></b>
                        </td>
                    <?php } ?>
                </tr>
                <tr>
                    <td></td>
                    <?php
                    foreach ($item as $it) { ?>
                        <td class="text-end">
                            <?php echo number_format($it['total'], 2); ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<style>
    #table {
        width: 100% !important;
        border-collapse: separate;
        border-spacing: 0;
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .08);
        font-family: "Segoe UI", Arial, sans-serif;
        font-size: 14px;
    }

    /* Encabezados */
    #table thead th {
        background: #34495e;
        color: #ffffff;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
        text-align: center;
        padding: 14px 12px;
        border: none;
        white-space: nowrap;
    }

    /* Primera y última esquina */
    #table thead th:first-child {
        border-top-left-radius: 10px;
    }

    #table thead th:last-child {
        border-top-right-radius: 10px;
    }

    /* Celdas */
    #table tbody td {
        padding: 12px;
        border-bottom: 1px solid #e9ecef;
        color: #2c3e50;
        transition: all .2s ease;
    }

    /* Primera columna (Fecha) */
    #table tbody td:first-child {
        font-weight: 600;
        color: #34495e;
        white-space: nowrap;
    }

    /* Columnas numéricas */
    #table tbody td:not(:first-child) {
        text-align: right;
        font-variant-numeric: tabular-nums;
    }

    /* Filas alternadas */
    #table tbody tr:nth-child(even) {
        background: #f8fafc;
    }

    /* Hover */
    #table tbody tr:hover {
        background: #eef5ff;
    }

    /* Última fila */
    #table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Scroll horizontal si hay muchas columnas */
    .table-responsive {
        border-radius: 10px;
        overflow: auto;
    }

    /* Barra de scroll moderna */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #bfc7d1;
        border-radius: 20px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #9ea8b4;
    }

    /* Responsive */
    @media (max-width: 768px) {

        #table {
            font-size: 12px;
        }

        #table thead th,
        #table tbody td {
            padding: 8px;
        }

    }
</style>