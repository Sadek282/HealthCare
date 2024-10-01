<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
       // contact section
       public function contact() {
        $user=Auth::user();
        return view('main-site.pages.contact',compact('user'));
    }
    public function contactData (Request $req){
        $req->validate([
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required',
            'Subject' => 'required',
            'message' => 'required',
        ],[
        'name.required' => 'Enter your name.',
        'email.required' => 'Enter your email.',
        'email.email' => 'Enter a valid email.',
        'phone.required' => 'Enter your phone number.',
        'Subject.required' => 'Enter the subject.',
        'message.required' => 'Enter your message.',
        ]);
        $contact=[
            'name'=>$req->name,
            'email'=>$req->email,
            'phone'=>$req->phone,
            'Subject'=>$req->Subject,
            'message'=>$req->message,
        ];
        try {
            Contact::create($contact);
    
            Alert::success('Success', 'Your data was sent successfully.');
    
        } catch (\Exception $e) {
            Alert::error('Error', 'An error occurred while sending your message.');
        }
    
     return redirect()->back();
}
    public function contactTable(){
        $user=Auth::user();
        $contactData=Contact::get();
        return view('admin-site.pages.table.contactTable',['contactDataStor'=>$contactData],compact('user'));
    }
}

