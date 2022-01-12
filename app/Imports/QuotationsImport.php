<?php

namespace App\Imports;

use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuotationsImport implements ToModel, WithHeadingRow
{
    public $quotation = false;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (!$this->quotation) {
            $quotation = new Quotation();
            $quotation->client_id = Auth::user()->client_id;
            $quotation->employee_id = Auth::user()->employee()->id;
            $quotation->company = Auth::user()->client()->name;
            $quotation->discount = 0;
            $quotation->original_amount = 0;
            $quotation->total_amount = 0;
            $quotation->quotation_type = 'rcvd';
            $quotation->gst = fetchSetting('gst');
            # Save the quotation
            $this->quotation = $quotation->save();
        }

        return new QuotationItem([
            'quotation_id' => $quotation->id,
            'product_id' => '',
            'quantity' => $row['quantity'],
            'previous_quantity' => 0,
            'discount' => '',
            'original_unit_price' => '',
            'original_total_price' => '',
            'unit_price' => '',
            'total_price' => '',
        ]);
    }
}
