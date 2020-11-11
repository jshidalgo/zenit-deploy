<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product_order;
use App\Models\Provider;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PurchaseController extends Controller
{
    /**
     * Funcion que se encarga de registrar una nueva compra
     * con la compra se cran los productos incluidos
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_purchase(Request $request)
    {
        //1 registrar la compra
        //2 crear el produto
        //crear la orden de compra y asociar

        //formato fecha aaa-mm-dd
        //informacion recibida sin incluir los productos
        $dat = $request->get('dat');
        //informacion recibidad de los producto
        $productData = $request->get('product');

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

                //variable que permite conocer que dato del producto se obtiene
                //1= nombre del producto
                //2= costo unitario de compra del producto
                //3= cantidad de producto
                $auxProducto = 1;
                $countProduct = 1;
                $product = new Product();
                //costo del producto
                $productCost = 0;

                foreach ($productData as $key => $value) {
                    if ($auxProducto == 1) {
                        echo "haciendo registro del  producto";
                        //codigo genara para asiganar al producto

                        $codProduct = $this->obtenerIdProducto($purchase->cod, $countProduct);
                        //crear instancia de producto
                        $product = new Product();
                        $product->code = $codProduct;
                        $product->name = $value;
                        //incremento de aux
                        $auxProducto += 1;
                        //incremento de contador
                        $countProduct += 1;
                    } elseif ($auxProducto == 2) {
                        //obtener costo
                        $productCost = $value;
                        //aumentar auxiliar
                        $auxProducto += 1;
                    } elseif ($auxProducto == 3) {
                        //almacenar cantidad
                        $product->units_available = $value;
                        //establecer valor unitario en cero
                        $product->sale_price = 0;
                        //guardar
                        $product->save();
                        //crear y guardar pedido de producto

                        //orden
                        $product_order = new Product_order();
                        $product_order->purchase_id = $purchase->id;
                        $product_order->quantity = $product->units_available;
                        $product_order->product_id = $product->id;
                        $product_order->cost = ($product->units_available * $productCost);
                        //almacenar orden
                        $product_order->save();

                        //restablear aux
                        $auxProducto = 1;
                    }
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
     * metodo que permite obtener un identificador para un producto a partir de un codigo de compra
     *
     *@param string $purchaseCod codigo de compra
     *@param integer $productCod numero de producto
     *@return string codigo de producto asignado
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
     * @param $word
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
//            dd($purchase);
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
     * @param $cc
     * @return array
     */
    public function get_purchase($id){
        $purchase = DB::table('purchases')
            ->join('providers', 'providers.id', '=', 'purchases.provider_id')
            ->select('purchases.*', 'providers.name as provider_name')
            ->where('purchases.id','=', $id)
            ->get();
        // $customer = Customer::where('id','=',id)->first();
        // $phone = Customer_phone::where('customer_id','=',$customer->id)->first();
        // $result = [$customer, $phone];

        return $purchase;
    }

    /**
     * Función que edita un cliente
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit_purchase(Request $request){
        // $dat = $request->get('dat');

        // $customer = DB::table('customers')
        //     ->join('customer_phones','customers.id','=','customer_phones.customer_id')
        //     ->where('customers.id','=',$dat['id'])
        //     ->get()->first();

        // $exist_customer = Customer::where('identification_card','=',$dat['cc'])->get();
        // $exist_phone = Customer_phone::where('number','=',$dat['phone'])->get();
        // $exist_mail = Customer::where('mail','=',$dat['mail'])->get();

        // $flag_cc = true;

        // //Verifica si la cédula ya se encuentra registrada
        // if($exist_customer->count() == 1) {
        //     if($exist_customer[0]->id != $customer->id){
        //         $flag_cc=false;
        //         $request->session()->flash('fail_msg','Ya existe un cliente con esta cédula');
        //     }
        // }

        // $flag_phone = true;
        // // Verifica si el telefono ingresado ya se encuentra registrado
        // if($exist_customer->count() >= 1){
        //     foreach ($exist_phone as $aux){
        //         if($aux->customer_id != $customer->id){
        //             $flag_phone=false;
        //             $request->session()->flash('fail_msg','Un cliente ya tiene este número de teléfono');
        //         }
        //     }

        // }

        // $flag_mail=true;
        // //Verifica si el mail ingresado ya se encuentra registrado
        // if($exist_mail->count() == 1){
        //     if($exist_mail[0]->id != $customer->id){
        //         $flag_mail=false;
        //         $request->session()->flash('fail_msg','Un cliente ya tiene este correo electrónico');

        //     }
        // }

        // if($flag_cc && $flag_phone && $flag_mail){
        //     $exist_customer = Customer::find($dat['id']);
        //     $exist_customer->identification_card = $dat['cc'];
        //     $exist_customer->name = $dat['name'];
        //     $exist_customer->last_name = $dat['last_name'];
        //     $exist_customer->address = $dat['address'];
        //     $exist_customer->mail = $dat['mail'];
        //     $exist_customer->save();

        //     $exist_phone = Customer_phone::where('customer_id','=',$dat['id'])->first();
        //     $exist_phone->number = $dat['phone'];
        //     $exist_phone->save();
        //     $request->session()->flash('check_msg','Se actualizaron los datos del cliente con éxito');
        // }
        return redirect()->route('view_customer');

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
