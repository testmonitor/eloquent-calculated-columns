<?php

namespace TestMonitor\CalculatedColumns\Test;

use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Database\Eloquent\Collection;
use TestMonitor\CalculatedColumns\Exceptions\InvalidCalculatedColumn;
use TestMonitor\CalculatedColumns\Test\Models\Order;
use TestMonitor\CalculatedColumns\Test\Models\OrderItem;
use TestMonitor\CalculatedColumns\Requests\CalculatedColumnsRequest;

class CalculatedColumnsTest extends TestCase
{
    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    protected $orders;

    public function setUp(): void
    {
        parent::setUp();

        $this->orders = Order::factory()->count(3)->create();

        $this->orders->each(function (Order $order) {
            OrderItem::factory()->for($order)->count(5)->create();
        });
    }

    #[Test]
    public function it_will_add_a_calculated_column()
    {
        // Given
        $this->app->bind(CalculatedColumnsRequest::class, fn () => CalculatedColumnsRequest::fromRequest(
            new Request(['calculate' => 'total_price'])
        ));

        // When
        $results = Order::query()
            ->withCalculatedColumns()
            ->get();

        // Then
        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(3, $results);
        $this->assertEquals(
            $results->first()->total_price,
            $results->first()->items->sum(fn ($item) => $item->amount * $item->price)
        );
    }

    #[Test]
    public function it_will_add_multiple_calculated_columns()
    {
        // Given
        $this->app->bind(CalculatedColumnsRequest::class, fn () => CalculatedColumnsRequest::fromRequest(
            new Request(['calculate' => 'total_amount,total_price'])
        ));

        // When
        $results = Order::query()
            ->withCalculatedColumns()
            ->get();

        // Then
        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(3, $results);
        $this->assertEquals(
            $results->first()->total_amount,
            $results->first()->items->sum(fn ($item) => $item->amount)
        );
        $this->assertEquals(
            $results->first()->total_price,
            $results->first()->items->sum(fn ($item) => $item->amount * $item->price)
        );
    }

    #[Test]
    public function it_will_throw_an_exception_when_an_invalid_column_is_provided()
    {
        // Given
        $this->app->bind(CalculatedColumnsRequest::class, fn () => CalculatedColumnsRequest::fromRequest(
            new Request(['calculate' => 'total'])
        ));

        $this->expectException(InvalidCalculatedColumn::class);

        // When
        $results = Order::query()
            ->withCalculatedColumns()
            ->get();

        // Then
        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(0, $results);
    }
}
