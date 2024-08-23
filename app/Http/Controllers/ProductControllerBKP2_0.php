<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductListingResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use HelperApi;

class ProductController extends Controller
{
    public function index()
    {
        try {
            $products = ProductListingResource::collection(Product::all());
            return response()->json([
                'error' => false,
                'response' => $products
            ], 200);
        } catch (\Exception $e) {
            HelperApi::logError($e);
            return HelperApi::errorResponse('Não foi possível executar a ação.', 500);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), Product::$rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        try {
            $product = Product::create($request->all());
            return response()->json([
                'error' => false,
                'response' => $product,
            ], 201);
        } catch (\Exception $e) {
            HelperApi::logError($e);
            return HelperApi::errorResponse('Não foi possível executar a ação.', 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), Product::$rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());
            return response()->json([
                'error' => false,
                'response' => $product,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return HelperApi::errorResponse('Registro não encontrado.', 404);
        } catch (\Exception $e) {
            HelperApi::logError($e);
            return HelperApi::errorResponse('Não foi possível executar a ação.', 500);
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return response()->json([
                'error' => false,
                'message' => 'Produto deletado com sucesso.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return HelperApi::errorResponse('Registro não encontrado.', 404);
        } catch (\Exception $e) {
            HelperApi::logError($e);
            return HelperApi::errorResponse('Não foi possível executar a ação.', 500);
        }
    }
}