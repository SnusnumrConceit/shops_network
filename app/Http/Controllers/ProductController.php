<?php

namespace App\Http\Controllers;

use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
//            Product::create($request->all());
            $days = intval(substr($request->input('manufacture_date'), 8, 2)) + 1;
            $date = substr(strstr($request->input('manufacture_date'), 'T', true), 0, 8);
            $date .= $days;

            $q = DB::select('call create_product(?, ?, ?)', [
                $request->input('name'),
                $request->input('cost'),
                $date

            ]);

            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $products = Product::paginate(10);
        return response()->json([
            'status' => 'success',
            'products' => $products
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'product' => $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $product = Product::findOrFail($id);
//        $product->update($request->all());

        $date = substr(strstr($request->input('manufacture_date'), 'T', true), 0, 8);

        if (! $date) {
            $date = $request->input('manufacture_date');
        } else {
            $days = intval(substr($request->input('manufacture_date'), 8, 2)) + 1;
            $date .= $days;
        }

        DB::select('call edit_product(?, ?, ?, ?)', [
            $request->input('name'),
            $request->input('cost'),
            $date,
            $id
        ]);
        return response()->json([
            'status' => 'success',
            'product' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        Product::findOrFail($id);
        DB::select('call drop_product(?)', [ $id ]);
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function search(Request $request)
    {
        $filter = $request->input('filter');
        $keyword = $request->input('keyword');

        if (! $filter && ! $keyword) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Не переданы необходимые параметры'
            ]);
        }

        $query = '';
        switch ($filter) {
            case 'name': $query = Product::where('name', 'LIKE', '%'.$keyword.'%')->paginate(10); break;
            case 'cost': $query = Product::where('cost', 'LIKE', '%'.$keyword.'%')->paginate(10); break;
        }
        if (! $query->count() ) {
            return response()->json([
                'status' => 'error',
                'msg' => 'По вашему запросу ничего не найдено'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'products' => $query
        ], 200);
    }
}
