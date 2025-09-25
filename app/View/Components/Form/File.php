<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class File extends Component
{
    public ?string $id;
    public string $name;
    public ?string $label;
    public bool $multiple;
    public string $accept;
    public $value; // string|array|null

    /**
     * Create a new component instance.
     *
     * @param  string|null  $id
     * @param  string  $name
     * @param  string|null  $label
     * @param  bool  $multiple
     * @param  string  $accept
     * @param  mixed  $value
     */
    public function __construct(
        ?string $id = null,
        string $name = '',
        ?string $label = null,
        bool $multiple = false,
        string $accept = '*/*',
        $value = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->label = $label;
        $this->multiple = $multiple;
        $this->accept = $accept;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        // Les propriétés publiques sont automatiquement disponibles dans la vue,
        // mais on peut aussi les passer explicitement si besoin :
        return view('components.form.file', [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'multiple' => $this->multiple,
            'accept' => $this->accept,
            'value' => $this->value,
        ]);
    }
}
