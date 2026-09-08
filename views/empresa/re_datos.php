<div class="card">
    <!-- /.card-header -->
    <div class="card-body">
     <?php foreach( $listado as $row) {?>   
     <input class="form-control" type="text" value="<?php echo 'Nombre : '.$row['empresa'] ?>" aria-label="default input example" readonly>
     <input class="form-control" type="text" value="<?php echo 'RUC : '.$row['nruc'] ?>" aria-label="default input example" readonly>
     <input class="form-control" type="text" value="<?php echo 'USUARIO SOL 1 : '.$row['gene_usol1'] ?>" aria-label="default input example" readonly>
     <input class="form-control" type="text" value="<?php echo 'CLAVE SOL 1 : '.$row['gene_csol1'] ?>" aria-label="default input example" readonly>
     <input class="form-control" type="text" value="<?php echo 'USUARIO SOL 2 : '.$row['gene_usol'] ?>" aria-label="default input example" readonly>
     <input class="form-control" type="text" value="<?php echo 'CLAVE SOL 2 : '.$row['gene_csol'] ?>" aria-label="default input example" readonly>
     <?php } ?>
    </div>
    <!-- /.card-body -->
</div>
