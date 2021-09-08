<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Messages extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer_receiver_id' => $this->customer_receiver_id,
            'staff_id' => $this->staff_id,
            'staff_receiver_id' => $this->staff_receiver_id,
            'messages' => $this->messages,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
