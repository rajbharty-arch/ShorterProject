<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\url;
use App\Models\Payment;
use Razorpay\Api\Api;


use App\Models\company;
use App\Mail\gmailMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
use App\Http\Middleware\AuthMiddleware;
use App\Jobs\SendInvoice;
class UserController extends Controller
{
    private  $api ;
    public function __construct(){
        $this->api = new Api(
            env('RAZOR_PAY_ID'),
            env('RAZOR_PAY_SECURTE_KEY'));
    }
    public function logout(Request $request){
        $request->session()->flush();
        if ($request->session()->has('email')) {
            return  redirect()->route('login');
        }else{
            return  redirect()->route('loginViewPage');

        }

    }

    public function login(Request $request){
        
      
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $userData = Auth::user();

                 $session = array(
                    'user_id' => $userData->id,
                    'email'   => $userData->email,
                    'type'    => $userData->type,
                    'c_id'    => $userData->c_id,
                 );
                 session($session);  //session set

               return redirect()->route('dashboard');
            }else{
                return "nhi mila";
            }

       }

       public function dashboard(Request $request){ 
        $authUser =  Auth::user();
        $subQuery = DB::table('urls')
        ->selectRaw('COUNT(urls.id) as urlsnum, companies.id as companyId, companies.name as c_name')
        ->rightJoin('users', 'users.id', '=', 'urls.user_id')
        ->join('companies', 'companies.id', '=', 'users.c_id')
        ->where('users.id', '<>', 9);
        if($authUser->type == 'admin'){
            $subQuery->where('users.c_id','=',$authUser->c_id);
        }elseif($authUser->type == 'user'){
            $subQuery->where('users.id','=',$authUser->id);

        }
        $subQuery->groupBy('users.id');
    
        $details = DB::query()
        ->fromSub($subQuery, 'temp')
        ->selectRaw('COUNT(companyId) as users, SUM(urlsnum) as url, c_name as company')
        ->groupBy('companyId')
        ->get();

        $urlSql = DB::table('urls')
        ->select('urls.*','companies.name as company')
        ->join('users','users.id','=','urls.user_id')
        ->join('companies','companies.id','=','users.c_id');
    
        if($authUser->type == 'admin'){
            $urlSql->where('users.c_id','=',$authUser->c_id);
        }elseif($authUser->type == 'user'){
            $urlSql->where('users.id','=',$authUser->id);
        }
        $urlDetials = $urlSql->get();
        

        
        return  view('dashboard',compact('details','urlDetials'));
       }
        
    
   

  function invite(Request  $request){
     return  view('invite');
   }

  function addInvite(Request $request){
    $authUser = Auth::user();
    if($authUser->type == 'superAdmin'){
     $c_id =  company::create([
      'name' => $request->company_name
    ])->id;
    }else{
       $c_id = $authUser->c_id;
    }

    $rand = rand();
     User::create([
        'name'     => $request->name,
        'password' => Hash::make($rand),
        'email'    => $request->email,
        'c_id'     => $c_id,
        'type'     =>  $authUser->type == 'superAdmin' ? 'admin' : $request->role
    ]);
    $subject = "Invitation";
    $data = array(
        'email'    => $request->email,
        'password' => $rand
      );
        
       SendInvoice::dispatch($request->email,$subject,$data);

      return redirect()->route('dashboard')->with('success', 'Mail sent successfully.');

  }

  public function addURL(Request $request){
     $insertData=  url::create([
                      'org_url' => $request->org_url,
                      'm_url'   => rand(),
                      'user_id' => AUTH::user()->id,
                      'hits'    =>  0,
                      'amount'  => $request->amount,
      ]);
      if(!empty( $insertData)){
        echo json_encode(['status' => true]);
      }else{
        echo json_encode(['status' => false]);
      }
  }

  function shortUrl($urlCode = ''){
     if(!empty($urlCode)){
        $urDetails =  url::where('m_url',$urlCode)->first();
        
        $hits =  $urDetails->hits+1;

        url::where('id', $urDetails->id)
            ->update(['hits'=>$hits]);
        return redirect($urDetails->org_url);
     }else{
        return redirect()->route('pageNotFound');
     }
     
  }

  function payment($id){
     
      $urlData =  url::find($id);
      
     
      $order =  $this->api->order->create([
        'amount' => $urlData->amount*100, // amount in paise (100 paise = 1 rupee)
        'currency' => 'INR',
        'receipt' => 'Appsquadz'.rand(1000,9999)
    ]);
      
      Payment::create([
        'order_id' => $order->id
      ]);
      return view('payment',compact('order')); 
  }

  function paymentCallback(Request $request){
       $success = true; 
       if(!empty($request)){
        $updateTable =  Payment::where('order_id',$request->razorpay_order_id)
                  ->update([
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature'  => $request->razorpay_signature,
                  ]);
        if($updateTable){
            try{
                $attributes = array(
                 'razorpay_order_id'   => $request->razorpay_order_id,
                 'razorpay_payment_id' => $request->razorpay_payment_id,
                 'razorpay_signature'  => $request->razorpay_signature
                );
                $this->api->utility->verifyPaymentSignature($attributes);
            }catch(\Razorpay\Api\Errors\SignatureVerificationError $e){
              $success = false;
              $error = 'Razorpay Signature Verification Failed';
            }
            if($success){
                $paymentDetails =  $this->api->payment->fetch($request->razorpay_payment_id);
                
                $updateStatus =  Payment::where('order_id',$request->razorpay_order_id)
                ->update([
                  'status' => $paymentDetails->status,
                ]);
                if($updateStatus->status == 'captured'){
                    return view('payment_success');

                }
            }

        }
        
       }

    
  }
}
