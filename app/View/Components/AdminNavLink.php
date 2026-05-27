<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdminNavLink extends Component
{
    public string $route;
    public string $icon;
    public string $label;
    public int $badge;
    public string $badgeColor;
    public ?bool $active; // Tambahkan properti ini (boleh null)

    public function __construct(
        string $route,
        string $icon,
        string $label,
        int $badge = 0,
        string $badgeColor = 'red',
        ?bool $active = null
    ) {
        $this->route      = $route;
        $this->icon       = $icon;
        $this->label      = $label;
        $this->badge      = $badge;
        $this->badgeColor = $badgeColor;
        $this->active     = $active;
    }

    public function render(): View
    {
        return view('components.admin-nav-link');
    }
}
