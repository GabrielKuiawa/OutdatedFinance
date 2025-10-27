<?php

namespace App\Enums;

enum ExpensePayment: string
{
    case BOLETO = 'boleto';
    case CARTAO = 'cartao';
    case PIX = 'pix';
}
