[1mdiff --cc app/Http/Controllers/Web/GameProviders/NEXUSController.php[m
[1mindex 27cc4b03c,990499f94..000000000[m
[1m--- a/app/Http/Controllers/Web/GameProviders/NEXUSController.php[m
[1m+++ b/app/Http/Controllers/Web/GameProviders/NEXUSController.php[m
[36m@@@ -1278,11 -1313,13 +1316,17 @@@[m [mnamespace VanguardLTE\Http\Controllers\[m
                  Log::error('Nexus CallBack Balance : No params. PARAMS= ' . json_encode($data));[m
                  return response()->json([[m
                      "code" => 1,               // 0: 정상, -1: 오류 메시지 확인[m
[31m-                     "msg" => 'No params'  [m
[32m+                     "msg" => 'No params',[m
                  ]);[m
              }[m
[32m++<<<<<<< HEAD[m
[32m +            $userid = intval(preg_replace('/'. self::NEXUS_PROVIDER .'(\d+)/', '$1', $data['params']['siteUsername'])) ;[m
[32m++=======[m
[32m+             $userid = intval(preg_replace('/'. self::NEXUS_BLUEPREFIX .'(\d+)/', '$1', $data['params']['siteUsername'])) ;[m
[32m+ [m
[32m++>>>>>>> bdd382a6f73b51cd29493bcfa6022832ced49a2e[m
              $user = \VanguardLTE\User::where(['id'=> $userid, 'role_id' => 1])->first();[m
[32m+ [m
              if (!$user)[m
              {[m
                  Log::error('Nexus CallBack Balance : Not found user. PARAMS= ' . json_encode($data));[m
