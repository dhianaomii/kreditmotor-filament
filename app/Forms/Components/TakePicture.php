<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class TakePicture extends Field
{
    protected string $view = 'forms.components.take-picture';
    protected function setUp(): void
    {
        parent::setUp();
    }
}
