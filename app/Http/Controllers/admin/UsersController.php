<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\User;

class UsersController extends Controller
{

    public function all(){

        $users = User::paginate(10);

        return view('admin.users.all',compact('users'));

    }
    public function create(){

        return view('admin.users.create');
    }

    public function store(StoreRequest $request){


        $validatedData = $request->validated();

        $createdUser = User::create([
            'name'    =>  $validatedData['name'],
            'email'   =>  $validatedData['email'],
            'mobile'  =>  $validatedData['mobile'],
            'role'    =>  $validatedData['role']
        ]);

        if(!$createdUser){
            return back()->with('failed','کاربر ایجاد نشد');
        }
        return back()->with('success','کاربر ایجاد شد');

    }


    public function edit($user_id){


        $user = User::findOrFail($user_id);
        return view('admin.users.edit' ,compact('user'));

    }

    public function delete($user_id){

        $user = User::findOrFail($user_id);

        $deleteUser = $user->delete();

        
        if(!$deleteUser)
           return back()->with('failed','کاربر حذف نشد');

        return back()->with('success','کاربر حذف شد');

    }

    public function update(UpdateRequest $request,$user_id){


        $validatedData = $request->validated();

        $user = User::findOrFail($user_id);


        $updatedUser = $user->update([
            'name'    =>  $validatedData['name'],
            'email'   =>  $validatedData['email'],
            'mobile'  =>  $validatedData['mobile'],
            'role'    =>  $validatedData['role']
        ]);


        if(!$updatedUser)
           return back()->with('failed','کاربر بروزرسانی نشد');

        return back()->with('success','کاربر بروزرسانی شد');
    
    }
}
