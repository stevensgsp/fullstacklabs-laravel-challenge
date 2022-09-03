<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UrlResource extends JsonResource
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
            'type' => 'urls',
            'id' => (string) $this->resource->id,
            'attributes' => [
                'created-at' => (string) $this->resource->created_at,
                'original-url' => $this->resource->original_url,
                'url' => $this->resource->short_url,
                'clicks' => $this->resource->clicks->count(),
            ],
            'relationships' => $this->getRelationships(['clicks']),
        ];
    }

    /**
     * @param  array  $relationships
     * @return array
     */
    private function getRelationships(array $relationships): array
    {
        $resourceIdentifiers = [];

        foreach ($relationships as $key) {
            $relationshipModels = $this->resource->getRelationValue($key);

            foreach ($relationshipModels as $relationshipModel) {
                $resourceIdentifiers[$key]['data'] = [
                    'type' => $relationshipModel->getTable(),
                    'id' => (string) $relationshipModel->id,
                ];
            }
        }

        return $resourceIdentifiers;
    }
}
