<?php

namespace App\Http\Controllers;

use App\Model\Contract;
use App\Model\Product;
use App\User;
use App\Model\Provider;
use App\Model\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $deadline = substr(strstr($request->input('deadline'), 'T', true), 0, 8);

            if (! $deadline) {
                $deadline = $request->input('deadline');
            } else {
                $days = intval(substr($request->input('deadline'), 8, 2)) + 1;
                $deadline .= $days;
            }

            $ca = substr(strstr($request->input('created_at'), 'T', true), 0, 8);

            if (! $ca) {
                $ca = $request->input('created_at');
            } else {
                $days = intval(substr($request->input('created_at'), 8, 2)) + 1;
                $ca .= $days;
            }

            DB::select('call create_contract(?, ?, ?, ?, ?)', [
                $request->input('provider_id'),
                $request->input('author_id'),
                $request->input('shop_id'),
                $ca,
                $deadline
            ]);
            $contract = Contract::where(function ($q) use ($request, $ca, $deadline) {
                $q->where('provider_id', $request->input('provider_id'));
                $q->where('author_id', $request->input('author_id'));
                $q->where('shop_id', $request->input('shop_id'));
                $q->where('created_at', $ca);
                $q->where('deadline', $deadline);
            })->first();
            if ($request->input('products') !== null) {
                $products = $request->input('products');
                foreach ($products as $product) {
                    if (! isset($product)) continue;
                    DB::select('call refresh_contracts_products(?,?)', [
                        $product,
                        $contract->id
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
        $contracts = Contract::with(['author', 'products', 'provider', 'shop'])->paginate(10);
        return response()->json([
            'contracts' => $contracts,
            'status' => 'success'
        ], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $contract = Contract::with('products')->findOrFail($id);
        return response()->json([
            'contract' => $contract,
            'status' => 'success'
        ], 200);
    }

    public function form_info()
    {
        $providers = Provider::all();
        $users = User::all();
        $shops = Shop::all();
        $products = Product::all();
        return response()->json([
            'providers' => $providers,
            'users' => $users,
            'shops' => $shops,
            'products' => $products,
            'status' => 'success',
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $edit_contract = Contract::findOrFail($id);

        $deadline = substr(strstr($request->input('deadline'), 'T', true), 0, 8);

        if (! $deadline) {
            $deadline = $request->input('deadline');
        } else {
            $days = intval(substr($request->input('deadline'), 8, 2)) + 1;
            $deadline .= $days;
        }

        $ca = substr(strstr($request->input('created_at'), 'T', true), 0, 8);

        if (! $ca) {
            $ca = $request->input('created_at');
        } else {
            $days = intval(substr($request->input('created_at'), 8, 2)) + 1;
            $ca .= $days;
        }

        DB::select('call edit_contract (?, ?, ?, ?, ?, ?)', [
            $request->input('provider_id'),
            $request->input('author_id'),
            $request->input('shop_id'),
            $ca,
            $deadline,
            $id
        ]);
//        $edit_contract->update(($request->all()));
        if ($request->input('products') !== null) {
            DB::select('call clear_contracts_products(?)', [ $id ]);
            $products = $request->input('products');
            foreach ($products as $product) {
                if (! isset($product)) continue;
                DB::select('call refresh_contracts_products(?,?)', [
                    $product,
                    $id
                ]);
            }
        }
        return response()->json([
            'status' => 'success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        Contract::findOrFail($id);
        DB::select('call drop_contract(?)', [ $id ]);
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
            case 'provider': $query = Contract::with(['author', 'products', 'provider', 'shop'])->where('provider_id', $keyword)->paginate(10); break;
            case 'shop': $query = Contract::with(['author', 'products', 'provider', 'shop'])->where('shop_id', $keyword)->paginate(10); break;
            case 'author': $query = Contract::with(['author', 'products', 'provider', 'shop'])->where('author_id', $keyword)->paginate(10); break;
        }
        if (! count($query)) {
            return response()->json([
                'status' => 'error',
                'msg' => 'По вашему запрсоу ничего не найдено'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'contracts' => $query
        ], 200);
    }
}
