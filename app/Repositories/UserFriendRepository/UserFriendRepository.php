<?php

namespace App\Repositories\UserFriendRepository;

use App\Filters\PayableFilter;
use App\Models\User;
use App\Models\UserFriend;
use App\Repositories\BaseRepository;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;

class UserFriendRepository extends BaseRepository implements UserFriendRepositoryInterface
{
    private $user_friend;

    public function __construct(UserFriend $user_friend){
        $this->setModel($user_friend);
        $this->user_friend = $user_friend;
    }

    public function check_user($user_id,$id){
        // $user_can_access = $this->user_friend->where('id',$id)->where('user_id',$user_id)->exists();
        $user_can_access = false;
        if(!$user_can_access){ abort(404); }
    }

    public function create(array $data){
        $whom = User::where('email',$data['with_whom_email']);
        
        $not_friends = function($query) use($data){
            $query->where('who',$data['user_id'])->orWhere('with_whom',$data['user_id']);
        };
        $whom = $whom->whereDoesntHave('my_friends',$not_friends)->whereDoesntHave('other_friends',$not_friends);
        $whom = $whom->first();

        if($whom){
            $friendship = $this->user_friend->create([
                'who' => $data['user_id'],
                'with_whom' => $whom->id,
            ]);
        }else{ $friendship = null; }

        return $friendship;
    }

    public function update(array $data,$id){
        
    }

    public function delete_friendship($user_id,$with_whom_email){
        $friend = User::where('email',$with_whom_email)->first();
        if(!$friend){ abort(404); }
        $friend_id = $friend->id;

        $friendship = $this->user_friend->where(function($query) use($user_id,$friend_id){
            $query->where('who',$user_id)->where('with_whom',$friend_id);
        })->orWhere(function($query) use($user_id,$friend_id){
            $query->where('who',$friend_id)->where('with_whom',$user_id);
        });

        $friendship->delete();
    }

    public function read($id){
        return $this->user_friend->where('id',$id)
            ->with(['user','whom'])
            ->first();
    }

    public function all_friends($search=null,$status='all',$user_id,$per_page){
        $all_friends = $this->user_friend->where(function($query) use($user_id){
            $query->where('who',$user_id)->orWhere('with_whom',$user_id);
        });

        if($search != null){
            $search_function = function($query) use($search){
                $query->where('name','like',"%$search%")->orWhere('family','like',"%$search%")->orWhere('email','like',"%$search%");
            };
            $all_friends = $all_friends->whereHas('user',$search_function)->orWhereHas('whom',$search_function);
        }

        // $all_friends = app(Pipeline::class)
        //     ->send($all_friends)
        //     ->through([
        //         PayableFilter::class,
        //     ])
        //     ->thenReturn();

        $all_friends = $all_friends->with(['user','whom'])->latest();
        if($per_page != 'all'){
            $all_friends = $all_friends->paginate($per_page);
        }else{ $all_friends = $all_friends->get(); }

        return $all_friends;
    }

    public function users_list($search=null,$user_id){
        $users = User::where('id','!=',$user_id);
        $users = $this->search($users,User::SEARCHABLE,$search);

        $not_friends = function($query) use($user_id){
            $query->where('who',$user_id)->orWhere('with_whom',$user_id);
        };
        $users = $users->whereDoesntHave('my_friends',$not_friends)->whereDoesntHave('other_friends',$not_friends);
        $users = $users->limit(UserFriend::PER_PAGE)->get();
        return $users;
    }

    public function friend_requests($user_id){
        $friend_requests = $this->user_friend->where('with_whom',$user_id)->where('accepted',0)->paginate(10);
        return $friend_requests;
    }

    public function friends_count($user_id){
        $all_friends = $this->user_friend->where(function($query) use($user_id){
            $query->where('who',$user_id)->orWhere('with_whom',$user_id);
        });

        $friends = with(clone $all_friends)->where('accepted',1)->count();
        $pending = with(clone $all_friends)->where('accepted',0)->count();

        return [
            'friends' => $friends,
            'pending' => $pending,
        ];
    }

    public function change_state($user_id,$with_whom_email,$state){
        $friend = User::where('email',$with_whom_email)->first();
        if(!$friend){ abort(404); }

        $friendship = $this->user_friend->where('who',$friend->id)->where('with_whom',$user_id)->first();
        if($friendship){
            $this->user_friend->where('who',$friend->id)->where('with_whom',$user_id)->update([
                'accepted' => $state,
            ]);
        }
    }

}