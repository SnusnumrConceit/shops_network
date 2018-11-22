<?php

namespace App\Http\Resources\Providers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderProducts extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
