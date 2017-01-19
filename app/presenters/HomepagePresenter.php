<?php

namespace App\Presenters;


class HomepagePresenter extends BasePresenter
{
    /** @var string @persistent */
    public $token;

    public function startup()
    {
        if (!$this->token) {
            $this->flashMessage('Nejste přihlášeni!', 'danger');
            $this->redirect('Sign:');
        }

        parent::startup();
    }
}
