<?php

namespace App\Http\Resources;

use App\Core\Concerns\HasResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    use HasResource;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'birth_date' => $this->birth_date,
            'phones' => $this->phones->map(fn ($h) => [
                'id' => $h->id,
                'number' => $h->number,
            ]),
            'plans' => $this->when(
                $this->isDisplayed($request) || $request->has('plans.load'),
                fn () => $this->plans->map(fn ($h) => [
                    'id' => $h->id,
                    'description' => $h->description,
                    'contract_number' => $h->pivot->contract_number,
                ])
            ),
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
