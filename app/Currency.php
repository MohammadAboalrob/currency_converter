<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
	protected $fillable = ['currency_code', 'symbol', 'rate'];

    public static function set_val($code, $val){
    	$currency = Currency::where('currency_code', $code)->first();
    	$currency ->rate = $val;
    	$currency ->save();
    	
    	return $currency;
    }
 
}
