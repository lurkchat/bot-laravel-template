<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::get();
        return view('users.index')->with(compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $user = null;
        return view('users.create_or_edit')->with(compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'login' => 'required|string|max:255',
            'password' => 'required|string|max:255'
        ];

        $this->validate($request, $rules);

        $user = new User();
        $user->name = $request->name;
        $user->login = $request->login;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect(route('users.index'))->with('success', 'Administrator added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.create_or_edit')->with(compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'login' => 'required|string|max:255',
            'password' => 'nullable|string|max:255'
        ];

        $this->validate($request, $rules);

        $user->name = $request->name;
        $user->login = $request->login;
        if ($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect(route('users.index'))->with('success', 'Changes saved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect(route('users.index'))->with('success', 'Administrator deleted');
    }
}
