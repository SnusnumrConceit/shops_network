<?php

namespace App\Http\Controllers;

use App\Model\Role;
use App\User;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Hash;
use Auth;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error',
                    'msg' => 'Неверный логин или пароль'
                ]);
            }
        } catch (JWTException $error ) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Could\'t create token'
            ]);
        }
        $user = Auth::attempt($credentials);
        return response()->json([
            'token' => $token,
            'user' => Auth::user()
        ], 200);
    }

    public function logout()
    {
        Auth::logout();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
//            $user = User::create($request->all());
            DB::select('call create_user(?, ?, ?, ?, ?, ?)', [
                $request->input('email'),
                Hash::make($request->input('password')),
                $request->input('first_name'),
                $request->input('last_name'),
                $request->input('phone'),
                $request->input('role_id'),
            ]);
            return response()->json([
                'status' => 'success'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ], 200);
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
        $users = User::with('role')->paginate(10);
        return response()->json([
            'status' => 'success',
            'users' => $users
        ], 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'user' => $user
        ], 200);
    }

    public function form_info()
    {
        $roles = Role::all();
        return response()->json([
            'roles' => $roles
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $user = User::findOrFail($id);
//            $user->update($request->all());
            DB::select('call edit_user(?, ?, ?, ?, ?, ?, ?)', [
                $request->input('email'),
                bcrypt($request->input('email')),
                $request->input('first_name'),
                $request->input('last_name'),
                $request->input('phone'),
                $request->input('role_id'),
                $id
            ]);
            return response()->json([
                'status' => 'success',
                'user' => $user
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        User::findOrFail($id);
        DB::select('call drop_user(?)', [ $id ]);
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function getUser()
    {
        return response()->json([ 'user' => Auth::user(), 'status' => 'success'], 200);
    }
}
