<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Models\InventoryModel;
use App\Models\FavoriteProductModel;
use App\Models\InventoryHistoryModel;

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
        $data = ProductModel::select("t_products.*","t_inventories.stock");
        if($strSearch != ""){
            $data->where("code","LIKE","%$strSearch%")
                    ->orWhere("name","LIKE","%$strSearch%");
        }

        $data->leftJoin("t_inventories","t_inventories.id_product", "t_products.id");

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
        $stock = (is_null($request->input("inventory")) || $request->input("inventory") == "") ? 0 : $request->input("inventory");
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
        $product->inventory = ($stock != 0) ? 1 : 0;
        $product->unit = $unit;
        try {
            $product->save();
        } catch (\Throwable $th) {
            return [
                "status" => "error",
                "msg" => "Ocurrio un error, intentalo de nuevo mas tarde"
            ];
        }

        if($id == 0 && $stock != 0){
            $inventory = new InventoryModel();
            $inventory->id_product = $product->id;
            $inventory->stock = $stock;
            $inventory->save();

            $history = new InventoryHistoryModel();
            $history->id_inventory = $inventory->id;
            $history->stock = $stock;
            $history->type = 1;
            $history->save();
        }

        return [
            "status" => "success",
            "msg" => ($id == 0) ? "Producto guardado" : "Producto actualizado"
        ];
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

    /* Inventarios */
    function getInventory(Request $request)
    {
        $data = InventoryModel::select()->with("product");
        return $data->get();
    }

    /* POS */
    function getProductsDialog(Request $request)
    {
        $strSearchDialog = $request->input("strSearchDialog");
        $data = ProductModel::select("t_products.*","t_inventories.stock","t_favorite_product.id as isFavorite");
        if($strSearchDialog != ""){
            $data->where("name","LIKE","%$strSearchDialog%");
        }
        $data->leftJoin("t_inventories","t_inventories.id_product", "t_products.id");
        $data->leftJoin("t_favorite_product","t_favorite_product.id_product", "t_products.id");
        return $data->get();
    }

    function setFavorite(Request $request)
    {
        $idProduct = $request->input("idProduct");
        $check = FavoriteProductModel::where("id_product",$idProduct)->first();
        if(is_null($check)){
            $fav = new FavoriteProductModel();
            $fav->id_product = $idProduct;
            try {
                $fav->save();
                $data = [
                    "status" => "success",
                    "msg" => "Se agrego a favoritos el producto"
                ];
            } catch (\Throwable $th) {
                $data = [
                    "status" => "success",
                    "msg" => "Ocurrio un error inesperado, intentalo de nuevo mas tarde"
                ];
            }
        }else{
            $fav = FavoriteProductModel::find($check->id);
            try {
                $fav->delete();
                $data = [
                    "status" => "success",
                    "msg" => "Se removio de favoritos el producto"
                ];
            } catch (\Throwable $th) {
                $data = [
                    "status" => "success",
                    "msg" => "Ocurrio un error inesperado, intentalo de nuevo mas tarde"
                ];
            }
        }
        return $data;
    }

    function getFavorites(Request $request)
    {
        $data = FavoriteProductModel::with("product")->get();
        return $data;
    }
}
