<?php

namespace App\Enum;

/**
 * Statuts possibles d'une commande tout au long de son cycle de vie.
 * getLabel() retourne le libellé en français pour l'affichage côté client.
 */
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

