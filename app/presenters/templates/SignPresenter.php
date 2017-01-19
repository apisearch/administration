<?php

namespace App\Presenters;

use Nette;


class SignPresenter extends Nette\Application\UI\Presenter
{
    public function actionOut()
    {
        $this->redirect('Sign:');
    }
}
