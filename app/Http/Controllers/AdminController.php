<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
   public function admin(){
    $user=Auth::user();
        return view('admin-site.pages.home',compact('user'));
    }
   public function addTestimonial(){
    $user=Auth::user();
        return view('admin-site.pages.from.addTestimonial',compact('user'));
    }
    public function addTestimonialData(Request $req) {
        $req->validate([
            'image' => 'required',
            'name' => 'required',
            'profeson' => 'required',
            'description' => 'required',
        ]);

        if(! is_dir(public_path('admin-site/img/new'))){
          mkdir(public_path('admin-site/img/new'), 0777, true);
        }

        $testimonialData=[
            'name'=>$req->name,
            'profeson'=>$req->profeson,
            'description'=>$req->description,
        ];

        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $name = $image->getClientOriginalName();
            $imageName = time() . '_' . $name;
        
            $image->move(public_path('admin-site/img/new'), $imageName);
        
            $testimonialData['image'] = 'admin-site/img/new/' . $imageName;
        } 
        Testimonial::create($testimonialData);
        Alert::success('Success', 'Your data was sent successfully.');
        return redirect()->back();
        dd($testimonialData);
    }
   public function testimonialList(){
        $testimonialStor=Testimonial::get();
        $user=Auth::user();
        return view('admin-site.pages.table.TestimonialTable',['testimonialData'=>$testimonialStor],compact('user'));
    }
    public function adminProfile(){
        $user=Auth::user();
            return view('admin-site.pages.adminProfile',compact('user'));
        }
}
