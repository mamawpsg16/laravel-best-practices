<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
     public function index()
     {
         $menus = Menu::active()->orderByRaw('COALESCE(parent_id, id), `order`')
                             ->get(['id', 'name', 'path as to', 'parent_id','icon', 'order', DB::raw('IF(parent_id IS NULL AND is_item = 0, "CNavGroup", "CNavItem") as component'), 'is_item']);
         return response()->json(['menus'=> $menus]);
     }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
