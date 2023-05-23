<?php

namespace App\Http\Controllers;
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
    public function store(Request $request)
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
    public function update(Request $request, string $id)
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
    public function validation(Request $request)
    {
        return ProductFacade::validation($request);
    }
    public function EditValidation(Request $request)
    {
        return ProductFacade::editValidation($request);
    }
}