<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory;

    protected $table = 'revenues';

    protected $fillable = [
        'date',
        'number_of_items_sold',
        'number_of_orders',
        'average_net_sales_amount',
        'coupon_amount',
        'shipping_amount',
        'gross_sales_amount',
        'amount',
        'refund_amount',
        'from_file',
        'user_id'
    ];

    public function user() {
        $this->belongsTo(User::class);
    }
}
