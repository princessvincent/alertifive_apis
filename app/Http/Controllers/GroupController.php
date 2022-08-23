<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Keyword;
use App\Models\Message;
use App\Models\Groupmember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index(Request $request){
$group = new Group();

$group->user_id = Auth::user()->id;
$group->name = $request->name;
$group->description = $request->description;

$group->save();

return response()->json([
    'status' => true,
    "msg" => "Group created Successfully!"
]);
    }
//this one is for postman/api
    public function profile(){
        $user = auth::user();

        return response()->json([
            'status' => true,
            'Fullname' => $user->fullname,
            'username' => $user->username,
            'Email' => $user->email,
        ]);
    }

    //this one for returning to view

    public function mypro(){
$user = User::where('id', auth::user()->id)->get();
return view('myviews.myprofile', compact('user'));
    }

    public function keywords(Request $request){
// $key = new Keyword();

Keyword::create([
    'group_id' => $request->group_id,
    'user_id' => auth::user()->id,
    'sender_name' => $request->sender_name,
]);

// $key->save();

return response()->json([
    'status' => true,
    "msg" => "Keywords created Successfully!"
]);

    }

    public function listgro(){
$group = Group::where('user_id', auth::user()->id)->get();
return view('myviews.viewgro', compact('group'));
    }
    //for api

    public function listgroup(){
        $groups = Group::where('user_id', auth::user()->id)->get();

              return response()->json([
            'status' => true,
            'message' => "Groups fetched successfully",
            'data' => $groups,
        ]);

      
            }
    public function inviteuser(Request $request){
$group = new Group();

$group->id = $request->group_id;
// $group->user->username = $request->invite_username;

$user = User::where('username', $request->invite_username)->exists();
if($user)
{
if(Group::where('user_id', Auth::user()->id))
{
Groupmember::create([
'group_id' => $request->group_id,
'invite_username' => $request->invite_username,
]);
return response()->json([
    "status" => true,
    "msg" => "User Invited!"
]);  

}else{
    return response()->json([
        "status" => false,
        "msg" => "You are not the Group Owner"
    ]);
}
}else{
    return response()->json([
        "status" => false,
        "msg" => "This User does not Exist!"
    ]);
}
    }

    public function groupuser($id){
         $group =  Group::find($id);
        //  dd($group->id);
        $member = Groupmember::where('group_id', $group->id)->get();

        return response()->json([
            'status' => true,
            'Members' =>  $member
            
        ]);

    }



public function listkey(){
    // $group = new Group();
    $keyword = Keyword::where('user_id', Auth::user()->id)->first(); 
return response()->json([
    'status' => true,
    'Group Id' => $keyword->group_id,
    'Sender Name' => $keyword->sender_name,
    
]);
}

public function deletegroup($id){
    $group = Group::find($id);

$group->delete();

return response()->json([
    'status' => true,
    'msg' => 'Group Deleted',
    ]);
        
}

public function deletekeyword( $id){

    $key = Keyword::find($id);

$key->delete();

return response()->json([
    'status' => true,
    'msg' => 'Keyword Deleted',
    ]);
        
    }

    public function groupsetting(Request $request, $id){
$group = Group::find($id);
$group->status = $request->status;
$group->save();

return response()->json([
'status' => true,
'message' => 'You have Updated Group Status'
]);
    }

    public function message(Request $request){
$msg = new Message();

$msg->user_id = Auth::user()->id;
$msg->message = $request->message;
$msg->sender = $request->username;
$msg->date = $request->date;
$msg->metadata = $request->metadata;

$msg->save();

return response()->json([
    'status' => true,
    'message' => 'Message has been saved!'
]);

    }

    public function viewmessage(){
        $viewmsg = Message::where('user_id', Auth::user()->id)->get();
        return response()->json([
            'status' => true,
            'messages' => $viewmsg
        ]);

    }

}
