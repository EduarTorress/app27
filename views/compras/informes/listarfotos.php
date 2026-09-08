<div class="row text-center">
    <?php
    $carpeta = "correa";
    if (session()->get("gene_nruc") == '10470458530') {
        $carpeta = "norbil";
    } else {
        $carpeta = "walter";
    }
    $path    = $_SERVER['DOCUMENT_ROOT'] . '/img/compras/norbil';
    $minuto =  date('H:i');
    foreach ($listado as $l):
    ?>
        <div class="col-md-4 mb-4">
            <h4><?php echo date('d/m/Y h:m:s', filectime($path . "/" . $l)); ?></h4>
            <img src="<?php echo  '/img/compras/' . $carpeta . '/' . $l . "?fecha=" . $minuto ?>" alt="Image" class="img-fluid rounded">
        </div>
    <?php endforeach; ?>
</div>