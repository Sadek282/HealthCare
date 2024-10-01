<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class SignupLoginController extends Controller
{
     //signUp section
 public function signUp() {
    return view('main-site.pages.signupLogin.signUp');
}
public function signupData(Request $req){
    $req->validate([
     'profilePicture'=>'required',
     'name'=>'required|max:15',
     'email'=>'required|email:rfc,dns',
     'password'=>'required',
     'password_confirmation'=>'required|same:password',
     'iAgree'=>'required'
    ]);
    if(! is_dir(public_path('admin-site/img/profilePicture'))){
        mkdir(public_path('admin-site/img/profilePicture'), 0777, true);
      }
    $data=[
        'name'=>$req->name,
        'email'=>$req->email,
        'password'=>Hash::make($req->password),
        'iAgree'=>$req->iAgree == '1'? 1 : 0,
    ];
    if ($req->hasFile('profilePicture')) {
        $image = $req->file('profilePicture');
        $name = $image->getClientOriginalName();
        $imageName = time() . '_' . $name;
    
        $image->move(public_path('admin-site/img/profilePicture'), $imageName);
    
        $data['profilePicture'] = 'admin-site/img/profilePicture/' . $imageName;
    } 
    User::create($data);
    Alert::success('Success sign up', 'Your sign up Success');
    return redirect()->route('logIn');
}

//Login section
public function logIn() {
    return view('main-site.pages.signupLogin.login');
}
public function logInData(Request $req){
 if(Auth::attempt(['email' => $req->email, 'password' => $req->password])){
    if(Auth::user()->is_tyep == 'admin'){
        Alert::success('Success Login', 'Your admin login Success');
        return redirect()->route('adminHome');
    }else{
        Alert::success('Success Login', 'Your user login Success');
        return redirect()->route('home');
    }
 }else{
    Alert::error('Error', 'An error occurred during login.');
    return redirect()->back();
 }
}
public function logOut(){
    Auth::logOut();
    Alert::success('Success Logout', 'Your logout Success ');
    return redirect()->route('home');
}


public function updateProfile(Request $request)
{
    $user = Auth::user();
    $request->validate([
        'email'=>'email|unique:users,email,'.Auth::id(),
    ]);
    if(! is_dir(public_path('admin-site/img/profilePicture'))){
                mkdir(public_path('admin-site/img/profilePicture'), 0777, true);
              }
    $dataup = [
        'name' => $request->name,
        'email' => $request->email,
        'Phone' => $request->phone,
        'address' => $request->address,
        'about' => $request->about,
        'twitter' => $request->twitter,
        'facebook' => $request->facebook,
    ];
    if ($request->hasFile('profilePicture')) {
                $image = $request->file('profilePicture');
                $name = $image->getClientOriginalName();
                $imageName = time() . '_' . $name;
            
                $image->move(public_path('admin-site/img/profilePicture'), $imageName);
            
                $dataup['profilePicture'] = 'admin-site/img/profilePicture/' . $imageName;
            } 
    $user->update($dataup);

    return redirect()->back();
}

public function updatePassword(Request $req)
{
    $req->validate([
        'oldpassword' => 'required',
        'new_password' => 'required|confirmed',
        'new_password_confirmation' => 'required',
    ]);
    if (Hash::check($req->oldpassword, Auth::user()->password)) {

        User::whereId(Auth::user()->id)->update([
            'password' => Hash::make($req->new_password),
        ]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    } else {
        
        return redirect()->back()->with('error', 'Old password is incorrect.');
    }
}

}

