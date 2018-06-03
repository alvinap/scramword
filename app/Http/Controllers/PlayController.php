<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PlayController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
    	$current_date = date('Y-m-d H:i:s');

    	// Data Array
        $data = [];

        // Get Data
        // Users
        $users_id = auth()->id();
        $users = DB::table('users')
        		->where('id', $users_id)
        		->get()
        		->first();
        $data['users'] = $users; // Parsing to Array
        // Users Session
        $users_session_id = null;
        $users_session = DB::table('users_session')
        		->where('users_id', $users_id)
        		->where('status', 'Active')
        		->get()
        		->first();
        if ($users_session) {
        	$users_session_id = $users_session->id;
        } else {
        	DB::table('users_session')
        		->insert(['users_id' => $users_id, 'created_at' => $current_date, 'updated_at' => null]);
        	$users_session = DB::table('users_session')
        		->where('users_id', $users_id)
        		->where('status', 'Active')
        		->get()
        		->first();
        	$users_session_id = $users_session->id;
        }
        $data['users_session_id'] = $users_session_id; // Parsing to Array
        // Users Score
        $users_score = DB::table('users_score')
        		->where('users_session_id', $users_session_id)
        		->orderBy('updated_at', 'desc')
        		->get()
        		->first();
        $score = ($users_score)?$users_score->score:0;
        $data['score'] = $score; // Parsing to Array
       	// Users Progress
        $users_progress = DB::table('users_progress')
        		->select('word.sort as sort')
				->join('word','word.id','=','users_progress.word_id')
        		->where('users_progress.users_session_id', $users_session_id)
        		->where('users_progress.status', 'Active')
        		->orderBy('users_progress.updated_at', 'desc')
        		->get()
        		->first();
        $progress = ($users_progress)?$users_progress->sort:0;
        $data['progress'] = $progress; // Parsing to Array
        // Word
        $array_word = [];
        $word = DB::table('word')
        		->orderBy('sort', 'asc')
        		->get();
        for ($i=0; $i < count($word); $i++) {
        	$scramble = str_shuffle($word[$i]->name);
        	while ($scramble == $word[$i]->name) {
        	 	$scramble = str_shuffle($word[$i]->name);
        	} 
        	$array_word[$i]['id'] = $word[$i]->id;
        	$array_word[$i]['word'] = $word[$i]->name;
        	$array_word[$i]['scramble'] = $scramble;
        	$array_word[$i]['word_users'] = null;
        	$array_word[$i]['scramble_users'] = null;
        	// Users Word
	        $users_word = DB::table('users_word')
	        		->where('users_session_id', $users_session_id)
	        		->where('word_id', $word[$i]->id)
	        		->where('status', 'Correct')
	        		->get()
	        		->first();
	        if ($users_word) {
	        	$array_word[$i]['word_users'] = $users_word->word;
        		$array_word[$i]['scramble_users'] = $users_word->scramble;
	        }
        }
        $data['word'] = $array_word; // Parsing to Array

        // Interval
        $users_session_new = DB::table('users_session')
                ->where('users_id', $users_id)
                ->whereNull('updated_at')
                ->get()
                ->first();
        $created_at = date_create($users_session_new->created_at);
        $updated_at = date_create($current_date);
        $interval = date_diff($created_at, $updated_at);
        $hh = $interval->format('%h');
        $mm = $interval->format('%i');
        $ss = $interval->format('%s');
        $interval = [];
        if ($hh > 0) { $interval['hours'] = $hh.' Hours '; }
        if ($mm > 0) { $interval['minutes'] = $mm.' Minutes '; }
        if ($ss > 0) { $interval['seconds'] = $ss.' Seconds '; }
        $data['interval'] = $interval;
        // dd($interval); die();

        // Return to View
        return view('play', $data);
    }

    public function post(Request $request)
    {
    	$win = false;
    	$status = '';
        $users_id = auth()->id();
        $current_date = date('Y-m-d H:i:s');

        // Users Session
        $users_session = DB::table('users_session')
        		->where('users_id', $users_id)
        		->where('status', 'Active')
        		->get()
        		->first();
        $users_session_id = $users_session->id;

        // User Score
        $users_score = DB::table('users_score')
        		->where('users_session_id', $users_session_id)
        		->orderBy('updated_at', 'desc')
        		->get()
        		->first();
        $score = ($users_score)?$users_score->score:0;

        // Score
        // User Score
        $score_correct = DB::table('score')
        		->where('type', 'Correct')
        		->get()
        		->first();
        // User Score
        $score_wrong = DB::table('score')
        		->where('type', 'Wrong')
        		->get()
        		->first();

        // Define Variable Request
        $id = $request->input('id');
        $name = $request->input('name');
        $scramble = $request->input('scramble');

        // Get Word Table
        $word = DB::table('word')
        		->where('id', $id)
        		->get()
        		->first();
        $post_users_word = [];
        $post_users_word['users_session_id'] = $users_session_id;
        $post_users_word['word_id'] = $id;
        $post_users_word['scramble'] = $scramble;
        $post_users_word['word'] = $name;
        $post_users_word['created_at'] = $current_date;
        $post_users_word['updated_at'] = $current_date;
        if ($name == $word->name) {
        	$status = 'Correct';
        	$score = (int)$score+(int)$score_correct->score;
        	$post_users_word['score'] = $score;
        	$post_users_word['status'] = 'Correct';
        	// update users_progress
        	DB::table('users_progress')
                ->where('users_session_id', $users_session_id)
                ->update(['status' => 'Inactive']);
        	// insert users_progress
        	$post_users_progress = [];
        	$post_users_progress['users_session_id'] = $users_session_id;
        	$post_users_progress['word_id'] = $id;
        	$post_users_progress['status'] = 'Active';
        	$post_users_progress['created_at'] = $current_date;
        	$post_users_progress['updated_at'] = $current_date;
        	DB::table('users_progress')
        		->insert($post_users_progress);
        	// if win
        	$users_progress = DB::table('users_progress')
                                ->where('users_session_id', $users_session_id)
                                ->where('status', 'Inactive')
                                ->count();
            if ($users_progress == 9) {
            	// update users_progress
            	DB::table('users_progress')
	                ->where('users_session_id', $users_session_id)
	                ->update(['status' => 'Inactive']);
	            // Inactive users session
	            DB::table('users_session')
	                ->where('id', $users_session_id)
	                ->update(['status' => 'Inactive','updated_at' => $current_date]);
	            $win = true;
            }
        } else {
        	$status = 'Wrong';
        	$score = (int)$score-(int)$score_wrong->score;
        	$post_users_word['score'] = $score;
        	$post_users_word['status'] = 'Wrong';
        	if ((int)$score < 0) {
        		// update users_progress
	        	DB::table('users_progress')
	                ->where('users_session_id', $users_session_id)
	                ->update(['status' => 'Inactive']);
	            // Inactive users session
	            DB::table('users_session')
	                ->where('id', $users_session_id)
	                ->update(['status' => 'Inactive','updated_at' => $current_date]);
        	}
        }
        // insert users_word
        DB::table('users_word')
        	->insert($post_users_word);
        // users_score
        if ($users_score) {
        	// update
        	DB::table('users_score')
                ->where('users_session_id', $users_session_id)
                ->update(['score' => $score]);
        } else {
        	// insert
        	$post_users_score = [];
	        $post_users_score['users_session_id'] = $users_session_id;
	        $post_users_score['score'] = $score;
	        $post_users_score['created_at'] = $current_date;
	        $post_users_score['updated_at'] = $current_date;
        	DB::table('users_score')
        		->insert($post_users_score);
        }
        
        $return = [
        	'status' => $status,
        	'score' => $score,
        	'win' => $win
        ];

        return response()->json($return);
    }
}
