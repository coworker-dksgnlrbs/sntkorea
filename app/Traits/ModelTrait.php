<?php

namespace App\Traits;

trait ModelTrait
{
    public function getBasicFilterItems($model, $attributes)
    {
        $orderBy = isset($attributes["orderBy"]) ? $attributes["orderBy"] : "created_at";

        $align = isset($attributes["align"]) ? $attributes["align"] : "desc";

        return $model->orderBy($orderBy, $align);
    }
}
