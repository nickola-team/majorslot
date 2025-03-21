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

                CallbackController::callbackLog($callback, ['username' => $username], $resObj);

                if ($resObj['status'] != 1 || !isset($resObj['balance']) || !is_numeric($resObj['balance']) || $resObj['balance'] <= 0) {
                    $result['msg'] = $resObj['msg'] ?? "balance is either not set, not numeric, or not greater than 0.";
                    return $result;
                }

                $result['status'] = 1;
                $result['balance'] = intval($resObj['balance']);
            } catch (\Exception $ex) {
                $result['msg'] = $ex->getMessage();

                CallbackController::callbackLog($callback, ['username' => $username], ['msg' => $ex->getMessage()]);
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

                CallbackController::callbackLog($callback, $transaction, $resObj);

                if ($resObj['status'] != 1 || !isset($resObj['balance']) || !is_numeric($resObj['balance']) || $resObj['balance'] <= 0 || !isset($resObj['beforeBalance']) || !is_numeric($resObj['beforeBalance']) || $resObj['beforeBalance'] <= 0) {
                    $result['msg'] = $resObj['msg'] ?? "balance is either not set, not numeric, or not greater than 0.";
                    return $result;
                }

                $result['status'] = 1;
                $result['balance'] = intval($resObj['balance']);
                $result['beforeBalance'] = intval($resObj['beforeBalance']);
            } catch (\Exception $ex) {
                $result['msg'] = $ex->getMessage();

                CallbackController::callbackLog($callback, $transaction, ['msg' => $ex->getMessage()]);
            }

            return $result;
        }

        public static function callbackLog($callback, $request, $response)
        {
            $strlog = '########## Callback Log (' . $callback . ') ##########' . PHP_EOL
                . 'Request => ' . json_encode($request, JSON_PRETTY_PRINT) . PHP_EOL
                . 'Response => ' . json_encode($response, JSON_PRETTY_PRINT) . PHP_EOL
                . 'Timestamp => ' . date('Y-m-d H:i:s') . PHP_EOL . PHP_EOL;

            $logDir = storage_path('logs/');
            $fileName = 'callback.log';
            $filePath = $logDir . $fileName;

            // 디렉토리가 존재하지 않으면 생성
            if (!file_exists($logDir)) {
                mkdir($logDir, 0777, true);
            }

            // 파일이 없으면 새 파일 생성
            if (!file_exists($filePath)) {
                file_put_contents($filePath, '');
            }

            // 로그 파일에 내용을 추가
            file_put_contents($filePath, $strlog, FILE_APPEND | LOCK_EX);

            // 파일 크기 제한 (예: 32MB 초과 시 백업 후 새로운 로그 파일 생성)
            $maxFileSize = 32 * 1024 * 1024; // 5MB
            if (filesize($filePath) > $maxFileSize) {
                rename($filePath, $logDir . 'callback_' . time() . '.log');
                file_put_contents($filePath, ''); // 새 로그 파일 생성
            }
        }
    }
}
