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
            'patient' => $this->patient->only('id', 'name', 'code'),
            'doctor' => $this->doctor->only('id', 'name', 'code', 'crm'),
            'procedures' => $this->procedures->map(
                fn ($procedure) => $procedure->only('id', 'code', 'name', 'value')
            ),
            'total_procedure' => $this->totalProcedure,
            'created_at' => $this->created_at?->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at?->diffForHumans(),
        ];
    }
}
