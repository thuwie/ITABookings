<?php

namespace App\Domain\Entity;

class VNPAY
{
    private string $vnp_Url;
    private string $vnp_Returnurl;
    private string $vnp_TmnCode;
    private string $vnp_HashSecret;

    private string $vnp_TxnRef;
    private string $vnp_OrderInfo;
    private string $vnp_OrderType;

    private int|string $vnp_Amount;
    private string $vnp_Locale;
    private ?string $vnp_BankCode = null;
    private string $vnp_IpAddr;
    private string $vnp_CreateDate;
    private string $vnp_ExpireDate;

    public function __construct(array $data)
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $this->vnp_Url        = $data['vnp_Url']        ?? "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $this->vnp_Returnurl  = $data['vnp_Returnurl']  ?? "http://localhost:3000/booking/payment/callback";
        $this->vnp_TmnCode    = $data['vnp_TmnCode'] ?? 'HYTSXLFP';
        $this->vnp_HashSecret = $data['vnp_HashSecret'] ?? 'T1N3S3XXTSYCXMNWY6X9O649UPF1QRF8';

        $this->vnp_TxnRef     =  time();
        $this->vnp_OrderInfo  = $data['vnp_OrderInfo'];
        $this->vnp_OrderType  = $data['vnp_OrderType'];

        $this->vnp_Amount     = $data['vnp_Amount'] * 100;
        $this->vnp_IpAddr     = $data['vnp_IpAddr'] ?? '123.123.123.123';

        $this->vnp_Locale     = $data['vnp_Locale']     ?? "vn";
        $this->vnp_BankCode   = $data['vnp_BankCode']   ??  "NCB";

        $this->vnp_CreateDate = date('YmdHis');
        $this->vnp_ExpireDate = date('YmdHis', strtotime('+15 minutes'));

    }


  
    // ===============================
    //            GETTERS
    // ===============================

    public function getVnpUrl(): string {
        return $this->vnp_Url;
    }

    public function getVnpReturnurl(): string {
        return $this->vnp_Returnurl;
    }

    public function getVnpTmnCode(): string {
        return $this->vnp_TmnCode;
    }

    public function getVnpHashSecret(): string {
        return $this->vnp_HashSecret;
    }

    public function getVnpTxnRef(): string {
        return $this->vnp_TxnRef;
    }

    public function getVnpOrderInfo(): string {
        return $this->vnp_OrderInfo;
    }

    public function getVnpOrderType(): string {
        return $this->vnp_OrderType;
    }

    public function getVnpAmount(): int|string {
        return $this->vnp_Amount;
    }

    public function getVnpLocale(): string {
        return $this->vnp_Locale;
    }

    public function getVnpBankCode(): ?string {
        return $this->vnp_BankCode;
    }

    public function getVnpIpAddr(): string {
        return $this->vnp_IpAddr;
    }

    public function getVnpExpireDate(): string {
        return $this->vnp_ExpireDate;
    }

    // ===============================
    //            SETTERS
    // ===============================

    public function setVnpUrl(string $url): void {
        $this->vnp_Url = $url;
    }

    public function setVnpReturnurl(string $url): void {
        $this->vnp_Returnurl = $url;
    }

    public function setVnpTmnCode(string $code): void {
        $this->vnp_TmnCode = $code;
    }

    public function setVnpHashSecret(string $secret): void {
        $this->vnp_HashSecret = $secret;
    }

    public function setVnpTxnRef(string $ref): void {
        $this->vnp_TxnRef = $ref;
    }

    public function setVnpOrderInfo(string $info): void {
        $this->vnp_OrderInfo = $info;
    }

    public function setVnpOrderType(string $type): void {
        $this->vnp_OrderType = $type;
    }

    public function setVnpAmount(int|string $amount): void {
        $this->vnp_Amount = $amount;
    }

    public function setVnpLocale(string $locale): void {
        $this->vnp_Locale = $locale;
    }

    public function setVnpBankCode(?string $code): void {
        $this->vnp_BankCode = $code;
    }

    public function setVnpIpAddr(string $ip): void {
        $this->vnp_IpAddr = $ip;
    }

    public function setVnpExpireDate(string $expire): void {
        $this->vnp_ExpireDate = $expire;
    }

    public function toArray(): array
    {
        return [
            "vnp_Version"    => "2.1.0",
            "vnp_Command"    => "pay",
            "vnp_TmnCode"    => $this->vnp_TmnCode,
            "vnp_Amount"     => $this->vnp_Amount,
            "vnp_BankCode"   => $this->vnp_BankCode,
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => $this->vnp_IpAddr,
            "vnp_Locale"     => $this->vnp_Locale,
            "vnp_OrderInfo"  => $this->vnp_OrderInfo,
            "vnp_OrderType"  => $this->vnp_OrderType,
            "vnp_ReturnUrl"  => $this->vnp_Returnurl,
            "vnp_TxnRef"     => $this->vnp_TxnRef,
            "vnp_CreateDate"=> $this->vnp_CreateDate,
            "vnp_ExpireDate" => $this->vnp_ExpireDate,
        ];
    }

    public function buildUrl(): string
    {
        $inputData = $this->toArray();

        // Sort by key
        ksort($inputData);

        $query = "";
        $hashdata = "";
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i === 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }

            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // Base URL + Query
        $url = $this->vnp_Url . "?" . $query;

        // Gen hash
        if (!empty($this->vnp_HashSecret)) {
            $secureHash = hash_hmac('sha512', $hashdata, $this->vnp_HashSecret);
            $url .= "vnp_SecureHash=" . $secureHash;
        }

        return $url;
    }   
}
    