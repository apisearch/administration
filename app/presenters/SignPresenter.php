<?php

namespace App\Presenters;

use Nette\Application\UI\Form;


class SignPresenter extends BasePresenter
{
    public function actionOut()
    {
        $this->redirect('Sign:');
    }

    public function createComponentSignForm()
    {
        $form = new Form;
        $form->addText('login')
            ->addRule(Form::EMAIL, 'Zadejte platnou e-mailovou adresu')
            ->addRule(Form::REQUIRED, 'Zadání e-mailu je povinné');
        $form->addPassword('password')
            ->addRule(Form::REQUIRED, 'Zadání hesla je povinné');
        $form->addSubmit('submit');
        $form->onSuccess[] = [$this, 'signIn'];

        return $form;
    }

    public function signIn(Form $form)
    {
        $values = $form->getValues(true);
        $token = '';

        try {
            $token = $this->api->signIn($values['login'], $values['password']);
            $this->flashMessage('Byli jste úspěšně přihlášeni do aplikace Apisearch', 'success');
        } catch (\Exception $e) {
            $this->flashMessage('Nepodařilo se přihlásit: ' . $e->getMessage(), 'danger');
            $this->redirect('this');
        }

        $this->redirect('Homepage:', ['token' => $token]);
    }
}
