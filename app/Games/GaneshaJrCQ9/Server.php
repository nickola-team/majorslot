<?php 
namespace VanguardLTE\Games\GaneshaJrCQ9
{
    class Server
    {
        public $demon = 10;
        public function get($request, $game, $userId) // changed by game developer
        {
            $response = '';
            \DB::beginTransaction();
            if( $userId == null ) 
            {
            	$response = 'unlogged';
                exit( $response );
            }// changed by game developer
            
            $user = \VanguardLTE\User::lockForUpdate()->find($userId);
            $credits = $userId == 1 ? $request->action === 'doInit' ? 5000 : $user->balance : null;
            $slotSettings = new SlotSettings($game, $userId, $credits);
            $find = array("#i", "#b", "#s", "#f", "#l");
            // $paramData = trim(file_get_contents('php://input'));
            $paramData = json_decode(str_replace($find, "", trim(file_get_contents('php://input'))), true);
            $paramData = $paramData['gameData'];
            $originalbet = 5;
            $slotSettings->SetBet();
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 5}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                }else if($paramData['req'] == 2){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $response_packet = [];
                    $result_vals = [];
                    for($i = 0; $i < count($gameDatas); $i++){
                        $gameData = json_decode($gameDatas[$i]);
                        $type = $gameData->Type;
                        $packet_id = $gameData->ID;
                        $emulatorType = 0;
                        $result_val = [];
                        $result_val['Type'] = $type;
                        $result_val['ID'] = $packet_id + 100;
                        $result_val['Version'] = 0;
                        $result_val['ErrorCode'] = 0;
                        $initDenom = 100;
                        if($packet_id == 11){
                            $denomDefine = [];
                            $betButtons = [];
                            for($k = 0; $k < count($slotSettings->Bet); $k++){
                                array_push($denomDefine, $initDenom);
                                array_push($betButtons, $slotSettings->Bet[$k]);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 100000;// $slotSettings->Bet[count($slotSettings->Bet) - 4] * $this->demon;
                            $result_val['MaxLine'] = 10;
                            $result_val['WinLimitLock'] = 1500000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = null;
                            $result_val['ExtendFeatureByGame2'] = [["name" => "FeatureMinBet","value" => "600"]];
                            $result_val['GameFlowBranch'] = 0;
                            $result_val['IsReelPayType'] = true;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null; //$slotSettings->getPromotionData();
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = null;
                            $result_val['UserAccount'] = $user->username;
                            //$result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom * $this->demon;
                            $result_val['Currency'] ="KRW";
                            //$result_val['FreeSpinLeftTimesInfoList'] = null;
                            //$result_val['RecommendList'] = $slotSettings->getRecommendList();
                            $result_val['Tag'] = ["g" => "GB6","s" => "5.16.3.6","l" => "0.0.0.71","si" => "11"];
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 3;
                            $result_val['BGContext'] = [
                                ["padSHAKdXgFIpKHY1hznzMHxGq4RqsZDz735DzJV9Rg96srjM0beQS3zWHwrsCNUvPL23ILqnSTFIT7Y11OsNVpSrdTGv9AhM8Hp9CH08GA+r/amkwSLTZzC76tmSV0/U5PnF8r7Uaqh65I0ouhN4FiRE8HD3ZwnGkhcbEBqfDcQeOYN4lraPiPAlh2woA4PDz7z7jGkc7TBhP8Jkoy50pm13llqx/CBQ+MMng8tKhuyhgzNzxWuUbXEKb0=","CX31RxCSz8kPJXCQlkKN+03iC5Url+iquk12g0zkWOx/istoVBIascukuylEnn1mIwJZ2NzIUhdJsxakJrYmdgQWfLHhiWqNAb5RLklKWqSgEw1fErd/hTgJ0r8nLLUMRHpZoji+Fs1ebSXshHoFKChbq3QmjBHdRwaMImVLcWC4IbbCdAMdFSTgUgzmYopD14ibMwByNEsFmJjfCdteDe66kUrHy+juoOkzY8LhdozTpT0v/4EO9l2/z34=","hHkQ6wuuMpkIJycNpPaE0gEFPwr29AyKAlNto7pTwXZQUBj+emJ7KGktNsd9oadZaB5EpqHUqu9tiQK3MOLyatYHkCGrBtF6+epgyNFlZzdhu5le7hN/4wHWbCUfPH/DQpDAmDx9A8wqp+HCJUVrufnAJVx9SbughE4PcXoPYEhg+mzx2V1bKzv0knUXgJFixH+xoMYkuMu7oQe6iK6tUU/ezm6tMApx4yql0tbhwutjeBKG0jYj9dgQazw=","jjoy2LoyUSvjitKZhyXPZUph09+EjJT7jxmmoGMTCxjll2LIB1IlT1cfW8kO4oWdueOu2ZNHAo1YaDCdsSd3FeCwSHF9WmOK877wPBEc0wPSZk3h0UbCvY7E5XV5yZ7Qdbaan0Z8GJtZgs4hFxRkchk8y2FipyywfL7mXrbTF2UdGBeX1z+yMyKcdsl6SmzI16i7akWV+Qb5C4CMUM4HxhM3DdGcs7G/pL6Cj1TbEGAo8JYMTbZpKHKoIMQ=","TdTbUpvb3RcAtt5VqFiELl1eA/I20YDZjFEcYXMTmlZK7WaKZ7L/0dYQE0WiKk30p8zYTZoycw7jjaiLQVaC6safrMwOWfmskKQHKIWpw4G8w2kLSfzP2Ce5MG8xvQmkDFsjgPhF7THxsUFuUmypqdawghz/Um5K3f4zygmMaos0wqGzdxf8Sur+1IoLp144CPYrQrF3h7+rCQ6TxyKlMntUfcC68WEa06F5IV+l2FdSjc7ITS29xHiPCzw="],["cMCAWcwE2b8RZ53qLHIrjtjpYb9bxG4CVwjwhSIjvYoZAoEXuM4/BSLDRzK1TD4HwPbZiwRKP2hsRehLAvDGIVt+oyZrgNh0PtsPRjPFWKwUIDtPgEPB71Tefy1HxH9RaFJ59FuFnm02lk8zNhjkWuHrj96zRCZcICXlTHSIxySrI0phvOCU80HSxab2lVq/7Yk7Js3iATdvY/sy3tw/3hucpY47VNwurKoFsu0AOoBlv0s90H7vVSOVf4w=","mQWVDayB7zA8VGhKh8NWbGRyG+3Eu5zZ/7ZYxS5jzj5VxwgTIq/bpHIl+KFUbUsiyeh6T37Y/FBggRCSe9abR9mt6r67kAlp3TP2T8qBAOzxDWLXm6Xc1Wb+A28tDZfFnLzJbZnmox3ExYM0iPcNXxlvCNubWuup+iV7h7Uk6MYcwuO9igarsOjA6LrN53+d0FUTBi85nMlvPd/JT8t6R2Adc9cwdhI2h5ogVCcOhR9gKz7sz29OQPs7pzg=","INIRvHGtUvBlq8agHGEJ1r071CIdJMeLluwmgfkL2xxnZkQemzxhT1JGTV1Dt717we3Rt11axBMV66XmuLXx79ZPhDUCV6HetpCrrF7U2EsxbDe61lpeYif7dSvDoJyqqvGEjEvMydOO7jtAjUCCmU1JoVN6yjhx2b34F5Vc6dKBYjlnpcncDF0fA7xE7iMq0mzacsZORJO6jq4NzPKla+gpkkdJznbYuWooOO6SOdoMf9uTgvAVQo5A260=","AbTix1mNVbhjTKetiaE2HhEV3DRsa7S0EuLqkmWbAUSJncpnVR+Jjw1nK308XIlDpXQyKFnCTGAVhILq7mgBxtkg8cmykU5Oivj6qkvQLfPfcCeGeTPv9TSWhT0PjyzOYbF/b49taWXCA2smqjzONLIDN4h65pc3guHese4gZ38sCoLxOY535rGHXvh6ctBZFDH07trXdjmQ4hms9w+meO08kJxa6RpMLOQWpoJ583HU49IsyHHWwQny25A=","VwOm2oLMlIgUzjw9RCxIRbbwbxsLkE9qRvzk3A9zrM0ui0jQsW2Ua5rO5uUZNgImfFdorrl6rNn1mEOU3/6sZhfys4V31VG5k43gR+/CJYMmIrwrbrPasHc+7JQjFTDDDTv5MkwDLY+uEgD0fhzRZtNj2+PEpK6UqdUJ8DmYTsImbDh048QDwa1/XwkuQSiiPzhF86gZ3gKyA13WKNEkAAuBNtrplKk76MXXsXAxEV9TjnQHuXEAoRha6QY="],["dbCH6vCuqpzeFjORgMQgego9CEN2yCKAYBz4h8YcXrybhIU0dCRIGRkkE7EjBJ+EsfMm+wADXqpLongVUvBCvAchiNFj4Th7kB5GicpeEcYbrpbCH/N11m0P57iTOuQQebFxS2EEo3nYDNp7hKU9UgoEOsS75TwWOGfTZfGnJB+bOnSLZ74BKPIfkao9HHlbYGu8NbN6+yDpl8Vjks6lqqlu7tpqGBI4N5AmPoc1KEB5MxSU5K+ZQ5Wsr7g=","tZr1qSHkEplVEjwQ32A6qNyEO5D9O7dzVVjcXljY+axxum16p05DZDDrkFIh5OAxrlZbpSYrnR9KoVmvJp/JoXaUtC+lWZrd5pMjO6b5aF1jYmdLZJVJ8+UIgWRm4DhzRzH0kUgepd7kDZQCa4MLn7OI8qEzoPYIfiks6kPTw7b6rT59Z5T0fNob8wlquQjfZbTiIdvYy2IrwrkmDAzsxP3/Q4BHAw3HqcRNw9UYoZcyTLPKgFMV8lz5MUs=","MSNVqnMG3qhgMU9XlrVPdINYCUr7Jc7OrLJxc2PYyCpvWE4m52hiD//aByWaeUx13hV2DtDdDhfuu2bC/6RZV6xJqiTZ3jXvsSe5Pw9ZfP+sWZ4TuAWf20BKilr/aVO9uMejOYqSWhTB++d1KFnPTHhVnVULpEFzR2klB0TLzHjgDTRJnMkdQG8gmACR8Vvwew3d73w0ydsfY7TJf+PeXP69ZpEfDT40lVjaZoUwlAdUdpQusKOsohbi6s8=","YowY1jeozxU2flvmItXE2/LanM8KMsWQgXp3F+zyV+UZPK6pkUH/zgeqPqvt37lDHQm//rxJ9bM2eEYxkd/WTDMbWJfg296zrblm22RuoZyY08sh3HHiXuNVoK/paVHR3oOeb58J326B5ZIrKN5VnW52uqwjltW6FFN3L1tKXUfQ7R8oK9EHHncnT1T+aE1axaOfPsrw2sr+wZ+6444787E8CkDmOv0EKzYqbdzC+0qmDLeyejw2Qj3xLko=","H91jIpODh4nk8rhH3FTrzjD9OCDv24HotU0/JJFjwBogQthMZWnKa8j2TGoiUkjhp9PO2Ck1eVQlML43/uLFGMWlrFEas+i5huzfOAW6Ya0MBePtQB9DLxvfCzUfKLF2Kav7O3RMMDy49jIA5PfWGtiQLqFq/JVL14fGr63ckBNGwukO3DdNy6qLRgX538m53trF6FjrTR5pG1P8ezy86KRIEoh38MTfl85+t0C5rT3bQ8oguQqA3v5Z1hg="]
                              ];
                            $result_val['FGStripCount'] = 3;
                            $result_val['FGContext'] = [
                                ["Cdf6b0kM2ZX7nUY7JPSdd0dAG+t15mpB6qgHAE8+GZjzmJpx2iCzJSvsK/UIS8UwDwjox6Tw7kseL2nwgKSaTY32Tc3z1M9We0UslT7RjP/B/GKhxDzKTKI33PUZXDvWxrqrrqMVp3mLHVOezy+8HYkQv9JiKQM+cSNgLIHlAQ37Q4bK95UfazjXyZf2hxc8MExgwX3SHs/mrttRbtf/h+z165ZJ4x7J2lYdeFPRBR7X5y5WgVWDH0hplww=","h3xcrlONnFxGdH700Ijk4VnGo4u+sM25M4RaGNvkW+W2VKxRnpZ+7vtnANzlwNMGDk/Dr9JQPfCWnD3/Vz/f52EFdTgPt2GhBZkHg8N+EWcuogqrdp72ZHv/G7LcRQDHW9mMbmAp+aPOzH+RdK5mqt37WfnLpeClWkIzzpIId6z1408phjkMrzqxt/7q1MU4u+7w0BLuHAtH32TV/RlIH1dPu/9lfudqLZr10g67FLVCfXnOYm8b/SXxH3M=","6MVpVTZttyNmzpDjhrgR4MMpeYF2fi801Y7ZKFwj0pefkXNVObezugMpiyX5HlfR2JCF96alhJjtBK7eWmVU7TxtK6bXLDkE1iUrzvA/sDRkksA7heSVH1qicVPFOB+3ySgHfKGnVwK/mkWekhtVjNknMcQZJdTbPhh83/sGF/UjZB76qaRptjzGNl3Zc4GnhLhxiQadLfCcdgqVlAhxeqW7eV8uV8aFi4383rM3zjlrrr1Sq2GhW9HcyxM=","Wfa7BYonh8qWrPq6pMtw+lJcmExqUB2YGp7OeHPrlzMvXqqoftQzDYCbs78aDRNlZzT/Xv+jSbYLBd9Ss1fSRiKl+Cs7W3Dsxx9pL70/27LMUQieBm5/Qm5PfEk7ErFsW0EqrGoFcrVzyJtYjIG9do+c9cNEr+Fcc19lJZ7Wi+2DJblWHXpF3gM+fpvxb+QN3JaytThJAPP20s+0dWlIBH/Xwf2DVjCtlgJ5xWUEnr/ZfEpro0Y6Bd21HU8=","rg6RfKF6jlYDxRJYASnBrLyt7V3nSM3fhyDi/23BWXELZ9MKFhRDYhTIqA0BBO0yxx5kdfUVgwZBV33oxOiJhxqZbNvS35QZzb026MZra63ZfajYupYC66uIlm/ga+K4JA9vW9rdEV23U01AGTou4P+X/0s7H6UuUGc0zaoDFaprSOfQULENVC4ax1vdTYz3eQ9sR0f4B1PKOVB+rG4N3t7ogRjYJNp9+fggxHC89E0A5y/u3FlD2MFt+mo="],["VrOj54nOrcl4VUbZMMKXmcY9bYZxPy3pAZ1vLvrixVl0bKuCsM9gXDkz6WXji1pVhX7Ok2mGPj6JdC7W9lyx04G8SXr6KHCs60CmmTC6l44YGMNUYHK6o2iBcksGGKi4F1Yazsmi+rHL4zSLuRSCcP3KNgdYG/OLANCYu1qxnzdiuz7bsH4NQgHueSsKMgIb4G8SSOWw832A1ESj+4wUFyetxF3K1B2z469uct87aqSzCOKsl6vsnrT2KI0=","ueIaMFz7X0l1AatGmFGrmVXcD0/rlqn3IRGlIzMw0Eo/qDh7ZlkubguPdlD8mU+0Fu8bf7ywmLrhgmyMHyRIAUHtJZIj7hWY65ovc3a0Ss6ng0GStdnlQCigB5fzQ27mm6xovo2K7GW5L+JO4cL/B1zc6BhRv3Rp4WpAZDBgnwpowzpSdInnNKx5WnPhujZ7/cZbo4OkA74foKi5Nlf6O8ut+euYd6oOyb0mtnJL/Q9pb5HjYodx/zKC9W8=","yRvQyQRODeWsAF97BJvqYm54FyEAXQ6I33OOOae5eMKPnYZnaK+Ip0VU9UPipkJ3hnTrA+EIkfb5/hVTpqWbassE9QZ1+EKPFV6fy0I8tNwLKPslufcbfP3UOLSU2fV9+sNAO+NKbm734qara0Gh1yIDcYkabqkNTRP+h0VluXRa15A9e3R2by8AxD7E8NxBgJ7+jQgGrVNnTX3tdsbH74gEFtsxBNcDOCzn59eEVjz3BfYUJtKpxjYf9VY=","zusUKZoLOGTjMOoiHbpcD5oPUaW+j/PAYZhyI5tRkbx9eqZ8m/soAxc2l9Cm88RXGa4OzbpRW1n83Q/l7PtuxqSR7aL+oGnLXQV+n5DmsCsGOGnDnsg31ITEnG3HT/AxY8Uu1bvAEzcSKpz+1yPRTOZIujwh3HykEC21IOvr29cDqsRIcPV2NX6brMGvgPMWAZOLsNvsAW10YZhOpCaECA5g5buX29wRuiKxyCVew0RYdNaoiVNieitrtvg=","0psvsmyVPnhz1tXjW6/ttTZ7MKn932nyWAa2VkjFFgtgRFRnWpcZoODsbCeReqheU/sKbgU/HrqgHMEdGxsKmRW1ZIHVcud26gJb3FFSQMjJif+Sh/V9N2LV8ZuPlgaPhmuKsJXLq2forMR36+q17Ca1slpPB77E0pR+ItXUUw6i7ZicB8LZ9+eCpwHECaA37x95RSD7n9ePsZLuNkt2LREJ8oCXjcHPQbrXcR6piJdfbWwIAA6QoQkUauU="],["APfWNLkgroUqbmQK3eNeVEF7RIsFz8Tcpq0hvWaxqNOmnQbjVhY1PhMI4K0EL6UobBY9ZI3RwDgCxarnn/kiMJ7j/Vl4kU8ihmA/XWu/AyglC6bsYqNeL4pRDcBJM0SkN6FxAXpCWXxj72tGwrKRqeS2JwdR63RiPo3u0V8MCxHWH0dsMRzid+JMcg33ay92l38JR4gWfZ7dBhr//f2SKUh+hP+7xpSURkO/R4njPjJcBjJ4ApkscUd2Zyc=","frZahXhNr4YtMhaTqyot369lYId+5m6BuDYhlLRpGh/Z9YU+59D5sff4OBcuy/7piecFYJF90LsWXeaekna31MdgMhprrEVYXKEXvBE1ph4WRlsoj5bbjuRwQLYS9dIzGKv7duUolQv5d8imlFd/dSa9DxVHDEqY47EjxH/dfKeXowmLZ2Em6Az4xtTK/oZjesG3t0SEY7z4pOGCN0fNCCfz70Qx3DwzVa/wKrEJd7HS5IJFzTALqqvGNMk=","rn20AajxeTQiCw7o9VRocS8zga1y3opD8nO1TlXkC4dI0tD3QmNvAQeQmZCsO9QxpwxbNwpcsgX49mX0U8wPoY2mV3fKqD/ndjSHS7qtrJlhoFs5XpkHVGLDuly9M8YEfTztYlhXSEnbHGjr75eVFdatybH0EC8bRtE6m2qd0rt6SL2axAhiRfkpaXoVMso/y0xTyxo0+3IqScX4bW1iaq81/Tzkpe2VhM+mVs9QepzVXXibZOcZLTTWU8k=","nkUiUzXc3h3jWxsiPsPCDH+BT1NMrpXFGPArmDPiMWJ9eX7kwdgacZs5Sg2ctc7GMkKLW2rftJHlNFsTjyKGk6iWIUCF1yQVRTfI+EQy+l50IznBDxNTUyvGB26BLruSR035WwFIn7D6Ls+bkUmMVfh2DCyXzhSVvJpD2AbrneWmDzZ3VljPd2aRN7vCBDnal2J3BAju3Ib1YNZozUc9MAUYZfkCMoCEkWGvH0B6Nn9EzYFeOuneghLa8rI=","IrPmJV5UPJZb5BuOx8/GW8lYkm1Iw++Q7xKhKWhBS8D4Kp7Y9Rh6oNjP6DuVZQsXlHSb3StfOLiUjrD0tJ2NmKGWzb0qCAWJGLIIRdU5+sXHCjz0thNAGhuuaxFtXgtnwOyfJJkuWU3rXx2walZQXP1CxT9fWXbV+miC6HgaBpE5Xw6cPaKb2jf+6TIN6xrBUMXseh9s1YuxM35BfHPqxqL5bAYab0awncGTGTQL3+z5vkMiRtnwFC6RKxQ="]
                            ];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 10;
                            if($packet_id == 31){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else{
                                $slotEvent['slotEvent'] = 'bet';
                                $pur_level = -1;
                                if($gameData->ReelPay > 0){
                                    $pur_level = 0;
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BuyFreeSpin', $pur_level);
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                $slotSettings->SetGameData($slotSettings->slotId . 'Lines', 10);
                                $slotSettings->SetBet();
                                $allBet = ($betline /  $this->demon) * $lines;
                                $isBuyFreespin = false;
                                if($pur_level == 0){
                                    $allBet = $allBet * 60;
                                    $isBuyFreespin = true;
                                }
                                $slotSettings->SetBalance(-1 * $allBet, $slotEvent['slotEvent']);
                                $_sum = $allBet / 100 * $slotSettings->GetPercent();
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent'], $isBuyFreespin);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '134' . substr($roundstr, 3, 7);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                            $result_val['EmulatorType'] = $emulatorType;

                            $slotSettings->SaveGameData();
                        }else if($packet_id == 32 || $packet_id == 41){
                            $result_val['ErrorCode'] = 0;
                            // if($type == 3){

                            //     $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'ScatterWin'));
                            // }
                            if($packet_id == 41){
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance() - $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'));
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                                $result_val['PlayerBet'] = $betline;
                                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                $result_val['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                                $result_val['MaxRound'] = $stack['MaxRound'];
                                $result_val['AwardRound'] = $stack['AwardRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                $result_val['MaxSpin'] = $stack['MaxSpin'];
                                $result_val['AwardSpinTimes'] = $stack['AwardSpinTimes'];
                                $result_val['Multiple'] = $stack['Multiple'];
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                            }
                        }else if($packet_id == 43){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['NextModule'] = 0;
                            $result_val['GameExtraData'] = "";
                        }
                        array_push($result_vals, count($result_vals) + 1);
                        array_push($result_vals, json_encode($result_val));
                    }
                    $val_str = '';
                    $response_packet["err"] = 200;
                    $response_packet["res"] = 2;
                    $response_packet["vals"] = $result_vals;
                    $response_packet["msg"] = null;
                    // $response = $this->encryptMessage('{"err":200,"res":2,"vals":['. $val_str .'],"msg":null}');
                    $response = $this->encryptMessage(json_encode($response_packet));
                    if(($packet_id == 32 || $packet_id == 31) && $type == 3){
                        $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentBalance').'],"evt": 1}');
                    }
                }else if($paramData['req'] == 202){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $game_id = json_decode($gameDatas[0])->GameID;
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1, "'. $slotSettings->GetNewGameLink($game_id) .'"],"msg": null}');
                }else if($paramData['req'] == 1000){  // socket closed
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                        $lines = 10;
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 142;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet);
                        }
                    }
                }
            }else if(isset($paramData['irq']) && $paramData['irq'] == 1){
                $response = $this->encryptMessage('{"err":0,"irs":1,"vals":[1,-2147483648,2,988435344],"msg":null}');
                $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetGameData($slotSettings->slotId . 'CurrentBalance').'],"evt": 1}');
            }
            $slotSettings->SaveGameData();
            \DB::commit();
            return $response;
        }

        public function parseMessage($vals){
            $result = [];
            $length = count($vals);
            for($i = 0; $i < floor($length / 2); $i++){
                $result[$i] = $vals[$i * 2 + 1];
            }
            return $result;
        }
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines, $originalbet){
            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
            }else{
                $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, ($betline /  $this->demon) * $lines, $lines);
                $winType = $_spinSettings[0];
                if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') >= 0){
                    $winType = 'bonus';
                }
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin'));
                if($tumbAndFreeStacks == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                $stack = $tumbAndFreeStacks[0];
            }
            $isState = true;
            $isTriggerFG =false;
            if(isset($stack['GamePlaySerialNumber'])){
                $stack['GamePlaySerialNumber'] = $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber');
            }
            if(isset($stack['BaseWin']) && $stack['BaseWin'] > 0){
                $stack['BaseWin'] = $stack['BaseWin'] / $originalbet * $betline;
            }
            $totalWin = 0;
            if(isset($stack['TotalWin']) && $stack['TotalWin'] > 0){
                //$stack['TotalWin'] = floor(($stack['TotalWin'] / $originalbet * $betline) / $this->demon + 0.05);
                $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline);
                /*if($slotEvent == 'freespin'){
                    
                    
                }else{
                    $stack['TotalWin'] = ($stack['TotalWin'] / $originalbet * $betline);
                }*/
                $totalWin = $stack['TotalWin'] / $this->demon;
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                $stack['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = $stack['AccumlateJPAmt'] / $originalbet * $betline;
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
            }
            if(isset($stack['SpecialAward']) && $stack['SpecialAward'] > 0){
                $stack['SpecialAward'] = $stack['SpecialAward'] / $originalbet * $betline;
            }
            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                $awardSpinTimes = $stack['AwardSpinTimes'];    
                $currentSpinTimes = $stack['CurrentSpinTimes'];    
            }
            foreach($stack['udsOutputWinLine'] as $index => $value){
                if($value['LinePrize'] > 0){
                    $value['LinePrize'] = $value['LinePrize'] / $originalbet * $betline;
                }
                $stack['udsOutputWinLine'][$index] = $value;
            }
            if($slotEvent != 'freespin' && isset($stack['IsTriggerFG'])){
                $isTriggerFG = $stack['IsTriggerFG'];
            }
            $freespinNum = 0;
            if(isset($stack['FreeSpin']) && count($stack['FreeSpin']) > 0){
                $freespinNum = $stack['FreeSpin'][0];
            }
            $stack['Type'] = $result_val['Type'];
            $stack['ID'] = $result_val['ID'];
            $stack['Version'] = $result_val['Version'];
            $stack['ErrorCode'] = $result_val['ErrorCode'];
            $result_val = $stack;
            
            if($totalWin > 0){
                $slotSettings->SetBalance($totalWin);
                $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin);
            }

            if($freespinNum > 0){
                $isTriggerFG = true;
                if($slotEvent != 'freespin'){                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                }else{
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') + $freespinNum);
                }
                $isState = false;
            }
            if($slotEvent == 'freespin'){                
                $isState = false;
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes && $stack['AwardRound'] == $stack['CurrentRound']){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            

            $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
            if($isState == true){
                $allBet = ($betline /  $this->demon) * $lines;
                if($slotEvent == 'freespin' && $slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                    $allBet = $allBet * 60;
                }
                $slotSettings->SaveLogReport(json_encode($gamelog), $allBet, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, 'GB' . $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
            }

            if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $allBet = ($betline /  $this->demon) * $lines;
            if($slotSettings->GetGameData($slotSettings->slotId . 'BuyFreeSpin') == 0){
                $allBet = $allBet * 60;
            }
            $proof = [];
            $proof['win_line_data']             = [];
            $proof['symbol_data']               = $result_val['SymbolResult'];
            $proof['symbol_data_after']         = [];
            $proof['extra_data']                = $result_val['ExtraData'];
            $proof['reel_pos_chg']              = $result_val['ReellPosChg'];
            $proof['reel_len_change']           = $result_val['ReelLenChange'];
            if(isset($result_val['ReelPay'])){
                $proof['reel_pay']                  = $result_val['ReelPay'];
            }
            
            $proof['respin_reels']              = $result_val['RespinReels'];
            $proof['bonus_type']                = $result_val['BonusType'];
            $proof['special_award']             = $result_val['SpecialAward'];
            $proof['special_symbol']            = $result_val['SpecialSymbol'];
            $proof['is_respin']                 = $result_val['IsRespin'];
            $proof['fg_times']                  = $result_val['FreeSpin'];
            if(isset($result_val['CurrentRound'])){
                $proof['fg_rounds']                 = $result_val['CurrentRound'];
            }else{
                $proof['fg_rounds']                 = 0;
            }
            $proof['next_s_table']              = $result_val['NextSTable'];
            $proof['extend_feature_by_game']    = [];
            $proof['extend_feature_by_game2']   = [];
            foreach($result_val['ExtendFeatureByGame2'] as $item){
                $newItem = [];
                $newItem['name'] = $item['Name'];
                if(isset($item['Value'])){
                    $newItem['value'] = $item['Value'];
                }
                $proof['extend_feature_by_game2'][] = $newItem;
            }

            foreach( $result_val['udsOutputWinLine'] as $index => $outWinLine) 
            {
                $lineData = [];
                $lineData['line_extra_data']    = $outWinLine['LineExtraData'];
                $lineData['line_multiplier']    = $outWinLine['LineMultiplier'];
                $lineData['line_prize']         = $outWinLine['LinePrize'] /  $this->demon;
                $lineData['line_type']          = $outWinLine['LineType'];
                $lineData['symbol_id']          = $outWinLine['SymbolId'];
                $lineData['symbol_count']       = $outWinLine['SymbolCount'];
                $lineData['num_of_kind']        = $outWinLine['NumOfKind'];
                $lineData['win_line_no']        = $outWinLine['WinLineNo'];
                $lineData['win_position']       = $outWinLine['WinPosition'];
                array_push($proof['win_line_data'], $lineData);
            }
            if($slotEvent == 'freespin'){
                $log = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');

                $proof['lock_position']         = $result_val['LockPos'];

                $sub_log = [];
                $sub_log['sub_no']              = $result_val['CurrentSpinTimes'];
                $sub_log['game_type']           = 50;
                $sub_log['rng']                 = $result_val['RngData'];
                $sub_log['multiple']            = $result_val['Multiple'];
                $sub_log['win']                 = $result_val['TotalWin'] / $this->demon;
                $sub_log['win_line_count']      = $result_val['WinLineCount'];
                $sub_log['win_type']            = $result_val['WinType'];
                $sub_log['proof']               = $proof;
                array_push($log['detail']['wager']['sub'], $sub_log);
            }else{
                $log = [];
                $log['account']                 = $slotSettings->playerId;
                $log['parentacc']               = 'major_prod';
                $log['actionlist']              = [];
                $log['detail']                  = [];
                $bet_action = [];
                $bet_action['action']           = 'bet';
                $bet_action['amount']           = $allBet;
                $bet_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $bet_action);
                $win_action = [];
                $win_action['action']           = 'win';
                $win_action['amount']           = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                $wager['seq_no']                = 'GB' . $result_val['GamePlaySerialNumber'];
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = 'GB6';
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $allBet;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                $wager['rng']                   = $result_val['RngData'];
                $wager['multiple']              = $result_val['Multiple'];
                $wager['base_game_win']         = $result_val['TotalWin'] /  $this->demon;
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                $wager['win_type']              = $result_val['WinType'];
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $wager['win_line_count']        = $result_val['WinLineCount'];
                $wager['bet_tid']               =  'pro-bet-' . 'GB' . $result_val['GamePlaySerialNumber'];
                $wager['win_tid']               =  'pro-win-' . 'GB' . $result_val['GamePlaySerialNumber'];
                $wager['proof']                 = $proof;
                $wager['sub']                   = [];
                $wager['pick']                  = [];
                
                $log['detail']['wager']         = $wager;
            }
            $slotSettings->SetGameData($slotSettings->slotId . 'GameLog', $log);
            return $log;
        }
        public function getCurrentTime(){
            $date = new \DateTime(date('Y-m-d H:i:s'));
            $timezoneName = timezone_name_from_abbr("", -4*3600, false);
            $date->setTimezone(new \DateTimeZone($timezoneName));
            $time= $date->format(DATE_RFC3339_EXTENDED);
            return $time;
        }
        public function encryptMessage($param){
            $param = "~j~" . $param;
            return "~m~" . strlen($param) . "~m~" . $param;
        }
    }

}
