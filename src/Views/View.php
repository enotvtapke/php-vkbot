<?php

namespace App\Views;

abstract class View
{
    private string $view;

    public function set(string $view): void
    {
        $this->view = $view;
    }

    public function render(): string
    {
        return $this->view;
    }
}