<?php

namespace Dbs\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class PaymentExport implements FromArray
{
    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function array(): array
    {
        return $this->rows;
    }
}
