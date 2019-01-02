<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;
use Carbon\Carbon;
use GuzzleHttp\Client;

class CurrencyController extends Controller
{

	public function index()
	{
        return Currency::all();
    }

    protected function getCurrencyFromApi($curr)
    {
    	$client = new client();
		$res = $client->request('GET', 'https://free.currencyconverterapi.com/api/v6/convert?q=USD_'.$curr.'&compact=y');
		$data = json_decode($res->getbody()->getContents());
		
		return data_get($data,'USD_'.strtoupper($curr).'.val');
    }

	public function show($curr)
	{
		$data = Currency::where('currency_code', $curr)->first();
		if($data == null){
			$data = $this->getCurrencyFromApi($curr);
			if($data == null){
				return 'Currency not found !';
			}
			
			Currency::create(['currency_code'=>$curr, 'symbol'=> ' ', 'rate'=>$data]);

			return \Response::json($data);
		}

		$time = $data->updated_at;
		$mytime =  Carbon::now();
		$diff = $mytime->diffInSeconds($time);
        if($time == null or $diff >= 1800){
        	$data = $this->getCurrencyFromApi($curr);

			return \Response::json(Currency::set_val($curr,$data)->rate);
        } else {
        	return \Response::json($data->rate);
        }
    }
}
