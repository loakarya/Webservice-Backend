<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        dd($this);
        return [
            // 'email' => $this->user()->email,
            // 'first_name' => $this->,
            // 'last_name' => $this->,
            // 'address' => $this->,
            // 'zip_code' => $this->,
            // 'city' => $this->,
            // 'province' => $this->,
            // 'country' => $this->,
            'employee_code' => $this->employee_number,
            'private_email' => $this->private_email,
            'bank_account_number' => $this->bank_account_number,
            'bank_account_provider' => $this->bank_account_provider,
            'division' => $this->division,
            'title' => $this->title,
        ];
    }
}
