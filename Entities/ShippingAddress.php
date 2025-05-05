<?php

namespace Entities;

class ShippingAddress extends \App\Entities\Entity
{
    public string $address1;
    public string $address2 = '';
    public string $address3 = '';
    public string $town = '';
    public string $city = '';
    public string $countryCode;
    public string $zip;
}