<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UrlCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        foreach ($this->collection as $resource) {
            foreach ($resource->clicks as $includedResource) {
                $this->with['included'][] = ClickResource::make($includedResource);
            }
        }

        return parent::toArray($request);
    }
}
