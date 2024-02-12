<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:products',
            'code' => 'required|unique:products',
            'category' => 'required|exists:product_categories,id'
        ]);

        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => '<h5>Some fields failed validation. Please check and try again</h5>' . make_html_list($validator->errors()->all())
            ];
            return response()->json($res);
        }

        $product = new Product();
        $product->code = $request->code;
        $product->name = $request->name;
        $product->unit = $request->unit;
        $product->price = $request->price;
        $product->product_category_id = $request->category;
        $product->save();

        $res = [
            'success' => true,
            'message' => 'Product added',
            'product' => $product
        ];
        return response()->json($res);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products',
            'code' => 'required|unique:products,code,id,' . $request->id,
            'name' => 'required|unique:products,name,id,' . $request->id,
            'category' => 'required|exists:product_categories,id'
        ]);

        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => '<h5>Some fields failed validation. Please check and try again</h5>' . make_html_list($validator->errors()->all())
            ];
            return response()->json($res);
        }

        $product = Product::find($request->id);
        $product->code = $request->code;
        $product->name = $request->name;
        $product->unit = $request->unit;
        $product->price = $request->price;
        $product->category_id = $request->category;
        $product->save();

        $res = [
            'success' => true,
            'message' => 'Product updated',
            'product' => $product
        ];
        return response()->json($res);
    }

    public function postDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:products',
        ]);

        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => '<h5>Some fields failed validation. Please check and try again</h5>' . make_html_list($validator->errors()->all())
            ];
            return response()->json($res);
        }

        $product = Product::find($request->id);
        $product->delete();

        $res = [
            'success' => true,
            'message' => 'Product deleted'
        ];
        return response()->json($res);
    }
}
