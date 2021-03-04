<?php
require_once '../vendor/autoload.php';

$api = \Roniwahyu\VaBtnUnmer\Api::getInstance();
$api->setId('');
$api->setKey('');
$api->setSecret('');
$api->setApiUrl('https://vabtn-dev.btn.co.id:9021/v1/unmer/');

// generaye VA Number
$va = \Roniwahyu\VaBtnUnmer\VANumberFormat::build(
    '9', '4572', '001', '1'
)->generate();

// create Api
$request = new \Roniwahyu\VaBtnUnmer\Request([
    'ref' => rand(11, 99).time(),
    'va' => $va,
    'nama' => 'Syahroni Wahyu Iriananda',
    'layanan' => 'Pembayaran UKT',
    'kodelayanan' => '001',
    'jenisbayar' => 'UKT Tahun 2019-2020 Ganjil',
    'kodejenisbyr' => '20191',
    'noid' => '137006107',
    'tagihan' => 1000000,
    'flag' => 'F',
    'expired' => '1909082359',
    'reserve' => '',
    'description' => '',
    'angkatan' => '2013',
]);
$update_request = $request;
$response = $api->create($request);
var_dump($response->getAll());

//
//// inquiry Api
//$request = new \Roniwahyu\VaBtnUnmer\Request([
//    'ref' => rand(11, 99).time(),
//    'va' => $va
//]);
//$response = $api->inquiry($request);
//var_dump($response->getAll());
//
//
//// update Api
//$update_request['description'] = "Update Nominal";
//$update_request['tagihan'] = 50000;
//$response = $api->update($update_request);
//var_dump($response->getAll());
//
//// inquiry Api
//$request = new \Roniwahyu\VaBtnUnmer\Request([
//    'ref' => rand(11, 99).time(),
//    'va' => $va
//]);
//$response = $api->inquiry($request);
//var_dump($response->getAll());
//
//
//// delete Api
//$request = new \Roniwahyu\VaBtnUnmer\Request([
//    'ref' => rand(11, 99).time(),
//    'va' => $va
//]);
//$response = $api->delete($request);
//var_dump($response->getAll());
//
//
//// inquiry Api
//$request = new \Roniwahyu\VaBtnUnmer\Request([
//    'ref' => rand(11, 99).time(),
//    'va' => $va
//]);
//$response = $api->inquiry($request);
//var_dump($response->getAll());

