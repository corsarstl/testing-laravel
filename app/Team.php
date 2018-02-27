<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'size'];

    public function add($user)
    {
        $this->guardAgainstTooManyMembers();

//        if($user instanceof User) {
//            return $this->members()->save($user);
//        }
//
//        $this->members()->saveMany($user);

        $method = $user instanceof User ? 'save' : 'saveMany';

        $this->members()->$method($user);
    }

    public function remove($users = null)
    {
        if ($users instanceof User) {
            return $users->leaveTeam();
        }

       return $this->removeMany($users);

//        $this->members()->where('user_id', $user->id)->delete();
//        $user->update(['team_id' => null]);
    }

    public function removeMany($users)
    {
        return $this->members()
                ->whereIn('id', $users->pluck('id'))
                ->update(['team_id' => null]);
    }

    public function restart()
    {
        return $this->members()->update(['team_id' => null]);
    }

    public function members()
    {
        return $this->hasMany(User::class);
    }

    public function count()
    {
        return $this->members()->count();
    }

    protected function guardAgainstTooManyMembers()
    {
        if($this->count() >= $this->size) {
            throw new \Exception;
        }
    }
}
