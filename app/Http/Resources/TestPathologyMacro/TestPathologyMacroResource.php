<?php

namespace App\Http\Resources\TestPathologyMacro;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Employee\EmployeeResource;

class TestPathologyMacroResource extends JsonResource
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
            "code" => $this->order->code,
            "date" => $this->date ? $this->date : now(),
            "observation" => $this->observation,
            "mounting" => $this->mounting,
            "created_at" => $this->created_at,
            "user" => $this->user->fullname(),
            "employee" => new EmployeeResource($this->employee),
            "employee_id" => $this->employee->id,
            "branch_id" => $this->branch_id,
        ];
    }
}
