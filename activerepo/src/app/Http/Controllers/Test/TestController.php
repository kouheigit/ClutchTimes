<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Http\Requests\TestValidationRequest;
use Illuminate\Http\Request;
use App\Models\Thread;
use Illuminate\Support\Carbon;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = $request->input('search');
        $order = $request->input( 'order');

        if($order==null){
            $order = 'asc';
        }

        if($order=='asc'){
            $query = Thread::query()->orderBy('id','asc');
        }else{
            $query = Thread::query()->orderBy('id','desc');
        }

        if(isset($search)){
            $threads = Thread::query()->where('title','like',"%{$search}%")
                ->orwhere('name', 'lIKE', "%{$search}%")
                ->orwhere('comment', 'lIKE', "%{$search}%")->paginate(30);
        }else{
            $threads = $query->paginate(30);
        }


        return view('test.index',compact('threads','order'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(TestValidationRequest $request)
    {
        return view('test.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TestValidationRequest $request)
    {
        //middlewareから値を取得してきたIPアドレス↓


        $validated = $request->validated();
        $title = $request->input('title');
        $name = $request->input('name');
        $comment = $request->input('comment');
        $generateid = $request->input('GenerateID');
        $imageFileName = null;

        if ($request->hasFile('image')) {
            $imageFileName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('images'),$imageFileName);
        }

        $theadData = [
            'title'=>$title,
            'name'=>$name,
            'comment'=>$comment,
            'fileName'=>$imageFileName,
            'generateid'=>$generateid,
            'created_at'=> Carbon::now('Asia/Tokyo'),
            'updated_at'=> Carbon::now('Asia/Tokyo'),
            ];
        Thread::insert($theadData);
        return redirect('test');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
