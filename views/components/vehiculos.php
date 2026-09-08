<select class="form-control form-control-sm" id="cmbvh" name="cmbvh">
    <option value="0">Todos</option>
    <?php foreach ($lista['lista']['items'] as $row) : ?>
        <option value=<?php echo $row['vehi_idve'] ?>><?php echo $row['vehi_plac'] ?></option>
    <?php endforeach; ?>
</select>