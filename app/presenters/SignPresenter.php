<?php

namespace App\Presenters;


class SignPresenter extends BasePresenter
{
    public function actionOut()
    {
        $this->redirect('Sign:');
    }
}
