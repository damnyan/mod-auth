<?php

namespace Dmn\Modules\Auth\Contracts;

interface AuthModel
{
    /**
     * Get Type identifier
     *
     * @return string
     */
    public function getTypeIdentifier(): string;
}
