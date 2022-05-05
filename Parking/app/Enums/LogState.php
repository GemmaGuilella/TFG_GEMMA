<?php

declare(strict_types=1);

namespace App\Enums;

enum LogState: string
{
    case Enter = 'enter';
    case Exit = 'exit';
    case Payment = 'payment';

    public function label(): string
    {
        return match ($this) {
            LogState::Enter => 'Entrada',
            LogState::Exit => 'Sortida',
            LogState::Payment => 'Pagament',
        };
    }
}
