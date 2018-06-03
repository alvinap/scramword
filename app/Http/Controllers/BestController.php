<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class BestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Data Array
        $data = [];

        // Get Data
        $users_id = auth()->id();
        // Users Progress
        $array_users_history = [];
        $users_session = DB::table('users_session')
                ->select(
                    'users.name as name',
                    'users_score.score as score',
                    'users_session.id as id',
                    'users_session.status as status',
                    'users_session.created_at as created_at',
                    'users_session.updated_at as updated_at'
                )
                ->join('users_score','users_score.users_session_id','=','users_session.id')
                ->join('users','users.id','=','users_session.users_id')
                ->where('users_session.users_id', $users_id)
                ->where('users_session.status', 'Inactive')
                ->orderBy('users_score.score', 'desc')
                ->get();
        for ($i=0; $i < count($users_session); $i++) { 
            $users_progress = DB::table('users_progress')
                                ->where('users_session_id', $users_session[$i]->id)
                                ->where('status', 'Inactive')
                                ->count();
            if ($users_progress == 10) {
                $array_users_history[$i]['name'] = $users_session[$i]->name;
                $array_users_history[$i]['score'] = ($users_session[$i]->score > 0)?$users_session[$i]->score:'-';
                // Interval
                $created_at = date_create($users_session[$i]->created_at);
                $updated_at = date_create($users_session[$i]->updated_at);
                $interval = date_diff($created_at, $updated_at);
                $hh = $interval->format('%h');
                $mm = $interval->format('%i');
                $ss = $interval->format('%s');
                $interval = "";
                if ($hh > 0) { $interval .= $hh.' Hours '; }
                if ($mm > 0) { $interval .= $mm.' Minutes '; }
                if ($ss > 0) { $interval .= $ss.' Seconds '; }
                $array_users_history[$i]['interval'] = ($users_session[$i]->updated_at)?$interval:'-';
            }
        }
        $data['history'] = $array_users_history;

        return view('best', $data);
    }
}
