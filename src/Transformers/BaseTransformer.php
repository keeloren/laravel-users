<?php

namespace NguyenND\Users\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

/**
 * Class BaseTransformer.
 *
 * @package namespace App\Transformers;
 */
abstract class BaseTransformer extends TransformerAbstract
{
    /**
     * Transform the Base entity.
     *
     * @param Model $model
     * @param array $customFields
     * @param array $removeFields
     *
     * @return array
     */
    public function baseTransform($model, $customFields = [], $removeFields = [])
    {
        $fillable = $model->getFillable();
        $hidden = $model->getHidden();
        $resultField = array_filter($fillable, function ($item) use ($hidden, $removeFields) {
            return !in_array($item, $hidden) && !in_array($item, $removeFields);
        });
        $results = [];
        foreach ($resultField as $item) {
            $results[$item] = $model->$item;
        }
        $relations = $model->relationsToArray();
        foreach ($relations as $key => $relation) {
            $results = array_merge($results, $relations);
        }
        $results = array_merge($results, $customFields);
        return $results;
    }
}
