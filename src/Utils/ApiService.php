<?php

namespace App\Utils;

class ApiService {
    const BASE_URL = 'https://swapi.dev/api/';
    private $customUri = '';

    /**
     * @param $method
     * @param $root
     * @param $data
     * @return bool|string
     * @throws \Exception
     */
    public function callAPI($method, $root, $data) {
        $url = !$this->customUri ? self::BASE_URL . $root : $this->customUri;
        $result = [];
        try {
            $curl = \curl_init();
            switch ($method) {
                case "POST":
                    \curl_setopt($curl, CURLOPT_POST, 1);
                    if ($data)
                        \curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                case "PUT":
                    \curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                    if ($data)
                        \curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                default:
                    if ($data)
                        $url = sprintf("%s?%s", $url, http_build_query($data));
            }
            // OPTIONS:
            \curl_setopt($curl, CURLOPT_URL, $url);
            \curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'APIKEY: 111111111111111111111',
                'Content-Type: application/json',
            ));
            \curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            \curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            // EXECUTE:
            $result = \curl_exec($curl);
            if(!$result){
                die("Connection Failure");
            }
            \curl_close($curl);
        } catch (\Exception $e) {
            throw $e;
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getCustomUri(): string
    {
        return $this->customUri;
    }

    /**
     * @param string $customUri
     * @return ApiService
     */
    public function setCustomUri(string $customUri): ApiService
    {
        $this->customUri = $customUri;
        return $this;
    }
}