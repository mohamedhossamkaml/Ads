<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

use App\Model\Tags ;

class TagsController extends Controller
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
            return Tags::latest()->paginate(10);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->authorize('isAdmin');

        $data = $this->validate(
            request(),
            [
                'title'         =>  'required|string',
                'description'   =>  'required|string|max:191',
            ],
        );

        Tags::create($data);

        return response([
            'status' => true,
            'message' => 'Created Tags'
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
        $this->authorize('isAdmin');

        $data = $this->validate(
            request(),
            [
                'title'         =>  'required|string',
                'description'   =>  'required|string|max:191',
            ],
        );

        Tags::where('id', $id)->update($data);

        return response([
            'status' => true,
            'message' => 'Updated Tags'
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
        $this->authorize('isAdmin');
        $Tags = Tags::findOrfail($id);

        $Tags->delete();

        // Delete The Tags

        return ['message' => 'Delete Tags Success'];
    }
}
