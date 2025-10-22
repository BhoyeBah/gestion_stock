<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
       $categories = Category::all();
        return view("back.categories.index", compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        //

        Category::create([
           "name" => $request->name,
           "slug" => $request->slug,
           "tenant_id" => Auth::user()->tenant_id,

        ]);

        return back()->with("success", "La catégorie est enrégistrée avec succès");

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
        return view("back.categories.edit", compact("category"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->save();

         return redirect()->route('categories.index')->with("success", "Catégorie modifié avec succés");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
        $category->delete();
        return back()->with("success", "La catégorie est supprimée avec succés");
    }
}
