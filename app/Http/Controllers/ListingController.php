<?php

namespace App\Http\Controllers;
use App\Models\Listing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;



class ListingController extends Controller
{
    //show all listings
 public function index( ){
    return view('listings.index', [
        // 'heading' => 'Listings Elements',
   
        'listings' =>   Listing::latest()->filter(request(['tag','search']))->paginate(4)
        // same as below
        // 'listings' =>   Listing::all()
    ] );

 }

//  show single listings
  public function show($idvar)
     {
        $listing = Listing::find($idvar);
        if($listing){
            return view('listings.show', 
            ['listing' => $listing]);
        }
        else{
            abort('404');
        }  
    }

    public function create(){
        return view('listings.create');
    }

    
    public function store(Request $request) {
        // Validate the request data
        $formFields = $request->validate([
            'title' => 'required|max:255',
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => 'required',
            'website' => 'nullable|url',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',

        ]);
        if($request->hasFile('logo')){
            $formFields['logo']= $request->file('logo')->store('logos','public');
        }
        $formFields['user_id']= auth()->id();
        Listing::create($formFields);
        return redirect('/')->with('message', 'Listing created successfully!');
    }
          

    // show edit form
    public function edit(Listing $listing){
      
        return view('listings.edit', ['listing' => $listing]);
    }
    // update listing
    public function update(Request $request, Listing $listing){
         
        //first check if user is the owner
        if($listing->user_id != auth()->id()){
            abort('403','Unauthorized ACtion');
        }

        // Validate the request data
        $formFields = $request->validate([
            
            'title' => 'required|max:255',
            'company' => 'required',
            'location' => 'required',
            'website' => 'nullable|url',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required',
        ]);
        if($request->hasFile('logo')){
            $formFields['logo']= $request->file('logo')->store('logos','public');
        }
        $listing->update($formFields);
        return redirect("/")->with('message', 'Listing Updated successfully!');
    }
    // destroy listing item
    public function destroy(Listing $listing){
         //first check if user is the owner
         if($listing->user_id != auth()->id()){
            abort('403','Unauthorized delete ACtion');
        }

        $listing->delete();
        return redirect('/')->with('message', 'Listing deleted successfully!');
    }
    //manage listings
    public function manage() {
        $listings = auth()->user()->listings()->get();
        // dd($listings); // or use logger()->info($listings);
        return view('listings.manage', ['listings' => $listings]);
    }
    
}
