<?php

namespace App\Presenters;

use Nette\Application\UI\Form;


class RegisterPresenter extends BasePresenter
{
    public function createComponentRegistrationForm()
    {
        $form = new Form;
        $form->addText('login')
            ->addRule(Form::EMAIL, 'Zadejte platnou e-mailovou adresu')
            ->addRule(Form::REQUIRED, 'Zadání e-mailu je povinné');
        $form->addPassword('password')
            ->addRule(Form::MIN_LENGTH, 'Heslo musí být dlouhé alespoň 6 znaků', 6)
            ->addRule(Form::FILLED, 'Zadání hesla je povinné');
        $form->addPassword('passwordAgain')
            ->addConditionOn($form['password'], Form::VALID)
            ->addRule(Form::FILLED, 'Zadejte heslo znovu')
            ->addRule(Form::EQUAL, 'Hesla se neshodují.', $form['password']);
        $form->addText('xml')
            ->addRule(Form::URL, 'Zadejte platnou adresu XML feedu')
            ->addRule(Form::REQUIRED, 'Zadání XML feedu je povinné');
        $form->addSubmit('submit');
        $form->onSuccess[] = [$this, 'register'];

        return $form;
    }

    public function register(Form $form)
    {
        $values = $form->getValues(true);
        $token = '';

        try {
            $token = $this->api->register($values['login'], $values['password'], $values['xml']);
            $this->flashMessage('Byli jste úspěšně registrováni', 'success');
        } catch (\Exception $e) {
            $this->flashMessage('Registrace se nezdařila: ' . $e->getMessage(), 'danger');
            $this->redirect('this');
        }

        $this->redirect('Homepage:', ['token' => $token]);
    }
}
