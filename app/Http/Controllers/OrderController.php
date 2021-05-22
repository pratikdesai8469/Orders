<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderImport;
use Yajra\DataTables\DataTables;
use App\Models\Order;
use Carbon\Carbon;
use Session;

class OrderController extends Controller
{
    
    public function index()
    {
        $productName = Order::groupBy('product_name')->pluck('product_name','product_name')->toArray();
        return view('order',compact('productName'));
    }
    public function import(Request $request)
    {
        $extensions = array("xls","xlsx","xlm","xlw",'csv');
        if($request->order_sheet){
            $result = array($request->file('order_sheet')->getClientOriginalExtension());
            if(!in_array($result[0],$extensions)){
                Session::flash('upload_error','Please upload valid format.');
                return back();
            }
            $import = new OrderImport;
            $path1 = $request->file('order_sheet')->store('temp'); 
            $path=storage_path('app').'/'.$path1;  
            $data = \Excel::import(new OrderImport,$path);
            Session::flash('success','Your sheet successfully uploaded.');
            return back();
        }
    }

    public function data(Request $request)
    {
        $order = Order::query();
        $pName = $request->product_name;
        if ($pName) {
            $order = $order->where(function ($query) use ($pName) {
                foreach ($pName as $name) {
                   $query->orWhere('product_name', 'LIKE', '%'.$name.'%');
                }
            });
        }
        $date = $request->date;
        if($date){
            $date = explode('/',$date);
            $startDate =  trim($date[0],' ');
            $endDate =  Carbon::parse(trim($date[1],' '));
            $endDate = $endDate->addDays(1);
            $order = $order->whereBetween('order_date', [$startDate, $endDate]);
        }
        $order = $order->get()->map(function($q){
            return $q;
        });
        $totalPriceSum = $order->sum('final_price');
        $data['cash']['amount'] = $order->where('payment_type','cash')->sum('final_price');
        $data['cash']['percentage'] = $data['cash']['amount'] ? number_format((($data['cash']['amount'] * 100) / $totalPriceSum),2) : 0;
        $data['cheque']['amount'] = $order->where('payment_type','cheque')->sum('final_price');
        $data['cheque']['percentage'] = $data['cheque']['amount'] ? number_format((($data['cheque']['amount'] * 100) / $totalPriceSum),2) : 0;
        $data['online']['amount'] = $order->where('payment_type','online')->sum('final_price');
        $data['online']['percentage'] = $data['online']['amount'] ? number_format((($data['online']['amount'] * 100) / $totalPriceSum),2) : 0;
        $data['paymentDue']['amount'] = $order->where('payment_type','paymentDue')->sum('final_price');
        $data['paymentDue']['percentage'] = $data['paymentDue']['amount'] ? number_format((($data['paymentDue']['amount'] * 100) / $totalPriceSum),2) : 0;
        $data['webPayment']['amount'] = $order->where('payment_type','webPayment')->sum('final_price');
        $data['webPayment']['percentage'] = $data['webPayment']['amount'] ? number_format((($data['webPayment']['amount'] * 100) / $totalPriceSum),2) : 0;
        return DataTables::of($order)
        ->with([
            'amount_data'=>$data
         ]) ->addColumn('order_date', function ($order) {
            return Carbon::parse($order->order_date)->format('d-m-Y H:i:a');
        })
        ->make(true);
    }
}
