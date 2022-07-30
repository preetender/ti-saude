<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsultantResource extends JsonResource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'is_private' => $this->private,
            'code' => $this->code,
            'date' => $this->date,
            'hour' => $this->hour,
            'patient' => $this->patient,
            'doctor' => $this->doctor,
            'procedures' => $this->procedures,
            'value' => 0,
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->diffForHumans(),
        ];
    }
}
