<?php

namespace App\Presenters;

use GuzzleHttp\Client;
use Nette;


class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var Client @inject */
    public $client;

    /** @var string API endpoint */
    private $api;

    public function setApiEndpoint($url)
    {
        $this->api = $url;
    }
}
