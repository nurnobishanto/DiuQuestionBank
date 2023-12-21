<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Document;
use App\Models\Semester;
use App\Models\Subscription;
use App\Models\UserDocument;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function semester_list(){
        return response()->json([
            'list' => Semester::all(),
        ]);
    }
    public function department_list(){
        return response()->json([
            'list' => Department::all(),
        ]);
    }
    public function year_list(){
        return response()->json([
            'list' => Year::all(),
        ]);
    }
    public function get_document(Request $request) {
        $data = array();
        $documents = Document::where('semester_id',$request->semester_id)
            ->where('department_id',$request->department_id)
            ->where('year_id',$request->year_id)
            ->where('type',$request->type)
            ->get();

        foreach ($documents as $document) {
            $data[] = array(
                'id' => $document->id,
                'name' => $document->name,
                'type' => $document->type,
                'file' => asset('uploads/' . $document->file),
                'semester' => $document->semester->name,
                'department' => $document->department->name,
                'year' => $document->year->name,
                'updated_at' => date('d M Y, h:i A',strtotime($document->updated_at)),
            );
        }

        return response()->json([
            'documents' => $data,
        ]);
    }
    public function save_document(Request $request){

        $document_id = $request->document_id;
        $user_id = Auth::guard()->user()->id;

        $userDocument =  UserDocument::where('user_id',$user_id)->where('document_id',$document_id)->first();
        if ($userDocument){
            return response()->json(["message" =>"This document already saved in your saves"]);
        }else{
            UserDocument::create([
                'user_id' => $user_id,
                'document_id' => $document_id,
            ]);
            return response()->json(["message" =>"Document added in your saves successfully"]);
        }


    }
    public function remove_document(Request $request){

        $document_id = $request->document_id;
        $user_id = Auth::guard()->user()->id;

        $userDocument =  UserDocument::where('user_id',$user_id)->where('document_id',$document_id)->first();
        if ($userDocument){
            $userDocument->delete();
            return response()->json(["message" =>"This document removed successfully"]);
        }else{
            return response()->json(["message" =>"This document already removed from your saves"]);
        }


    }
    public function get_save_document(Request $request){
        $user_id = Auth::guard()->user()->id;
        $data = array();
        $user_documents = UserDocument::where('user_id',$user_id)->get();

        foreach ($user_documents as $ud) {
            $document = Document::find($ud->document_id);
            $data[] = array(
                'id' => $document->id,
                'name' => $document->name,
                'type' => $document->type,
                'file' => asset('uploads/' . $document->file),
                'semester' => $document->semester->name,
                'department' => $document->department->name,
                'year' => $document->year->name,
                'updated_at' => date('d M Y, h:i A',strtotime($document->updated_at)),
            );
        }

        return response()->json([
            'documents' => $data,
        ]);
    }
    public function add_subscription(Request $request)
    {
        $user_id = Auth::guard()->user()->id;
        $subscription_plan_id = $request->subscription_plan_id;

        // Get the existing subscription for the user
        $existingSubscription = Subscription::where('user_id', $user_id)
            ->where('end_date', '>', now())
            ->where('status', 'success')
            ->orderBy('end_date', 'desc')
            ->first();

        // Check if the user has already subscribed today
        $today = Carbon::now()->toDateString();
        if ($existingSubscription && $existingSubscription->start_date == $today) {
            return response()->json(['message' => 'Already Subscribed today']);
        }

        // Calculate the start date for the new subscription
        $start_date = $existingSubscription
            ? Carbon::parse($existingSubscription->end_date)->addDay()->toDateString()
            : now()->toDateString();

        // Calculate the end date for the new subscription (add 1 month)
        $end_date = Carbon::parse($start_date)->addMonth();

        // Create a new subscription
        $subscription = Subscription::create([
            'user_id' => $user_id,
            'subscription_plan_id' => $subscription_plan_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => 'success',
            'payment_method' => 'manual',
            'payment_status' => 'success',
            'auto_renew' => true,
        ]);

        return response()->json(['message' => 'Subscribed Successfully']);
    }
    public function guard()
    {
        return Auth::guard('api');
    }
}
