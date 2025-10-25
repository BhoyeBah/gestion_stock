<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InvoiceService
{
    public function createInvoice(array $data)
    {

        try {

            DB::beginTransaction();

            $invoiceData = $this->getInvoiceData($data);

            $invoice = Invoice::create($invoiceData);

            $lines = $this->getFormatedInvoiceLines($data['items'], $invoice->id);

            if (! empty($lines)) {
                DB::table('invoice_items')->insert($lines);
            }

            DB::commit();


        } catch (\Exception $e) {
            // throw $th;
            DB::rollBack();
            throw $e;
        }

    }

    private function getInvoiceData(array $data)
    {
        return [
            'supplier_id' => $data['supplier_id'],
            'warehouse_id' => $data['warehouse_id'],
            'due_date' => $data['due_date'],
            'invoice_date' => $data['invoice_date'],
        ];
    }

    private function getFormatedInvoiceLines(array $items, string $invoice_id)
    {
        $rows = [];
        foreach ($items as $item) {

            $quantity = (int) $item['quantity'];
            $discount = (int) $item['discount'];
            $price = (int) $item['price'];
            $total_line = $price * $quantity - $discount;
            $rows[] = [
                'quantity' => $quantity,
                'discount' => $discount,
                'purchase_price' => $price,
                'total_line' => $total_line,
                'product_id' => $item['product_id'],
                'invoice_id' => $invoice_id,
                'id' => (String) Str::uuid(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

        }

        return $rows;

    }
}
