<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|    example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|    https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|    $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|    $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|    $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:    my-controller/index    -> my_controller/index
|        my-controller/my-method    -> my_controller/my_method
 */
$route['default_controller'] = 'login';
$route['mis-puntos/asignar-puntos/(:num)'] = 'user/puntosAsignar/$1';
$route['mis-puntos'] = 'user/puntos';
$route['precompras'] = 'user/CargarCompras';
$route['modificar_compras'] = 'user/modificar_compras';
$route['cuenta-usuario'] = 'user/cuenta';
$route['cuenta-administrador'] = 'ProfileAdmin/cuenta';
$route['correo'] = 'user/email';
$route['cambiar-correo'] = 'ProfileAdmin/ventanaEmail';
$route['cambiar-contrasena'] = 'ProfileAdmin/contrasena';
$route['calculadora'] = 'Login/calculadora';
$route['rastreo-paquetes'] = 'user/paquetes';
$route['rastreo-paquetes/update-prealerta'] = 'user/updatePrealertaProcess';
$route['rastreo-paquetes/eliminar-prealerta'] = 'user/deletePrealertaProcess';
$route['clave'] = 'user/contrasena';
$route['CambiarClave'] = 'user/CambiarContraseña';
$route['cambiar-clave-administrador'] = 'ProfileAdmin/CambiarContraseña';
$route['estadosCompra'] = 'user/CargarEstadosCompra';
$route['inicio'] = 'Admin/index';
$route['bandeja-de-salida'] = 'Admin/bandejaSalida';
$route['cargar-clientes-guia'] = 'Admin/ClienPaqueDespachados';
$route['bandeja-de-salida/agrupar-paquetes/(:num)'] = 'Admin/enviarCorreoClientesCali/$1';
$route['bandeja-de-salida/envio-de-guia/(:num)'] = 'Admin/enviarCorreoClientesGuia/$1';
$route['editar-clientes'] = 'Admin/editarClientes';
$route['crear-clientes'] = 'Admin/CrearCliente';
$route['informacion-clientes'] = 'Admin/clientesInfo';
$route['informacion-clientes/(:num)'] = 'Admin/clientesInfo/$1';
$route['imprimir-informacion'] = 'Admin/informacionCliente';
$route['mas-informacio-envio-entregado'] = 'Admin/mostrar_mas_info_entregados';
$route['mis-pedidos'] = 'user';
$route['tracking'] = 'Admin';
$route['tracking/(:num)'] = 'Admin/index/$1';
$route['admin/ingreso-paquetes'] = 'IngresoPaquetes/ingresoPaquetes';
$route['mis-ordenes'] = 'Ordenes/ordenesLst';
$route['mis-ordenes/pagar-con-comprobante/(:num)'] = 'Ordenes/pagarOrdenCm/$1';
$route['mis-ordenes/pagar-en-linea/(:num)'] = 'Ordenes/pagarOrdenTc/$1';
$route['ordenes-de-compra'] = 'OrdenesAdmin/ordenesLst';
$route['ordenes-de-compra/pagadas'] = 'OrdenesAdmin/ordenesPagadasLst';
$route['ordenes-de-compra/pagadas/(:num)'] = 'OrdenesAdmin/ordenesPagadasLst/$1';
/* $route['ordenes-de-compra/anuladas'] = 'OrdenesAdmin/ordenesAnuladasLst'; */
$route['ordenes-de-compra/verificar/(:num)'] = 'OrdenesAdmin/verificar/$1';
$route['ordenes-de-compra/rechazar/(:num)'] = 'OrdenesAdmin/rechazar/$1';
$route['mis-ordenes/reenviar-comprobante/(:num)'] = 'Ordenes/reenviarOrden/$1';
$route['ordenes-de-compra/ver/(:num)'] = 'OrdenesAdmin/verOrden/$1';
$route['mis-ordenes/ver/(:num)'] = 'Ordenes/verOrden/$1';
$route['ordenes-de-compra/pagar-orden/(:num)'] = 'OrdenesAdmin/pagarOrden/$1';
$route['mis-ordenes/seleccionar-metodo-de-pago/(:num)'] = 'Ordenes/paymentMethod/$1';
$route['ordenes-de-compra/imprimir-prueba/(:num)'] = 'OrdenesAdmin/imprimirPrueba/$1';
$route['ordenes-de-compra/abandonar-orden/(:num)'] = 'OrdenesAdmin/abandonarOrden/$1';
$route['ordenes-de-compra/eliminar-orden/(:num)'] = 'OrdenesAdmin/eliminarOrden/$1';
$route['tracking/ver/(:num)'] = 'Admin/mostrar_mas_info/$1';
$route['administrar-usuarios'] = 'AdminUsers';
$route['inventario-miami'] = 'InventarioMiami';
$route['ingreso-bodega'] = 'InventarioMiami/ingresoPaquetes';
$route['reenviar-orden/(:num)'] = 'OrdenesAdmin/reenviarOrden/$1';
$route['reportes-y-metricas'] = 'Dashboard';
$route['api/paquetes/(:num)'] = 'Api/paquetes/$1';
$route['api/ordenes/(:num)'] = 'Api/ordenes/$1';
$route['api/orden/(:num)'] = 'Api/orden/$1';

$route['cupones'] = 'Cupones';
$route['cupon-process'] = 'CuponesUser/cuponProcess';

$route['ingreso-costos'] = 'IngresoCostos';
$route['ingreso-costos/(:num)'] = 'IngresoCostos/index/$1';
$route['costo-process'] = 'IngresoCostos/costoProcess';
$route['ingreso-costos/pagadas'] = 'IngresoCostos/pagadas';
$route['ingreso-costos/pagadas/(:num)'] = 'IngresoCostos/pagadas/$1';


$route['api-agencia/crear-prealerta'] = 'ApiAgencia/CrearPrealerta';
$route['api-agencia/update-prealerta'] = 'ApiAgencia/updatePrealerta';
$route['api-agencia/set-articulo-vuelo'] = 'ApiAgencia/setArticuloVuelo';
$route['api-agencia/set-articulo-estado'] = 'ApiAgencia/setArticuloEstado';
$route['api-agencia/set-articulo-referer-id'] = 'ApiAgencia/setArticuloRefererId';
$route['api-agencia/punteo-articulo'] = 'ApiAgencia/punteoArticulo';


$route['pago-en-linea/pse/(:num)'] = 'Epayco/pse/$1';
$route['pago-en-linea/cards/(:num)'] = 'Epayco/cards/$1';
$route['pago-en-linea/pay-card/(:num)/(:num)'] = 'Epayco/payCard/$1/$2';
$route['pago-en-linea/card-transaction/(:num)'] = 'Epayco/cardTransaction/$1';


$route['404_override'] = '';
$route['translate_uri_dashes'] = false;
