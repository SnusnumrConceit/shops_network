<?php

namespace App\Http\Controllers;

use App\Model\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $shop = $request->all();
//            $shop = Shop::create($request->all());
            DB::select('call create_shop(?, ?, ?, ?)',
                [
                    $request->input('name'),
                    $request->input('address'),
                    $request->input('bank_account'),
                    $request->input('contact_phone')
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
        $shops = Shop::paginate(10);
        return response()->json([
            'shops' => $shops,
            'status' => 'success'
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $shop = Shop::findOrFail($id);
        return response()->json([
            'shop' => $shop,
            'status' => 'success'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $shop = Shop::findOrFail($id);
//        $shop->update($request->all());
        DB::select('call edit_shop(?, ?, ?, ?, ?)',[
            $request->input('name'),
            $request->input('address'),
            $request->input('bank_account'),
            $request->input('contact_phone'),
            $id
        ]);
        return response()->json([
            'shop' => $shop,
            'status' => 'success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Shop  $shop
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        Shop::findOrFail($id);
        DB::select('call drop_shop(?)', [ $id ]);
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
            case 'name': $query = Shop::where('name', 'LIKE', '%'.$keyword.'%')->paginate(10); break;
            case 'address': $query = Shop::where('address', 'LIKE', '%'.$keyword.'%')->paginate(10); break;
            case 'bank': $query = Shop::where('bank_account', 'LIKE', '%'.$keyword.'%')->paginate(10); break;
            case 'phone': $query = Shop::where('contact_phone', 'LIKE', '%'.$keyword.'%')->paginate(10); break;
        }
        if (! $query->count() ) {
            return response()->json([
                'status' => 'error',
                'msg' => 'По вашему запросу ничего не найдено'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'shops' => $query
        ], 200);
    }
}
