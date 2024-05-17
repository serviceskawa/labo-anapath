
{{ $data->order->code }} <br> <small style="text-transform: uppercase;"><b>{{ DB::table('type_orders')->where('id', $data->order->type_order_id)->value('title') }}</b></small>
