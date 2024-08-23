<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ProductListingResource;
use App\Http\Interfaces\ProductControllerInterface;

class ProductController extends Controller implements ProductControllerInterface
{
    use ApiResponse;

    public function index()
    {
        try {
            $products = ProductListingResource::collection(Product::all());
            return $this->responseSuccess($products);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), Product::$rules);

        if ($validator->fails()) {
            return $this->responseErrorValidation($validator->errors());
        }

        try {
            $product = Product::create($request->all());
            return $this->responseSuccess($product, 201);
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), Product::$rules);

        if ($validator->fails()) {
            return $this->responseErrorValidation($validator->errors());
        }
        
        if ($validator->fails()) {
            return $this->responseErrorValidation($validator->errors());
        }

        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());
            return $this->responseSuccess($product);
        } catch (ModelNotFoundException $e) {
            return $this->responseRegisterNotFound('Registro não encontrado.');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function delete($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            return $this->responseSuccess('Produto deletado com sucesso.');
        } catch (ModelNotFoundException $e) {
            return $this->responseRegisterNotFound('Registro não encontrado.');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}