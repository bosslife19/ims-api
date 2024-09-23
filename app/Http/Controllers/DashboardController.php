<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function get_dashboard_info():JsonResponse
    {
        $user = auth()->user();
        if($user->role->name == 'admin'){
            $dashboard_info = [
                "total_schools" => 0,
                "total_inventory_items" => 0,
                "stock_level_summary" => [],
                "material_usage_chart" => [],
                "material_usage_graph" => [],
                "recent_activities" => []
            ];
        } else if ($user->role->name == 'qa') {
            $dashboard_info = [

            ];
        } else if ($user->role->name == 'warehouse-staff') {
            $dashboard_info = [
                "total_inventory_items" => 0,
                "low_stock_alerts" => 0,
                "scanned_items" => 0,
                "reported_items" => 0,
                "stock_level_summary" => [],
                "recent_activities" => []
            ];
        } else if ($user->role->name == 'head-teacher') {
            $dashboard_info = [
                "total_inventory_items" => 0,
                "low_stock_alerts" => 0,
                "stock_level_summary" => [],
                "material_usage_chart" => [],
                "material_usage_graph" => [],
            ];
        }

        return response()->json(["dashboard_info" => $dashboard_info], Response::HTTP_OK);
    }
}
