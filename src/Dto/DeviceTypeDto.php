<?php
namespace App\Dto;

readonly class DeviceTypeDto
{
    final public function __construct(
        public string $deviceType_id,

        public string $deviceType_name,

        public int $discount_rate,

        public float $normal_price,
    ) {}
}
