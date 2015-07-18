<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\UserType;

use App\Http\Requests\NewUserRequest;

class AdminUsersController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = User::where('user_type_id', '=', UserType::where('value', '=', 'Admin')->first()->id)->get();
        $page = 'users';
        return View('backend.users.userList', compact('users', 'page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(NewUserRequest $request)
    {
        $oldUser = User::withTrashed()->where('email', '=', $request->get('email'))->first();
        if($oldUser){
            if($oldUser->trashed()){
                $oldUser->restore();
                $oldUser->fill($request->except('password'));
                $oldUser->password = \Hash::make($request->get('password'));
                $oldUser->user_type_id = UserType::where('value', '=', 'Admin')->first()->id; 
                $oldUser->save();
                return \Redirect::back()->with('success', 'The user has been re activated.');
            }else{
                return \Redirect::back()
                    ->withInput()
                    ->withErrors([
                    'email' => 'That email address has been taken.',
                ]);
            }
        }else{
            $user = new User;
            $user->fill($request->except('password'));
            $user->password = \Hash::make($request->get('password'));
            $user->user_type_id = UserType::where('value', '=', 'Admin')->first()->id;
            $user->save();
            return \Redirect::back()->with('success', 'The user has been created.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return \Redirect::back()->with('success', 'The user has been deleted.');
    }
}
