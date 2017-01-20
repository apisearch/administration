<?php

namespace App\Presenters;

use App\Model\ApiConnector;
use Nette;


class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var string @persistent */
    public $token;

    /** @var ApiConnector @inject */
    public $api;
}
