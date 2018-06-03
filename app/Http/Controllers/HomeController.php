<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
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
                    'users_score.score as score',
                    'users_session.id as id',
                    'users_session.status as status',
                    'users_session.created_at as created_at',
                    'users_session.updated_at as updated_at'
                )
                ->leftjoin('users_score','users_score.users_session_id','=','users_session.id')
                ->where('users_session.users_id', $users_id)
                ->orderBy('users_session.updated_at', 'desc')
                ->get();
        for ($i=0; $i < count($users_session); $i++) { 
            $array_users_history[$i]['id'] = $users_session[$i]->id;
            $users_progress = DB::table('users_progress')
                                ->where('users_session_id', $users_session[$i]->id)
                                ->where('status', 'Inactive')
                                ->count();
            $status = ($users_progress == 10)?'Win':'Lose';
            if ($status == 'Lose') { $status = ($users_session[$i]->updated_at)?'Lose':'Playing'; }
            $array_users_history[$i]['status'] = $status;
            $array_users_history[$i]['score'] = ($users_session[$i]->score > 0)?$users_session[$i]->score:'-';
            $array_users_history[$i]['created_at'] = date('d M Y, (H:i:s)', strtotime($users_session[$i]->created_at));
            $array_users_history[$i]['updated_at'] = ($users_session[$i]->updated_at)?date('d M Y, (H:i:s)', strtotime($users_session[$i]->updated_at)):'-';
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
        $data['history'] = $array_users_history;
        // Users Session
        $users_session = DB::table('users_session')
                        ->where('users_id', $users_id)
                        ->where('status', 'Active')
                        ->get()
                        ->first();
        if ($users_session) {

        }
        $data['play'] = ($users_session)?'Continue':'New';

        return view('home', $data);
    }
}
