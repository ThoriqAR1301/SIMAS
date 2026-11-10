<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * Membuat instance komponen layout tamu (Guest) baru.
     *
     * @param  string|null  $title  Judul halaman (Opsional, default: Login)
     * @param  string|null  $subtitle  Sub-judul halaman (Opsional)
     */
    public function __construct(
        public readonly ?string $title = 'Login',
        public readonly ?string $subtitle = null,
    ) {}

    public function render(): View
    {
        return view('layouts.guest');
    }
}
