<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\UserMenu;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class MenuPermissionController extends Controller
{
    public function getUserMenuPermission($userId)
    {
        $data['menu'] = Menu::select('MenuID', 'Name')->with(['MenuItem' => function ($item) {
            $item->select('Id', 'MenuID', 'Name');
        }])->orderBy('Order', 'asc')->get();

        $data['usermenu'] = UserMenu::where('UserCode', $userId)->pluck('MenuItemId');

        return response()->json([
            'data' =>$data
        ],200);
    }

    public function saveUserMenuPermission(Request $request)
    {
        $userId = $request->userId;
        $permission = $request->permission;
        $sortedPerm = [];
        foreach ($permission as $key => $value) {
            if ($value) array_push($sortedPerm, $key);
        }

        $current = UserMenu::where('UserCode', $userId)->pluck('MenuItemId')->toArray();
        $inserted = array_diff($sortedPerm, $current);
        foreach ($inserted as $item) {
            UserMenu::create(['UserCode' => $userId, 'MenuItemId' => $item]);
        }
        $remove = array_diff($current, $sortedPerm);
        UserMenu::where('UserCode', $userId)->whereIn('MenuItemId', $remove)->delete();

        return response()->json(['message' => "Menu permissions updated Successfully"]);
    }

    public function getSidebarAllUserMenu(){
        $user = JWTAuth::parseToken()->authenticate();
        $menuId = MenuItem::join('UserMenu', 'UserMenu.MenuItemId', 'MenuItem.Id')
            ->where('UserMenu.UserCode', $user->UserCode)
            ->pluck('MenuItem.MenuID');

        $menuItemId = MenuItem::join('UserMenu', 'UserMenu.MenuItemId', 'MenuItem.Id')
            ->where('UserMenu.UserCode', $user->UserCode)
            ->pluck('MenuItem.Id');

        $menu = Menu::whereIn('MenuID', $menuId)->where('Status', '1')
            ->orderBy('Order', 'asc')->with(['menuItem' => function ($item) use ($menuItemId) {
                $item->whereIn('Id', $menuItemId)->where('Status', '1');
            }])->get();
        return response()->json(['user_menu'=> $menu],200);
    }
}
