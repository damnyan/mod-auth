<?php

namespace Dmn\Modules\Auth\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Auth extends JsonResource
{
    /**
     * @OA\Schema(
     *  schema="AuthUser",
     *  title="Auth User",
     *  type="object",
     *  @OA\Property(
     *      property="access_token",
     *      type="string",
     *      example="1|KSADUY45654LKYTACZ",
     *  )
     * )
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
