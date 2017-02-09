<?php

namespace App\Presenters;

use Tracy\Debugger;


class HomepagePresenter extends BasePresenter
{
    private $userId;

    public function startup()
    {
        if (!$this->token) {
            $this->redirect('Sign:');
        }

        try {
            $user = $this->api->getUser($this->token);
            $this->userId = $user['id'] ?? null;
            $this->template->settings = $user;
        } catch (\Exception $e) {
            $this->flashMessage('Nepodařilo se našíst nastavení uživatele: ' . $e->getMessage(), 'danger');
            $this->token = '';
            $this->redirect('Sign:');
        }

        parent::startup();
    }

    public function renderDefault($newUser = '')
    {
        $this->template->searchEndpoint = $this->api->getSearchEndpoint($this->userId, 'apple iphone');

        try {
            $suggestions = $this->api->suggestTerms($this->userId)['terms'];

            if (!isset($suggestions[0]) || !$suggestions[0]) {
                $this->template->refreshSugg = true;
            }

            $this->template->suggestions = $suggestions;
        } catch (\Exception $e) {
            $this->template->suggestions = [];
            $this->template->refreshSugg = true;
        }

        if ($newUser) {
            $this->template->newUser = true;
        }
    }

    public function renderSuggest()
    {
        try {
            $this->template->suggestions = $this->api->suggestTerms($this->userId)['terms'];
        } catch (\Exception $e) {
            $this->template->suggestions = [];
        }
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
