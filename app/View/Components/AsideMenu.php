<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class AsideMenu extends Component
{
    public array $menuItems = [];
    public string $currentRoute = '';

    public function __construct()
    {
        $this->menuItems = $this->getFilteredMenuItems();
        $this->currentRoute = Route::currentRouteName();
    }

    private function getFilteredMenuItems(): array
    {
        $menu = config('menu.sidebar', []);

        return array_filter($menu, function ($item) {
            if (empty($item['permission'])) {
                return true;
            }

            return auth()->user()->can($item['permission']) ?? false;
        });
    }

    private function isActive(string $route): bool
    {
        if ($route === '#' or $route === '') return false;

        $i = 0;
        if (str_starts_with($this->currentRoute, 'my.')) $i = 1;
        return explode('.', $this->currentRoute)[$i] === explode('.', $route)[$i];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View | string | Closure
    {
        return view('layouts.partials.dashboard.asideMenu', [
            'menuItems' => $this->menuItems,
            'isActive' => fn($route) => $this->isActive($route),
            'studly' => fn($string) => Str::studly($string)
        ]);
    }
}
