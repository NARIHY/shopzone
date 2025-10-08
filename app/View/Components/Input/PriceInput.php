<?php

namespace App\View\Components\Input;

use Illuminate\View\Component;

class PriceInput extends Component
{
    public string $name;
    public ?float $value;

    public function __construct(string $name, ?float $value = null)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function render()
    {
        return view('components.input.price-input');
    }
}
