<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'lname' => $this->lname,
            'fname' => $this->fname,
            'userstype_id' => $this->userstype_id,
            'email' => $this->email,
            'password' => $this->password
            
        ];
    }
}
