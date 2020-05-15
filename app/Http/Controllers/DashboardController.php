<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;


// use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;

use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->table_name = 'company';
        // $this->middleware('auth'); //USE THIS TO AUTH ALL FUNCTION BELOW
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $returns = array();
        
        try{
            $returns['status'] = 'success';

            $dtCompany = DB::table('company');
            $dtCompanyJobs = DB::table('company_jobs');
            $dtRank = DB::table('rank');
            $dtUsers = DB::table('users');

            $returns['total_company'] = DB::table('company')->count();
            $returns['total_jobs'] = DB::table('company_jobs')->where('state', 1)->count();
            $returns['total_jobs_inactive'] = DB::table('company_jobs')->where('state', 0)->count();
            $returns['total_rank'] = $dtRank->count();
            $returns['total_users'] = DB::table('users')->count();
            $returns['total_seafarer'] = DB::table('users')->where('type', 'user')->count();
            $returns['total_seafarer_inactive'] = DB::table('users')->where('type', 'user')->count();
            $returns['total_seafarer_free'] = DB::table('users')->where('type', 'user')->count();
            $returns['total_seafarer_paid'] = DB::table('users')->where('type', 'user')->count();
            $returns['total_crewmanager'] = DB::table('users')->where('type', 'manager')->count();
            $returns['total_crewmanager_inactive'] = DB::table('users')->where('type', 'manager')->count();

            return response()->json($returns);

        }
        catch(\Exception $e){
            $returns['status'] = 'error';
            $returns['message'] = 'error on server!';
            // $returns['message'] = $e;

            return response()->json($returns);
        }
    }

    public function lastXCreatedSeafarer($num){
        $returns = array();
        
        try{
            $returns['status'] = 'success';

            $data = DB::table('users');
            $data->select('users.id', 'users.email', 'users.firstname', 'users.created_at', 'users.updated_at');
            $data->where('type', '=', 'user');

            $limit = $num;
            $offset = 0;
            $sort = 'created_at';

            $data->offset($offset);
            $data->limit($limit);
            $data->orderBy($sort, 'desc');

            $returns['data'] = $data->get();

            return response()->json($returns);

        }
        catch(\Exception $e){
            $returns['data'] = array();
            $returns['status'] = 'error';
            $returns['message'] = 'error on server!';
            // $returns['message'] = $e;

            return response()->json($returns);
        }
    }

    public function lastXJobs($num){
        $returns = array();
        
        try{
            $returns['status'] = 'success';

            $data = DB::table('company_jobs');
            $data->select('company.name', 'company_jobs.rank_name', 'company_jobs.vessel_type_name', 'company_jobs.created_at');
            $data->leftjoin('company', 'company_jobs.company_id', '=', 'company.id');
            // $data->where('type', '=', 'user');

            $limit = $num;
            $offset = 0;
            $sort = 'created_at';

            $data->offset($offset);
            $data->limit($limit);
            $data->orderBy($sort, 'desc');

            $returns['data'] = $data->get();

            return response()->json($returns);

        }
        catch(\Exception $e){
            $returns['data'] = array();
            $returns['status'] = 'error';
            $returns['message'] = 'error on server!';
            // $returns['message'] = $e;

            return response()->json($returns);
        }
    }


}
