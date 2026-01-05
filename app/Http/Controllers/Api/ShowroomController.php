<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Showroom\ShowroomStoreRequest;
use App\Http\Resources\Showroom\ShowroomCollection;
use App\Models\Showroom;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ShowroomController extends Controller
{
    public function index(Request $request)
    {

        $area_id =$request->area_id;
        $showrooms = Showroom::query()->with('Area');
        if (!empty($area_id)){
            $showrooms=$showrooms->where('area_id',$area_id);

        }
        $showrooms = $showrooms->paginate();

        return new ShowroomCollection($showrooms);
    }

    public function store(ShowroomStoreRequest $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/showroom/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $showroom = new Showroom();
        $showroom->area_id = $request->area_id;
        $showroom->address = $request->address;
        $showroom->name = $request->responsible_person;
        $showroom->showroom_name = $request->showroom_name;
        $showroom->mobile_number = $request->mobile;
        $showroom->lat = $request->lat;
        $showroom->lon = $request->long;
        $showroom->image =$name;
        $showroom->save();
        return response()->json([
            'status'=>'success',
            'message' => 'Showroom created Successfully', 200
        ]);
    }


    public function update(Request $request, $id)
    {

        $showroom = Showroom::where('id', $id)->first();

        $image = $request->image;
        if ($image != $showroom->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/showroom/';
                if ($showroom->image){
                    $file_old = public_path('/').$destinationPath.$showroom->image;
                    if (file_exists($file_old)){
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/showroom/') . $name);
            } else {
                $name = $showroom->image;
            }

        }
        else{
            $name = $showroom->image;
        }
        $showroom->area_id = $request->area_id;;
        $showroom->address = $request->address;
        $showroom->name = $request->responsible_person;
        $showroom->showroom_name = $request->showroom_name;
        $showroom->mobile_number = $request->mobile;
        $showroom->lat = $request->lat;
        $showroom->lon = $request->long;
        $showroom->image =$name;
        $showroom->save();
        return response()->json([
            'status'=>'success',
            'message' => 'Showroom updated Successfully', 200
        ]);
    }

    public function destroy($id)
    {
        Showroom::where('id', $id)->delete();
        return response()->json(['message' => 'Showroom Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new ShowroomCollection(Showroom::Where('responsible_person', 'like', "%$query%")->latest()->paginate(10));
    }

}
