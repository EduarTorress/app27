<table class="table table-sm small table table-hover" id="griddetalle">
    <thead>
        <tr>
            <th scope="col" class="text-end" data-footer-formatter="formatTotal">Efectivo Isl.</th>
            <th scope="col" class="text-end" data-footer-formatter="formatTotal">Efectivo Depos.  </th>
            <th scope="col" class="text-end" data-footer-formatter="formatTotal">Diferencia</th>
            <th scope="col" class="text-center">Descripción Bancos </th>
        </tr>
    </thead>
    <tbody id="">
        <?php foreach ($lista as $l) : ?>
            <tr>
                <td><?php echo $l['efectivo_isla']; ?></td>
                <td><?php echo $l['depositado_cash_today']; ?></td>
                <td><?php echo $l['diferencia']; ?></td>
                <td><?php echo $l['bancos']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
    reportetablebt("#griddetalle");
</script>