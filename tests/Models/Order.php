<?php

namespace TestMonitor\CalculatedColumns\Test\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use TestMonitor\CalculatedColumns\HasCalculatedColumns;

class Order extends Model
{
    use HasFactory, HasCalculatedColumns;

    protected $table = 'orders';

    public $timestamps = false;

    protected $guarded = [];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculatedColumns(): array
    {
        return [
            'total_amount' => function (Builder $query) {
                $query->select(DB::raw('SUM(order_items.amount)'))
                    ->from('order_items')
                    ->whereColumn('order_items.order_id', '=', 'orders.id');
            },
            'total_price' => function (Builder $query) {
                $query->select(DB::raw('SUM(order_items.price * order_items.amount)'))
                    ->from('order_items')
                    ->whereColumn('order_items.order_id', '=', 'orders.id');
            },
        ];
    }
}
