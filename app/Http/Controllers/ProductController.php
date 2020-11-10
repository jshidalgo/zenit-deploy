<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    /**
     * metodo que permite crear un producto sin ningun tipo de trazabilidad
     * No recomendable
     */
    public function create_product(Request $request)
    {
        //informacion recibida
        $dat = $request->get('dat');
        //producto
        $exist_product = Product::where('code', '=', $dat['cod'])->get();
        //verificando que no exista el producto
        if (isset($exist_product) &&  $exist_product->count() == 0) {
            $product = new Product();
            $product->code = $dat['cod'];
            $product->name = $dat['name'];
            $product->sale_price = $dat['price'];
            $product->units_available = $dat['amount'];
            $product->description = $dat['description'];
            $product->save();
            $request->session()->flash('check_msg', 'El registro del producto se realizo con éxito');
        } else {
            $request->session()->flash('fail_msg', 'El código de producto ingresado ya existe');
        }

        return redirect()->route('view_product');
    }

    /**
     * Función que busca empleado a partir de una palabra
     * Esta función solo tiene en cuenta la cédula, nombre y los apellidos
     * @param Request $request
     * @param $word
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_view_product(Request $request)
    {
        $dat = $request->get('dat');
        $product['fail_msg'] = Session::get('fail_msg');
        $product['check_msg'] = Session::get('check_msg');
        if (isset($dat) && !empty($dat)) {
            $word = $dat['search'];
            $product['product'] = DB::table('products')
                ->leftJoin('product_orders', function ($join) {
                    $join->on('product_orders.product_id', '=', 'products.id')
                        ->join('purchases', 'purchases.id', '=', 'product_orders.purchase_id')
                        ->join('providers', 'providers.id', '=', 'purchases.provider_id');
                })
                ->Where('products.code','like','%'.$word.'%')
                ->orWhere('products.name','like','%'.$word.'%')
                ->orWhere('products.units_available','like','%'.$word.'%')
                ->orWhere('products.sale_price','like','%'.$word.'%')
                ->select('purchases.id as purchase_id', 'providers.name as provider_name', 'product_orders.product_id', 'products.*')
                ->get();
            // foreach ($product as $key => $value) {
            //         echo $key;
            //         echo "=";
            //         echo $value;
            //     }

        } else {
            $product['product'] = DB::table('products')
                ->leftJoin('product_orders', function ($join) {
                    $join->on('product_orders.product_id', '=', 'products.id')
                        ->join('purchases', 'purchases.id', '=', 'product_orders.purchase_id')
                        ->join('providers', 'providers.id', '=', 'purchases.provider_id');
                })
                ->where('products.deleted_at', '=', null)
                ->select('purchases.id as purchase_id', 'providers.name as provider_name', 'product_orders.product_id', 'products.*')
                ->get();
            // foreach ($product as $key => $value) {
            //     echo $key;
            //     echo "=";
            //     echo $value;
            // }
        }
        return view("product", $product);
    }

    /**
     * Función que obtiene un producto por medio de su id
     * @param $cod
     * @return array
     */
    public function get_product($id)
    {
        $product = DB::table('products')
            ->leftJoin('product_orders', function ($join) {
                $join->on('product_orders.product_id', '=', 'products.id')
                    ->join('purchases', 'purchases.id', '=', 'product_orders.purchase_id')
                    ->join('providers', 'providers.id', '=', 'purchases.provider_id');
            })
            ->where('products.id', '=', $id)
            ->where('products.deleted_at', '=', null)
            ->select('purchases.id as purchase_id', 'providers.name as provider_name', 'product_orders.product_id', 'products.*')
            ->get();

        return $product;
    }

    /**
     * Función que edita un producto
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit_product(Request $request)
    {
        $dat = $request->get('dat');

        $product = DB::table('products')
            ->where('products.id','=',$dat['id'])
            ->get()->first();

        $exist_product = Product::where('code','=',$dat['cod'])->get();

        $flag_cod = true;

        //Verifica si el codigo se encuentra registrado
        if($exist_product->count() == 1) {
            if($exist_product[0]->id != $product->id){
                $flag_cod=false;
                $request->session()->flash('fail_msg','Ya existe un producto con este código');
            }
        }

        if($flag_cod){
            $exist_product = Product::find($dat['id']);
            $exist_product->code = $dat['cod'];
            $exist_product->name = $dat['name'];
            $exist_product->sale_price = $dat['price'];
            $exist_product->description = $dat['description'];
            $exist_product->units_available = $dat['amount'];
            $exist_product->save();

            $request->session()->flash('check_msg','Se actualizaron los datos del producto con éxito');
        }
        return redirect()->route('view_product');
    }

    /**
     * Función que elimina los productos seleccionados
     * @param Request $request
     * @return int
     */
    public function delete_product(Request $request)
    {

        if (sizeof($request->selected) > 0) {
            foreach ($request->selected as $aux) {
                $product = Product::find($aux);
                if (!empty($product)) {
                    $product_order = Product_order::where('product_id', '=', $product->id)->get();
                    foreach ($product_order as $order) {
                        $order->delete();
                    }
                    $product->delete();
                }
            }
            return 1;
        }
        return 0;
    }
}
