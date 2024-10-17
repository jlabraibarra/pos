<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

class PosController extends Controller
{
    function index(Request $request)
    {
        return view("pos",[
            "title" => "POS"
        ]);
    }

    function getProducts(Request $request)
    {
        $strSearch = $request->input("strSearch");
        $data = ProductModel::select();
        if($strSearch != ""){
            $data->where("code","LIKE","%$strSearch%")
                    ->orWhere("name","LIKE","%$strSearch%");
        }

        return $data->get();
    }

    function getDataProd(Request $request)
    {
        $code = $request->input("code");
        $product = ProductModel::where("code",$code)->first();
        return [
            "id" => $product->id,
            "code" => $product->code,
            "name" => $product->name,
            "depto" => $product->depto,
            "cost" => $product->price,
            "priceSale" => $product->priceSale,
            "priceSaleMin" => $product->priceSaleMin,
            "inventory" => $product->inventory,
            "unit" => $product->unit,
            "_token" => csrf_token(),
        ];
    }

    function saveProduct(Request $request)
    {
        $id = $request->input("id");
        $code = (is_null($request->input("code")) || $request->input("code") == "") ? $this->generateCode() : $request->input("code");;
        $name = $request->input("name");
        $depto = $request->input("depto");
        $cost = $request->input("cost");
        $priceSale = $request->input("priceSale");
        $priceSaleMin = $request->input("priceSaleMin");
        $inventory = (is_null($request->input("inventory")) || $request->input("inventory") == "") ? 0 : $request->input("inventory");
        $unit = $request->input("unit");

        if($id == 0){
            $product = new ProductModel();
            $check = ProductModel::where("code",$code)->first();
            if(!is_null($check)){
                return [
                    "status" => "error",
                    "msg" => "El codigo ya se encuentra registrado con el producto: '$check->name'"
                ];
            }
        }else{
            $product = ProductModel::find($id);
        }

        $product->code = $code;
        $product->name = $name;
        $product->price = $cost;
        $product->priceSale = $priceSale;
        $product->priceSaleMin = $priceSaleMin;
        $product->depto = $depto;
        $product->inventory = $inventory;
        $product->unit = $unit;
        try {
            $product->save();
            return [
                "status" => "success",
                "msg" => ($id == 0) ? "Producto guardado" : "Producto actualizado"
            ];
        } catch (\Throwable $th) {
            return [
                "status" => "error",
                "msg" => "Ocurrio un error, intentalo de nuevo mas tarde"
            ];
        }
    }

    function deleteProduct(Request $request)
    {
        $id = $request->input("id");
        $product = ProductModel::find($id);
        try {
            $product->delete();
            return [
                "status" => "success",
                "msg" => "Producto eliminado con exito"
            ];
        } catch (\Throwable $th) {
            return [
                "status" => "error",
                "msg" => "Ocurrio un error, intentalo de nuevo mas tarde"
            ];
        }
    }

    function generateCode()
    {
        $code = "";
        do {
            $code = "PROD-".rand(1,999999);
            $check = ProductModel::where("code",$code)->first();
        } while (!is_null($check));
        return $code;
    }
}
