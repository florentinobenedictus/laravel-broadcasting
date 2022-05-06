<?php

namespace App\Http\Controllers;

use App\Events\Chat;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index(Request $request){
		event(new Chat(
			$request->input('username'),
			$request->input('message'),
		));
		
		return true;
	}
}
