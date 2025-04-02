<?php

namespace VanguardLTE\Http\Controllers\Web\GameProviders;

use VanguardLTE\StatGame;
use VanguardLTE\User;
use Log;

class Evolution extends \VanguardLTE\Http\Controllers\Controller
{

    public static function history(\Illuminate\Http\Request $request)
    {
        $transactionId = $request->transaction_id;
        $stat = StatGame::where(["transactionid" => $transactionId, "bet_type" => "win"])->first();

        if (!$stat) {
            return view('frontend.Default.games.evol.empty');
        }

        $user = User::where(["id" => $stat->user_id])->first();

        $username = "Unknown";
        if ($user) {
            $username = $user->username;
        }

        $parts = explode("#P#", $username);
        if (isset($parts[1])) {
            $username = $parts[1];
        }

        $detailData = json_decode($stat->detail);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $detailData = null;
        }

        if (!$detailData) {
            return view('frontend.Default.games.evol.empty');
        }

        $data = json_encode([
            'data' => $detailData,
            'username' => $username
        ]);

        return view('frontend.Default.games.evol.history', compact('data'));
    }

}
