<?php

namespace App\Http\Controllers;

use App\Jobs\NewMessageJob;
use App\Models\User;
use App\Models\Group;
use App\Models\Keyword;
use App\Models\Message;
use App\Models\Groupmember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $group = new Group();

        $group->user_id = Auth::user()->id;
        $group->name = $request->name;
        $group->description = $request->description;

        $group->save();

        Groupmember::create([
            'group_id' => $group->id,
            'invite_username' => Auth::user()->username,
        ]);

        return response()->json([
            'status' => true,
            "message" => "Group created Successfully!"
        ]);
    }

//this one is for postman/api
    public function profile()
    {
        $user = auth::user();

        return response()->json([
            'status' => true,
            'Fullname' => $user->fullname,
            'username' => $user->username,
            'Email' => $user->email,
        ]);
    }

    //this one for returning to view

    public function mypro()
    {
        $user = User::where('id', auth::user()->id)->get();
        return view('myviews.myprofile', compact('user'));
    }

    public function keywords(Request $request)
    {
// $key = new Keyword();

        Keyword::create([
            'group_id' => $request->group_id,
            'user_id' => auth::user()->id,
            'sender_name' => $request->sender_name,
        ]);

// $key->save();

        return response()->json([
            'status' => true,
            "message" => "Keywords created Successfully!"
        ]);

    }

    public function listgro()
    {
        $group = Group::where('user_id', auth::user()->id)->get();
        return view('myviews.viewgro', compact('group'));
    }

    //for api

    public function listgroup()
    {
        $groups = Group::where('user_id', auth::user()->id)->get();

        return response()->json([
            'status' => true,
            'message' => "Groups fetched successfully",
            'data' => $groups,
        ]);


    }

    public function inviteuser(Request $request)
    {
        $group = new Group();

        $group->id = $request->group_id;
// $group->user->username = $request->invite_username;

        $user = User::where('username', $request->invite_username)->exists();
        if ($user) {
            if (Group::where('user_id', Auth::user()->id)) {

                $gm=Groupmember::where(['invite_username' => $request->invite_username, 'group_id' =>$request->group_id])->first();

                if($gm){
                    return response()->json([
                        "status" => false,
                        "message" => "User already exist in this group"
                    ]);
                }

                Groupmember::create([
                    'group_id' => $request->group_id,
                    'invite_username' => $request->invite_username,
                ]);
                return response()->json([
                    "status" => true,
                    "message" => "User Invited!"
                ]);

            } else {
                return response()->json([
                    "status" => false,
                    "message" => "You are not the Group Owner"
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "This User does not Exist!"
            ]);
        }
    }

    public function removeuser(Request $request)
    {

        $user = User::where('username', $request->invite_username)->exists();
        if (!$user) {

            return response()->json([
                "status" => false,
                "message" => "This User does not Exist!"
            ]);
        }

        $gro=Group::where(['user_id' => Auth::user()->id, 'id' =>$request->group_id])->first();
            if (!$gro) {

                return response()->json([
                    "status" => false,
                    "message" => "You are not the Group Owner"
                ]);
            }

            $gm=Groupmember::where(['invite_username' => $request->invite_username, 'group_id' =>$request->group_id])->first();

            if(!$gm){
                return response()->json([
                    "status" => false,
                    "message" => "User not in this group"
                ]);
            }

            $gm->delete();

        return response()->json([
            "status" => true,
            "message" => "User removed successfully"
        ]);
    }

    public function groupuser($id)
    {
        $group = Groupmember::where([['group_id', $id], ['invite_username', Auth::user()->username]])->first();

        if (!$group) {
            return response()->json([
                'status' => false,
                'message' => "Group does not exist"
            ]);
        }

        $member = Groupmember::where('group_id', $id)->get();

        return response()->json([
            'status' => true,
            'data' => $member
        ]);

    }


    public function listkey()
    {
        // $group = new Group();
        $keyword = Keyword::where('user_id', Auth::user()->id)->get();
        return response()->json([
            'status' => true,
            'data' => $keyword
        ]);
    }

    public function listkey_group($id)
    {
        // $group = new Group();
        $keyword = Keyword::where([['user_id', Auth::user()->id], ['group_id', $id]])->get();
        return response()->json([
            'status' => true,
            'data' => $keyword
        ]);
    }

    public function deletegroup($id)
    {
        $group = Group::find($id);

        $group->delete();

        return response()->json([
            'status' => true,
            'message' => 'Group Deleted',
        ]);

    }

    public function deletekeyword($id)
    {

        $key = Keyword::find($id);

        $key->delete();

        return response()->json([
            'status' => true,
            'message' => 'Keyword Deleted',
        ]);

    }

    public function groupsetting(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'id' => 'required',
            'auto_notify' => 'required'
        );

        $validator = Validator::make($input, $rules);

        $input = $request->all();

        if (!$validator->passes()) {
            return response()->json(['success' => 0, 'message' => implode(",", $validator->errors()->all())]);
        }

        $group=Group::where(['user_id' => Auth::user()->id, 'id' =>$input['id']])->first();

        if(!$group){
            return response()->json([
                "status" => false,
                "message" => "Group does not exist or you are not the owner"
            ]);
        }

        $group->auto_notify = $input['auto_notify'];
        $group->save();

        return response()->json([
            'status' => true,
            'message' => 'You have Updated Group Status'
        ]);
    }

    public function message(Request $request)
    {
        $msg = new Message();

        $msg->user_id = Auth::user()->id;
        $msg->message = $request->message;
        $msg->sender = $request->sender;
        $msg->date = $request->date;
        $msg->metadata = $request->metadata;

        $msg->save();

        NewMessageJob::dispatch($msg);

        return response()->json([
            'status' => true,
            'message' => 'Message has been saved!'
        ]);

    }

    public function viewmessage()
    {
        $viewmsg = Message::where('user_id', Auth::user()->id)->get();
        return response()->json([
            'status' => true,
            'data' => $viewmsg
        ]);

    }

    public function viewGroupMessage($id)
    {
        $groupM = Groupmember::where([['group_id', $id], ['invite_username', Auth::user()->username]])->first();

        if (!$groupM) {
            return response()->json([
                'status' => false,
                'message' => "You are not a member of this group"
            ]);
        }

        $allMsg=[];
        foreach ($groupM->keywords as $keywords){
            $viewmsg = Message::where([['user_id', $groupM->Group->user_id], ['sender', 'LIKE', '%'.$keywords->sender_name.'%']])->get()->toArray();
//            dd($viewmsg);
            $allMsg=array_merge($allMsg, $viewmsg);
        }

        return response()->json([
            'status' => true,
            'data' => $allMsg
        ]);

    }

}
