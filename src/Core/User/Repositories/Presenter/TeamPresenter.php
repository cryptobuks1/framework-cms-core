<?php

namespace Core\User\Repositories\Presenter;

use Core\Repository\Presenter\FractalPresenter;

class TeamPresenter extends FractalPresenter {

    /**
     * Prepare data to present
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TeamTransformer();
    }
}