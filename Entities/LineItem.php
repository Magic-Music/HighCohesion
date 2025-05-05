<?php

namespace Entities;

use App\Entities\Entity;
use App\Interfaces\CartInterface;

class LineItem extends Entity implements CartInterface
{
    public string $sku;
    public string $title;
    public int $quantity = 1;
    public float $price;
    public float $total;

    public function getTitle(): string
    {
        return $this->title;
    }
}