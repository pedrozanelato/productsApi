<?php

namespace App\Http\Interfaces;

use Illuminate\Http\Request;

interface ProductControllerInterface
{
    public function index();
    public function create(Request $request);
    public function update(Request $request, $id);
    public function delete($id);
}