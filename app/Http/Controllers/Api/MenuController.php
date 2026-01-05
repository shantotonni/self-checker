<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Http\Resources\MenuCollection;
use App\Http\Resources\MenuResource;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\UserMenu;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    public function index()
    {
        return new MenuCollection(MenuItem::with('menu')->paginate(15));
    }

    public function store(MenuRequest $request)
    {
        $menu_item = new MenuItem();
        $menu_item->MenuID = $request->MenuID;
        $menu_item->Name = $request->Name;
        $menu_item->Icon = $request->Icon;
        $menu_item->Link = $request->Link;
        $menu_item->Order = $request->Order;
        $menu_item->Status = 'Y';
        $menu_item->save();

        return response()->json([
            'message'=>'Menu Item Created Successfully'
        ],200);
    }

    public function edit($id)
    {
        $menu = Menu::where('MenuID',$id)->first();
        return new MenuResource($menu);
    }

    public function update(MenuRequest $request, $id)
    {
        $menu_item = MenuItem::where('Id',$id)->first();
        $menu_item->MenuID = $request->MenuID;
        $menu_item->Name = $request->Name;
        $menu_item->Icon = $request->Icon;
        $menu_item->Link = $request->Link;
        $menu_item->Order = $request->Order;
        $menu_item->Status = 'Y';
        $menu_item->save();

        return response()->json([
            'message'=>'Menu Item Updated Successfully'
        ],200);
    }

    public function destroy($id)
    {
        $menu_item = MenuItem::where('Id',$id)->first();
        if ($menu_item) {
            $user_menu = UserMenu::where('MenuID',$id)->get();
            foreach ($user_menu as $value){
                $value->delete();
            }
        }

        MenuItem::where('Id',$id)->delete();

        return response()->json([
            'message'=>'Menu Item Deleted Successfully'
        ],200);
    }

    public function search($query)
    {
        return new MenuCollection(MenuItem::where('Name','LIKE',"%$query%")->paginate(20));
    }

    public function getAllMenu(){
        $menus = Menu::orderBy('MenuID','desc')->get();
        return response()->json([
            'menus'=>$menus
        ]);
    }
}
