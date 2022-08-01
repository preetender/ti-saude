<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SpecialityResource extends JsonResource
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
            'doctors' => $this->when(
                $this->wasRecentlyCreated || $request->has('doctors.load'),
                fn () => $this->doctors->map(fn ($h) => [
                    'id' => $h->id,
                    'name' => $h->name,
                    'crm' => $h->crm,
                    'code' => $h->code,
                ])
            ),
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
