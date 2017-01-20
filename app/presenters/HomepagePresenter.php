<?php

namespace App\Presenters;


use Tracy\Debugger;

class HomepagePresenter extends BasePresenter
{
    private $userId;

    public function startup()
    {
        if (!$this->token) {
            $this->flashMessage('Nejste přihlášeni!', 'danger');
            $this->redirect('Sign:');
        }

        try {
            $this->userId = $this->api->getUser($this->token)['id'] ?? null;
        } catch (\Exception $e) {
            $this->flashMessage('Nepodařilo se našíst nastavení: ' . $e->getMessage(), 'danger');
            $this->redirect('Sign:');
        }

        parent::startup();
    }

    public function renderDefault()
    {
    }

    public function renderSearch($query = '')
    {
        $this->template->products = [];
        $this->template->time = 0;

        if ($query) {
            try {
                Debugger::timer('fetch');
                $this->template->products = $this->api->search($this->userId, $query)['products'];
                $this->template->time = Debugger::timer('fetch') * 1000;
            } catch (\Exception $e) {
                echo '<div class="alert alert-danger col-sm-10 col-sm-offset-1">Chyba: ' . $e->getMessage() . '</div>';
                $this->terminate();
            }
        }
    }
}
