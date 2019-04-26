<?php

namespace App\Http\Controllers;

use Log;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BIBDController extends Controller
{
    /**
     * Initialize the soapclients
     */
    public function __construct()
    {
        $this->middleware('auth');

        try {
            $this->mobile_client = new \SoapClient('https://cib.bibd.com.bn/CCMiBWs/MiBWSServices/?wsdl');
            $this->epay_client = new \SoapClient('https://cib.bibd.com.bn/CCMiBWs/EPayWSServices/?wsdl');
            $this->savings_accs = array();
            $this->savings_accs_pos = array();
            $this->vcard_acc = "";
            $this->vcard_acc_pos = "";
        } catch (\Exception $e) {
            return "Probably missing soap extension.";
        }
    }

    /**
     * Log in to BIBD's Mobile Webservice
     * Get the cookie
     * Save it to the users table
     * If cookie exist and usable, re-use cookie, else login again and repeat.
     */
    private function loginBIBD()
    {
        try {
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

                if ($this->mobile_client->retriveAccountSummaryV2()->return->obHeader->statusMessage == "SUCCESS") {
                    $i = $this->mobile_client->retriveAccountSummaryV2()->return;
                    foreach ($i->obSavingsAccountDetails as $s) {
                        array_push($this->savings_accs, $s->accNo);
                    }
                    $this->vcard_acc = $i->obDebitCardAccountDetails->accNo;

                    $t = $this->mobile_client->fundTransferOwnRetrieve()->return->debitingAccountDetails;

                    $c = 1;
                    foreach ($t as $_t) {
                        if (in_array($_t->accNo, $this->savings_accs)) {
                            array_push($this->savings_accs_pos, $c);
                        }
                        if ($_t->accNo == $this->vcard_acc) {
                            $this->vcard_acc_pos = $c;
                        }
                        $c++;
                    }

                    $user->cookie = $this->mobile_client->_cookies["JSESSIONID"][0];
                    $user->save();
                    $this->epay_client->__setCookie("JSESSIONID", $user->cookie);

                    return;
                }
            }
            if ($user->cookie != null) {
                $this->mobile_client->__setCookie("JSESSIONID", $user->cookie);
                $this->epay_client->__setCookie("JSESSIONID", $user->cookie);

                if ($this->mobile_client->retriveAccountSummaryV2()->return->obHeader->statusMessage == "SUCCESS") {
                    $i = $this->mobile_client->retriveAccountSummaryV2()->return;
                    foreach ($i->obSavingsAccountDetails as $s) {
                        array_push($this->savings_accs, $s->accNo);
                    }
                    $this->vcard_acc = $i->obDebitCardAccountDetails->accNo;

                    $t = $this->mobile_client->fundTransferOwnRetrieve()->return->debitingAccountDetails;

                    $c = 1;
                    foreach ($t as $_t) {
                        if (in_array($_t->accNo, $this->savings_accs)) {
                            array_push($this->savings_accs_pos, $c);
                        }
                        if ($_t->accNo == $this->vcard_acc) {
                            $this->vcard_acc_pos = $c;
                        }
                        $c++;
                    }

                    return;
                }

                $user->cookie = null;
                $user->save();
                $this->loginBIBD();
            }
        } catch (\Exception $e) {
            report($e);
            return "Error (loginBIBD): Check Logs.";
        }

        return "Error";
    }

    /**
     * Show logged in home page with basic account summary
     */
    public function getHome()
    {
        $this->loginBIBD();

        try {
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
                'vcard' => $vcard,
                'savings_accs' => $this->savings_accs,
                'savings_accs_pos' => $this->savings_accs_pos,
            ]);
        } catch (\Exception $e) {
            report($e);
            return "Error: Check Logs.";
        }

        return "Error";
    }

    /**
     * API-ish to get the vcard transaction history
     */
    public function getVcardHistory($accNo)
    {
        $this->loginBIBD();

        try {
            $decrypted = decrypt($accNo, false);

            $params = array(
                "accountNumber" => $decrypted,
            );

            $result = $this->mobile_client->retrieveDebitAccountDetails($params)->return;
            $vmchist = array();
            $app = app();

            foreach ($result->tHistory as $r) {
                $v = $app->make('stdClass');

                $v->date = $r->date;
                $v->description = $r->desc;
                $v->amount = $r->amt;

                array_push($vmchist, $v);
            }

            return $vmchist;
        } catch (\Exception $e) {
            report($e);
            return "Error: Check Logs.";
        }

        return "Error";
    }

    /**
     * API-ish to get the savings transaction history
     */
    public function getSavingsHistory($accNo)
    {
        $this->loginBIBD();

        try {
            $decoded = base64_decode($accNo);

            $params = array(
                "accountNumber" => $decoded,
            );

            $result = $this->mobile_client->retriveSavingsAccountDetails($params)->return;
            $savhist = array();
            $app = app();

            foreach ($result->history as $r) {
                $s = $app->make('stdClass');

                $s->date = $r->transactionDate;
                $s->description = $r->transactionDescription;
                $s->amount = $r->amount->amount;
                $s->balance = $r->balance->amount;

                array_push($savhist, $s);
            }

            return $savhist;
        } catch (\Exception $e) {
            report($e);
            return "Error: Check Logs.";
        }

        return "Error";
    }

    /**
     * Send a payment using vCard
     */
    public function postVcardSend(Request $request)
    {
        $this->loginBIBD();

        try {
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
        } catch (\Exception $e) {
            flash('Error!', 'Please check logs!', 'error');

            report($e);
            return back();
        }

        return "Error";
    }

    /**
     * API-ish to get the pin number for cardless withdrawal
     */
    public function getPin()
    {
        $this->loginBIBD();
        try {
            $params = array(
                "location" => "114.9399723,4.8887523"
            );

            $result = $this->mobile_client->retrieveCardlessPin($params)->return;

            return [
                'pin' => base64_decode($result->cvv2)
            ];
        } catch (\Exception $e) {
            report($e);
            return "Error: Check Logs.";
        }

        return "Error";
    }

    /**
     * Check if the vCard phone number is valid
     */
    public function postVcardCheckPhoneNumber(Request $request)
    {
        $this->loginBIBD();

        try {
            $this->validate($request, [
                'to' => 'required|integer|digits:7'
            ]);
            $params = array(
                "mobileNumber" => '673' . $request->to,
                "amount" => "1",
                "description" => "check username"
            );

            if ($this->epay_client->performEPaySendCashConfirm($params)->return->obHeader->statusMessage == 'Success') {
                return $this->epay_client->performEPaySendCashConfirm($params)->return->obTransaction->userFullName;
            }

            return 'User does not exist';
        } catch (\Exception $e) {
            report($e);
            return "Error: Check Logs.";
        }

        return "Error";
    }

    /**
     * Top up vCard
     */
    public function postVcardTopUp(Request $request)
    {
        $this->loginBIBD();

        try {
            $this->validate($request, [
                'from' => 'required|integer|in:' . implode(',', $this->savings_accs_pos),
                'amount' => 'required|numeric|min:2',
            ]);

            $params = array(
                "transferFromAccount" => $request->from,
                "transferToAccount" => $this->vcard_acc_pos,
                "transferAmount" => $request->amount
            );

            $this->mobile_client->fundTransferOwnConfirm($params);
            if ($this->mobile_client->fundTransferOwnSumbit($params)->return->obHeader->success) {
                flash('Success!', 'Transaction succeeded!');
                return back();
            }

            flash('Error!', 'Transaction failed!', 'error');
            return back();
        } catch (\Exception $e) {
            report($e);
            return "Error: Check Logs.";
        }

        return "Error";
    }
}
