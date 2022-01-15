<?php

namespace App\Imports;

use App\Models\ImportedProducts;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Ramsey\Uuid\Uuid;

class ProductsImport implements ToModel, WithHeadingRow
{
    public $batch_id;

    public function __construct()
    {
        $this->batch_id = Uuid::uuid4()->getHex();
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ImportedProducts([
            'batch_id' => $this->batch_id,
            'client_id' => Auth::user()->client_id,
            'name' => $row['name'],
            'category_title' => $row['category'],
            'unit' => $row['unit'],
            'in_stock' => $row['stock'],
            'unit_price' => $row['unit_price']
        ]);
    }
}
