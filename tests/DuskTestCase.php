<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use DatabaseTruncation;

    protected function driver(): RemoteWebDriver
    {
        $headless = ! $this->hasHeadlessDisabled();
        $args = collect([
            '--window-size=1920,1080',
            '--disable-gpu',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
            '--ignore-certificate-errors',
        ])->when($headless, fn ($c) => $c->push('--headless=new'));

        $options = (new ChromeOptions)->addArguments($args->unique()->all());

        $host = $_ENV['DUSK_DRIVER_URL']
            ?? env('DUSK_DRIVER_URL')
            ?? 'http://dusk:4444/wd/hub';

        return RemoteWebDriver::create(
            $host,
            DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options),
        );
    }
}
