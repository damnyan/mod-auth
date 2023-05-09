<?php

namespace Dmn\Modules\Auth\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Auth extends JsonResource
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
            'access_token' => $this->plainTextToken,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode(201);
    }
}
