<?php

namespace App\Controllers;

use App\Helpers\RajaOngkir;
use Pusher\Pusher;

class Home extends BaseController
{
    private $rajaOngkir;

    public function __construct()
    {
        $this->rajaOngkir = new RajaOngkir();
        \Midtrans\Config::$serverKey = env('midtrans.serverKey');
        \Midtrans\Config::$clientKey = env('midtrans.clientKey');
        \Midtrans\Config::$is3ds = false;
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
    public function snapPayment()
    {
        $destinationCity = $this->request->getVar('destination');
        $destinationCity = get_object_vars($this->rajaOngkir->getCity($destinationCity));
        $total = (int) $this->request->getVar('total');

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
    public function cardPayment()
    {
        $token_id = $this->request->getVar('token_id');
        $destinationCity = $this->request->getVar('destination');
        $destinationCity = get_object_vars($this->rajaOngkir->getCity($destinationCity));
        $total = (int) $this->request->getVar('total');

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
            'payment_type' => 'credit_card',
            'credit_card'  => array(
                'token_id'      => $token_id,
                'authentication' => false,
            ),
            'customer_details' => $customer_details
        );
        $response = \Midtrans\CoreApi::charge($params);

        return json_encode($response);
    }

    public function gopayQrisPayment()
    {
        $destinationCity = $this->request->getVar('destination');
        $destinationCity = get_object_vars($this->rajaOngkir->getCity($destinationCity));
        $total = (int) $this->request->getVar('total');

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
            'payment_type' => 'gopay',
            'gopay' => array(
                'enable_callback' => false,
            ),
            'customer_details' => $customer_details
        );

        $response = \Midtrans\CoreApi::charge($params);
        return json_encode($response);
    }

    public function bcaVaPayment()
    {
        $token_id = $this->request->getVar('token_id');
        $destinationCity = $this->request->getVar('destination');
        $destinationCity = get_object_vars($this->rajaOngkir->getCity($destinationCity));
        $total = (int) $this->request->getVar('total');

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
            'payment_type' => 'bank_transfer',
            'credit_card'  => array(
                'token_id'      => $token_id,
                'authentication' => false,
            ),
            'customer_details' => $customer_details,
            'bank_transfer' => array(
                'bank' => 'bca'
            )
        );

        $response = \Midtrans\CoreApi::charge($params);

        return json_encode($response);
    }

    public function tesNotification()
    {
        $order_id = $this->request->getVar('order_id');
        $app_id = env('pusher.app_id');
        $app_key = env('pusher.key');
        $app_secret = env('pusher.secret');
        $app_cluster = env('pusher.cluster');
        $pusher = new Pusher($app_key, $app_secret, $app_id, ['cluster' => $app_cluster]);
        $data['order_id'] = $order_id;
        $channel = 'bcava-' . $order_id;
        $data['message'] = 'Tes notif bayar via bca va berhasil';
        $pusher->trigger('fixit', $channel, $data);
        return $channel;
    }
}
