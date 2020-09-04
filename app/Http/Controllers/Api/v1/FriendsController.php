<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FriendResource;
use App\Models\UserFriend;
use App\Repositories\UserFriendRepository\UserFriendRepositoryInterface;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    private $userFriend;

    public function __construct(UserFriendRepositoryInterface $userFriendRepository){
        $this->userFriend = $userFriendRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if($request->has('all')){
            $per_page = 'all';
        }else{ $per_page = UserFriend::PER_PAGE; }

        $all_friends = $this->userFriend->all_friends($request->search,'all',$user->id,$per_page);
        return FriendResource::collection($all_friends);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $friendship = $this->userFriend->create([
            'user_id' => $user->id,
            'with_whom_email' => $request->email,
        ]);
        if(!$friendship){ return response('',400); }

        return response('',200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $email
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $email)
    {
        $user = $request->user();
        $friendship = $this->userFriend->delete_friendship($user->id,$email);
        return response('',200);
    }

    // ======================================================================
    // ======================================================================

    /**
     * Get the users list that are not friends.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_users_list(Request $request){
        $response = [];
        $user = $request->user();
        $users = $this->userFriend->users_list($request->search,$user->id);
        foreach($users as $user){
            $response[] = [
                'avatar' => $user->avatar,
                'name' => ucfirst($user->name),
                'family' => ucfirst($user->family),
                'email' => $user->email,
            ];
        }
        return response()->json($response);
    }

    /**
     * Get the friend request list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function friends_count_info(Request $request){
        $user = $request->user();
        $friends_count = $this->userFriend->friends_count($user->id);

        return response()->json([
            'friends' => $this->number_with_units($friends_count['friends']),
            'pending' => $this->number_with_units($friends_count['pending']),
        ]);
    }

    /**
     * Get the friend request list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function friend_request_list(Request $request){
        $user = $request->user();
        $friendship_requests = $this->userFriend->friend_requests($user->id);
        return FriendResource::collection($friendship_requests);
    }

    /**
     * accepts friend request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function accept_friend_request(Request $request,$email){
        $user = $request->user();
        $friendship_requests = $this->userFriend->change_state($user->id,$email,1);
        return response('',200);
    }
    /**
     * rejects friend request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reject_friend_request(Request $request,$email){
        $user = $request->user();
        $friendship_requests = $this->userFriend->delete_friendship($user->id,$email);
        return response('',200);
    }

}
