<?php

namespace App\Testing;

use Laravel\Dusk\Browser;

class SlowBrowser extends Browser
{
    protected function maybePause(): void
    {
        if (config('dusk.slow_mode') && config('dusk.slow_ms') > 0) {
            $this->pause((int) config('dusk.slow_ms'));
        }
    }

    public function visit($url)
    {
        $result = parent::visit($url);
        $this->maybePause();
        return $result;
    }

    public function click($selector = null)
    {
        $result = parent::click($selector);
        $this->maybePause();
        return $result;
    }

    public function clickLink($link, $element = 'a')
    {
        $result = parent::clickLink($link, $element);
        $this->maybePause();
        return $result;
    }

    public function press($button)
    {
        $result = parent::press($button);
        $this->maybePause();
        return $result;
    }

    public function type($field, $value)
    {
        $result = parent::type($field, $value);
        $this->maybePause();
        return $result;
    }

    public function assertSee($text, $ignoreCase = false)
    {
        $result = parent::assertSee($text, $ignoreCase);
        $this->maybePause();
        return $result;
    }

    public function assertPathIs($path)
    {
        $result = parent::assertPathIs($path);
        $this->maybePause();
        return $result;
    }

    public function assertPresent($selector)
    {
        $result = parent::assertPresent($selector);
        $this->maybePause();
        return $result;
    }
}
