<?php

namespace App\Livewire;

use App\Models\Admission\Wave;
use Livewire\Component;

class HomePage extends Component
{
    public $waves;

    public function render()
    {
        $this->waves = Wave::opened()->get();

        return view('livewire.homepage');
    }
}
