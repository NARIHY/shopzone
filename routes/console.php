<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// CONVERT ALL IMAGE TO WEBP SCHEDULER AT 00:00 DAILY
Schedule::command('app:convert-images-to-webp-scheduler')->daily()->at('00:00');
