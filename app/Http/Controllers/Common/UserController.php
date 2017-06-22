<?php

namespace App\Http\Controllers\Common;

use App\Events\NewUserActivate;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Create a new array user instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('pages.common.account')
            ->with('user', $user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  $userID
     * @return \Illuminate\Http\Response
     */
    public function show($userID)
    {
        $user = User::find($userID);
        return view('pages.common.profile')
            ->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = Auth::user();
        $company = $user->company();

        $user->name = $request->input('name');
        $user->job_title = $request->input('job_title');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        if (!is_null($request->input('password'))) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        $company->name = $request->input('company_name');
        $company->save();

        // flash message for update success
        $request->session()->flash('update_success', 'Your account info was updated successfully.');

        return redirect($request->path())
            ->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }


    /*
    *
    * Switch the block status of an user
    * Only available to admins, and an Admin cannot switch it's status
    */
    public function switchBlock($userID)
    {
        $user = User::find($userID);
        if (Auth::user() != $user && Auth::user()->admin == 1) {
            $user->toggleUser()->save();

            if ($user->blocked == 0) {
                // Dispatch a new event for an activated user. (Notification Type: 9)
                event(new NewUserActivate($user));
            }
        }
        return redirect()->route('profile', ['id' => $user->id]);
    }


    /*
    * Redirect to blocked page if user is inactive
    */
    public function blocked_page()
    {
        return view('pages.common.blocked_user');
    }

    /**
     * Get a validator for an incoming account update request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $user = Auth::user();
        return Validator::make($data, [
            'name' => 'required|full_name|max:255',
            'company_name' => 'required|max:255',
            'job_title' => 'nullable|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|phone_number',
            'password' => 'nullable|min:8',
        ]);
    }
}
