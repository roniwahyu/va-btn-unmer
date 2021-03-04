<?php


namespace Roniwahyu\VaBtnUnmer;


class VANumberFormat extends DataStructure
{
    public function generate() {
        $columns = [
            'prefix', 'kode_institusi', 'kode_payment', 'customer_number'
        ];
        $valid = $this->errorMustExists($columns);
        $this->filter($columns);

        $prefix = $this->getColumn('prefix');
        if (strlen($prefix) != 1) throw new \Exception("Prefix VA tidak valid, panjang karakter harus 1 karakter");

        $kode_institusi = $this->getColumn('kode_institusi');
        if (strlen($kode_institusi) != 4) throw new \Exception("Kode Institusi VA tidak valid, panjang karakter harus 4 karakter");


        $kode_payment = $this->getColumn('kode_payment');
        if (strlen($kode_payment) > 3) throw new \Exception("Kode Payment VA tidak valid, panjang karakter maksimal 3 karakter");
        $this->setColumn('kode_payment',
            str_pad($this->getColumn('kode_payment'), 3, '0', STR_PAD_LEFT)
        );

        $customer_number = $this->getColumn('customer_number');
        if (strlen($customer_number) > 10) throw new \Exception("Customer Number VA tidak valid, panjang karakter maksimal 10 karakter");
        $this->setColumn('customer_number',
            str_pad($this->getColumn('customer_number'), 10, '0', STR_PAD_LEFT)
        );

        return implode("", $this->getAll());
    }

    public static function build($prefix, $kode_institusi, $kode_payment, $customer_number) {
        return new self(compact('prefix', 'kode_institusi', 'kode_payment', 'customer_number'));
    }

}