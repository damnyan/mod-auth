<?php

namespace SwagDocs;

/**
 * @OA\Tag(
 *  name="Auth",
 *  description="Auth",
 * )
 *
 * @OA\Schema(
 *  schema="LoginRequest",
 *  title="Login Request",
 *  type="object",
 *  required={"email", "password"},
 *  @OA\Property(
 *      property="email",
 *      type="string",
 *      example="jsdelacruz@email.com",
 *  ),
 *  @OA\Property(
 *      property="password",
 *      type="string",
 *      example="123sdf123",
 *  ),
 * )
 *
 * @OA\Schema(
 *  schema="AuthUserResource",
 *  title="Auth User Resource",
 *  type="object",
 *  @OA\Property(
 *      property="access_token",
 *      type="string",
 *      example="1|KSADUY45654LKYTACZ",
 *  )
 * )
 *
 * @OA\Post(
 *  tags={"Auth Vendor"},
 *  path="/api/auth/login",
 *  description="Login",
 *  @OA\RequestBody(
 *      @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(ref="#/components/schemas/LoginRequest"),
 *      ),
 *  ),
 *  @OA\Response(
 *      response=201,
 *      description="OK",
 *      @OA\MediaType(
 *          mediaType="application/json",
 *          @OA\Schema(
 *              type="object",
 *              @OA\Property(
 *                  property="data",
 *                  type="object",
 *                  ref="#/components/schemas/AuthUserResource",
 *              )
 *          )
 *      )
 *  )
 * ),
 *
 * @OA\Delete(
 *  tags={"Auth Vendor"},
 *  path="/api/auth/logout",
 *  description="Logout",
 *  @OA\Response(
 *      response=204,
 *      description="OK",
 *  ),
 * security={"User":{}},
 * ),
 */
class DModAuth
{
}
