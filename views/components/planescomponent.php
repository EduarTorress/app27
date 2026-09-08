<div class="col-auto">
    <select class="form-control form-control-sm" name="" id="<?php echo $cname; ?>" onkeypress="entertest(this)">
        <?php foreach ($listadoplanes as $p) : ?>
            <option id="<?php echo $p['idcta'] ?>" value="<?php echo $p['idcta'] . '&' . trim($p['nomb']) ?>" <?php echo (($cplanselect == $p['ncta']) ? 'selected ' : ''); ?>><?php echo $p['ncta']; ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="col-auto">
    <input type="text" class="form-control form-control-sm" value="<?php echo ($cplan == '42' ? 'HABER' : 'DEBE') ?>" style="width:60px;" disabled>
</div>
<div class="col-auto">
    <input type="text" class="form-control form-control-sm" id="<?php echo $ctxtdescri; ?>" value="<?php echo (empty($cdescriselect) ? $listadoplanes[0]['nomb'] : $cdescriselect); ?>" value="" style="width: 200px;" disabled>
</div>
<script>
    $("<?php echo '#' . $cname ?>").on("change", function() {
        cname = $("<?php echo '#' . $cname; ?>").val();
        cname = cname.split("&");
        $("<?php echo '#' . $ctxtdescri ?>").val(cname[1]);
    });
</script>