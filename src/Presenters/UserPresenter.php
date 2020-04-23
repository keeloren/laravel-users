<?php

namespace NguyenND\Users\Presenters;

use NguyenND\Users\Transformers\UserTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserPresenter.
 *
 * @package namespace App\Presenters;
 */
class UserPresenter extends FractalPresenter
{
    /**
     * @var string
     */
    protected $resourceKeyItem = 'user';

    /**
     * @var string
     */
    protected $resourceKeyCollection = 'user';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserTransformer();
    }
}
