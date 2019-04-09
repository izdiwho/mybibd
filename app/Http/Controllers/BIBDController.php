<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
                flash('Error!', 'BIBD Login Failed!', 'error');
                return;
            }
            $user->cookie = $this->mobile_client->_cookies["JSESSIONID"][0];
            $user->save();
            $this->epay_client->__setCookie("JSESSIONID", $user->cookie);

            session()->forget('status');
            session()->flush();

            return;
        }

        if ($user->cookie != null) {
            $this->mobile_client->__setCookie("JSESSIONID", $user->cookie);
            $this->epay_client->__setCookie("JSESSIONID", $user->cookie);

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

    public function getVcardHistory($accNo)
    {
        $this->loginBIBD();

        $params = array(
            "accountNumber" => decrypt($accNo, false),
        );

        $result = $this->mobile_client->retrieveDebitAccountDetails($params)->return;

        $vmchist = array();
        foreach ($result->tHistory as $r) {
            if (preg_match('(\bSend VMC\b)', $r->desc, $matches)) {
                array_push($vmchist, $r);
            }
        }

        return $vmchist;
    }

    public function getSavingsHistory()
    { }

    public function postVcardSend(Request $request)
    {
        $this->loginBIBD();

        $validator = Validator::make($request->all(), [
            'to' => 'bail|required|integer|digits:7',
            'amount' => 'bail|required|numeric|min:1',
            'description' => 'required|string|max:15'
        ]);

        if ($validator->fails()) {
            flash('Error!', 'Transaction failed!', 'error');

            return back()->withErrors($validator, 'vcard_send')
                ->withInput();
        }

        $params = array(
            "mobileNumber" => '673' . $request->to,
            "amount" => $request->amount,
            "description" => $request->description
        );

        if ($this->epay_client->performEPaySendCash($params)->return->obHeader->statusMessage == 'Success') {
            flash('Success!', 'Transaction succeeded!');
            return back();
        }

        flash('Error!', 'Transaction failed!', 'error');
        return back();
    }

    public function postVcardCheckUsername(Request $request)
    {
        $this->loginBIBD();

        $this->validate($request, [
            'to' => 'required|integer|min:7|max:7'
        ]);

        $params = array(
            "mobileNumber" => '673' . $request->to,
            "amount" => "1",
            "description" => "check username"
        );

        if ($this->epay_client->performEPaySendCashConfirm($params)->return->obHeader->statusMessage == 'Success') {
            return $this->epay_client->performEPaySendCashConfirm($params)->return->obTransaction->userFullName;
        }

        return 'User does not exists';
    }

    public function getPin()
    {
        $this->loginBIBD();

        $params = array(
            "location" => "114.4750512,4.6708717"
        );

        $result = $this->mobile_client->retrieveCardlessPin($params)->return;

        return [
            'pin' => base64_decode($result->cvv2)
        ];
    }
}
