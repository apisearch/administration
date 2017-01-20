<?php

namespace App\Presenters;


class HomepagePresenter extends BasePresenter
{
    public function startup()
    {
        if (!$this->token) {
            $this->flashMessage('Nejste přihlášeni!', 'danger');
            $this->redirect('Sign:');
        }

        parent::startup();
    }
}
