<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function add(){
    // $categories = Category::all()->toArray();
    // return view('admin.product.add', compact('categories'));
    return view('admin.product.add');
  }
}
