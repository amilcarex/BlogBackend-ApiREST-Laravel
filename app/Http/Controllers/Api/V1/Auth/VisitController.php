<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FormatTime;

class VisitController extends Controller
{
    //
    public function getVisits(Request $request)
    {
        $previousYear = date('Y', strtotime('-' . 1 . ' year'));
        $currentYear = date('Y');


        $visits = DB::table('public_views')->select('views')->whereYear('updated_at', $currentYear)->whereNull('post_id')->whereNull('post_tittle')->orderBy('updated_at', 'desc')->get();
        $total_visits = 0;


         if(DB::table('public_views')->whereYear('updated_at', $currentYear)->orderBy('updated_at', 'desc')->first()){
            $updated = DB::table('public_views')->whereYear('updated_at', $currentYear)->orderBy('updated_at', 'desc')->first();
            $updated = FormatTime::LongTimeFilter(new \Datetime($updated->updated_at));
        }else{
            $updated = null;   
        }
       
        foreach($visits as $page_visits){
            $total_visits = $total_visits + $page_visits->views;
        }

        $current_month = date('m');
        $previous_month = date('m', strtotime('-' . 1 . ' month'));

        $current_month_visits = DB::table('public_views')->select('views')->whereYear('updated_at', $currentYear)->whereMonth('updated_at', $current_month)->whereNull('post_id')->whereNull('post_tittle')->orderBy('updated_at', 'desc')->get();


        $previous_month_visits = DB::table('public_views')->select('views')->whereYear('updated_at', $currentYear)->whereMonth('updated_at', $previous_month)->whereNull('post_id')->whereNull('post_tittle')->orderBy('updated_at', 'desc')->get();
        
        $total_current_month = 0;
        $total_previous_month = 0;

        foreach ($previous_month_visits as $page_visits) {
            $total_previous_month = $total_previous_month + $page_visits->views;
        }
        foreach ($current_month_visits as $page_visits) {
            $total_current_month = $total_current_month + $page_visits->views;
        }

        
        //Visits Per Month

        $months = 12;
        $i = 1;
        $x = 1;
        $visits_array_previous_year = [];
        $visits_array_current_year = [];
       
        $series = [];
        

        for ($i; $i <= $months; $i++) {
            $month_visits = DB::table('public_views')->select('views')->whereMonth('updated_at', $i)->whereYear('updated_at', $previousYear)->whereNull('post_id')->whereNull('post_tittle')->get();
            $total_per_month = 0;
            if (count($month_visits) > 0) {
                foreach ($month_visits as $visit_per_month) {
                    $total_per_month = $total_per_month + $visit_per_month->views;
                }
                array_push($visits_array_previous_year, $total_per_month);
            } else {
                array_push($visits_array_previous_year, $total_per_month);
            }
        }

        for($x; $x <= $months; $x++){
            $month_visits = DB::table('public_views')->select('views')->whereMonth('updated_at', $x)->whereYear('updated_at', $currentYear)->whereNull('post_id')->whereNull('post_tittle')->get();
            $total_per_month = 0;
            if(count($month_visits) > 0){
                foreach ($month_visits as $visit_per_month) {
                    $total_per_month = $total_per_month + $visit_per_month->views;
                }
                array_push($visits_array_current_year, $total_per_month);
            }else{
                array_push($visits_array_current_year, $total_per_month);
            }
           
        }

        $high_per_month = max($visits_array_current_year) > max($visits_array_previous_year) ? max($visits_array_current_year) : max($visits_array_previous_year);
        $high = (round(($high_per_month + 5 / 2) / 5) * 5) + 15;
        array_push($series, $visits_array_previous_year);
        array_push($series, $visits_array_current_year);
        return response()->json(['total_visits' => $total_visits, 'updated' => $updated, 'series' => $series, 'high' => $high, 'previous_month' => $total_previous_month, 'current_month' => $total_current_month]);

    }
}
