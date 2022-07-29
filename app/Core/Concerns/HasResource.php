<?php

namespace App\Core\Concerns;

trait HasResource
{
    /**
     * Determina se valor pode ser exibido.
     *
     * @param mixed $request
     * @return bool
     */
    public function isDisplayed($request)
    {
        return $this->wasRecentlyCreated || in_array($request->method(), ['PUT', 'POST']);
    }
}
