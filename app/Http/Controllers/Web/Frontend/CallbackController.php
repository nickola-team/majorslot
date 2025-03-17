<?php

namespace VanguardLTE\Http\Controllers\Web\Frontend {

    use Log;
    use Illuminate\Support\Facades\Http;

    class CallbackController extends \VanguardLTE\Http\Controllers\Controller
    {

        public static function userBalance($callback, $username)
        {
            $result = [
                'status' => 0,
                'msg' => '',
                'balance' => null,
            ];

            try {
                $response = Http::post($callback . '/balance', [
                    'username' => $username,
                ]);

                if (!$response->successful()) {
                    $result['msg'] = "HTTP request failed with status code: " . $response->status();
                    return $result;
                }

                $resObj = $response->json();

                if ($resObj['status'] != 1 || !isset($resObj['balance']) || !is_numeric($resObj['balance']) || $resObj['balance'] <= 0) {
                    $result['msg'] = $resObj['msg'] ?? "balance is either not set, not numeric, or not greater than 0.";
                    return $result;
                }

                $result['status'] = 1;
                $result['balance'] = intval($resObj['balance']);
            } catch (\Exception $ex) {
                $result['msg'] = $ex->getMessage();
            }

            return $result;
        }

        public static function setTransaction($callback, $transaction)
        {
            $result = [
                'status' => 0,
                'msg' => '',
                'balance' => null,
                'beforeBalance' => null
            ];

            try {
                $response = Http::post($callback . '/transaction', $transaction);

                if (!$response->successful()) {
                    $result['msg'] = "HTTP request failed with status code: " . $response->status();
                    return $result;
                }

                $resObj = $response->json();

                if ($resObj['status'] != 1 || !isset($resObj['balance']) || !is_numeric($resObj['balance']) || $resObj['balance'] <= 0 || !isset($resObj['beforeBalance']) || !is_numeric($resObj['beforeBalance']) || $resObj['beforeBalance'] <= 0) {
                    $result['msg'] = $resObj['msg'] ?? "balance is either not set, not numeric, or not greater than 0.";
                    return $result;
                }

                $result['status'] = 1;
                $result['balance'] = intval($resObj['balance']);
                $result['beforeBalance'] = intval($resObj['beforeBalance']);
            } catch (\Exception $ex) {
                $result['msg'] = $ex->getMessage();
            }

            return $result;
        }

    }
}
