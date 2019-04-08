<?php

namespace App\Http\Controllers;

use Auth;

class BIBDController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->mobile_client = new \SoapClient('https://cib.bibd.com.bn/CCMiBWs/MiBWSServices/?wsdl');
        $this->epay_client = new \SoapClient('https://cib.bibd.com.bn/CCMiBWs/EPayWSServices/?wsdl');
    }

    private function loginBIBD()
    {
        $user = Auth::user();
        if ($user->cookie == null) {
            $params = array(
                "loginID" => $user->username,
                "internetPin" => decrypt($user->internet_pin, false)
            );

            $result = $this->mobile_client->loginInternetPin($params);

            $pos = explode(',', $result->return->obUser->mobilePasswordPosition);

            $pin = str_split(decrypt($user->mobile_pin, false));

            $x = 1;
            foreach ($pin as $p) {
                $pin[$x] = $x . $p;
                $x++;
            }

            $mpass = array();
            foreach ($pos as $i) {
                array_push($mpass, $pin[$i]);
            }

            $mpassword = implode(",", $mpass);

            //     "deviceType" => "Android",
            //     "data" => "119.160.171.7",
            //     "deviceId" => "CVH7N16428000077"
            $params = array(
                "mPassword" => $mpassword,
                "deviceType" => "Testing",
                "data" => "0.0.0.0",
                "deviceId" => "Testing"
            );

            $result = $this->mobile_client->loginMobilePasswordConfirm($params);

            if (!isset($result->return->obHeader->success)) {
                session()->flash('status', 'BIBD Login Failed!');
                return;
            }
            $user->cookie = $this->mobile_client->_cookies["JSESSIONID"][0];
            $user->save();
            $this->epay_client->__setCookie = $user->cookie;

            session()->forget('status');
            session()->flush();

            return;
        }

        if ($user->cookie != null) {
            $this->mobile_client->__setCookie = $user->cookie;
            $this->epay_client->__setCookie = $user->cookie;

            if ($this->mobile_client->retriveAccountSummaryV2()->return->obHeader->statusMessage == "SUCCESS") {
                return;
            }
            $user->cookie = null;
            $user->save();
            $this->loginBIBD();
        }
    }

    public function getHome()
    {
        $this->loginBIBD();

        $acc_summary = $this->mobile_client->retriveAccountSummaryV2()->return;
        $savings = array();
        $app = app();

        foreach ($acc_summary->obSavingsAccountDetails as $s) {
            $saving = $app->make('stdClass');
            $saving->acc_no = $s->accNo;
            $saving->balance = '$ ' . $s->amtbalance;
            array_push($savings, $saving);
        }

        $v = $acc_summary->obDebitCardAccountDetails;
        $vcard = $app->make('stdClass');
        $vcard->acc_no = $v->accNo;
        $vcard->balance = '$ ' . $v->avaibalance;

        return view('home', [
            'savings' => $savings,
            'vcard' => $vcard
        ]);
    }

   
}
