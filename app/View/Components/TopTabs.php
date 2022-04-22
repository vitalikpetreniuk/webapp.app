<?php

namespace App\View\Components;

use http\Env\Request;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class TopTabs extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Генерация массива табов на странице
     * @return array[] массив названия и ссылки табов
     */
    public function tabsData()
    {
       $name = Route::currentRouteName();

        $tabs = [
            'P/L Corve model' => [
                'url' => route('home'),
                'active' => $name == 'home' ?: false,
            ],
            'Sweetspot Analytics' => [
                'url' => route('sweetspot'),
                'active' => $name == 'sweetspot' ?: false,
            ],
            'Special event Analytics' => [
                'url' => route('special'),
                'active' => $name == 'special' ?: false,
            ],
        ];

        return $tabs;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {

        return view('components.top-tabs')->with(['tabs' => $this->tabsData()]);
    }
}
