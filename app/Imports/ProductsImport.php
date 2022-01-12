<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'client_id' => Auth::user()->client_id,
            'name' => $row['name'],
            'unit' => $row['unit'],
            'in_stock' => $row['stock'],
            'unit_price' => $row['unit_price']
        ]);
    }
}
