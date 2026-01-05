<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dealer\DealerStoreRequest;
use App\Http\Resources\Dealer\DealerCollection;
use App\Models\Dealer;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class DealerController extends Controller
{
    public function index(Request $request)
    {
        $area_id        =$request->area_id;
        $district_id    =$request->district_id;

        $dealers = Dealer::query()->with('Area');
        if (!empty($area_id)){
            $dealers = $dealers->where('area_id',$area_id);
        }
        if (!empty($district_id)){
            $dealers = $dealers->where('district_id',$district_id);
        }
        $dealers = $dealers->orderBy('id','desc')->where('active','Y')->paginate();
        return new DealerCollection($dealers);
    }

    public function store(DealerStoreRequest $request)
    {
        $exist_check = Dealer::query()->where('dealer_code', $request->dealer_code)->exists();
        if ($exist_check){
            return response()->json([
                'status' => 'success',
                'message' => 'Dealer Already Exist'
            ]);
        }

        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/dealer/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $dealer = new Dealer();
        $dealer->area_id = $request->area_id;
        $dealer->district_id = $request->district_id;
        $dealer->upazilla_id = $request->upazilla_id;
        $dealer->address = $request->address;
        $dealer->responsible_person = $request->dealer_name;
        $dealer->dealer_code = $request->dealer_code;
        $dealer->store_name = $request->store_name;
        $dealer->dealer_type = $request->dealer_type;
        $dealer->mobile = $request->mobile;
        $dealer->lat = $request->lat;
        $dealer->lon = $request->long;
        $dealer->image =$name;
        $dealer->save();
        return response()->json(['message' => 'Dealer created Successfully', 200]);
    }

    public function update(Request $request, $id){
        $dealer = Dealer::where('id', $id)->first();
        $image = $request->image;
        if ($image != $dealer->image) {
            if ($request->has('image')) {
                $destinationPath = 'images/dealer/';
                if ($dealer->image){
                    $file_old = public_path('/').$destinationPath.$dealer->image;
                    if (file_exists($file_old)){
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/dealer/') . $name);
            } else {
                $name = $dealer->image;
            }
        } else{
            $name = $dealer->image;
        }
        $dealer->area_id = $request->area_id;
        $dealer->district_id = $request->district_id;
        $dealer->upazilla_id = $request->upazilla_id;
        $dealer->address = $request->address;
        $dealer->responsible_person = $request->dealer_name;
        $dealer->dealer_code = $request->dealer_code;
        $dealer->store_name = $request->store_name;
        $dealer->dealer_type = $request->dealer_type;
        $dealer->mobile = $request->mobile;
        $dealer->lat = $request->lat;
        $dealer->lon = $request->long;
        $dealer->image =$name;
        $dealer->save();
        return response()->json(['message' => 'Dealer updated Successfully', 200]);
    }

    public function destroy($id)
    {
        Dealer::where('id', $id)->delete();
        return response()->json(['message' => 'Dealer Deleted Successfully', 200]);
    }

    public function search($query)
    {
        return new DealerCollection(Dealer::Where('responsible_person', 'like', "%$query%")->latest()->paginate(10));
    }
}
