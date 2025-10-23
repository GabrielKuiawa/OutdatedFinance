<?php

namespace App\Enums;

enum ExpenseStatus: string
{
    case PENDENTE = 'pendente';
    case PAGO = 'pago';
    case CANCELADO = 'cancelado';
}
