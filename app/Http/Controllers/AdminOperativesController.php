<?php

namespace Dbs\Http\Controllers;

use Illuminate\Http\Request;

use Dbs\Http\Requests;
use Dbs\Http\Controllers\Controller;

use Dbs\User;
use Dbs\UserType;

use Dbs\Http\Requests\NewUserRequest;
use Dbs\Http\Requests\EditUserRequest;

class AdminOperativesController extends Controller
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
        $users = User::where('user_type_id', '=', UserType::where('value', '=', 'Operative')->first()->id)->paginate(15);
        $page = 'operatives';
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
                $oldUser->user_type_id = UserType::where('value', '=', 'Operative')->first()->id; 
                $oldUser->save();
                return \Redirect::back()->with('success', 'The operative '.$user->name.' has been re activated.');
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
            $user->user_type_id = UserType::where('value', '=', 'Operative')->first()->id;
            $user->save();
            return \Redirect::back()->with('success', 'The operative '.$user->name.' has been created.');
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
        $user = User::find($id);
        $page = 'operatives';
        return View('backend.users.userEdit', compact('user', 'page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(EditUserRequest $request, $id)
    {
        $user = User::find($id);
        $user->fill($request->except('password'));
        if($request->has('password')){ 
            $user->password = \Hash::make($request->get('password'));
        }
        $user->save();
        return \Redirect::to('/admin/operatives')->with('success', 'The operative '.$user->name.' has been edited.');
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
        return \Redirect::back()->with('success', 'The operative '.$user->name.' has been deleted.');
    }
}
