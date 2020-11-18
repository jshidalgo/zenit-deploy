<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_order;
use App\Models\Provider;
use App\Models\Purchase;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

/**
 * Class PurchaseController
 * @package App\Http\Controllers
 */
class PurchaseController extends Controller
{
    /**
     * Funcion que se encarga de registrar una nueva compra
     * con la compra se cran los productos incluidos
     * El formato de fecha obtenido es aaaa-mm-dd
     * @param Request $request
     * @return RedirectResponse
     */
    public function create_purchase(Request $request)
    {
        //informacion recibida sin incluir los productos
        $dat = $request->get('dat');
        //informacion recibidad de los producto
        $productData = $request->get('product');

        //existencia de la compra y el proveedor
        $exist_purchase = Purchase::where('cod', '=', $dat['cod'])->get();
        $exist_provider = Provider::where('id', '=', $dat['provider'])->get();

        //verificar que no exista la compra
        if (isset($exist_purchase) &&  $exist_purchase->count() == 0) {
            //verificar que exista el proveedor
            if (!(isset($exist_provider) &&  $exist_provider->count() == 0)) {
                //crea la compra
                $purchase = new Purchase();
                $purchase->cod = $dat['cod'];
                $purchase->cost = $dat['cost'];
                $purchase->status = $dat['status'];
                $purchase->concept = $dat['concept'];
                $purchase->date = $dat['date'];
                $purchase->provider_id = $dat['provider'];
                $purchase->save();
                //crear el producto

                //lista de model de productos recibidos y sus costos
                $productos = $this::obtenerProductos($productData, $purchase->cod);

                for ($i = 1; $i <= count($productos); $i++){
                    //guardar los productos
                    $product = $productos[$i]['modelo'];
                    $product->save();

                    //crear las ordenes
                    $product_order = new Product_order();
                    $product_order->purchase_id = $purchase->id;
                    $product_order->quantity = $product->units_available;
                    $product_order->product_id = $product->id;
                    $product_order->cost = ($product->units_available * $productos[$i]['costo']);
                    //almacenar la orden
                    $product_order->save();
                }
                $request->session()->flash('check_msg', 'La compra se registro con éxito');
            } else {
                $request->session()->flash('fail_msg', 'El proveedor seleccionado no existe');
            }
        } else {
            $request->session()->flash('fail_msg', 'La compra con el código ' . strval($dat['cod']) . ' ingresado ya existe');
        }
        return redirect()->route('view_purchase');
    }


    /**
     * funcion que permite obtener los medelos de cada uno de los productos recibidos
     * @param $productos - lista de productos recibidos
     * @param $codPurchase - id de la compra
     * @return array - array que contiene el model de producto y el costo del mismo
     */
    public function obtenerProductos($productos, $codPurchase){

        $productModel = array();
        //obteniedo la informacion de cada producto
        //instancia de un producto
        $product = new Product();
        //costo
        $productCost = 0;
        //posicion que representa el data de cada producto
        $posicion_data_producto = 0;
        $cont = 1;

        foreach ($productos as $value){
            if ($posicion_data_producto == 0){ //nombre producto

                $codProduct = $this->obtenerIdProducto($codPurchase, $cont);
                //crear instancia de producto
                $product = new Product();
                $product->code = $codProduct;
                $product->name = $value; //nombre del producto

                $posicion_data_producto++;
            }
            elseif ($posicion_data_producto == 1){ // costo producto
                //obtener costo
                $productCost = $value;

                $posicion_data_producto++;
            }
            elseif ($posicion_data_producto == 2){ //cantidad
                //almacenar cantidad
                $product->units_available = $value;
                //establecer valor unitario en cero
                $product->sale_price = 0;

                $productModel[$cont] = array(
                    'modelo' => $product,
                    'costo' => $productCost
                );

                $posicion_data_producto=0;
                $cont++;
            }
        }
        return $productModel;
    }

    /**
     * funcion que permite obtener un identificador para un producto a partir de un codigo de compra
     *
     * @param $pruchaseCod - codigo de la compra
     * @param $productCount - contador de compras
     * @return string codigo de producto asignado
     */
    public function obtenerIdProducto($pruchaseCod, $productCount)
    {
        do {
            $codProduct = $pruchaseCod . "-" . strval($productCount);
            $exist_product = Product::where('code', '=', strval($codProduct))->get();
            $productCount += 1;
        } while (!(isset($exist_product) &&  $exist_product->count() == 0));

        return $codProduct;
    }


    /**
     * Función que busca una compra a partir de una palabra
     *
     * @param Request $request
     * @return Factory|View
     */
    public function show_view_purchase(Request $request)
    {
        $dat = $request->get('dat');
        $data['fail_msg'] = Session::get('fail_msg');
        $data['check_msg'] = Session::get('check_msg');
        if (isset($dat) && !empty($dat)) {
            $word = $dat['search'];
            $purchase['purchase'] = DB::table('purchases')
                ->join('providers', 'providers.id', '=', 'purchases.provider_id')
                ->select('purchases.*', 'providers.name as provider_name')
                ->where('purchases.cod','like','%'.$word.'%')
                ->orWhere('purchases.cost','like','%'.$word.'%')
                ->orWhere('purchases.concept','like','%'.$word.'%')
                ->orWhere('providers.name','like','%'.$word.'%')
                ->get();
        } else {
            //datos de todos las compras
            $purchase['purchase'] = DB::table('purchases')
                ->join('providers', 'providers.id', '=', 'purchases.provider_id')
                ->select('purchases.*', 'providers.name as provider_name')
                ->where('purchases.deleted_at','=',null)
                ->get();

        }
        $provider['provider'] = Provider::all();
        //datos a enviar
        $data['data'] = [$purchase['purchase'], $provider['provider']];
        return view('purchase',$data);
    }

    /**
     * Función que obtiene una compra por su identificador
     * @param $id - id de la compra
     * @return Collection
     */
    public function get_purchase($id){

        return DB::table('purchases')
            ->join('providers', 'providers.id', '=', 'purchases.provider_id')
            ->join('product_orders', 'purchase_id', '=', 'purchases.id')
            ->join('products', 'products.id', '=', 'product_orders.product_id')
            ->where('purchases.id','=', $id)
            ->select('purchases.id', 'purchases.cod', 'purchases.provider_id', 'purchases.cost', 'purchases.status', 'purchases.concept', 'purchases.date',
                'providers.name as provider_name', 'product_orders.product_id','product_orders.quantity','product_orders.cost as all_product_cost',
                'products.name as product_name')
            ->get();

    }

    /**
     * Función que edita un cliente
     * @param Request $request
     * @return RedirectResponse
     */
    public function edit_purchase(Request $request){
         $dat = $request->get('dat');

         $purchase = DB::table('purchases')
             ->where('purchases.id','=',$dat['id'])
             ->get()->first();

         $exist_purchase = Purchase::where('cod','=',$dat['cod'])->get();
         $exist_provider = Provider::where('id', '=',$dat['provider'])->get();

         $flag_cod = true;

         //Verifica si el codigo se encuentra registrado
         if($exist_purchase->count() == 1 && ($exist_purchase[0]->id != $purchase->id)) {
             $flag_cod=false;
             $request->session()->flash('fail_msg','Ya existe una compra con este código');
         }

         $flag_provider = true;
         if ($exist_provider->count() != 1){
             $flag_provider = false;
             $request->session()->flash('fail_msg', 'El proveedor al cual está intentado asignar la compra no existe');
         }

         if($flag_cod && $flag_provider){
             $exist_purchase = Purchase::find($dat['id']);

             $exist_purchase->provider_id = $dat['provider'];
             $exist_purchase->date = $dat['date'];
             $exist_purchase->cod = $dat['cod'];
             $exist_purchase->cost = $dat['cost'];
             $exist_purchase->status = $dat['status'];
             $exist_purchase->concept = $dat['concept'];

             $exist_purchase->save();

             $request->session()->flash('check_msg','Se actualizaron los datos de la compra con éxito');
         }
        return redirect()->route('view_purchase');
    }

    /**
     * Función que elimina las compras seleccionados
     * @param Request $request
     * @return int
     */
    public function delete_purchase(Request $request){

        if(sizeof($request->selected) > 0){
            foreach ($request->selected as $aux){
                $purchase = Purchase::find($aux);
                if(!empty($purchase)){
                    $orders = Product_order::where('purchase_id','=',$purchase->id)->get();
                    foreach ($orders as $order){
                        $order->delete();
                    }
                    $purchase->delete();
                }
            }
            return 1;
        }
        return 0;
    }
}
