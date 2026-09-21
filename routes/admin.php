<?php

#rutas de autenticacion
$app->router->get("/admin", [\App\Controllers\DashboardController::class, 'index']);
$app->router->get("/", [\App\Controllers\DashboardController::class, 'index']);
// $app->router->get("/panel", [\App\Controllers\DashboardController::class, 'obtenerpanel']);
$app->router->get("/login", [\App\Controllers\LoginController::class, 'login']);
$app->router->post('/admin/sesion', [\App\Controllers\DashboardController::class, 'obtenerDatos']);
$app->router->post("/login", [\App\Controllers\LoginController::class, 'store']);
$app->router->get("/register", [\App\Controllers\RegisterController::class, 'register']);
$app->router->post("/register", [\App\Controllers\RegisterController::class, 'store']);
$app->router->get("/salir", [\App\Controllers\LoginController::class, 'salir']);
$app->router->get("/calcularfechavto", [\App\Controllers\DashboardController::class, 'calcularfechavto']);
$app->router->get("/dashboard", [\App\Controllers\DashboardController::class, 'obtenerpanel']);
$app->router->get("/ai/indexprediccionventasxmes", [\App\Controllers\DashboardController::class, 'indexprediccionventasxmes']);
$app->router->get("/ai/listaprediccionventasxmes", [\App\Controllers\DashboardController::class, 'listaprediccionventasxmes']);

#rutas de ventas x productos
$app->router->get('/vtas/index', [\App\Controllers\VentasController::class, 'index']);
$app->router->get('/vtas/listardetalle', [\App\Controllers\VentasController::class, 'listarDetalle']);
$app->router->post('/vtas/agregaritem', [\App\Controllers\VentasController::class, 'agregaritem']);
$app->router->post('/vtas/EditarUno', [\App\Controllers\VentasController::class, 'soloItem']);
$app->router->post('/vtas/editardescuentoxproducto', [\App\Controllers\VentasController::class, 'editardescuentoxproducto']);
$app->router->post('/vtas/registrar', [\App\Controllers\VentasController::class, 'registrar']);
$app->router->post('/vtas/actualizar', [\App\Controllers\VentasController::class, 'modificar']);
$app->router->post('/vtas/quitaritem', [\App\Controllers\VentasController::class, 'quitaritem']);
$app->router->get('/vtas/buscarventa/{id}', [\App\Controllers\VentasController::class, 'buscarVentaPorID']);
$app->router->get("/vtas/vtasresumidas", [\App\Controllers\VentasController::class, 'ventasresumidas']);
$app->router->get("/vtas/listavtasr", [\App\Controllers\VentasController::class, 'mostrarventasresumidas']);
$app->router->post('/vtas/limpiar', [\App\Controllers\VentasController::class, 'limpiarvta']);
$app->router->get("/vtas/imprimirdirecto/", [\App\Controllers\VentasController::class, 'imprimirdirecto']);
$app->router->get("/vtas/listarvtasnota", [\App\Controllers\VentasController::class, 'listarvtasnota']);
$app->router->get("/vtas/listarvtasxserviciostoanticipo", [\App\Controllers\VentasController::class, 'listarvtasxserviciostoanticipo']);
$app->router->post('/vtas/verificarvalorescarrito', [\App\Controllers\VentasController::class, 'verificarvalorescarrito']);

// $app->router->post('/vtas/convertirafactura', [\App\Controllers\VentasController::class, 'convertirafactura']);

$app->router->get("/vtas/listardetallenota", [\App\Controllers\VentasController::class, 'listardetallenota']);
$app->router->get('/vtas/regvtas', [\App\Controllers\VentasController::class, 'regvtasple']);
$app->router->get('/vtas/rvtas', [\App\Controllers\VentasController::class, 'regvtasp']);
$app->router->get('/vtas/indexvtasxvendedor', [\App\Controllers\VentasController::class, 'indexvtasxvendedor']);
$app->router->get('/vtas/listavtasxvendedor', [\App\Controllers\VentasController::class, 'listavtasxvendedor']);
$app->router->get('/vtas/indexventadproducto', [\App\Controllers\VentasController::class, 'indexventadproducto']);
$app->router->get('/vtas/listarventadproducto', [\App\Controllers\VentasController::class, 'listarventadproducto']);
$app->router->get('/vtas/indexlistavxcliente', [\App\Controllers\VentasController::class, 'indexlistavxcliente']);
$app->router->get('/vtas/listavtasxcliente', [\App\Controllers\VentasController::class, 'listavtasxcliente']);
$app->router->post('/detail/changedolar', [\App\Controllers\VentasController::class, 'detailchangedolar']);
$app->router->post('/vtas/verutilidad', [\App\Controllers\VentasController::class, 'verutilidad']);
$app->router->get('/vtas/indexlistaventasxano', [\App\Controllers\VentasController::class, 'indexlistaventasxano']);
$app->router->get('/vtas/listaventasxano', [\App\Controllers\VentasController::class, 'listaventasxano']);
$app->router->get('/vtas/consultardetalleventaxndoc', [\App\Controllers\VentasController::class, 'consultardetalleventaxndoc']);
$app->router->get("/vtas/indexlistavtasmodificadas", [\App\Controllers\VentasController::class, 'indexlistavtasmodificadas']);
$app->router->get("/vtas/listarvtasmodificadas", [\App\Controllers\VentasController::class, 'listarvtasmodificadas']);
$app->router->get("/vtas/indexvtasanuladas", [\App\Controllers\VentasController::class, 'indexvtasanuladas']);
$app->router->get("/vtas/listarvtasanuladas", [\App\Controllers\VentasController::class, 'listarvtasanuladas']);
$app->router->get("/vtas/indexlistarutilidades", [\App\Controllers\VentasController::class, 'indexlistarutilidades']);
$app->router->get("/vtas/mostrarvtasutilidades", [\App\Controllers\VentasController::class, 'mostrarvtasutilidades']);
$app->router->get("/vtas/indexlistarutilidadesdetallada", [\App\Controllers\VentasController::class, 'indexlistarutilidadesdetallada']);
$app->router->get("/vtas/listarutilidadesdetalle", [\App\Controllers\VentasController::class, 'listarutilidadesdetalle']);

$app->router->get("/operaciones/indexmovimientosdetallados", [\App\Controllers\VentasController::class, 'indexmovimientosdetallados']);
$app->router->get('/operaciones/listamovimientosdetalladas', [\App\Controllers\VentasController::class, 'listamovimientosdetalladas']);

$app->router->get("/vtas/indexlistavtasxcategoria", [\App\Controllers\VentasController::class, 'indexlistavtasxcategoria']);
$app->router->get('/vtas/listavtasxcategoria', [\App\Controllers\VentasController::class, 'listavtasxcategoria']);


#rutas para ventas x clave sol
$app->router->get('/vtassol/index', [\App\Controllers\VentasController::class, 'indexvtassol']);
$app->router->post('/vtassol/registrar', [\App\Controllers\VentasController::class, 'registrarvtassol']);

#rutas para ventas market
$app->router->get('/ventasrapidas/index', [\App\Controllers\VentasController::class, 'indexvtasrapidas']);
$app->router->get('/ventasrapidas/listardetalle', [\App\Controllers\VentasController::class, 'listardetallevtarapida']);
$app->router->post('/ventasrapidas/agregaritem', [\App\Controllers\VentasController::class, 'agregaritemvtarapida']);
$app->router->post('/ventasrapidas/EditarUno', [\App\Controllers\VentasController::class, 'soloitemvtarapida']);
$app->router->post('/ventasrapidas/quitaritem', [\App\Controllers\VentasController::class, 'quitaritemvtarapida']);
$app->router->post('/ventasrapidas/limpiar', [\App\Controllers\VentasController::class, 'limpiarvtarapida']);

#rutas de otras ventas
$app->router->get('/ovtas/index', [\App\Controllers\VentasController::class, 'indexovtas']);
$app->router->get("/ovtas/vtasresumidas", [\App\Controllers\VentasController::class, 'oventasresumidas']);
$app->router->get("/ovtas/listavtasr", [\App\Controllers\VentasController::class, 'mostraroventasresumidas']);
$app->router->post('/ovtas/registrar', [\App\Controllers\VentasController::class, 'registrarovta']);
$app->router->post('/ovtas/actualizar', [\App\Controllers\VentasController::class, 'modificarovta']);
$app->router->get('/ovtas/buscarventa/{id}', [\App\Controllers\VentasController::class, 'buscarOVentaPorID']);
$app->router->post('/ovtas/limpiar', [\App\Controllers\VentasController::class, 'limpiarSesionOvta']);
$app->router->post('/vtas/sesion', [\App\Controllers\VentasController::class, 'grabarSesion']);

#rutas de ordenes de compra
$app->router->get('/ordenescompra/index', [\App\Controllers\OrdenCompraController::class, 'index']);
$app->router->get('/ordenescompra/listardetalle', [\App\Controllers\OrdenCompraController::class, 'listardetalle']);
$app->router->post('/ordenescompra/agregaritem', [\App\Controllers\OrdenCompraController::class, 'agregaritem']);
$app->router->post('/ordenescompra/quitaritem', [\App\Controllers\OrdenCompraController::class, 'quitaritem']);
$app->router->post('/ordenescompra/limpiar', [\App\Controllers\OrdenCompraController::class, 'limpiar']);
$app->router->post('/ordenescompra/EditarUno', [\App\Controllers\OrdenCompraController::class, 'soloItem']);
$app->router->post('/ordenescompra/checkafecto', [\App\Controllers\OrdenCompraController::class, 'checkafecto']);
$app->router->get('/ordenescompra/listar', [\App\Controllers\OrdenCompraController::class, 'indexLista']);
$app->router->get('/ordenescompra/listado', [\App\Controllers\OrdenCompraController::class, 'listarXFecha']);
$app->router->post('/ordenescompra/registrar', [\App\Controllers\OrdenCompraController::class, 'grabar']);
$app->router->get('/ordenescompra/buscarOrdenCompraPorId/{id}', [\App\Controllers\OrdenCompraController::class, 'buscarOrdenCompraPorId']);
$app->router->post('/ordenescompra/sesion', [\App\Controllers\OrdenCompraController::class, 'grabarsesion']);
$app->router->post('/ordenescompra/actualizar', [\App\Controllers\OrdenCompraController::class, 'modificar']);
$app->router->get('/ordenescompra/imprimir', [\App\Controllers\OrdenCompraController::class, 'imprimir']);

#rutas de notas de crédito
$app->router->get('/notascredito/index', [\App\Controllers\NotasCreditoController::class, "index"]);
$app->router->post('/notascredito/registrar', [\App\Controllers\NotasCreditoController::class, "registrar"]);

#rutas de canjes remitentes 
$app->router->get('/vtas/canjes', [\App\Controllers\VentasController::class, 'indexcanjes']);
$app->router->get('/vtas/listardetallecanjeguias', [\App\Controllers\VentasController::class, 'listarDetallecanjesguias']);
$app->router->get('/vtas/listarDetalleCanje', [\App\Controllers\VentasController::class, 'listarDetalleCanje']);
$app->router->post('/canje/registrar', [\App\Controllers\VentasController::class, 'registrarCanje']);

#rutas de canjes pedidos 
$app->router->get('/vtas/canjespedidos', [\App\Controllers\VentasController::class, 'indexcanjespedidos']);
$app->router->get('/vtas/listardetallecanjepedidos', [\App\Controllers\VentasController::class, 'listardetallecanjepedidos']);
$app->router->get('/pedidos/listarpedidosparacanje', [\App\Controllers\PedidoController::class, 'listarpedidosparacanje']);
$app->router->get('/pedidos/listardetallepedidoxid', [\App\Controllers\PedidoController::class, 'listardetallepedidoxid']);
$app->router->post('/vtas/registrarpedido', [\App\Controllers\VentasController::class, 'registrarcanjepedido']);

#rutas de canjes notas a facturas 
$app->router->get('/vtas/indexcanjearnotas', [\App\Controllers\VentasController::class, 'indexcanjearnotas']);
$app->router->get('/vtas/listardetallenotastocanje', [\App\Controllers\VentasController::class, 'listardetallenotastocanje']);
$app->router->get('/vtas/listarnotastocanje', [\App\Controllers\VentasController::class, 'listarnotastocanje']);
$app->router->post('/vtas/registrarcanjearnota', [\App\Controllers\VentasController::class, 'registrarcanjearnota']);

#rutas de canjes transportistas
$app->router->get('/guiastr/listarGuiasTrparacanje', [\App\Controllers\GuiasController::class, 'listarGuiasTrparacanje']);
$app->router->get('/vtas/canjestr', [\App\Controllers\VentasController::class, 'indexcanjestr']);
$app->router->get('/vtas/listarDetalleCanjeTr', [\App\Controllers\VentasController::class, 'listarDetalleCanjeTr']);
$app->router->post('/vtas/registrarcanjetr', [\App\Controllers\VentasController::class, 'registrarcanjetr']);

#rutas de compras
$app->router->get('/compras/ocompras', [\App\Controllers\ComprasController::class, 'index']);
$app->router->get('/compras/index', [\App\Controllers\ComprasController::class, 'indexcompra']);
$app->router->get('/compras/listardetalle', [\App\Controllers\ComprasController::class, 'listardetalle']);
$app->router->post('/compras/agregaritem', [\App\Controllers\ComprasController::class, 'agregaritem']);
$app->router->post('/compras/quitaritem', [\App\Controllers\ComprasController::class, 'quitaritem']);
$app->router->post('/compras/limpiar', [\App\Controllers\ComprasController::class, 'limpiar']);
$app->router->post('/compras/EditarUno', [\App\Controllers\ComprasController::class, 'soloItem']);
$app->router->get('/compras/listar', [\App\Controllers\ComprasController::class, 'indexListaCompras']);
$app->router->get('/compras/listado', [\App\Controllers\ComprasController::class, 'listarComprasXFecha']);
$app->router->post('/compras/registrar', [\App\Controllers\ComprasController::class, 'grabar']);
$app->router->get('/compras/buscarcompra/{id}', [\App\Controllers\ComprasController::class, 'buscarCompraPorID']);
$app->router->post('/compras/sesion', [\App\Controllers\ComprasController::class, 'grabarsesion']);
$app->router->post('/compras/checkafecto', [\App\Controllers\ComprasController::class, 'checkafecto']);
$app->router->post('/compras/actualizar', [\App\Controllers\ComprasController::class, 'modificar']);
$app->router->get('/dolar/obtenerdolar', [\App\Controllers\ValorDolarController::class, 'obtenerDolar']);
$app->router->get('/compras/listarPLE', [\App\Controllers\ComprasController::class, 'indexListaComprasPLE']);
$app->router->get('/compras/listadoPLE', [\App\Controllers\ComprasController::class, 'listarComprasXFechaPLE']);

$app->router->get('/compras/indexcompradproducto', [\App\Controllers\ComprasController::class, 'indexcompradproducto']);
$app->router->get('/compras/listarcompradproducto', [\App\Controllers\ComprasController::class, 'listarcompradproducto']);
$app->router->get("/compras/exportarsire", [\App\Controllers\ComprasController::class, 'exportarsire']);

$app->router->get("/compras/indexlistacomprasmodificadas", [\App\Controllers\ComprasController::class, 'indexlistacomprasmodificadas']);
$app->router->get("/compras/listarcomprasmodificadas", [\App\Controllers\ComprasController::class, 'listarcomprasmodificadas']);

$app->router->get("/compras/indexlistacomprasxprov", [\App\Controllers\ComprasController::class, 'indexlistacomprasxprov']);
$app->router->get("/compras/listacomprasxprov", [\App\Controllers\ComprasController::class, 'listacomprasxprov']);

$app->router->post('/compras/importarcompraxarchivo', [\App\Controllers\ComprasController::class, 'importarcompraxarchivo']);
$app->router->post('/compras/agregaritemxposicion', [\App\Controllers\ComprasController::class, 'agregaritemxposicion']);

$app->router->get("/compras/indeximportarfotos", [\App\Controllers\ComprasController::class, 'indeximportarfotos']);
$app->router->get("/compras/listarfotos", [\App\Controllers\ComprasController::class, 'listarfotos']);
$app->router->post('/compras/importarfoto', [\App\Controllers\ComprasController::class, 'importarfoto']);

#rutas para otras compras
$app->router->get('/ocompras/index', [\App\Controllers\ComprasController::class, 'indexocompra']);
$app->router->get('/ocompra/getvaluedolar', [\App\Controllers\ValorDolarController::class, 'getvaluedolarocompra']);
$app->router->post('/ocompras/registrar', [\App\Controllers\ComprasController::class, 'registrarocompra']);
$app->router->post('/ocompras/modificar', [\App\Controllers\ComprasController::class, 'modificarocompra']);
$app->router->get('/ocompras/buscarcompra/{id}', [\App\Controllers\ComprasController::class, 'buscarOCompraPorID']);

#rutas de liquidación caja
$app->router->get('/cajas/index', [\App\Controllers\CajaController::class, 'index']);
$app->router->get('/cajas/buscar', [\App\Controllers\CajaController::class, 'buscar']);
$app->router->get('/cajas/indexcomparabancos', [\App\Controllers\CajaController::class, 'indexcomparabancos']);
$app->router->get('/cajas/listarcomparabancos', [\App\Controllers\CajaController::class, 'listarcomparabancos']);
$app->router->get('/cajas/indexIngresosEgresos', [\App\Controllers\CajaController::class, 'indexIngresosEgresos']);
$app->router->post('/cajas/registrarIngresoEgreso', [\App\Controllers\CajaController::class, 'registrarIngresoEgreso']);
$app->router->post('/cajas/registrarTransferencia', [\App\Controllers\CajaController::class, 'registrarTransferencia']);
$app->router->post('/cajas/registrarretiroparabancos', [\App\Controllers\CajaController::class, 'registrarretiroparabancos']);
$app->router->get('/cajas/obtenernumerodocumento', [\App\Controllers\CajaController::class, 'obtenernumerodocumento']);
$app->router->get('/caja/imprimirticketingresoyegreso', [\App\Controllers\CajaController::class, 'imprimirticketingresoyegreso']);


#rutas de lecturas
$app->router->get('/lecturas/indexhistorial', [\App\Controllers\LecturasController::class, 'indexhistorial']);
$app->router->get('/lecturas/buscarhistorial', [\App\Controllers\LecturasController::class, 'buscarhistorial']);

$app->router->get('/lecturas/indexregistro', [\App\Controllers\LecturasController::class, 'indexregistro']);
$app->router->get('/lecturas/listaregistro', [\App\Controllers\LecturasController::class, 'listaregistro']);
$app->router->post('/lecturas/registrar', [\App\Controllers\LecturasController::class, 'registrarlecturas']);

#rutas para cobranzas
$app->router->get('/cobranzas/index', [\App\Controllers\CobranzasController::class, 'index']);
$app->router->get('/cobranzas/listarvtos', [\App\Controllers\CobranzasController::class, 'listarvtos']);
$app->router->get('/cobranzas/indexlistacobranzastodo', [\App\Controllers\CobranzasController::class, 'indexlistacobranzastodo']);
$app->router->get('/cobranzas/listarcobranzastodo', [\App\Controllers\CobranzasController::class, 'listarcobranzastodo']);
$app->router->post('/cobranzas/registrarcobranzas', [\App\Controllers\CobranzasController::class, 'registrarcobranzas']);
$app->router->get('/cobranzas/listarestadocuenta', [\App\Controllers\CobranzasController::class, 'listarestadocuenta']);
$app->router->get('/cobranzas/consultardetalleventa', [\App\Controllers\CobranzasController::class, 'consultardetalleventa']);
$app->router->get("/cobranzas/exportardocumentocondetalle", [\App\Controllers\CobranzasController::class, 'exportardocumentocondetalle']);
$app->router->get('/cobranzas/indexresumencreditos', [\App\Controllers\CobranzasController::class, 'indexresumencreditos']);
$app->router->get('/cobranzas/listarresumencreditos', [\App\Controllers\CobranzasController::class, 'listarresumencreditos']);
#rutas para cuentas por pagar
$app->router->get('/pagosproveedor/index', [\App\Controllers\CuentasxPagarController::class, 'index']);
$app->router->get('/pagosproveedor/listarvtos', [\App\Controllers\CuentasxPagarController::class, 'listarvtos']);
$app->router->post('/pagosproveedor/registrar', [\App\Controllers\CuentasxPagarController::class, 'registrarpagos']);
$app->router->get('/pagosproveedor/listarestadocuenta', [\App\Controllers\CuentasxPagarController::class, 'listarestadocuenta']);
$app->router->get('/pagosproveedor/indexlistarctasxpagartodo', [\App\Controllers\CuentasxPagarController::class, 'indexlistarctasxpagartodo']);
$app->router->get('/pagosproveedor/listartodasctasxpagar', [\App\Controllers\CuentasxPagarController::class, 'listartodasctasxpagar']);

#rutas de guias transportista
$app->router->get('/guias/index', [\App\Controllers\GuiasController::class, 'index']);
$app->router->post('/guias/registrar', [\App\Controllers\GuiasController::class, 'registrar']);
$app->router->get('/guias/listar', [\App\Controllers\GuiasController::class, 'indexListar']);
$app->router->get('/guias/listarxenviar', [\App\Controllers\GuiasController::class, 'indexListarxenviar']);
$app->router->get('/guias/listarGuias', [\App\Controllers\GuiasController::class, 'listarGuias']);
$app->router->get('/guias/listarGuiasxenviar', [\App\Controllers\GuiasController::class, 'listarGuiasxenviar']);
$app->router->get('/guias/imprimir/', [\App\Controllers\GuiasController::class, 'imprimir']);
$app->router->get('/guias/descargarxml', [\App\Controllers\GuiasController::class, 'descargarxml']);
$app->router->get('/guias/enviarsunatguiatr/', [\App\Controllers\GuiasController::class, 'enviarsunatguiatr']);
$app->router->get('/guias/enviarsunatguiar/', [\App\Controllers\GuiasController::class, 'enviarsunatguiar']);
$app->router->get('/guias/buscarGuia/{id}', [\App\Controllers\GuiasController::class, 'consultarGuiaPorId']);
$app->router->post('/guias/modificar', [\App\Controllers\GuiasController::class, 'actualizar']);
$app->router->get("/guias/imprimirdirecto/", [\App\Controllers\GuiasController::class, 'imprimirdirecto']);
$app->router->get('/guias/actualizarEstadoGuiaTr', [\App\Controllers\GuiasController::class, 'actualizarEstadoGuiaTr']);

#rutas de guias remitentes x ventas
$app->router->get('/guiasr/index', [\App\Controllers\GuiasRemiController::class, 'index']);
$app->router->post('/guiasr/registrar', [\App\Controllers\GuiasRemiController::class, 'registrar']);
$app->router->get('/guiasr/listar', [\App\Controllers\GuiasRemiController::class, 'indexListar']);
$app->router->get('/guiasr/listarxenviar', [\App\Controllers\GuiasRemiController::class, 'indexListarxenviar']);
$app->router->get('/guiasr/listarGuias', [\App\Controllers\GuiasRemiController::class, 'listarGuias']);
$app->router->get('/guiasr/listarGuiasparacanje', [\App\Controllers\GuiasRemiController::class, 'listarGuiasparacanje']);
$app->router->get('/guiasr/indexListarGuias', [\App\Controllers\GuiasRemiController::class, 'indexListarGuias']);
$app->router->get('/guiasr/imprimir/', [\App\Controllers\GuiasRemiController::class, 'imprimir']);
$app->router->get('/guiasr/descargarxml', [\App\Controllers\GuiasRemiController::class, 'descargarxml']);
$app->router->get('/guiasr/buscarGuia/{id}', [\App\Controllers\GuiasRemiController::class, 'consultarGuiaPorId']);
$app->router->post('/guiasr/modificar', [\App\Controllers\GuiasRemiController::class, 'actualizar']);
$app->router->get("/guiasr/imprimirdirecto/", [\App\Controllers\GuiasRemiController::class, 'imprimirdirecto']);
$app->router->post('/guiasr/agregaritem', [\App\Controllers\GuiasRemiController::class, 'agregaritem']);
$app->router->get('/guiasr/listardetalle', [\App\Controllers\GuiasRemiController::class, 'listarDetalle']);
$app->router->post('/guiasr/quitaritem', [\App\Controllers\GuiasRemiController::class, 'quitaritem']);
$app->router->post('/guiasr/EditarUno', [\App\Controllers\GuiasRemiController::class, 'soloItem']);
$app->router->get('/guiasr/buscarGuia/{id}', [\App\Controllers\GuiasRemiController::class, 'consultarGuiaPorId']);
$app->router->get('/guiasr/listar', [\App\Controllers\GuiasRemiController::class, 'indexListar']);
$app->router->get('/guiasr/actualizarEstadoGuiaR', [\App\Controllers\GuiasRemiController::class, 'actualizarEstadoGuiaR']);
$app->router->get('/guiasr/listarvtastocanje', [\App\Controllers\GuiasRemiController::class, 'listarvtastocanje']);
$app->router->get('/guiasr/listardetalledevtatocanje', [\App\Controllers\GuiasRemiController::class, 'listardetalledevtatocanje']);
$app->router->get('/guiasr/limpiar', [\App\Controllers\GuiasRemiController::class, 'limpiar']);

#rutas de guias remitente x compras
$app->router->get('/guiasc/index', [\App\Controllers\GuiasRemicompraController::class, 'index']);
$app->router->post('/guiasc/registrar', [\App\Controllers\GuiasRemicompraController::class, 'registrar']);
$app->router->post('/guiasc/agregaritem', [\App\Controllers\GuiasRemicompraController::class, 'agregaritem']);
$app->router->post('/guiasc/quitaritem', [\App\Controllers\GuiasRemicompraController::class, 'quitaritem']);
$app->router->get('/guiasc/imprimir/', [\App\Controllers\GuiasRemicompraController::class, 'imprimir']);
$app->router->get('/guiasc/descargarxml', [\App\Controllers\GuiasRemicompraController::class, 'descargarxml']);
$app->router->get('/guiasc/buscarGuia/{id}', [\App\Controllers\GuiasRemicompraController::class, 'consultarGuiaPorId']);
$app->router->post('/guiasc/modificar', [\App\Controllers\GuiasRemicompraController::class, 'actualizar']);
$app->router->get("/guiasc/imprimirdirecto/", [\App\Controllers\GuiasRemicompraController::class, 'imprimirdirecto']);
$app->router->post('/guiasc/EditarUno', [\App\Controllers\GuiasRemicompraController::class, 'soloItem']);
$app->router->get('/guiasc/limpiar', [\App\Controllers\GuiasRemicompraController::class, 'limpiar']);


#ruta de traspasos
$app->router->get('/traspasos/index', [\App\Controllers\TraspasoController::class, 'index']);
$app->router->get('/traspasos/listardetalle', [\App\Controllers\TraspasoController::class, 'listardetalle']);
$app->router->get('/traspasos/listardetallecompratocanje', [\App\Controllers\TraspasoController::class, 'listardetallecompratocanje']);
$app->router->post('/traspasos/limpiar', [\App\Controllers\TraspasoController::class, 'limpiar']);
$app->router->post('/traspasos/agregaritem', [\App\Controllers\TraspasoController::class, 'agregaritem']);
$app->router->post('/traspasos/quitaritem', [\App\Controllers\TraspasoController::class, 'quitaritem']);
$app->router->post('/traspasos/EditarUno', [\App\Controllers\TraspasoController::class, 'soloItem']);
$app->router->post('/traspasos/registrar', [\App\Controllers\TraspasoController::class, 'grabar']);
$app->router->get("/traspasos/imprimirdirecto/", [\App\Controllers\TraspasoController::class, 'imprimirdirecto']);
$app->router->get('/traspasos/indexlistar', [\App\Controllers\TraspasoController::class, 'indexlistar']);
$app->router->get('/traspasos/listarxfecha', [\App\Controllers\TraspasoController::class, 'listarxfecha']);
$app->router->get('/traspasos/imprimir/', [\App\Controllers\TraspasoController::class, 'imprimir']);
$app->router->get('/traspasos/indexlistarxrecibir', [\App\Controllers\TraspasoController::class, 'indexlistarxrecibir']);
$app->router->get('/traspasos/listarxrecibir', [\App\Controllers\TraspasoController::class, 'listarxrecibir']);
$app->router->get('/traspasos/verdetalletraspaso', [\App\Controllers\TraspasoController::class, 'verdetalletraspaso']);
$app->router->get('/traspasos/aceptartraspaso', [\App\Controllers\TraspasoController::class, 'aceptartraspaso']);

#rutas de inventario
$app->router->post('/producto/updateStock', [\App\Controllers\ProductoController::class, 'updateStock']);
$app->router->post('/producto/registrarvarillajemedicion', [\App\Controllers\ProductoController::class, 'registrarvarillajeymedicion']);
$app->router->get('/inventarios/kardex', [\App\Controllers\InventarioController::class, 'indexkardex']);
$app->router->get('/inventarios/listarkardex', [\App\Controllers\InventarioController::class, 'listarkardex']);

$app->router->get('/inventarios/indexkardexgeneral', [\App\Controllers\InventarioController::class, 'indexkardexgeneral']);
$app->router->get('/inventarios/listarkardexgeneral', [\App\Controllers\InventarioController::class, 'listarkardexgeneral']);

$app->router->get('/inventarios/indexexistalmacen', [\App\Controllers\InventarioController::class, 'indexexistalmacen']);
$app->router->get('/inventarios/listarexistenciaalmacen', [\App\Controllers\InventarioController::class, 'listarexistenciaalmacen']);

$app->router->get('/inventarios/indexlistastockvalorizado', [\App\Controllers\InventarioController::class, 'indexlistastockvalorizado']);
$app->router->get('/inventarios/listarstockvalorizado', [\App\Controllers\InventarioController::class, 'listarstockvalorizado']);

$app->router->get('/inventarios/indexlistaajustes', [\App\Controllers\InventarioController::class, 'indexlistaajustes']);
$app->router->get('/inventarios/listaajustes', [\App\Controllers\InventarioController::class, 'listaajustes']);
$app->router->get('/inventarios/verdetalleajuste', [\App\Controllers\InventarioController::class, 'verdetalleajuste']);


$app->router->get('/inventarios/indexlistavarillajeymedicion', [\App\Controllers\InventarioController::class, 'indexlistavarillajeymedicion']);
$app->router->get('/inventarios/listavarillajeymedicion', [\App\Controllers\InventarioController::class, 'listavarillajeymedicion']);

$app->router->get('/inventarios/calcularstock', [\App\Controllers\InventarioController::class, 'calcularstock']);

$app->router->get('/inventarios/indexproductosmenosrotados', [\App\Controllers\InventarioController::class, 'indexproductosmenosrotados']);
$app->router->get('/inventarios/listaproductosmenosrotados', [\App\Controllers\InventarioController::class, 'listaproductosmenosrotados']);

$app->router->get('/inventarios/indexcomparativohistorico', [\App\Controllers\InventarioController::class, 'indexcomparativohistorico']);
$app->router->get('/inventarios/listacomparativohistorico', [\App\Controllers\InventarioController::class, 'listacomparativohistorico']);

#rutas generales
$app->router->get('/empresa/importarucydni', [\App\Controllers\EmpresaController::class, 'importarucyotros']);
$app->router->get('/empresa/obtenervalordolar', [\App\Controllers\EmpresaController::class, 'obtenervalordolar']);

#rutas de informes
$app->router->get("/cpe/enviarpdfwhatsapp", [\App\Controllers\CpeController::class, 'enviarpdfwhatsapp']);
$app->router->get("/cpe/rpte", [\App\Controllers\CpeController::class, 'informeventas']);
$app->router->get("/cpe/fxe", [\App\Controllers\CpeController::class, 'consultafne']);
$app->router->get("/cpe/lista", [\App\Controllers\CpeController::class, 'noenviados']);
$app->router->get("/cpe/bxe", [\App\Controllers\CpeController::class, 'consultabne']);
$app->router->get("/cpe/listat", [\App\Controllers\CpeController::class, 'listat']);
$app->router->get("/cpe/listaticket", [\App\Controllers\CpeController::class, 'listaticket']);
$app->router->get("/cpe/boletasne", [\App\Controllers\CpeController::class, 'boletaspendientesporenviar']);
$app->router->get("/cpe/ticket10", [\App\Controllers\CpeController::class, 'consultaticket']);
$app->router->get("/cpe/api", [\App\Controllers\CpeController::class, 'consultarapi']);
$app->router->get("/cpe/descargarxml", [\App\Controllers\CpeController::class, 'descargarxml']);
$app->router->get("/cpe/exportarsire", [\App\Controllers\CpeController::class, 'exportarsire']);
$app->router->get("/cpe/descargarpdf", [\App\Controllers\CpeController::class, 'descargarpdf']);
$app->router->get("/cpe/descargarpdfticket", [\App\Controllers\CpeController::class, 'descargarpdfticket']);
$app->router->get('/cpe/enviarboletas', [\App\Controllers\CpeController::class, 'enviarboletas']);
$app->router->get('/empresa/datos', [\App\Controllers\EmpresaController::class, 'index']);
$app->router->get('/empresa/listardatos', [\App\Controllers\EmpresaController::class, 'datos']);
$app->router->get('/cpe/eliminarticket', [\App\Controllers\CpeController::class, 'eliminarticket']);
$app->router->get('/cpe/indexAnular', [\App\Controllers\CpeController::class, 'indexAnular']);
$app->router->get('/cpe/listarDetalleAnular', [\App\Controllers\CpeController::class, 'buscarDetalleDocumento']);
$app->router->post('/cpe/eliminarDocumento', [\App\Controllers\CpeController::class, 'eliminarDocumento']);
$app->router->post('/cpe/bajadocumento', [\App\Controllers\CpeController::class, 'bajaDocumento']);

#rutas de pedidos
$app->router->get('/pedidos/indexespecial', [\App\Controllers\PedidoController::class, 'indexespecial']);
$app->router->post('/pedidos/EditarUno', [\App\Controllers\PedidoController::class, 'soloItem']);
$app->router->post('/pedidos/agregaritemrapido', [\App\Controllers\PedidoController::class, 'agregaritemrapido']);
$app->router->post('/pedidos/agregaritem', [\App\Controllers\PedidoController::class, 'agregaritem']);
$app->router->post('/pedidos/quitaritem', [\App\Controllers\PedidoController::class, 'quitaritem']);
$app->router->post('/pedidos/editaritem', [\App\Controllers\PedidoController::class, 'editaritem']);
$app->router->post('/pedidos/cambiaritem', [\App\Controllers\PedidoController::class, 'cambiaritem']);
$app->router->post('/pedidos/limpiar', [\App\Controllers\PedidoController::class, 'limpiar']);
$app->router->get('/pedidos/listarpedido', [\App\Controllers\PedidoController::class, 'listarpedido']);
$app->router->get('/pedidos/listarcarrito', [\App\Controllers\PedidoController::class, 'listarcarrito']);
$app->router->post('/pedido/grabarpedido', [\App\Controllers\PedidoController::class, 'grabar']);
$app->router->post('/pedido/actualizar', [\App\Controllers\PedidoController::class, 'actualizar']);
$app->router->get('/pedido/listarpedidos', [\App\Controllers\PedidoController::class, 'listarpedidos']);
$app->router->get('/pedidos/listartpedidos', [\App\Controllers\PedidoController::class, 'listarpedidosfechas']);
$app->router->get('/pedidos/buscarpedido/{id}', [\App\Controllers\PedidoController::class, 'buscarpedido']);
$app->router->get('/pedidos/itemdetalle/{id}', [\App\Controllers\PedidoController::class, 'detalle']);
$app->router->get('/pedidos/verificaritem', [\App\Controllers\PedidoController::class, 'verificarsiyaesta']);
$app->router->post('/pedidos/eliminar/{id}', [\App\Controllers\PedidoController::class, 'eliminarpedidoporid']);
$app->router->get('/pedidos/imprimir/', [\App\Controllers\PedidoController::class, 'imprimirpedido']);
$app->router->get('/pedidos/listartpedidosweb', [\App\Controllers\PedidoController::class, 'listarpedidosfechasweb']); #agregado
$app->router->get('/pedido/listarpedidosweb', [\App\Controllers\PedidoController::class, 'listarpedidosweb']); #agregado
$app->router->post('/pedido/sesion', [\App\Controllers\PedidoController::class, 'grabarSesion']);
$app->router->post('/detail/changedolarp', [\App\Controllers\PedidoController::class, 'changedolarp']);
$app->router->post('/pedido/verutilidad', [\App\Controllers\PedidoController::class, 'verutilidad']);

#rutas de planillas
$app->router->get('/pagosplanilla/indexpagos', [\App\Controllers\PagoPlanillaController::class, 'indexpagos']);
$app->router->get('/pagosplanilla/buscarsaldoxusuario', [\App\Controllers\PagoPlanillaController::class, 'buscarsaldoxusuario']);
$app->router->post('/pagosplanilla/registrarpago', [\App\Controllers\PagoPlanillaController::class, 'registrarpago']);
$app->router->get('/pagosplanilla/indexreporte', [\App\Controllers\PagoPlanillaController::class, 'indexreporte']);
$app->router->get('/pagosplanilla/buscarreporte', [\App\Controllers\PagoPlanillaController::class, 'buscarreporte']);
$app->router->get('/pagosplanilla/mostrarmodalpagosausuarios', [\App\Controllers\PagoPlanillaController::class, 'mostrarmodalpagosausuarios']);
$app->router->post('/pagosplanilla/eliminar/{id}', [\App\Controllers\PagoPlanillaController::class, 'eliminar']);

#rutas de guias x devolucion
$app->router->get('/guiasd/index', [\App\Controllers\GuiaDevolucionController::class, 'index']);
$app->router->post('/guiasd/registrar', [\App\Controllers\GuiaDevolucionController::class, 'registrar']);
$app->router->post('/guiasd/agregaritem', [\App\Controllers\GuiaDevolucionController::class, 'agregaritem']);
$app->router->post('/guiasd/quitaritem', [\App\Controllers\GuiaDevolucionController::class, 'quitaritem']);
$app->router->get('/guiasd/imprimir/', [\App\Controllers\GuiaDevolucionController::class, 'imprimir']);
$app->router->get('/guiasd/descargarxml', [\App\Controllers\GuiaDevolucionController::class, 'descargarxml']);
$app->router->get('/guiasd/buscarGuia/{id}', [\App\Controllers\GuiaDevolucionController::class, 'consultarGuiaPorId']);
$app->router->post('/guiasd/modificar', [\App\Controllers\GuiaDevolucionController::class, 'actualizar']);
$app->router->get("/guiasd/imprimirdirecto/", [\App\Controllers\GuiaDevolucionController::class, 'imprimirdirecto']);
$app->router->post('/guiasd/EditarUno', [\App\Controllers\GuiaDevolucionController::class, 'soloItem']);
$app->router->get('/guiasd/limpiar', [\App\Controllers\GuiaDevolucionController::class, 'limpiar']);
$app->router->get('/guiasd/listarcomprastocanje', [\App\Controllers\GuiaDevolucionController::class, 'listarcomprastocanje']);
$app->router->get('/guiasd/listardetallecompratocanje', [\App\Controllers\GuiaDevolucionController::class, 'listardetallecompratocanje']);

#rutas administrativas:

#rutas de usuarios
$app->router->get('/usuarios/index', [\App\Controllers\UsuarioController::class, 'index']);
$app->router->get('/usuarios/buscar', [\App\Controllers\UsuarioController::class, 'buscar']);
$app->router->get('/usuarios/create', [\App\Controllers\UsuarioController::class, 'create']);
$app->router->get('/usuarios/edit/{id}', [\App\Controllers\UsuarioController::class, 'edit']);
$app->router->post('/usuarios/store', [\App\Controllers\UsuarioController::class, 'store']);
$app->router->post('/usuarios/update/{id}', [\App\Controllers\UsuarioController::class, 'update']);
$app->router->post('/usuarios/verificar', [\App\Controllers\UsuarioController::class, 'verificar']);

#rutas de sucursales
$app->router->get('/sucursales/index', [\App\Controllers\SucursalController::class, 'index']);
$app->router->get('/sucursales/buscar', [\App\Controllers\SucursalController::class, 'buscar']);
$app->router->get('/sucursales/create', [\App\Controllers\SucursalController::class, 'create']);
$app->router->get('/sucursales/edit/{id}', [\App\Controllers\SucursalController::class, 'edit']);
$app->router->post('/sucursales/store', [\App\Controllers\SucursalController::class, 'store']);
$app->router->post('/sucursales/update/{id}', [\App\Controllers\SucursalController::class, 'update']);
$app->router->post('/sucursales/verificar', [\App\Controllers\SucursalController::class, 'verificar']);

#rutas de transportistas
$app->router->get('/transportista/lista', [\App\Controllers\TransportistaController::class, "buscar"]);
$app->router->get('/transportista/seleccionar', [\App\Controllers\TransportistaController::class, 'seleccionadoTranportista']);
$app->router->get('/transportista/listarChoferes', [\App\Controllers\TransportistaController::class, 'listarChoferes']);
$app->router->get('/transportista/index', [\App\Controllers\TransportistaController::class, 'index']);
$app->router->get('/transportista/listar', [\App\Controllers\TransportistaController::class, 'lista']);
$app->router->get('/transportista/create', [\App\Controllers\TransportistaController::class, 'create']);
$app->router->get('/transportista/edit/{id}', [\App\Controllers\TransportistaController::class, 'edit']);
$app->router->post('/transportista/store', [\App\Controllers\TransportistaController::class, 'store']);
$app->router->post('/transportista/update/{id}', [\App\Controllers\TransportistaController::class, 'update']);

#rutas de unidades
$app->router->get('/unidades/index', [\App\Controllers\VehiculosController::class, 'index']);
$app->router->get('/unidades/lista', [\App\Controllers\VehiculosController::class, 'lista']);
$app->router->get('/unidades/create', [\App\Controllers\VehiculosController::class, 'create']);
$app->router->get('/unidades/edit/{id}', [\App\Controllers\VehiculosController::class, 'edit']);
$app->router->post('/unidades/store', [\App\Controllers\VehiculosController::class, 'store']);
$app->router->post('/unidades/update/{id}', [\App\Controllers\VehiculosController::class, 'update']);
$app->router->post('/unidades/darBaja/{id}', [\App\Controllers\VehiculosController::class, 'darBaja']);
$app->router->get('/vehiculo/listar', [\App\Controllers\VehiculosController::class, "listar"]);
$app->router->get('/vehiculo/seleccionar', [\App\Controllers\VehiculosController::class, "seleccionadoVehiculo"]);
$app->router->get('/vehiculo/listarplacas', [\App\Controllers\VehiculosController::class, "listarplacas"]);

#rutas de proveedores/remitentes
$app->router->get('/proveedor/index', [\App\Controllers\ProveedorController::class, 'index']);
$app->router->get('/proveedor/buscar', [\App\Controllers\ProveedorController::class, "buscar"]);
$app->router->get('/proveedor/create', [\App\Controllers\ProveedorController::class, 'create']);
$app->router->get('/proveedor/edit/{id}', [\App\Controllers\ProveedorController::class, 'edit']);
$app->router->post('/proveedor/store', [\App\Controllers\ProveedorController::class, 'store']);
$app->router->post('/proveedor/update/{id}', [\App\Controllers\ProveedorController::class, 'update']);
$app->router->post('/proveedor/darBaja/{id}', [\App\Controllers\ProveedorController::class, 'darBaja']);
$app->router->get('/proveedor/seleccionar', [\App\Controllers\GuiasController::class, 'seleccionadoRemitente']);
$app->router->get('/proveedor/lista', [\App\Controllers\ProveedorController::class, 'lista']);
$app->router->get('/remitente/lista', [\App\Controllers\GuiasController::class, "buscarRemitente"]);

#rutas de clientes/destinatarios
$app->router->get('/cliente/seleccionar', [\App\Controllers\ClienteController::class, 'seleccionado']);
$app->router->get('/cliente/buscar', [\App\Controllers\ClienteController::class, 'buscar']);
$app->router->get('/cliente/index', [\App\Controllers\ClienteController::class, 'index']);
$app->router->get('/cliente/create', [\App\Controllers\ClienteController::class, 'create']);
$app->router->get('/cliente/edit/{id}', [\App\Controllers\ClienteController::class, 'edit']);
$app->router->post('/cliente/store', [\App\Controllers\ClienteController::class, 'store']);
$app->router->post('/cliente/update/{id}', [\App\Controllers\ClienteController::class, 'update']);
$app->router->post('/cliente/darBaja/{id}', [\App\Controllers\ClienteController::class, 'darBaja']);
$app->router->get('/cliente/lista', [\App\Controllers\ClienteController::class, 'lista']);
$app->router->get("/vtas/indexlistaclientesfrecuentes", [\App\Controllers\VentasController::class, 'indexlistaclientesfrecuentes']);
$app->router->get("/vtas/listaclientesfrecuentes", [\App\Controllers\VentasController::class, 'listaclientesfrecuentes']);
$app->router->get('/destinatario/seleccionar', [\App\Controllers\GuiasController::class, 'seleccionadoDestinatario']);
$app->router->get('/destinatario/lista', [\App\Controllers\GuiasController::class, 'buscarDestinatario']);

#rutas de vendedores
$app->router->get('/vendedores/index', [\App\Controllers\VendedorController::class, 'index']);
$app->router->get('/vendedores/create', [\App\Controllers\VendedorController::class, 'create']);
$app->router->get('/vendedores/edit/{id}', [\App\Controllers\VendedorController::class, 'edit']);
$app->router->post('/vendedores/store', [\App\Controllers\VendedorController::class, 'store']);
$app->router->post('/vendedores/update/{id}', [\App\Controllers\VendedorController::class, 'update']);
// $app->router->post('/vendedores/darBaja/{id}', [\App\Controllers\VendedorController::class, 'darBaja']);
$app->router->get('/vendedores/lista', [\App\Controllers\VendedorController::class, 'lista']);

#rutas de direcciones
$app->router->get('/direccion/index', [\App\Controllers\DireccionGuiasController::class, 'index']);
$app->router->get('/direccion/create', [\App\Controllers\DireccionGuiasController::class, 'create']);
$app->router->get('/direccion/edit/{id}', [\App\Controllers\DireccionGuiasController::class, 'edit']);
$app->router->post('/direccion/store', [\App\Controllers\DireccionGuiasController::class, 'store']);
$app->router->post('/direccion/update/{id}', [\App\Controllers\DireccionGuiasController::class, 'update']);
$app->router->post('/direccion/darBaja/{id}', [\App\Controllers\DireccionGuiasController::class, 'darBaja']);
$app->router->get('/direccion/lista', [\App\Controllers\DireccionGuiasController::class, 'lista']);
$app->router->get('/direccion/lista1', [\App\Controllers\DireccionGuiasController::class, 'listarxremitente']);

#rutas de productos
$app->router->get('/productos/registro', [\App\Controllers\ProductoController::class, 'indexregistro']);
$app->router->get('/productos/index/{id}', [\App\Controllers\ProductoController::class, 'index']);
$app->router->get('/productos/lista', [\App\Controllers\ProductoController::class, 'buscar']);
$app->router->post('/productos/registrar', [\App\Controllers\ProductoController::class, 'registrarProducto']);
$app->router->get('/presentacion/obtener', [\App\Controllers\ProductoController::class, 'obtenerPresentacion']);
$app->router->get('/productos/listaModal', [\App\Controllers\ProductoController::class, 'buscarProductoModal']);
$app->router->get('/productos/buscarProducto/{id}', [\App\Controllers\ProductoController::class, 'consultarProductoPorID']);
$app->router->post('/productos/actualizar', [\App\Controllers\ProductoController::class, 'actualizar']);
$app->router->post('/productos/darBaja/{id}', [\App\Controllers\ProductoController::class, 'anularProducto']);

$app->router->get('/productos/create', [\App\Controllers\ProductoController::class, 'create']);
$app->router->post('/productos/consultarProductoPorID/', [\App\Controllers\ProductoController::class, 'consultarProductoPorID']);

$app->router->get('/productos/consultarvtasxprod', [\App\Controllers\ProductoController::class, 'consultarvtasxprod']);
$app->router->get('/productos/consultarcompxprod', [\App\Controllers\ProductoController::class, 'consultarcompxprod']);
$app->router->get('/productos/consultarstockxminimos', [\App\Controllers\ProductoController::class, 'consultarstockxminimos']);
$app->router->get('/productos/verdetallecombo', [\App\Controllers\ProductoController::class, 'verdetallecombo']);

#Rutas de grupos
$app->router->get('/grupos/index', [\App\Controllers\GrupoController::class, 'index']);
$app->router->get('/grupos/lista', [\App\Controllers\GrupoController::class, 'lista']);
$app->router->get('/admin/grupo/create', [\App\Controllers\GrupoController::class, 'create']);
$app->router->get('/admin/grupo/edit/{id}', [\App\Controllers\GrupoController::class, 'edit']);
$app->router->post('/admin/grupo/store', [\App\Controllers\GrupoController::class, 'store']);
$app->router->post('/admin/grupo/update/{id}', [\App\Controllers\GrupoController::class, 'update']);

#rutas de marcas
$app->router->get('/marcas/index', [\App\Controllers\MarcaController::class, 'index']);
$app->router->get('/marcas/lista', [\App\Controllers\MarcaController::class, 'lista']);
$app->router->get('/admin/marca/create', [\App\Controllers\MarcaController::class, 'create']);
$app->router->get('/admin/marca/edit/{id}', [\App\Controllers\MarcaController::class, 'edit']);
$app->router->post('/admin/marca/store', [\App\Controllers\MarcaController::class, 'store']);
$app->router->post('/admin/marca/update/{id}', [\App\Controllers\MarcaController::class, 'update']);

#rutas de categorias
$app->router->get('/categorias/index', [\App\Controllers\CategoriaController::class, 'index']);
$app->router->get('/categorias/obtenergrupoporcat', [\App\Controllers\CategoriaController::class, 'obtenergrupoporcat']);
$app->router->get('/admin/categoria/search', [\App\Controllers\CategoriaController::class, 'search']);
$app->router->get('/admin/categoria/create', [\App\Controllers\CategoriaController::class, 'create']);
$app->router->get('/admin/categoria/edit/{id}', [\App\Controllers\CategoriaController::class, 'edit']);
$app->router->post('/admin/categoria/store', [\App\Controllers\CategoriaController::class, 'store']);
$app->router->post('/admin/categoria/update/{id}', [\App\Controllers\CategoriaController::class, 'update']);
$app->router->post('/admin/categoria/eliminar/{id}', [\App\Controllers\CategoriaController::class, 'eliminar']);

#rutas de fletes
$app->router->get('/fletes/index', [\App\Controllers\FleteController::class, 'index']);
$app->router->get('/fletes/lista', [\App\Controllers\FleteController::class, 'lista']);
$app->router->get('/admin/flete/create', [\App\Controllers\FleteController::class, 'create']);
$app->router->get('/admin/flete/edit/{id}', [\App\Controllers\FleteController::class, 'edit']);
$app->router->post('/admin/flete/store', [\App\Controllers\FleteController::class, 'store']);
$app->router->post('/admin/flete/update/{id}', [\App\Controllers\FleteController::class, 'update']);

#rutas de notas de crédito compras
$app->router->get('/compras/indexnotascredito', [\App\Controllers\ComprasController::class, "indexnotascredito"]);
$app->router->post('/compras/registrarnotacredito', [\App\Controllers\ComprasController::class, "registrarnotacredito"]);
$app->router->get("/compras/listarcomprasnota", [\App\Controllers\ComprasController::class, 'listarcomprastonota']);
$app->router->get("/compras/listardetallenota", [\App\Controllers\ComprasController::class, 'listardetallenota']);

// #plan contable
// $app->router->get('/planescontables/index', [\App\Controllers\PlanContableController::class, 'index']);
// $app->router->get('/planescontables/lista', [\App\Controllers\PlanContableController::class, 'lista']);
// $app->router->get('/planescontables/create', [\App\Controllers\PlanContableController::class, 'create']);
// $app->router->get('/planescontables/edit/{id}', [\App\Controllers\PlanContableController::class, 'edit']);
// $app->router->post('/planescontables/store', [\App\Controllers\PlanContableController::class, 'store']);
// $app->router->post('/planescontables/update/{id}', [\App\Controllers\PlanContableController::class, 'update']);

// $app->router->get('/planescontables/getctabynro', [\App\Controllers\PlanContableController::class, 'getctabynro']);

// #caja y bancos
// $app->router->get('/cajaybancos/index', [\App\Controllers\CajaController::class, 'indexregistrocajaybancos']);
// $app->router->get('/cajaybancos/listaringresos', [\App\Controllers\CajaController::class, 'listaringresos']);
// $app->router->get('/cajaybancos/listaregresos', [\App\Controllers\CajaController::class, 'listaregresos']);
// $app->router->post('/cajaybancos/registraroingresolibro', [\App\Controllers\CajaController::class, 'registraringresolibro']);
// $app->router->post('/cajaybancos/registraregresolibro', [\App\Controllers\CajaController::class, 'registraregresolibro']);
// $app->router->get('/cajaybancos/indexlistar', [\App\Controllers\CajaController::class, 'indexlistarcajabanco']);
// $app->router->get('/cajaybancos/listarinformes', [\App\Controllers\CajaController::class, 'listarinformescajaybancos']);
$app->router->get('/cajas/generarticketcaja', [\App\Controllers\CajaController::class, 'generarticketcaja']);

// #Caja Efectivo
// $app->router->get('/cajayefectivo/index', [\App\Controllers\CajaController::class, 'indexregistrocajayefectivo']);
// $app->router->post('/cajayefectivo/registraringresolibro', [\App\Controllers\CajaController::class, 'registraringresolibroefectivo']);
// $app->router->post('/cajayefectivo/registraregresolibro', [\App\Controllers\CajaController::class, 'registraregresolibroefectivo']);

#ruta para combos
// $app->router->get('/combos/modalcreatedetalle', [\App\Controllers\CombosController::class, 'modalcreatedetalle']);
// $app->router->get('/productos/buscarproductoparacombo', [\App\Controllers\ProductoController::class, 'buscarproductoparacombo']);
// $app->router->post('/combos/registrarcombo', [\App\Controllers\CombosController::class, 'registrarcombo']);
