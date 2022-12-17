<?php

namespace App\Controllers;

use App\Helpers\RajaOngkir;

class Home extends BaseController
{

    public function __construct()
    {
        $this->rajaOngkir = new RajaOngkir();
        \Midtrans\Config::$serverKey = 'SB-Mid-server-vfU0nOxkI1SO7PoLgmGn4ai3';
        \Midtrans\Config::$clientKey = 'SB-Mid-client-9I93965jr4kaGb9J';
        \Midtrans\Config::$is3ds = true;
    }

    public function index()
    {
        $provinces = $this->rajaOngkir->getProvince();

        $data = [
            'provinces' => $provinces
        ];
        return view('order', $data);
    }
    public function getCity()
    {
        $province = $this->request->getGet('province');
        $result = $this->rajaOngkir->getCity(province: $province, json: true);
        return $result;
    }

    public function getCost()
    {
        $origin = $this->request->getVar('origin');
        $destination = $this->request->getVar('destination');
        $weight = $this->request->getVar('weight');
        $courier = $this->request->getVar('courier');
        $result = $this->rajaOngkir->getCost(origin: $origin, destination: $destination, weight: $weight, courier: $courier, json: true);

        return $result;
    }

    public function tes()
    {
        $tes = $this->rajaOngkir->getCity("27");
        $tes = get_object_vars($tes);
        dd($tes['city_id']);
    }
    public function snapPay()
    {
        // $originCity = $this->request->getVar('origin');
        // $originCity = get_object_vars($this->rajaOngkir->getCity($originCity));
        // $originCity = [
        //     "city_id" => "17",
        //     "city_name" => "Badung",
        //     "postal_code" => "80351",
        //     "type" => "Kabupaten",
        //     "province" => "Bali",
        //     "province_id" => "1"
        // ];

        $destinationCity = $this->request->getVar('destination');
        $destinationCity = get_object_vars($this->rajaOngkir->getCity($destinationCity));
        // $destinationCity = [
        //     "city_id" => "27",
        //     "city_name" => "Bangka",
        //     "postal_code" => "33212",
        //     "type" => "Kabupaten",
        //     "province" => "Bangka Belitung",
        //     "province_id" => "2"
        // ];

        $total = (int) $this->request->getVar('total');
        // $total = (int) "10000";

        $shipping_address = array(
            'first_name'    => "Nama",
            'last_name'     => "Penerima",
            'city'          => $destinationCity['city_name'],
            'postal_code'   => $destinationCity['postal_code']
        );

        $customer_details = array(
            'first_name'    => "Nama",
            'last_name'     => "Penerima",
            'shipping_address' => $shipping_address
        );

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $total,
            ),
            'customer_details' => $customer_details
        );
        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return $snapToken;
    }
}
