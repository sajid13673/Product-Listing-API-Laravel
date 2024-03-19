<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProductPostRequest;
use App\Http\Requests\ProductPutRequest;
use domain\Facades\ProductFacade;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductFacade::all();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductPostRequest $request)
    {
        ProductFacade::store($request);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return ProductFacade::get($id);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(ProductPutRequest $request, string $id)
    {
    return ProductFacade::update($request, $id);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ProductFacade::delete($id);
    }
    public function sort(Request $request)
    {
        return ProductFacade::sort($request);
    }
}