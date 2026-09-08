<?php

namespace App\Controllers;

use App\Middlewares\AuthAdminMiddleware;
use App\Models\Marca;
use Core\Http\Request;
use Core\Routing\Controller;
use Valitron\Validator;

class MarcaController extends Controller
{
   private $marca;
   function __construct()
   {
      $middlweare = new AuthAdminMiddleware(['index']);
      $this->registerMiddleware($middlweare);
      $this->marca = new Marca();
   }
   function index()
   {
      return \view('admin/marcas/index', ['titulo' => 'Marcas']);
   }
   function lista(Request $request)
   {
      $lista = $this->marca->listar($request->get('cbuscar'));
      return view('admin/marcas/listamarcas', ['lista' => $lista]);
   }
   function create()
   {
      $titulo = 'Registrar Marca';
      return view('admin/marcas/create', ['titulo' => $titulo, 'modo' => 'N', 'id' => 0]);
   }
   function edit($id)
   {
      $titulo = 'Editar Marca';
      $marca = $this->marca->buscarid($id);
      return view('admin/marcas/create', ['titulo' => $titulo, 'modo' => 'A', 'lista' => $marca, 'id' => $id]);
   }
   function store(Request $request)
   {
      if (!$this->marca->buscarnombre($request->get('txtnombre'), 0)) {
         $data = [
            'message' => 'Errores de validacion',
            'errors' => array('txtnombre' => array('El nombre de Marca ya está registrado'))
         ];
         return response()->json($data, 422);
      }
      $validator = new Validator($request->getBody());
      $validator->rule('required', 'txtnombre')->message('El Nombre de Marca es Obligatorio');
      $validator->rule('lengthMax', 'txtnombre', 50)->message('La Longitud Máxima es 50 caracteres');
      $validator->rule('lengthMin', 'txtnombre', 2)->message('La Longitud Mínima es 2 caracteres');
      $validator->labels([
         'nombre' => 'txtnombre'
      ]);
      if (!$validator->validate()) {
         $data = [
            'message' => 'Errores de validacion',
            'errors' => $validator->errors()
         ];
         return response()->json($data, 422);
      }
      // continuamos
      try {
         $marca = new Marca();
         $marca->nombre = $request->get('txtnombre');
         if ($marca->save()) {
            return response()->json(['message' => 'Marca registrada correctamente'], 201); // Created
         } else {
            return response()->json(['message' => 'Error al Registrar marca '], 400); // Created
         }
      } catch (\Exception $error) {
         return response()->json(['message' => 'Ocurrió un error' . $error], 500);
      }
   }
   public function update($id, Request $request)
   {
      $validator = new Validator($request->getBody());
      $validator->rule('required', 'txtnombre')->message('El Nombre de Marca es obligatorio');
      $validator->labels([
         'nombre' => 'txtnombre'
      ]);
      if (!$validator->validate()) {
         $data = [
            'message' => 'Errores de validacion',
            'errors' => $validator->errors()
         ];
         return response()->json($data, 422);
      }
      try {
         $marca = new Marca();
         $idmarca = $marca->buscarid($id);
         $marca->nombre = $request->get('txtnombre');
         if ($marca->update($idmarca['idmar'])) {
            return response()->json(['message' => 'Actualizado satisfactoriamente'], 200);
         } else {
            return response()->json(['message' => 'Error al actualizar'], 400);
         }
      } catch (\Exception $error) {
         return response()->json(['message' => 'Ocurrió un error' . $error], 500);
      }
   }
}
