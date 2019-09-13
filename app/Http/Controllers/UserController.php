<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EditProfileRequest;
use Illuminate\Support\Facades\Hash;
use App\Repository\UserEloquentRepository;

class UserController extends Controller
{
    protected $userEloquentRepository;


    public function __construct(UserEloquentRepository $userEloquentRepository)
    {
        $this->userEloquentRepository = $userEloquentRepository;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = auth()->user();
        return view('profile.profile', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\EditProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(EditProfileRequest $request)
    {
        $id = auth()->user()->id;
        $data = $request->all();
        if ($request->file() == null) {
            unset($data['file']);
        } else {
            $path = $this->uploadFile($request);
            $data['avatar'] = $path;
        }
        $this->userEloquentRepository->update($id, $data);
        return redirect('/profile');
    }

    /**
     * Show the form for editing password_current
     * 
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        return view('profile.password');
    }

    /**
     * Update password
     * 
     * @param  App\Http\Requests\EditProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function passwordUpdate(EditProfileRequest $request)
    {
        $id = auth()->user()->id;
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $this->userEloquentRepository->update($id, $data);
        return redirect('/profile');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $this->userEloquentRepository->delete(auth()->user()->id);
        return redirect('/logout');
    }


    public function uploadFile(EditProfileRequest $request)
    {
        $file = $request->file('file');
        $name = $file->hashName();
        $path = $file->storeAs('public/user_avatar', $name);
        return $path = str_replace('public', 'storage', $path);
    }
}
