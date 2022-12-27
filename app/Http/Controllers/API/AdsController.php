<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

use App\Model\Ads;
use App\Model\Category;
use App\Model\Tags;

class AdsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::allows('isAdmin') || Gate::allows('isAuthor')) {
            return Ads::with(['advertiser','category','tagsID'])->latest()->paginate(10);
        }
    }
    // Ads User api
    public function adsUser()
    {
        $ads = auth('api')->user();
        $id = $ads->id;
        return Ads::with(['advertiser','category','tagsID'])->where('advertiserid',$id)->latest()->paginate(10);
    }
    // Ads User api
    public function adsCat()
    {
        return Category::latest()->paginate(10);
    }
    // Ads User api
    public function adsTag()
    {
        return Tags::latest()->paginate(10);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // $this->authorize('isAdmin');

        $data = $this->validate(
            request(),
            [
                'title'         =>  'required|string|max:191',
                'description'   =>  'sometimes|string|max:191',
                'type'          =>  'required',
                'advertiserid'  =>  'sometimes|nullable|numeric',
                'category_id'   =>  'required|numeric',
                'tags_id'       =>  'required|numeric',
                'start_date'    =>  'required|date',
            ],
        );

        $data['advertiserid'] = auth()->user()->id;

        Ads::create($data);

        return response([
            'status' => true,
            'message' => 'Created Ads'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $this->authorize('isAdmin');

        $data = $this->validate(
            request(),
            [
                'title'         =>  'required|string|max:191',
                'description'   =>  'sometimes|string|max:191',
                'type'          =>  'required',
                'advertiserid'  =>  'sometimes|nullable|numeric',
                'category_id'   =>  'required|numeric',
                'tags_id'       =>  'required|numeric',
                'start_date'    =>  'required|date',
            ],
        );


        Ads::where('id', $id)->update($data);

        return response([
            'status' => true,
            'message' => 'Updated Ads'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $this->authorize('isAdmin');
        $ads = Ads::findOrfail($id);

        $ads->delete();

        // Delete The ads

        return ['message' => 'Delete Ads Success'];
    }
}
