<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(){
        $categories = Category::all();
        $items = Item::with('category')->get();
        return view('menu',compact('categories','items'));
    }
}
