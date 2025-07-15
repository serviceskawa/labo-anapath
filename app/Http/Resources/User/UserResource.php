<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "email" => $this->email,
            "signature" => $this->signature,
            "is_active" => $this->is_active,
            "lastlogindevice" => $this->lastlogindevice,
            "branches" => $this->branches,
            "branch" => $this->branch,
            "branch_count" => $this->branches()->count(),
            'permissions' => $this->permissions->pluck('slug'),
        ];
    }
}
