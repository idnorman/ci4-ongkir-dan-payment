<?php

namespace App\Helpers;

class RajaOngkir
{

    protected $key, $url;

    function __construct()
    {
        $this->key = env('rajaongkir.key');
        $this->url = env('rajaongkir.baseURI');
    }

    private function get($endpoint, $params = [], $json = false)
    {
        $curl = service('curlrequest');
        $url = $this->url . $endpoint;
        $res = $curl->request("GET", $url, [
            "headers" => [
                "Accept" => "application/json",
                "content-type: application/x-www-form-urlencoded",
                "key" => $this->key
            ],
            "query" => $params
        ],);

        if ($json) return json_encode(json_decode($res->getBody())->rajaongkir->results);
        return json_decode($res->getBody())->rajaongkir->results;
    }

    private function post($endpoint, $params = [], $json = false)
    {
        $curl = service('curlrequest');
        $url = $this->url . $endpoint;
        $res = $curl->request("POST", $url, [
            "headers" => [
                "Accept" => "application/json",
                "content-type: application/x-www-form-urlencoded",
                "key" => $this->key
            ],
            "form_params" => $params
        ],);
        if ($json) return json_encode(json_decode($res->getBody())->rajaongkir->results[0]);
        return json_decode($res->getBody())->rajaongkir->results[0];
    }

    public function getProvince($province = null,  $json = false)
    {
        if ($province) return $this->get('province', ['id' => $province],  $json);

        return $this->get('province');
    }

    public function getCity($city = null, $province = null, $json = false)
    {
        $params = array();
        if ($city) $params['id'] = $city;
        if ($province) $params['province'] = $province;

        return $this->get('city', $params, $json);
    }

    public function getCost($origin, $destination, $weight, $courier,  $json = false)
    {
        return $this->post('cost', ['origin' => $origin, 'destination' => $destination, 'weight' => $weight, 'courier' => $courier], $json);
    }
}
