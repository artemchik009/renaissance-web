<?php

declare(strict_types=1);

namespace App;

use Nette;
use Nette\Database\Explorer;

use App\Auth\User;

class Presenter extends Nette\Application\UI\Presenter
{
    var $db;

    public function __construct(private Explorer $database) {
        parent::__construct();

        $this->db = $database;
    }

    public function beforeRender()
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->template->userObj = new User($this->db, $this->getUser()->getId());
        }
    }
}
