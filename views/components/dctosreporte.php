<label class="my-1 mr-2">Documento:</label>
<select class="form-control form-control-sm tipodocumentos" id="dctos" name="dctos">
    <option value="0" selected>Todos</option>
    <?php foreach ($lista['lista']['items'] as $row) : ?>
        <option value=<?php echo $row['tdoc'] ?>><?php echo ucwords(strtolower($row['nomb'])) ?></option>
    <?php endforeach; ?>
</select>