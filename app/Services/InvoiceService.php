<?php

namespace App\Services;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceService
{
    public function createInvoice(array $data)
    {

        try {

            DB::beginTransaction();

            $invoiceData = $this->getInvoiceData($data);
            $invoiceData['total_invoice'] = $this->getTotalInvoice($data['items']);
            $invoiceData['balance'] = $invoiceData['total_invoice'];
            $invoiceData['type'] = $data['type'];

            $invoice = Invoice::create($invoiceData);

            $lines = $this->getFormatedInvoiceLines($data['items'], $invoice->id)['rows'];

            if (!empty($lines)) {
                DB::table('invoice_items')->insert($lines);
            }

            DB::commit();
            return $invoice;

        } catch (\Exception $e) {
            // throw $th;
            DB::rollBack();
            throw $e;
        }

    }

    private function getInvoiceData(array $data)
    {
        return [
            'contact_id' => $data['contact_id'],
            'invoice_number' => $data['invoice_number'],
            'due_date' => $data['due_date'],
            'invoice_date' => $data['invoice_date'],
        ];
    }

    private function getFormatedInvoiceLines(array $items, string $invoice_id)
    {
        $rows = [];
        $total_invoice = 0;
        foreach ($items as $item) {

            $quantity = (int) $item['quantity'];
            $discount = (int) $item['discount'];
            $price = (int) $item['unit_price'];
            $total_line = $price * $quantity - $discount;
            $rows[] = [
                'quantity' => $quantity,
                'discount' => $discount,
                'unit_price' => $price,
                'total_line' => $total_line,
                'product_id' => $item['product_id'],
                'warehouse_id' => $item['warehouse_id'],
                'invoice_id' => $invoice_id,
                'id' => (string) Str::uuid(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $total_invoice += $total_line;

        }

        return [
            'rows' => $rows,
            'total_invoice' => $total_invoice,
        ];

    }

    public function getTotalInvoice(array $items)
    {
        $total_invoice = 0;

        foreach ($items as $item) {
            $quantity = (int) $item['quantity'];
            $discount = (int) $item['discount'];
            $price = (int) $item['unit_price'];
            $total_line = $price * $quantity - $discount;
            $total_invoice += $total_line;
        }

        return $total_invoice;
    }
}
