<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DetailController extends Controller
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
    public function index($param)
    {
        // Data Array
        $data = [];

        // Get Data
        $users_id = auth()->id();
        // Users Word
        $users_word = DB::table('users_word')
                        ->where('users_session_id', $param)
                        ->orderBy('updated_at', 'asc')
                        ->get();
        foreach ($users_word as $row) {
            $row->created_at = date('d M Y, (H:i:s)', strtotime($row->created_at));
            $row->score = ($row->score < 0)?'Lose':$row->score;
        }
        $data['users_word'] = $users_word;

        return view('detail', $data);
    }
}
