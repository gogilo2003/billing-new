<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Ogilo\ApiResponseHelpers;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ApiResponseHelpers;

    public function index($id = null)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'nullable|exists:product_categories,id'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        if ($id) {
            return new ProductResource(Product::with('category')->find($id));
        } else {
            return ProductResource::collection(Product::with('category')->get());
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|exists:product_categories,id',
            'name' => 'required|unique:products,name',
            'code' => 'required|unique:products,code',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->code = $request->code;
        $product->product_category_id = $request->category;
        $product->price = $request->price;
        $product->unit = $request->unit;
        $product->description = $request->description;
        $product->save();

        $product->load('category');

        return $this->storeSuccess('Product Created', ['product' => new ProductResource($product)]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:products,id',
            'category' => 'required|exists:product_categories,id',
            'name' => 'required|unique:products,name,' . $request->id,
            'code' => 'required|unique:products,code,' . $request->id,
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->code = $request->code;
        $product->product_category_id = $request->category;
        $product->price = $request->price;
        $product->unit = $request->unit;
        $product->description = $request->description;
        $product->save();

        $product->load('category');

        return $this->updateSuccess('Product Updated', ['product' => new ProductResource($product)]);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:products,id',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $product = Product::find($request->id);
        $product->delete();

        return $this->deleteSuccess();
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'details' => $validator->errors(),
            ], 415);
        }

        $csv = \League\Csv\Reader::createFromPath($request->csv_file->getRealPath(), 'r');
        $csv->setHeaderOffset(0);
        $records = \League\Csv\Statement::create()->process($csv);

        foreach ($records->getRecords() as $key => $record) {
            // die(var_export($record['description'],true));
            $product = new Product();
            $product->name = $record['name'];
            $product->code = $record['code'];
            $product->product_category_id = $record['category'];
            $product->price = $record['price'];
            $product->unit = $record['unit'];
            $product->description = $record['description'];
            $product->save();
        }

        return $this->importSuccess(count($records) . ' Products imported', [
            'categories' => ProductResource::collection(Product::all()),
        ]);
    }
}
