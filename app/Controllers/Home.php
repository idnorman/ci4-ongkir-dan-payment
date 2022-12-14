<?php

namespace App\Controllers;

use App\Helpers\RajaOngkir;

class Home extends BaseController
{

    public function __construct()
    {
        $this->rajaOngkir = new RajaOngkir();
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
    }
}
