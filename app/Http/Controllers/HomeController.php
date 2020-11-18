<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function imagesUpload()
    {
        return view('imagesUpload');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function imagesUploadPost(Request $request)
    {
        request()->validate([
            'uploadFile' => 'required',
        ]);

        foreach ($request->file('uploadFile') as $key => $value) {
            $imageName = time(). $key . '.' . $value->getClientOriginalExtension();
            $value->move(public_path('images'), $imageName);
        }

        return response()->json(['success'=>'Images Uploaded Successfully.']);
    }

}