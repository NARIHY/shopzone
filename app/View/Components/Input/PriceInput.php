<?php

namespace App\View\Components\Input;

use Illuminate\View\Component;

class PriceInput extends Component
{
    public string $name;
    public ?float $value;

    /**
     * @param string $name
     * @param float|string|null $value Peut être float ou string numérique
     */
    public function __construct(string $name, $value = null)
    {
        $this->name = $name;
        // On convertit toute valeur en float si possible
        $this->value = $value !== null ? floatval(str_replace([',', ' '], ['.', ''], $value)) : null;
    }

    public function render()
    {
        return view('components.input.price-input');
    }
}
