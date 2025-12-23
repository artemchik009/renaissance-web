<?php

declare(strict_types=1);

namespace App\UI\Home;

use Nette;

use App\Presenter;


final class HomePresenter extends Presenter
{
    public function renderStatistics()
    {
        // to capture statistics, put `./bin/console mrim:stats` to cron for every minute
        $stats = file_get_contents(__DIR__ . '/../../../log/statistics.json');
        
        if ($stats === false) {
            $this->flashMessage('Статистика временно недоступна', 'error');
        } else {
            $stats = json_decode($stats);
            $this->template->usersCount = $stats->count;
            $this->template->usersClients = $stats->clients;
            
        }
    }
}
