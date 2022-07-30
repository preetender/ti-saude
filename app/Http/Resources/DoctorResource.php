<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'crm' => $this->crm,
            'specialties' => $this->when(
                $this->wasRecentlyCreated || $request->has('specialities.load'),
                fn () => $this->specialities->map(fn ($h) => [
                    'id' => $h->id,
                    'name' => $h->name,
                ])
            ),
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
