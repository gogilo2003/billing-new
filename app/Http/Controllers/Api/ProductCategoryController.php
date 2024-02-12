<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Ogilo\ApiResponseHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProductCategoryResource;

class ProductCategoryController extends Controller
{
    use ApiResponseHelpers;

    public function index($id = null)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'nullable|exists:product_categories,id'
        ]);

        if ($validator->fails()) return $this->validationError($validator);

        if ($id) {
            return new ProductCategoryResource(ProductCategory::with('products')->findOrFail($id));
        } else {
            return ProductCategoryResource::collection(ProductCategory::with('products')->get());
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:product_categories'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $category = new ProductCategory();
        $category->name = $request->name;
        $category->save();
        $category->loadMissing('products');
        return $this->storeSuccess('Product Category Created', ['category' => new ProductCategoryResource($category)]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:product_categories,id',
            'name' => 'required|unique:product_categories,name,' . $request->id,
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $category = ProductCategory::find($request->id);
        $category->name = $request->name;
        $category->save();
        $category->loadMissing('products');
        return $this->updateSuccess('Product Category updated', ['category' => new ProductCategoryResource($category)]);
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:product_categories,id',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $category = ProductCategory::find($request->id);
        $category->delete();
        return $this->deleteSuccess();
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $csv = \League\Csv\Reader::createFromPath($request->csv_file->getRealPath(), 'r');
        $csv->setHeaderOffset(0);
        $records = \League\Csv\Statement::create()->process($csv);

        foreach ($records->getRecords() as $key => $record) {
            $category = new ProductCategory();
            $category->name = $record['name'];
            $category->save();
        }

        return $this->importSuccess(count($records).' Product categories imported',[
            'categories' => ProductCategoryResource::collection(ProductCategory::all()),
        ]);
    }
}
