<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Validator;

class ProductCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_categories'
        ]);

        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => '<h5>Some fields failed validation. Please check and try again</h5>' . make_html_list($validator->errors()->all())
            ];
            return response()->json($res);
        }

        $category = new ProductCategory();
        $category->name = $request->name;
        $category->save();

        $res = [
            'success' => true,
            'message' => 'Category added',
            'category' => $category
        ];
        return response()->json($res);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:product_categories',
            'name' => 'required|unique:product_categories,id,' . $request->id,
        ]);

        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => '<h5>Some fields failed validation. Please check and try again</h5>' . make_html_list($validator->errors()->all())
            ];
            return response()->json($res);
        }

        $category = ProductCategory::find($request->id);
        $category->name = $request->name;
        $category->save();

        $res = [
            'success' => true,
            'message' => 'Category updated',
            'category' => $category
        ];
        return response()->json($res);
    }

    public function postDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:product_categories',
        ]);

        if ($validator->fails()) {
            $res = [
                'success' => false,
                'message' => '<h5>Some fields failed validation. Please check and try again</h5>' . make_html_list($validator->errors()->all())
            ];
            return response()->json($res);
        }

        $category = ProductCategory::find($request->id);
        $category->delete();

        $res = [
            'success' => true,
            'message' => 'Category deleted'
        ];
        return response()->json($res);
    }
}
