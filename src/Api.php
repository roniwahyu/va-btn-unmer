<?php
namespace Roniwahyu\VaBtnUnmer;


class Api
{
    protected static $id;
    protected static $key;
    protected static $secret;
    protected $endpoint_url;
    protected static $api_url;
    protected static $signature;
    /**
     * @var array
     */
    protected $data;
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    const DEFAULT_INQUIRY_URL='inqVA';
    const DEFAULT_CREATE_URL='createVA';
    const DEFAULT_UPDATE_URL='updVA';
    const DEFAULT_DELETE_URL='deleteVA';
    const DEFAULT_REPORT_URL='report';

    /**
     * @return mixed
     */
    public static function getId()
    {
        return self::$id;
    }

    /**
     * @param mixed $id
     */
    public static function setId($id)
    {
        self::$id = $id;
    }

    /**
     * @return mixed
     */
    public static function getKey()
    {
        return self::$key;
    }

    /**
     * @param mixed $key
     */
    public static function setKey($key)
    {
        self::$key = $key;
    }

    /**
     * @return mixed
     */
    public static function getSecret()
    {
        return self::$secret;
    }

    /**
     * @param mixed $secret
     */
    public static function setSecret($secret)
    {
        self::$secret = $secret;
    }

    /**
     * @return mixed
     */
    public static function getApiUrl()
    {
        return self::$api_url;
    }

    /**
     * @param mixed $api_url
     */
    public static function setApiUrl($api_url)
    {
        self::$api_url = $api_url;
    }

    /**
     * @return mixed
     */
    public function getEndpointUrl()
    {
        return $this->endpoint_url;
    }

    /**
     * @param mixed $endpoint_url
     */
    public function setEndpointUrl($endpoint_url)
    {
        $this->endpoint_url = $endpoint_url;
    }



    /**
     * @return mixed
     */
    public static function getSignature()
    {
        return self::$signature;
    }

    /**
     * @param mixed $signature
     */
    public static function setSignature($signature)
    {
        self::$signature = $signature;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }



    /**
     * @return Api
     */
    public static function getInstance()
    {
        static $instance;
        if ($instance==null) $instance = new self();
        return $instance;
    }


    public function _curl() {

        $curl = curl_init();

        $api_url = self::getApiUrl();
        if (!$api_url) throw new \Exception("Api URL belum diatur");

        $endpoint_url = self::getEndpointUrl();
        if (!$endpoint_url) throw new \Exception("Endpoint URL belum diatur");

        $id = self::getId();
        if (!$id) throw new \Exception("ID harus diisi");

        $key = self::getKey();
        if (!$key) throw new \Exception("Key harus diisi");

        $secret = self::getSecret();
        if (!$secret) throw new \Exception("Secret Harus diisi");

        $this->generateSignature();
        $signature = self::getSignature();
        if (!$signature) throw new \Exception("Signature Harus di generate");

        $url = $api_url."/".$endpoint_url;

        $data = json_encode($this->getData());

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Length: ".strlen($data),
                "Content-Type: application/json",
                "cache-control: no-cache",
                "id: $id",
                "key: $key",
                "secret: $secret",
                "signature: $signature"
            ),
        ));

        $response_string = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new \Exception($err);
        } else {
            $response = $this->parseResponse($response_string);
            $this->setResponse($response);
        }
    }

    protected function generateSignature() {
        $secret = self::getSecret();
        $id = self::getId();
        $key = self::getKey();
        $data = $this->getData();
        $data = json_encode($data);

        $payload = sprintf("%s:%s:%s", $id, $data, $key);

        $signature = hash_hmac("sha256", $payload, $secret);
        self::setSignature($signature);
    }

    public static function __callStatic($name, $arguments)
    {
        $instance = new static();

        if ( method_exists( $instance, $name ) ) {
            return call_user_func_array( [ $instance, $name ], $arguments );
        }
    }


    /**
     * @param Request $request
     * @throws \Exception
     * @return Response
     */
    public function inquiry(Request $request) {
        $this->setEndpointUrl(self::DEFAULT_INQUIRY_URL);
        $request->filter(['ref', 'va']);
        $this->setData($request);
        $this->_curl();
        $response = $this->getResponse();
        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request) {
        $this->setEndpointUrl(self::DEFAULT_CREATE_URL);
        $request->filter([
            'ref', 'va', 'nama', 'layanan', 'kodelayanan',
            'jenisbayar', 'kodejenisbyr', 'noid', 'tagihan',
            'flag', 'expired', 'reserve', 'description', 'angkatan',
            'createdate', 'createtime'
        ]);
        $this->setData($request);
        $this->_curl();
        $response = $this->getResponse();
        return $response;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function update(Request $request) {
        $this->setEndpointUrl(self::DEFAULT_UPDATE_URL);
        $request->filter([
            'ref', 'va', 'nama', 'layanan', 'kodelayanan',
            'jenisbayar', 'kodejenisbyr', 'noid', 'tagihan',
            'flag', 'expired', 'reserve', 'description', 'angkatan',
            'createdate', 'createtime'
        ]);
        $this->setData($request);
        $this->_curl();
        $response = $this->getResponse();
        return $response;
    }

    public function delete(Request $request) {
        $this->setEndpointUrl(self::DEFAULT_DELETE_URL);
        $request->filter([
            'ref', 'va'
        ]);
        $this->setData($request);
        $this->_curl();
        $response = $this->getResponse();
        return $response;
    }

    /**
     * @param $response_string
     * @return Response
     */
    public function parseResponse($response_string) {
        $response_array = json_decode($response_string, 1);
        return new Response($response_array);
    }
}