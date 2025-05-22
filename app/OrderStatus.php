<?php

namespace App;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::PAID => 'Pago',
            self::SHIPPED => 'Enviado',
            self::DELIVERED => 'Entregue',
            self::CANCELED => 'Cancelado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800',
            self::PAID => 'bg-green-100 text-green-800',
            self::SHIPPED => 'bg-blue-100 text-blue-800',
            self::DELIVERED => 'bg-purple-100 text-purple-800',
            self::CANCELED => 'bg-red-100 text-red-800',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'clock',
            self::PAID => 'check-circle',
            self::SHIPPED => 'truck',
            self::DELIVERED => 'home',
            self::CANCELED => 'x-circle',
        };
    }

    public function iconColor(): string
    {
        return match ($this) {
            self::PENDING => 'text-yellow-500',
            self::PAID => 'text-green-500',
            self::SHIPPED => 'text-blue-500',
            self::DELIVERED => 'text-purple-500',
            self::CANCELED => 'text-red-500',
        };
    }
}
