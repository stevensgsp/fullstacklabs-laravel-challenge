<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClickResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'clicks',
            'id' => (string) $this->resource->id,
            'attributes' => [
                'platform' => (string) $this->resource->platform,
                'browser' => $this->resource->browser,
            ],
        ];
    }
}
