<?php

namespace App\Http\Controllers\Support;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Input, Redirect;
use DB;
use Mail;
use Response;

class SupportController extends Controller
{
    public function index(){
        $wallet = file_get_contents("php://input"); 
		$wallet = json_decode($wallet, true); 
		
        $valid = Validator::make($wallet,[
                'username' => 'required', 
                'message' => 'required',
                'email' => 'required|email',
                'cattegory' => 'required']);
		if($valid->fails())
		{     
	        return Response::make(['error' => $valid->errors()], 406);
       	}else{
      	    $headers = 'From: webmaster@example.com' . "\r\n" .
                'Reply-To: webmaster@example.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            
            mail($wallet['email'], $wallet['cattegory'], $wallet['message'], $headers);
            return response()->json('OK');
		}
    }
}