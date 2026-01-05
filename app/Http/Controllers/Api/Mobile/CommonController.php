<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Models\Area;
use App\Models\Crop;
use App\Models\Menu;
use App\Models\Role;
use App\Models\User;
use App\Models\Brand;
use App\Models\Dealer;
use App\Models\JobCard;
use App\Models\Section;
use App\Models\Upazila;
use App\Models\Customer;
use App\Models\District;
use App\Models\FuelPump;
use App\Models\Products;
use App\Models\Showroom;
use App\Models\ServiceTips;
use App\Models\ServiceType;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Models\HarvesterInfo;
use App\Models\SeasonalCrops;
use App\Models\ServiceCenter;
use App\Models\ServicingType;
use App\Models\HarvesterParts;
use App\Models\HarvestingCost;
use App\Models\HarvesterService;
use App\Models\SparePartsMirror;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DailyFoodPriceCollection;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\Dealer\DealerCollection;
use App\Http\Resources\Customer\CustomerCollection;
use App\Http\Resources\FuelPump\FuelPumpCollection;
use App\Http\Resources\Products\ProductsCollection;
use App\Http\Resources\Showroom\ShowroomCollection;
use App\Http\Resources\HarvesterServiceDetailsCollection;
use App\Http\Resources\ServiceTips\ServiceTipsCollection;
use App\Http\Resources\ProductModel\ProductModelCollection;
use App\Http\Resources\HarvesterInfo\HarvesterInfoCollection;
use App\Http\Resources\SeasonalCrops\SeasonalCropsCollection;
use App\Http\Resources\ServiceCenter\ServiceCenterCollection;
use App\Http\Resources\HarvesterParts\HarvesterPartsCollection;
use App\Http\Resources\ServiceRequest\ServiceRequestJobCardCollection;

class CommonController extends Controller
{
    public function getAllDistricts(Request $request){
        // $today = date('Y-m-d');
        $today = $request->date;
        
        $submittedDistricts = DailyFoodPriceCollection::whereDate('Date', $today)
                            ->distinct()
                            ->pluck('DistrictCode')
                            ->toArray();

        $submittedDistricts = array_map(function ($code) {
            return str_pad((string)$code, 2, '0', STR_PAD_LEFT);
        }, $submittedDistricts);

        $districts = District::orderBy('DistrictName', 'asc')->get();
        
        $districtList = [];

        foreach ($districts as $district) {
            $code = str_pad((string) $district->DistrictCode, 2, '0', STR_PAD_LEFT);
            $districtList[] = [
                'DistrictCode' => $code,
                'DistrictName' => $district->DistrictName,
                'Type' => $district->Type,
                'Submitted' => in_array($code, $submittedDistricts) ? 'Y' : 'N',
            ];
        }

        return response()->json([
            'districts' => $districtList
        ]);
    }

    public function getAllUpazillaMokamByDistricts(Request $request){
        $district_id = $request->district_id;

        $upazillas = Upazila::where('DistrictCode', $district_id)
                    ->orderBy('UpazillaName', 'asc')->get();

        $upazillaList = [];
        foreach ($upazillas as $upazilla) {
            $upazillaList[] = [
                'DistrictCode' => str_pad((string) $upazilla->DistrictCode, 2, '0', STR_PAD_LEFT),
                'UpazillaCode' => str_pad((string) $upazilla->UpazillaCode, 4, '0', STR_PAD_LEFT),
                'UpazillaName' => $upazilla->UpazillaName,
            ];
        }

        $mokams = DB::table('Mokam')->where('DistrictCode', $district_id)
                ->orderBy('MokamName', 'asc')
                ->get();

        return response()->json([
            'upazillas' => $upazillaList,
            'mokams' => $mokams
        ]);
    }

    public function getAllBrand()
    {
        $brands = Brand::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'brands' => $brands
        ]);
    }

    public function getAllUser()
    {
        $users = User::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'users' => $users
        ]);
    }

    public function getAllCustomer()
    {
        $customers = Customer::where('customer_type','harvester')->orderBy('id', 'asc')->get();
        return new CustomerCollection($customers);
    }

    public function getAllMenu()
    {
        $menus = Menu::orderBy('CreatedAt', 'desc')->get();
        return response()->json([
            'menus' => $menus
        ]);
    }

    public function getAllRole()
    {
        $roles = Role::orderBy('CreatedDate', 'desc')->get();
        return response()->json([
            'roles' => $roles
        ]);
    }

    public function getAllServiceCenter()
    {
        $service_centers = ServiceCenter::orderBy('created_at', 'desc')->get();
        return new ServiceCenterCollection($service_centers);
    }

    public function getAllShowroom()
    {
        $showrooms = Showroom::orderBy('created_at', 'desc')->get();
        return new showroomCollection($showrooms);
    }

    public function getAllArea(){
        $areas = Area::OrderBy('name','asc')->where('active','Y')->get();
        return response()->json([
            'areas'=>$areas
        ]);
    }

    public function getAllProductModel(){
        $models = ProductModel::OrderBy('id','asc')->where('product_id',4)->get();
        return new ProductModelCollection($models);

    }

    public function getAllProducts(){
        $products = Products::OrderBy('id','asc')->where('id',4)->get();
        return new ProductsCollection($products);
    }

    public function getAllMirrorProduct(){

        $mirror_products = SparePartsMirror::OrderBy('ProductCode','desc')
            ->select('ProductCode','ProductName','UnitPrice')
            ->where('Business','W')
            ->where('Active','Y')
            ->get();
        return response()->json([
            'mirror_products'=>$mirror_products
        ]);

    }

    public function getAllPriceByMirror($ProductCode)
    {
        $prices = SparePartsMirror::select('UnitPrice','ProductCode','ProductName')
            ->where('Business', 'W')
            ->where('Active', 'Y')
            ->where('ProductCode', $ProductCode)
            ->get();

        return response()->json([
            'prices' => $prices
        ]);
    }

    public function getAllServiceType(){
        $service_types = ServiceType::OrderBy('id','asc')->paginate(15);
        return response()->json([
            'service_types'=>$service_types
        ]);
    }

    public function getAllHarvesterCost(){
        $harvesting_cost = HarvestingCost::all();
        $data = [];
        foreach ($harvesting_cost as $key => $value){
            $data [$value->cost_title]= $value->price;
        }
        return $data;
    }

    public function getAllServicingType()
    {
        $servicing_types = ServicingType::OrderBy('id', 'asc')->get();
        return response()->json([
            'servicing_types' => $servicing_types
        ]);
    }

    public function getAllSectionList()
    {
        $sections = Section::OrderBy('id', 'asc')->get();
        return response()->json([
            'sections' => $sections
        ]);
    }

    public function getAllTechnician()
    {
        $technitians = User::OrderBy('id', 'asc')->where('role_id',3)->get();
        return response()->json([
            'technitians' => $technitians
        ]);
    }

    public function getAllEngineer()
    {
        $engineers = User::OrderBy('id', 'asc')->where('role_id',2)->get();
        return new UserCollection($engineers);
    }

    public function getAllFuelPump()
    {
        $fule_pump = FuelPump::OrderBy('fuel_pump_id', 'asc')->get();
        return new FuelPumpCollection($fule_pump);
    }

    public function getAllDealer()
    {
        $dealer = Dealer::OrderBy('id', 'desc')->where('active','Y')->get();
        return new DealerCollection($dealer);
    }

    public function getAllHarvesterServiceDetails(Request $request){
        $hour = $request->hour;
        $harvester_services = HarvesterService::orderBy('created_at', 'desc')->with('ServicingType','ProductModel','SparePartsMirror')
            ->where('from_hr','<=', $hour)
            ->where('to_hr','>=', $hour)
            ->where('model_id',$request->model_id)->get();
       return new HarvesterServiceDetailsCollection($harvester_services);
    }

    public function getAllHarvesterInfo(){
        $harvester_infos = HarvesterInfo::with('ProductModel','Products')->orderBy('created_at', 'desc')->paginate(15);
        return response()->json([
            'product_list' => new HarvesterInfoCollection($harvester_infos)
        ]);
    }

    public function getAllHarvesterParts(Request $request){
        //$product_model_id = $request->product_model_id;
        $section_id = $request->section_id;
        $query = $request->search;
        $harvester_parts = HarvesterParts::query()->with('SparePartsMirror');
//        if (!empty($product_model_id)){
//            $harvester_parts = $harvester_parts->where('product_model_id',$product_model_id);
//        }
        if (!empty($section_id)){
            $harvester_parts = $harvester_parts->where('section_id',$section_id);
        }
        if (!empty($query)){
            $harvester_parts = $harvester_parts->where('ProductCode','like','%'.$query.'%');
        }
        $harvester_parts = $harvester_parts->orderBy('created_at', 'desc')->get();
        return new HarvesterPartsCollection($harvester_parts);
    }

    public function getAllDistrictWiseSeasonalCrops(Request $request){
        $district_id = $request->district_id;
        $seasonal_crops_id = $request->seasonal_crops_id;

        $seasonal_crops = SeasonalCrops::query()->with('District','Crop');
        if ($district_id){
            $seasonal_crops = $seasonal_crops ->where('district_id',$district_id);
        }
        if ($seasonal_crops_id){
            $seasonal_crops = $seasonal_crops->where('seasonal_crops_id',$request->seasonal_crops_id);
        }
        $seasonal_crops = $seasonal_crops->orderBy('created_at', 'desc')->get();
        return new SeasonalCropsCollection($seasonal_crops);
    }

    public function getAllCrops(){
        $crops = Crop::orderBy('created_at', 'desc')->get();
        return response()->json([
            'crops' => $crops
        ]);
    }

    public function getAllDistrictByArea(Request $request){
        $area_id = $request->area_id;
        $area_wise_districts = District::where('area_id',$area_id)->get();
        return response()->json([
            'area_wise_districts' => $area_wise_districts
        ]);
    }

    public function getAllSections(){
        $sections = Section::orderBy('created_at', 'asc')->where('product_id',4)->get();
        return response()->json([
            'sections' => $sections
        ]);
    }

    public function getAllServiceTips(){
        $service_tips = ServiceTips::orderBy('created_at', 'desc')->get();
        return response()->json([
            'service_tips' => new ServiceTipsCollection($service_tips)
        ]);
    }

    public function getAllServiceEngineer(){
        $service_engineers = User::orderBy('created_at', 'asc')->where('role_id',2)->where('is_active',1)->get();
        return new UserCollection($service_engineers);
    }

    public function getAllPendingServiceRequestList(){
        $job_cards = JobCard::orderBy('created_at', 'asc')->get();
        return response()->json([
            'job_cards' => new ServiceRequestJobCardCollection($job_cards)
        ]);
    }

    public function getAllCompletedServiceRequestList()
    {
        $job_cards = JobCard::orderBy('created_at', 'asc')->get();
        return response()->json([
            'job_cards' => new ServiceRequestJobCardCollection($job_cards)
        ]);
    }

    public function getAllServiceRequestDetailsList()
    {
        $job_cards = JobCard::orderBy('created_at', 'asc')->get();
        return response()->json([
            'job_card' => new ServiceRequestJobCardCollection($job_cards)
        ]);
    }

    public function getAllDistrictsUpazilla()
    {
        $districts = District::orderBy('created_at', 'desc')->where('active','Y')->get();
        $upazilla = Upazila::orderBy('created_at', 'desc')->where('active','Y')->get();
        return response()->json([
            'districts' => $districts,
            'upazilla' => $upazilla,
        ]);
    }

    public function getAllModelByProduct($id){
        return new ProductModelCollection(ProductModel::where('product_id',$id)->get());
    }

    public function getAllProblemSection(){
        $sections = Section::select('id','name','product_id')->where('product_id',4)->get();
        return response()->json([
            'status' => 'success',
            'sections' => $sections
        ]);
    }
}
