<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait ApiResponse
{
    protected function logError(\Exception $e)
    {
        Log::error('Erro ocorrido.', [
            'error' => $e->getMessage(),
            'stack' => $e->getTraceAsString(),
        ]);
    }

    protected function handleException(\Exception $e)
    {
        $this->logError($e);
        return $this->responseError('Não foi possível executar a ação.', 500);
    }


    protected function responseSuccess($data, $statusCode = 200)
    {
        return response()->json([
            'error' => false,
            'response' => $data,
        ], $statusCode);
    }

    protected function responseError($message, $statusCode)
    {
        return response()->json([
            'error' => true,
            'message' => $message,
        ], $statusCode);
    }

    protected function responseErrorValidation($errors)
    {
        return response()->json([
            'error' => true,
            'message' => 'Validação falhou',
            'errors' => $errors,
        ], 422);
    }

    protected function responseRegisterNotFound($message)
    {
        return $this->responseError($message, 404);
    }
}