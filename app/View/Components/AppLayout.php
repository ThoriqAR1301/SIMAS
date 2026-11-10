<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Membuat instance komponen layout baru.
     *
     * @param  string|null  $title  Judul halaman (Opsional)
     * @param  string|null  $subtitle  Sub-judul halaman (Opsional)
     */
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $subtitle = null,
    ) {}

    public function render(): View
    {
        return view('layouts.app');
    }
}
