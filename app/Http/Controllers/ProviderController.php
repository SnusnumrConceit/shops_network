<?php

namespace App\Http\Controllers;

use App\Model\Product;
use App\Model\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
//            Provider::create($request->all());

            DB::select('call create_provider(?, ?, ?, ?)', [
                $request->input('name'),
                $request->input('legal_address'),
                $request->input('bank_account'),
                $request->input('contact_phone')
            ]);
            $provider = Provider::where(function ($q) use ($request) {
                $q->where('name', $request->input('name'));
                $q->where('legal_address', $request->input('legal_address'));
                $q->where('bank_account', $request->input('bank_account'));
                $q->where('contact_phone', $request->input('contact_phone'));

            })->first();
            if ($request->input('products') !== null) {
                $products = $request->input('products');
                foreach ($products as $product) {
                    if (! isset($product)) continue;
                    DB::select('call refresh_providers_products(?,?)', [
                        $product,
                        $provider->id
                    ]);
                }
            }
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
        $providers = Provider::with('products')->paginate(10);
        return response()->json([
            'status' => 'success',
            'providers' => $providers
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $provider = Provider::with('products')->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'provider' => $provider
        ], 200);
    }

    public function form_info()
    {
        $products = Product::all();
        return response()->json([
            'products' => $products
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $provider = Provider::findOrFail($id);
        try {
//            $provider->update($request->all());
            DB::select('call edit_provider(?, ?, ?, ?, ?)', [
                $request->input('name'),
                $request->input('legal_address'),
                $request->input('bank_account'),
                $request->input('contact_phone'),
                $id
            ]);
            if ($request->input('products') !== null) {
                DB::select('call clear_provider_products(?)', [ $id ]);
                $products = $request->input('products');
                foreach ($products as $product) {
                    if (! isset($product)) continue;
                    DB::select('call refresh_providers_products(?,?)', [
                        $product,
                        $id
                    ]);
                }
            }
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        Provider::findOrFail($id);
        DB::select('call drop_provider(?)', [
            $id
        ]);
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
            case 'name': $query = Provider::with('products')->where('name', 'LIKE', '%'.$keyword.'%')->paginate(10); break;
            case 'address': $query = Provider::with('products')->where('legal_address', 'LIKE', '%'.$keyword.'%')->paginate(10); break;
            case 'bank': $query = Provider::with('products')->where('bank_account', 'LIKE', '%'.$keyword.'%')->paginate(10); break;
            case 'phone': $query = Provider::with('products')->where('contact_phone', 'LIKE', '%'.$keyword.'%')->paginate(10); break;
        }
        if (! $query->count() ) {
            return response()->json([
                'status' => 'error',
                'msg' => 'По вашему запросу ничего не найдено'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'providers' => $query
        ], 200);
    }
}
