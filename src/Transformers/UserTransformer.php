<?php

namespace NguyenND\Users\Transformers;

use NguyenND\Users\Models\User;
use NguyenND\Users\Transformers\BaseTransformer;

/**
 * Class UserTransformer.
 *
 * @package namespace App\Transformers;
 */
class UserTransformer extends BaseTransformer
{
    
    /**
     * Transform the User entity.
     *
     * @param User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        return [
            'id'          => (int) $model->id,
            'name'        => $model->name,
            'created_at'  => $model->created_at,
            'updated_at'  => $model->updated_at
        ];
    }
}
