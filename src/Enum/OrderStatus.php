<?php

namespace App\Enum;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case SHIPPED = 'shipped';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            OrderStatus::PENDING   => 'En attente de paiement',
            OrderStatus::PAID      => 'Payée',
            OrderStatus::SHIPPED   => 'Expédiée',
            OrderStatus::CANCELLED => 'Annulée',
        };
    }
}

