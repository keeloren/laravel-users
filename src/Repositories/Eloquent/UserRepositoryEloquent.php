<?php

namespace NguyenND\Users\Repositories\Eloquent;

use NguyenND\Users\Models\User;
use NguyenND\Users\Presenters\UserPresenter;
use NguyenND\Users\Repositories\Contracts\UserRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return UserPresenter::class;
    }
}
