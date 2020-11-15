<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    //constantes de acceso a BD
    const PRODUCT_ORDERS_PRODUCT_ID = 'product_orders.product_id';
    const PRODUCTS_ID = 'products.id';
    const PURCHASES_ID = 'purchases.id';
    const PRODUCT_ORDERS_PURCHASE_ID = 'product_orders.purchase_id';
    const PROVIDERS_ID = 'providers.id';
    const PURCHASES_PROVIDER_ID = 'purchases.provider_id';

    /**
     * metodo que permite crear un producto sin ningun tipo de trazabilidad
     * No recomendable
     * @param Request $request
     * @return RedirectResponse
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
     * @return Application|Factory|View
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
                    $join->on(ProductController::PRODUCT_ORDERS_PRODUCT_ID, '=', ProductController::PRODUCTS_ID)
                        ->join('purchases', ProductController::PURCHASES_ID, '=', ProductController::PRODUCT_ORDERS_PURCHASE_ID)
                        ->join('providers', ProductController::PROVIDERS_ID, '=', ProductController::PURCHASES_PROVIDER_ID);
                })
                ->Where('products.code','like','%'.$word.'%')
                ->orWhere('products.name','like','%'.$word.'%')
                ->orWhere('products.units_available','like','%'.$word.'%')
                ->orWhere('products.sale_price','like','%'.$word.'%')
                ->select('purchases.id as purchase_id', 'providers.name as provider_name', ProductController::PRODUCT_ORDERS_PRODUCT_ID, 'products.*')
                ->get();

        } else {
            $product['product'] = DB::table('products')
                ->leftJoin('product_orders', function ($join) {
                    $join->on(ProductController::PRODUCT_ORDERS_PRODUCT_ID, '=', ProductController::PRODUCTS_ID)
                        ->join('purchases', ProductController::PURCHASES_ID, '=', ProductController::PRODUCT_ORDERS_PURCHASE_ID)
                        ->join('providers', ProductController::PROVIDERS_ID, '=', ProductController::PURCHASES_PROVIDER_ID);
                })
                ->where('products.deleted_at', '=', null)
                ->select('purchases.id as purchase_id', 'providers.name as provider_name', ProductController::PRODUCT_ORDERS_PRODUCT_ID, 'products.*')
                ->get();

        }
        return view("product", $product);
    }

    /**
     * Función que obtiene un producto por medio de su id
     * @param $id
     * @return Collection
     */
    public function get_product($id)
    {
        return DB::table('products')
            ->leftJoin('product_orders', function ($join) {
                $join->on(ProductController::PRODUCT_ORDERS_PRODUCT_ID, '=', ProductController::PRODUCTS_ID)
                    ->join('purchases', ProductController::PURCHASES_ID, '=', ProductController::PRODUCT_ORDERS_PURCHASE_ID)
                    ->join('providers', ProductController::PROVIDERS_ID, '=', ProductController::PURCHASES_PROVIDER_ID);
            })
            ->where(ProductController::PRODUCTS_ID, '=', $id)
            ->where('products.deleted_at', '=', null)
            ->select('purchases.id as purchase_id', 'providers.name as provider_name', ProductController::PRODUCT_ORDERS_PRODUCT_ID,
                'products.code', 'products.description','products.id','products.name','products.sale_price','products.units_available')
            ->get();
    }

    /**
     * Función que edita un producto
     * @param Request $request
     * @return RedirectResponse
     */
    public function edit_product(Request $request)
    {
        $dat = $request->get('dat');

        $product = DB::table('products')
            ->where(ProductController::PRODUCTS_ID,'=',$dat['id'])
            ->get()->first();

        $exist_product = Product::where('code','=',$dat['cod'])->get();

        $flag_cod = true;

        //Verifica si el codigo se encuentra registrado
        if($exist_product->count() == 1 && ($exist_product[0]->id != $product->id)) {
            $flag_cod=false;
            $request->session()->flash('fail_msg','Ya existe un producto con este código');
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
