<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Interfaces\ProductControllerInterface;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProductListingResource;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller implements ProductControllerInterface
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

            // Salvar log
            Log::error('Não foi possível executar a ação.', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => true,
                'message' => 'Não foi possível executar a ação.'
            ], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), Product::$rules);
            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'response' => $validator->errors()
                ], 422);
            } else {
                $product = Product::create($request->all());
                return response()->json([
                    'error' => false,
                    'response' => $product,
                ], 201);
            }
        } catch (\Exception $e) {

            // Salvar log
            Log::error('Não foi possível executar a ação.', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => true,
                'message' => 'Não foi possível executar a ação.'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), Product::$rules);
            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'response' => $validator->errors()
                ], 422);
            } else {
                $product = Product::findOrFail($id);
                $product->update($request->all());
                return response()->json([
                    'error' => false,
                    'response' => $product,
                ], 200);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response([
                'error' => true,
                'message' => 'Registro não encontrado.'
            ], 404);
        } catch (\Exception $e) {

            // Salvar log
            Log::error('Não foi possível executar a ação.', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => true,
                'message' => 'Não foi possível executar a ação.'
            ], 500);
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
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response([
                'status' => 'ERROR',
                'error' => 'Registro não encontrado.'
            ], 404);
        } catch (\Exception $e) {

            // Salvar log
            Log::error('Não foi possível executar a ação.', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => true,
                'message' => 'Não foi possível executar a ação.'
            ], 500);
        }
    }
}
