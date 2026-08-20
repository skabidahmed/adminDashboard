<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // // Allow if user is the author OR has permission to delete post
        // return $user->id === $post->user_id || $user->can('delete post');

        // Allow if user is the author 
        
        return $user->id === $post->user_id ;
    }

    
}
