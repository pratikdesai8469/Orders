<?php

namespace App\Models;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use App\Models\Order;

class OrderImport implements ToCollection
{
    
    private $data = [];

    public function collection(Collection $rows)
    {
        $this->data = [];
        foreach ($rows as $key => $row) {
            if($key == 0) {
                continue;
            }
            if(!empty($row[0])){
                $order = new Order();
                if(!empty($row[0])){
                    $order->order_id = $row[0];
                }
                if(!empty($row[1])){
                    $order->pin_type_id = $row[1];
                }
                if(!empty($row[2])){
                    $order->payment_type = $row[2];
                }
                if(!empty($row[3])){
                    $order->customer_name = $row[3];
                }
                if(!empty($row[4])){
                    $order->full_address = $row[4];
                }
                if(!empty($row[5])){
                    $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[5]);
                    $order->order_date = \Carbon\Carbon::parse($date)->format('Y-m-d H:i:s');
                }
                if(!empty($row[6])){
                    $order->price = $row[6];
                }
                if(!empty($row[7])){
                    $order->quantity = $row[7];
                }
                if(!empty($row[8])){
                    $order->product_name = $row[8];
                }
                if(!empty($row[6]) && !empty($row[7])){
                    $order->final_price = $row[6] * $row[7]; 
                }
                $order->save();
            }
        }
        return true;
    }
}
