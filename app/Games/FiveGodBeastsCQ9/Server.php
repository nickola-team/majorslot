<?php 
namespace VanguardLTE\Games\FiveGodBeastsCQ9
{

    use Dotenv\Loader\Value;

    class Server
    {
        public $demon = 1;
        public $winLines = [];
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
            $originalbet = 1;
            $slotSettings->SetBet();  
            if(isset($paramData['req'])){
                if($paramData['req'] == 1){ // init
                    $response = $this->encryptMessage('{"err":200,"res":'.$paramData['req'].',"vals":[1,{"E": "'.$paramData['vals'][3].'","V": 7}],"msg": null}');
                    $response = $response . '------' . $this->encryptMessage('{"vals":[1,'.$slotSettings->GetBalance().'],"evt": 1}');                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                    
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());

                    $slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount',0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex', -1);
                    $slotSettings->SetGameData($slotSettings->slotId . 'ScatterGameValue', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 0);
                    $slotSettings->SetGameData($slotSettings->slotId . 'LastLevelSpinCount',0);
                }else if($paramData['req'] == 2){
                    $gameDatas = $this->parseMessage($paramData['vals']);
                    $response_packet = [];
                    $result_vals = [];
                    for($i = 0; $i < count($gameDatas); $i++){
                        $gameData = json_decode($gameDatas[$i]);
                        $type = $gameData->Type;
                        $packet_id = $gameData->ID;
                        $slotSettings->SetGameData($slotSettings->slotId . 'PackID', $packet_id);
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
                                array_push($betButtons, $slotSettings->Bet[$k] + 0);
                            }
                            $result_val['DenomDefine'] = $denomDefine;
                            $result_val['BetButton'] = $betButtons;
                            $result_val['DefaultDenomIdx'] = 3;
                            $result_val['MaxBet'] = 2000;
                            $result_val['MaxLine'] = 1;
                            $result_val['WinLimitLock'] = 300000000;
                            $result_val['DollarSignId'] = 4;
                            $result_val['EmulatorType'] = $emulatorType;
                            $result_val['GameExtraDataCount'] = 0;
                            $result_val['ExtraData'] = null;
                            $result_val['ExtendFeatureByGame'] = [["name"=>"AccumulateUpCount","value"=>0],["name"=>"LevelUpCount","value"=>3],["name"=>"CurrentLevel","value"=>1],["name"=>"NextLevel","value"=>2]];
                            $result_val['ExtendFeatureByGame2'] = null;
                            $result_val['IsReelPayType'] = false;
                            $result_val['Cobrand'] = null;
                            $result_val['PlayerOrderURL'] = config('app.cq9history') . '/platform/?gametoken=' . auth()->user()->api_token;
                            $result_val['PromotionData'] = null;
                            $result_val['IsShowFreehand'] = false;
                            $result_val['IsAllowFreehand'] = false;
                            $result_val['FeedbackURL'] = '/feedback/?token=' . auth()->user()->api_token;
                            $result_val['UserAccount'] = $user->username;
                            $result_val['FreeTicketList'] = null;
                            $result_val['DenomMultiple'] = $initDenom;
                            $result_val['RecommendList'] = $slotSettings->getRecommendList();
                        }else if($packet_id == 12){
                            $result_val['BGStripStartID'] = 0;
                            $result_val['FGStripStartID'] = 50;
                            $result_val['BGStripCount'] = 5;
                            // $result_val['BGContext'] = [[implode(',', $slotSettings->reelStrip1) , implode(',', $slotSettings->reelStrip2), implode(',', $slotSettings->reelStrip3), implode(',', $slotSettings->reelStrip4), implode(',', $slotSettings->reelStrip5)]];
                            $result_val['BGContext'] = [["Cqwc3kmGUyF0lPKX/U4WAXYTW+zfjpcQ7XoXdeVywLTYdvt7uG2aHa4/icg1fFq0xYtxqNC+7oHETje34PLhC9v5t6GHTl5a4oikDOY7OE4G/RAbayb9cT4E6u1BTxVYQ3RZiZcbl1i0mXcSFxcB5zp0h9Ol+kz1D5VNjwFggFlcrcI/U7suM+0EJiXisXIxzVW6651KzKnY6xNaH+/gKqdcgDOv26wxV387xcrCrWoNmUKCda6o0CShrX6s3ng4uAFAklzWEGnUMIqPcjyKOPZ7+tqbiqzX4YHbIungvKeeM1QoE0IrUGIDHlEIenVairyDWTz4rHTVY7A/fkeFpowOTG618vDIZYefD15ctvLI11KXU8vrts1may4=","nEmwXq7C9qp8he8PJrTzBMP0YxP2xCzO6+5I+hIwvqGDZOgelFxlDstuchn93cz2gJ+EN+FAKvywdfxF3VyJh2H2ShH6yr7QkHgSdchYLsfENy/Yjw3vkptwA01fEP5EEkN1FbUAw+uttD8XQW+JjaQmakxXDOl6mn/eV9a/AJkc50kuumRBOBbjQIkZ5NhsQp5PAChAsx67oNajSYwJ1ceifg55ZbbixSB8L3FcZCDLaeB3YbTwpvaPy0XSFM4Rw8L/1lQOSGJWkXwA82/aYjCaxxnbXnLV8GwIC6mxzQvFrXE27xn9BAYXFzoQt2ATB7PrQCN+7QM1fbgn8veLOVlcZXPxS5m19yARPqAZgXp6a0Lxm4AB8fWgM6Y=","E3Ybn7bPE2xbBiBVuyowvi8PXmo41aAJXAZgl7PS3MfFoOv5zMcdnFUYVH8eBkZLWzKXb26bzizvb6wEnDSBx8cqxSEazVqqMaQ5wkv/6YW564Bz3SKJjQc89vXqubEKcT7XoPZxE0otEO7+FRil/kvkcrNG7Mb6gG4j2CarlIFC6FWoHW+5TC4vdDivG0y4DHUbil5f9+Lerc07O9jZ+QjJzKHjN3VwQXtDCu+8vD6qP1EuS+cHMPzSrU2O1eSP3ivK1ohU0hJAPdqaW85ZA3qViSBWlR9mqf45J1bgB8QLdQ52UOd/WHFoBD3JmEBEm95vD04Yby7dEvlPgcb4KXY6N2cQTUkDmqYqzKxLdYrs24gI8g8PkqXDqZ/kGgDnEMRk27emccRT4urcWVVdYF4ifHih8oGuJKFsK3F5YiNlyxca3lbH3VHgA6DT6XWJarWsHoVLWQVLvZaAudykW42hE3dd5Flo/4z6uz/0btkS1X2xD3eKHPZQR6I=","ucvcHQqgJ5YjV13nC5L/zdJOPN+ffyn01YbQN33m1G1XwxjxYV4/l29OsMqUNuAGVcjSdptL7j233iVNpY9gjA0oi4kqUbpmsMowSHmxuWiEeamtEhxpYqeRhKsrrFhgqfLZdufeZE2DbdIifRy6BlUn6nTaQOuHXWtbuMoFrcATk8g+ShL25FrXHVdid+Nk18gyD06pkk8swf3dPFLPRdKgspVhJTMNfpESCSxOgc0J1Oo2spRUxggPA6H4rYtolfG9vkxlMYoDdS+anXhXzhoIJcjkWU/0Wt4344oLghXlZUVpU2aJkH12vugt51IuZrZ0CxWsQvmVn3WZZZIscJkrdOJMjDwyG9ksOm9S6oDgrSLezM40Dwzhit71UQDmyQpCIvAaL31oh1NpVNvSOEubJtqhB090r5KJf/v+l+vzXgAl01VNBzgyPCfCu0HFxa8uvENfGsVJV6NHVvtSI1kArmrIgq3Fsw+zWQt7465W8x5Tx1ohTN/UNwbBlyjImcDFXvt2gci8JfND","OiI9lFfRzWUWlozUNt7CVCZ4Rc1NlGlIkyxJ86FYEVhmC/ESG3BrRO+Sl9PtogFQ82J+/w9JiaOOZDNBHXR9r/fEyGHn0ILG3thV2PovEKhTyPL2ecOTiurGkD8rdqjmI6mHhRzj+YZUYObaz0QpORWmL1y5FzYpicsyDIe++Nazy3YHxNrQUVNqNf3KOr5CoEU1XdIOMgQXGp46J7g+xfgHm6g4F3Ry8FGe4mFUrSYpILavBC8ehD36JWIzEqut+Edn7hGJgfK+XqogA+3OqMQz7X3LCxyjqErEY+0PhXWdBis18Yofff7u/g1pQbJHcb++8mIlN/91sEuwVhSGpsOsjNLI6toWq9pxZrHAzTWBM73uZIdYQGhS4Nz1yRh7cGE/0GCnDVpI/qhTqiJkCI389XhS64qD3RoAiuCaqkhV/F4hvusDf+z4tuzNdT75HClGVf4O+7ZK2ki/pSORIhRTHyGt9gXvPsBEJ0e4OWDRGFo1FjD+jgoInbfbaQCiKpCaiEDE/DhsxPLN"],["05POg3wbKNqC7S5HqHrBUpbHXbKxibpce/pmFLFTzqAQd7EEEH7a/EISZYqaFgSUOOu5mxqs/B/QBz5lR7Lts5baU/34KIgyU6VdTsNx7DAnTAvp1NPfractN6e8yL4y1QlhHIiMFg5IcXPrdpr/aEn//KoZu+r1EsWEyJH6xUiYPJcOjjOvbFpKCbuNudx8KTDPIEpqH7UZiUdnn6Q8I7ROi1ixBEI4vf4I+kFSfH82haO3iumHWzuIZlITDg626EdS7ZD/Xy0NVPE78lDDA/LaDqddbGl5NShzXSE4msIcbhwPuq5n/7x0ro4=","ft7DlbOI0mde08QQk4Ej5Bnk44Z45yZSnzYOrKoC8+bcYFeo2G3O8AqckvFt+p2ZZme1oN82IIKk91Osnb3xUxE7IkscxXes6dEdpLE47IsotWUaH3Hro4sNCEHmD/yaMxiFMwo3MkezqWIDS7/eb98AlW65VRBSOFzRHs8/uDXcdfI1KwbTInWpW0IKefECGl8TS0RJ9yeuMsVrerex6THZ/TSdY+BxMj2u3lfrVAWm8fmvyLz842v3qnJEZ9NiXMTNzKYmFh33CG9Ca4yq/7TmLQ4hY4LVfdLUsQ==","0IYlk5k5eFJrlKdIxhz8oHuzD7w+ykewFn8IMAqRQ2zezqHLxlEa38KFeupWU2d29tus11DqXsKl9PbO78v5VtKEXpgoIDxC2aqjbhwVts2xCY9ahOhSvgJ0E9fSP67Xm/dPIcuAi6cu6KZ/AnOmIYd4cuwq5zLDCdI32mwfC4G2eON6/iY017u4hZVRciE4UNyLbRT9Zo9GP4/VBWur0mq7xnngWta4ZgAklvphFRQyqmEQQV7ovU/acDiXhiWMZBAu9iBUC3K656AUHEW8QA4iZyTHFBCvxLe7XzU280vsbSs0tnyhhHIusJoAEYlo102Og+HcvLJSpWGsW+6N8iFq5PXrccqSoFfeANt6NYMr/hxGOt7gsvpSqDco+XvcIfROVSxQE/aIMxcZRaX6Ze6HwvI/e6rbbVX0eTOJerWaEdt041R09C3iedMAiDwu/dLy1q/9WWuMGW+7cItYM5y8nq95IoVsODGH7Q==","CKh6TvCWPJSimeHREg18bLKEvPtniF9PanD6gUgH1cm6Eiuah8CiW7CnSvlahfcvhch2DUx1hn/FkZiCScL8pFJ82ZLYObWk1x5LD3hyP0vUKwf7c1M0BgQXzIy60Mk1LRzw/6iErbQVFuYXfRkdS4Trb2RFibwUBvU1J5Zm7Jfm/naLrOtefruYyknHV73f86ogMA2ZCyQrNEAQuinDTTV9+FX7mqUt8QIJfPhuzmws3s2r2LTut3geYOJ6Ev0XDVf8tK3loQLsbxULI9SbgWCmy3rUsY/+G7+0H6ZRIq0rdAFbRp8hi6qUjZcwLRoFRVlS/EMEs+xT4tfq","tOSWNF2xDAxp0N8qHEaQ1YE4xJLWp18nfr1a0ZlqFuLjjRnGgXQBVtSZNbsOSxclnaYxqG/cyL+qbj681b0cBCvf7gPUnFcaE6ZOV+oPFrCpROGVx9RYZxgvIpcO1XQ2lxOxJGXOM5kQRD1JkRUN7gWCJhkMk8sgFenV4TPrZUmORRFywsTuoMCGfx9JsDbAcW/J4A6CXEyhmn6Wmt7XKF+/pzSDAdPabWBMy146uNFisFDhZDeo4liBoJvgzLdWNfEhDBVrtjQ813nxk+cjDfMoHUUBXBexPbTGx+rCH9mWn/rChrSiltECc0KzWyZeC3pv0NDmkpKvivHo"],["NcEwAK5L3q1dZZzJsa2F300ln1QymWEZ3Q7Cnmz0RvROiZFl7jAokoL3XY6cunPt+skIMsLo0xox79PDtPp8mdE9Jl71LNSWD8g3qx8Fo+Y2NKzrKbyrHIY5kgJ6AZTac3V3i+fyI0Z82NB23YTvwWGXHqJXh/1AeOuZp0MITFP0sASFRmmU4miP7fqV1avwZ+db6RX6nzlBwnoLse6FOAp2dLQBW+if7Bd1ec0mRmmqVuaWnBlxnFL6pJZ9VALtzu04gJa16ObnUOrt","s7T6ifU8tkuwO7jccK4z2AzzGWOMSQXwKdRMQG5Dk33/MaGXwubf4IFzqB7IvgdqJx0puSsUiuKGDuivaWiV4bwYccMK2x37j8z6U1CrtNMX+h9jUv32HqeKv69euxkTFHMi9Omdmdls9vxKdwnfXtBlRYc3ugdf8u4wdIcUfIsNYtm5rBVn5p8r5nr7I7Lse6MdJbA4LQnHr//jKPVCchhlABtW+2RMXt4FMO616v6VffHq7eEl5YWRtfs=","Xbz7B9alohrZaq7g6gZV0ODxgSHfQroA0GkrhV+Irnml2gItNF5TGMR40189XVd8o2Rtu6tHLvcHtaJ20o9EMnmR0AZjT4xTazj4aZ1xv3tgBWx0jjRtSmaU+J8yQ0SIT8NiE4CAHX0p6ryMmjtc563IGouPhk7/xwZ/tu75UKAIYr8O4XIG4EhqZ2miBGELWANd5PXbM9NrelTfWEYuGdzaTsBQTHmzI+/oSTm42T1d9ofKdcHZ+sCRSPNWu5+RJkWs+hcfQghcOYlvP0XXbqFSXCfXZ4YtT4gWB1EY1nexYAOMicv05qgnYOdf33gpa5HLAmQk0e+Y91eNkRLBG2sgghrPsssnhRqktXvtzGHQf53E6JkNx/ZndOa32jm+Ynk3pT76ONT+CylDQuajTRNgCK9/l0IOvFONPg==","cdOdxmYd4uwpddnT+QTYZ447sZ/aJqIF0zqqGZgiDsYVbJWiC+CN1v4nXsGQ8XD6UJgTJuqgT46yIY023phHNNgzqrIqRQLgow09gOgOHfR1S7caHFyqXHjBk61bg4DfY8L+ea9ikaysB1et0t0gKZ3/6C66tebZO8sxAzWQkm21Q57BsK0q+/SrAwLqoNiVMdjc5EksvLQfdPSJs8Td1axLtfFhG2aa4Y5Zco3GRyIU6ogIDgiEtTpbxQkcFACCnur70lqZQMQ4AaeAzwtF3II70YlMl8KBCzU5Xg==","QXfJgVHy0xdvzKrUCRQY1uKukhuVcobACkN72Ih4jcszNY9GURELD9GDKglhXkpzmoRXj23m+Y4D3KQAhqomueCfhcwScseRFu+8cTZYFQ8+MFV7IqTR9M02ADsuhc+K8eofq8+jDKiVEAuXKHdW7Xl1PTtX27dl/ZOp6BPTLQ06Zd67xxSjj+qc0hQKsWC/YT2DMTkkk5KZ0V5L+h6c9p217jBSdLZ+N2cmeY3we0ANZKOvOYK6QPudVA73bPpFEb2a/n3bqKrcdjs25A1kGaheg5hYViErbrJzig=="],["xoRHQnmzDTKdjlvuh+R9LyZ2tFox04IBiaoarXRPXa2Jt6mFX7Z+fJMfgdvX0yOWitfKrvX0AsZNyW8VZ/B3qQPTa4q/5gFcOYgSXvdHel8lKgPTYSg+TLpX/XGYpHtpW4DE0gD6nyGZbA7LiT0T+21Me3s+HvR4cocadztFLuEhmG+O9v9RpiYqGZO22h7ogMw6UPkQWpyaYVQ6iCmDAy2U1sqcWfI2XiBuBiQQG6sgug0A4sHLvD8umX7f6KGDEbhofziZ2g6TsBbBJ8ssq6+kQ0ReHf23WCOV0A==","LFasxES8SCUW09Cxl4VadEUwgEG9rpEzM9D3uw/BVs67QvDiZlIYZpYw/XY10otKjyi3WXUxdX+JdozzDy8qYFYu000QfV3hs1UqvOhTNvjIdC1zzrrBXKPIX1DSGi0A6T3p7Y+RzdXBueSiE2QWdXqf2NGyuP5+v8r6ILJhG+b0G5H34nsQ2rZJSjnP2oq2/03AmRkY50seWQdR+6mTvSxqkLNqym+A0OfpTGfIo04jwVyhITK2/GPDYOUbs+dXTRjsWUlo1/IWjdfNRuKbYUwm+LPe/gDG/WAUNg==","e36sg4xIr80udPcvU2tuwWPJl7W7UJ+GH1n79JQZRcMhpiZHowejROiQy4Ghc55XB+xYj9GC6NmUdx2CqlJ682D9nbHW5I6cKDZo9c2VtJ9sN2I6LWwVgBwOvioo9Oxz3ZYSBs/Ur2JzKkaRVdhInIEi6CIF8lEuUYp28qw4dqnU2D/DHp97anUrzCJXgB2wQuxOsm+RZj26/5VPhPxObsxKILhUGDb7UBkvJd0YZ/eMBGs+YkSGBAN2wGQEyA+4fRCkfmJoLAT+BbJ8malShF08FeEi8k/wo/TI/qWAZKAW8GJMnleCURZCALDElnFHnK79Ctg0DCgz7ndOcHz/cssbHAD/QTMpdOTsUyWozkFzGzLCR/gunEMJqdLHxoVqmMEopFWrwosGF9NPb3FOGgHJ/jHKNcVhppiURDoID/RghicjR9pTYrLiSTK+MR/416eJ0oE3TQpDut5TkVvFhHWUOg0qYoGis9P0+A==","zDwScig1ZmnieOIaxbJ5UR90llrCaY76dq4a6BteI6O2/YBIW8osKOR4ptDAS/PItDvt42Q+GxNrwqRyV8dcyOrC6CKmNZD3Y9+nHG5sXqN7lWexmBSqwRFtH07oq1ju7mD668OSL/9VNQidnJ/O5RuSxTqCweR0056YnPvVBfDXZYzAjOdI7KFfF92p4TGDVSDIn6kT/Dxskbw6+30jVhV9ae5kCF5fEb2OL0kUaJ8MZ7UYLzY1sos88KoWWRFENPU6KTcLI5eyxHAJYXCfHGKQeMXeZFSTbpkD3TACqlbCFpFDA1o3ZeVkjnPg7cwgI8+RHsqifQkufp7XUlYm7j1+R7jFpYNtL9u4qA==","LuJ6n5D546ZdWjsKwFf6aIi6WsXSurDEWN5Kzn11Dw9/J0kbP540TJZMEiflccl0nW/k1QCgjEawnYi6JWWB5uZY0eGV+6JOUq8sCGkqCVeYFW2O3ab3o7tYCrvwpNLCAtJ+hKaY9ZWjmYho6iO2rvAay8t5s5bVDPNb/E5bQ6x2/5rJtKyd+2y+YYx+KWZkMnKShfbo/39ijDCncUOfFKXsSA9unY5h/M//4vpOjze39pPDqAelByWilQAK674qVQtdfzBMMTfT3nCl1s1vg1k8xY5XG8h0YgS5jAdmSld97G8iYLfhliSQnMk="],["uYnr7CvOgPK0T581WevcdvmLj0jyQ6RwNd3XGg8IJbB0fpsemwxdNA6JWGYGrdCEPz95t5aqiUntTrMG4daeQL99svxkbyGji0s1zh+xdEBHwGkHtr/XoC/urPgFZaJ6MKs9+LrldGcvGwuHBPZfcohDQ54jr7P/NVtqoFjx3JiuGVeENjrdACjBYv1XYjg8zB6ess28sl8zD7+H1/+yERAPBeJCJOLd7G9Lmg==","kqREgT7fLP4uxmAnJ4vrAEEkxQIKiqJz8mAB5cC1NDNCOn9uIt8jAbLKk0NWLkIIe78OxyN31BNIZV72nhx77fxQf7hT2xq/j0WmrGNZoczMN3dsnYnWXdZ9wtecKGh9UUyEIwz+rr9dpJCB49GqwtLxvNEN8Er6xWJpkI3HFpdAK/cNZZ48Gzv7uuL5f63gjE3Z4FIxrAQRKbpSDLE1UD5Oxis2BMc+9BIWKjY+RcPnGFPaRckekttQH5c=","Tqz8OLkHqbID8TZCRbQxL1l8+fxhaICRMiB9M1iW021HAai7ARo0CgLi0IHHI1Sccp5iJPCmuSNj2uKVSuFRjNVYrEzpVi1oCgdIRFmQXKWY941DiNw2BBndNfCOLM0qff/AYXrZcfLlL/QU2W7ik7oLb/8BRxfvTV+Y7qs0hSpRLeobvSovhV2h64Gix+jYcg1eVHdBlF9C35bZ","W12JdGE8fH6JWIFMQMZJsdOR261/T658IaxmmuPfgAvyEdE99u1VkYdyjoe4DnmyML8zA7ohRI3hT8CAVBWHimFO5ZNz9P9CHSijZDfHv4iCLfloWInIBshwf0460Zb0VbugZwcdtJi/3rFfFEhsBJXS/qoL22c9i513m+oixcGny80lRviwUktFcsUzTYWOKIb6b66f4IFw8OXvR2AKru/GbII9HU4tCySgF/nAkE3dsYeP5mg8ffv5acHG5kBpZKQh7ewihyiNLTrfn15mqNQODkmevLtIRjKQ3g==","pN5KJda6SR1qZ3p1klka9KkjV947c9/+klebIwUsqmEQ+DGUAFDHZXk4UIPJ8RaUsXvGhXWrUx0gYGHTHnatMp+tnqAcUBlrsqqTI+faTJUOLD1Rti6g7uRJxCNrT4gQEZxRTomXDidgTySdYFAlHrzKleYB1C4hyVFNlQEnzIfVXMvb9JykfTdvp/EPCPiYMucIri+rVdRhYytLboaYZHBYFpsS+qkQUFmXmK+X5zAruNy0Cr/BopReKXM="]];
                            $result_val['FGStripCount'] = 5;
                            // $result_val['FGContext'] = [[implode(',', $slotSettings->reelStripBonus1), implode(',', $slotSettings->reelStripBonus2), implode(',', $slotSettings->reelStripBonus3),implode(',', $slotSettings->reelStripBonus4),implode(',', $slotSettings->reelStripBonus5)]];
                            $result_val['FGContext'] = [["t938gWvBGhzLcnHROWpWd4w7KS/ruS5J5LYBoD2UUumJU8GQ5eQyS4cV2oeC2XT+haeFEw3fpGVTHaXYs0HoTGuI2Og+W7RzKazbxUaaj33o73ZWAENW+zHi2l6WoQNPBeP6khxn9RhXyEOh7fhZHumi51OJzvghYjyS7D2SAdkRm8NlQCwu1NU/fqisSlUuXJbfq5FZ7qptauIRtuZac08KlUfA90jun3mtX9f/vxGxWM0k+sUB7YEVKgCDQmsbJGUj8cTLdlT/VjF8","G7ZWpOW3QOFMSz9LvuQVB2OA/92g+yLmbXJkt5RGik6c+3gFy6cTPSxJDkeUwFbwZ3nIpNp7XNgMCNSw8uk0RiiPRl+I+bvYXKlHQXptVg9H73/tg0HBhA2RjBkJLafYjY29BY4Wd+zihVW+LDa4Zk7O0gP8lnIkwdD5iysapGn1nGVKUEvUNyOr+ctsoCWjB8uffjIp2ZbeDkh/tIIVg/DQ5A522eQ0JYaeUS480ljKkAHo1mcBBqcGH6kt011IV2JP5cJdSZ8jtVNkZHZ9FNEVnSsd46MRH4ELKg==","3OWTraScM1BjR1CcsMAPdsnl+vXB4TV0Qs7lBa8bc7bj6PCva3LQNGzsmymVjQTKIpczSKji5lEFEIwwobSxwfSY6QYsjKSn+0C7id4tQIE3FSkAQTfy1FwahlwiLST0qRZoJ48kg8wGnuGWehZMOQXhGw9Ccg5IYAHN2uZ/8TEwTM1S78jcNrcqWb0w8ryKh7Lf178nFaQrHLoyHLf/3KkyyAbGR16zLRRUZL1jzrclvYHy6VEjQxRt4lKhXWKR9zrLnj6fMt2Adkk7","qqVSvoddBStW2xusBDe8qUQwrh0m50t6KMlzPgqpsB4TB4JokGBc7ddUb+hddjn1enjJALegRb3AUVNbosCm/pmmXuZDImwYKdOWVZUtBX45GfwukYPeaQ9xRPsPd65m4+iuX3OxjyO5nVI7dyyoVBQyUqxINxtUh0t4mqOVmDetxmdORR5tnHgTWaXkkDXYSExtfakcGBdNk9BrggWr3ZkIWwt0ttIQsllFxQhNyfTXd/ur/JahCHOFYSQ89paMeM2kBlIcvtWxFT7fxKVPyIF2sa2IDjTKxGN128ohe9oDwLf/Wl2dfYpadpLsR9Vr+2FLN9v7BE9unolDyNFJ9fVA7152GNodJiflIg==","rieRaZVRbYQPHKq6KSTse5AOdBoy+9KkLFKCYiUgIbOile2wunl2yf0jcyBX1t2JsIHjOOP1/w58AjQyUN8jmoJcURFsdXHsR4DzcwxrCAN+HLw+2jn8tGXsFeWGu0HhVneQwvYRoWO6JgXmY6gk/q4nuKnlw1Z1js/C/TVnJ2fuWjqL0OwpTIfU8DCQjDNzG0z6LVEGh74i4h2JYw/zyRJG05ysT2dXJRVbeNKgsg/VfzdRzd78/dvzR1zwrPc2NIHMc8Ogjp7kMXuxSZHh6HSiYCkuIrxg5MyV5aJUDoX0Whp4cimWRH24wSg="],["VqGmH0G5ZofZZswUyV7VitrPxqz+yWV4lhYI5/fNAaINA+TTjIHfaCRtdX0N7OP30aK9CN7IPfyEq/GaOvOnsVmeUMOipPMXX/gHP/Kqsw26/9+YfEeyIGvKaLg6r5nmRL+NAkk/z6XVDpNNPi6CUQNcuXw8Yp7xiSwpezDAPD2fhgymkLdJOx3Y+H5n+QMsMqNRhBUZUcaSHE3IN4Gu4tkHSCCAqAeY9gCR1TxnXSO7MX4i++yBkLOB8hM=","TOqNbhNPKqTE0oA4A9ZDk4HP+cPtJN2V6LvsKeelch/njBQvDCSZdDwwy69lsk0pAy84NMzDKJaSKvKB9+TGEr5j7Cu5q897XlIbHWMX2htXH5qQz+opvT9GdHP4zKhUr9s+wvABDzjsqRk2KlHg7ALAhJiGub2IADCmMPJCTpKxLvoK59ry/30TGF2zDyGNiyo9YxhqmWyU4laC+I0FE2y7830nXW39MeamQISYoJ9yya4VbgigjvtKiMA=","KFkXoLvU2EoAI2KfF4r+Uw1RYgZv/Uz7xV69Dyo9xFrVVrHOVMLZIjmhldAeh+h+6wPJrypbfyWVxRAtdlBN/uZbbz/Jqaypn8OoM7WCrTv8EYB9W09JzOxw1vL5vUAbszpn2cqFtFmo99s5PlPhmwhRM7xJwlCwT+6wTU727OPuHiMZ/p8iXjw5OBKDkZFjws+FJz7kusApCnLisBVC8YBhwdGBb6hPF26DMXa0fZ1Y2XwEEQR++wfoOMoY7sPb+TvLSkk02MfPmNdj","aqRaVc5JZWZeoMuuTBIFOpqK6R2RYJnxzCDcxjQP02fba3u32JT9wNtUba5Ayn8OTJWaH79BGjO1/IDk85wy1hZ1A9Q7PFKJZEr1Gl1re2L3zEL8vmD61SbiqRQAelvrqYkd4NDycv4HehpctuBnRUvA7KU8/d9ZWV7ZtvKs0R5Zu0H2a5i1+OFJvsWaiGjlwRobTYxbQZtdxgKUDphDNlm3pSLaKhEAJi/U/DjvHdtqKHJuJCoiAQEWbA7atrOfGMToxreKn9ISq+DX3mD5ZSzlDkn/cyi7zkFLDFynsAMvDnOjt3OmGF+ex3w=","oBQt2vSVaWB3RE01Jq1wlHDCUGQAze1tmY7/mE+nALQGwY7Ly5Zp1h1VF82Ucj0PLKjgIeB9viSHKVBhxOgNxZSgAVRZVvjJv+GHFRJknCcBf5sQI4bx2LVQoFl8sCtD+hAcOxJFiNaJjPQiJlCKbiliFQ4+WTofPQ+CcaEaPveWNZ7F81TDwMbXkmAvfvHm4Ym5sjqSbANficqv4h6qu3gjT81ftivNudimffibNlzS+LkTRv6kpuvhAxDx+w241/sNF9jvWZENCuvKamdpw499uTyFobeT7/3ZgKCK4t5KoeWM0Zp3hO6dGS0IdrINdZk2WRd4rS18VOUpX5LUqLwwP7Fnl4JkYSfw8WoURHB8Su7p22KKk33wiQwSZwF1kuR06uPJmjC4lMx66bJ07GKbotHGU/oom8eL1Q=="],["csqlUjlj46tQwDaRpd3dr60esvOlBgLsWx3QWn+uhWvt/ZA4H5ea6DmjBI/godD0ORuIQsT4t9OCm3tHc7otp8hO7Lzpu2AXAtO61QHUjFdMFk9dn1oRol/uWLMNfuYeq4Z98IUk5qG3L+iRHPubveS8PwxyxFoJXrR17usvhlK/xJDrQwQXPycZ3M7ItiyweOJhkq1En3oq8E32zUHCXRmsOTW9K6YoHRL5CMD3rZcKm9ow11iabWuAP0s=","DbvoFSSjqqC1yNIpexl0QVW5doTANK5BSHHMedCJUQPcHXEcgEN/v8i6qfq6N2DoPESAsji77/vzfTSArYcft5A/KlgpgiCTaONzWpcKvpKgsO2rKAkUKCTTqeZoDkvrvXsG6UXoLiMcnYX1XL40JXEXhLRqVhTmBlnt8hqwKD7Ju4x/pB38RnXMNn0Yd6Jr6jNmj+wjwNJgiw/kfk/Cd4ZqdKuTvOoZl++NPUBGW0LmhFl1xFl37TJBLoWHANoQUFAdw4x7ma506iKt","OAfl7Zp35DsBgrOUIw7VeLVurOrwZcIOUNsQiTbwRUt5ctcXzAV13aKdq/5qQr65vY/SYSo6RCepXCiL0xUgo70u0d/zr2E63J/as5groOLHAnDsKB60LEIqccyLxwVE200UPX64PVSVkKtERLLuhKoHP1K4duPap3lal9fZJkCxZwMbbKdHmdUAhTswpo+4tquKVcfvEZQlp2hTSuseLkLlsnDzKjoHBte8cmePIishMPpqqZTkhSL0PKgOxXvPD+wC+Ae8r+e1+7w+","C1XulB2sRZSM5k8qenRX1xqidt3MetN8hZS0VGPKx1gi2F0fMXY5K7BUDF+r+vPaOb0mr7z9y8sG2H0EBaRpAGHsCKuzv5HsrUCM+Nwf8RgPR8IhZHiiqfs7QXYz8h4UWTVAISs0BH3/k7X+xm7RKzzIzI0TZ6hRX25kIQE6A557QQnLn+NkS7b6Z7Mk3ahCviaFQj20NtmkLn8OO+jnJs8bed+/8S1/QJxvtqcTwEym6jkk80Qh40/+UdYc/e4U5+KlFijzb8DXpsma","8BG0yUKpkheLGSCudW8hqMWKYPvLFAlH/TMrNDG/eONp5pHlyaO6plAqA5G3DhFAVfweg6OIQ5JV4BeDTvRXEPfcc9RVJgVM/5CLtkfSOXjSWVGZg342iyIG0cR8cbCM2/ZiS2JZfurG0fPQFuAS5dWJYXbh9QzELv/hzPwQ2BC2qDfVajQcKoEgWhBOWcE0lDr7djulj8jHaM8QPbvHhhumOwv01p4UFpcdu9ObXHeIHf5jDkhXz2ioG1k="],["wCmMC9KhpZ7Z36v6B2Bm0GXAYWfsWRY4FmWeEGm14X1OIjfKkxegJLjGGN/17M5fMUOXIbUKlOTqiQDCzNEsmE3sK40n/b22LhOQY9bhpH/CH7Exb12Yg6SGuw+nYFmHLprhNLbqrGUrERnBGqJxI2PnQVZqQj91KCpdy5b9qk2wWEzEDpO4WcMRc8qYz27gvFCiOwaw7R3lCYTQwR8VyesqtQndUpx5UNHw8RRk47+ay0QRHTKoC/8hmYQ=","5t5pqJbAH4rrOLIppPQhoy9eiihvdyjKtZ/zzgqDFjzq4xSjhFKyUgSTWOT2A51bKks+l6S9SJlSlCGmsUf4H+wPMEkKy2ivhQWe6ZvOUd2WDo6sMyrZEzmqPQHoWm6TVKL4G5NYlBqSt2uJ+PRqN4LpcuJE/pOIuk/ewlWWNTTBqvL3chmpVEzqCN+olB8z+DIYNR9stIkuL9MFrDlbU1ge2iP1xSTo7kBK3ETYyh76wl6JCriIAsc6+Fua8DUTBndBeS0LzrIL2us6","q4KC86BJGGbCYBfX8wWXqxT3s1q9FaJcziA6zTbX3gl/YWa5JmA2gA9CZ2hR1OayaYFVJ+vVWBrELxsCrb7wHZz/QV/rzSMTBSM5tOBuXfvfBBBpcahxrDe5j6qU6ECmFwlQ8LLwinn2d9QMy/zPhakyw16IP+yuPO8F5XGxtB46xByNwxvk0/Yp2yLTz+aeisFA716SRylCeycBNtS+Yqp8+Mk49YrqvTUr80p+rZlkXb5i8q6xt/sH4Kuniduu1RCY5IxnBywc1IsWSSFFALFxQPeanGd+CS834Q==","wTDBP6Be6f4n9eE1S6LEXLG6wQGOknx4tlaMbqFY3AeDezwY/JxGcSPJSqMvnG9D7ZoyBenqELEpWcZQhjlxGq7gOkL0EJqrChoEzsHnw86W3+hEibrOuxHOjKlWAyhA0sNOaUFaSW2mzKgdQdMPAUp5k9je2Z9KZIrn0G83UjTWs6waI9Rd6+WV4vMpyHpG4RdGHX+ywhVmT0wRelZSewHZg2UI9duTlugQfMH4dQF2qzzqXRKTZodS9oaJG13qKq2g9+3vpox5+zZiM5YN+Li0yuvzMySqyA5RuA==","Hj9LqxaVCc5b4LAYXOLZJNlFUPF31fd6NlhXz3jOAeieiThbvKB9kAcrg7K8yMTpKrK1GL69wsQzYDGvPSYMcsAzKIg1gT6R4kxpPhtUxgUnw78DVB7MatphiSR88qEUd7pjyByOWBjEVZ3S3aGvV9hQR4t3vlU7P+gxm83HRb5YSNn7M4YN7QVF+PWZYK4FDUCLdE/BskVq3l7IRY2cKZ4zuCqnH67W9erJuLZxiJC5w19NN5D8d43vWOc="],["QsmVHFqnMJ5Yla7clVtnISYQTAhiAhpTBDuwaL9hDSwcGh6T8ugxvCpqpS6/sU2Ww4Cfa+tWT4g8r7gqXDI8eXlzNvhOi6j1UZRQGEO7GXruYEAc5oWtHjzJiGXtmSAW112aKhaFm67hS0eCbzh9p4ZJSuQeGKJQdGn073Q6z8gf19xcf2owkKT61E1SYTIw55lq8RfSCU4C3NqV1z/LgwTz1OaUPKxsaqyDf44sdYud7g1ruDFj9J5kVXZdyQG64a3CegXzwotXPEXM","iV3QBrKTmbcdFu5nAQhA+L/M+T5jQgWVX+vJA18vSgDQ2nod+u00arOECJ1xk+HVwZo2wW0sxz0fjWiKwiCx+pOBKmpoyk5TCdRzWZi8fhB1tz3jHHLrs/TIJgyW1RhhMCZTcAqL+Gyp/qXvkCH8glygd+annkA8OD00Dtzyd9BsIrb+hqpjM8wkdAAs5Ms4U0b/WPA7PXvGn6Ndoon3xTSfs67jDLmGhdgUb+RhfztHyqe7jgTbpb08y2fjk4QFkajSVRuYjIPOU/DO+knZulnpKR1lytbTW82i7GWpSHjhgL2+OblSIlroYHFScmc/vL+CYzOOVCQOXnn+","6N7gxw9TTgAqx4XComMQmCsImVkkt/tas9qza0olER6ncnzB8gbvzfA+w0hLlo8vhSWRQfimMBHmX+N2yBo/SHStjiLcgVHGVpJ1E/A2q2F64ak20jAn0cYwpex1WEqYwaEMqBeqQzeJhlvxSFg6C/2POn0af1HVAshMe8p2GPUaqvSUKw38voLw0PmGP2sPkjmgFke+YoaiqaeURcyvN5mbLUCp2XTto/KHqT+LMtpCXZX12f/MnaQMSHAz/tB1HIKl8BksSx/mD9R83kK2f7GdVdzlxe9zWVd+ynH8WbEqrMJBJtvTu/iqabQ=","Bp4zkLbzIBYknlhd8dLT2YG3zzGJYq0oq//0YdOUqKlGhp0sq2uYpdMZQK1lI0GND8ZL8CLTU4YBP7SlcYz3j8DOJwaQIMoce23gYtuWo1iiDh20J7nVlVjCPsjhHjCcXSYKi5O+a1f8ChBE9dGZClBLFHEsz4S/T9METuxrmQyhDqHZB81RnqsHzvNHrGIAClgcZabFkUwWqPqmO/5/Eypr0BkSCwM2nqR7VJ9foSI6XNIkpYeem3EfBK6q7PH1dcOqTcpTgZjgA0FSYs/yrMzZAedAJMK4jdbLnryswoXMSUPSf7chR4mZvkbDuNQj2gwNo0iYCmqBTZpVzerDt0b+szB01kuBiCPJE+GQ7mQAM4WxjLE77ljpHzypOxcKDCxlSpVoYSwsSvMhOHYS5F4yyBW8yHZ7GX8KRuhW9d+xsN9y9wc672wan9xG8Sl5ttfOJF6F+E0cFvT+","yayoSLvEZTgYuJrowB5cVOiSoaD8yDZGdGM9vjRpiTuLlTFxdL7+5WhkevJTRFysH5u33btvmrqQe4J7u2J6z6qVVrkmNPqP7SUsyl+HfQ7MBdpLkeee01giq5MkXGq0M86rqg98sgYdm+hLpHllo4t0GAGsnwL3xZMAcNj7Q/iv2V37kZhtn67ts41BctDiehfK3VKhg6EtVw1eoYJ80yF0BNwUIrA5ekrx7Bb9PKLX2bsX72rxnr3E9k4="]];
                        }else if($packet_id == 31 || $packet_id == 42){
                            $lines = 88;
                            if($packet_id == 31){
                                $betline = $gameData->PlayBet;// * $gameData->MiniBet;
                            }else if($packet_id == 42){
                                $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            }
                            if($packet_id == 42 && $slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                                $slotEvent['slotEvent'] = 'freespin';
                            }else{
                                if($packet_id == 31){
                                    $slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds'));
                                    $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount',$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount'));
                                }
                                //$slotSettings->SetGameData($slotSettings->slotId. 'GameRounds',0);
                                $slotEvent['slotEvent'] = 'bet';
                                $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusWin', 0);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []); //FreeStacks  릴배치표 저장
                                if($packet_id == 42){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                                }else{
                                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                                }
                                $slotSettings->SetGameData($slotSettings->slotId . 'BonusMul', 1);
                                $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 0);
                                if(isset($gameData->PlayBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'PlayBet', $gameData->PlayBet);
                                }
                                if(isset($gameData->MiniBet)){
                                    $slotSettings->SetGameData($slotSettings->slotId . 'MiniBet', $gameData->MiniBet);
                                    $slotSettings->SetGameData($slotSettings->slotId . 'Lines', $lines);
                                }                                
                                $slotSettings->SetGameData($slotSettings->slotId . 'RealBet', $betline);
                                
                                $slotSettings->SetBet();        
                                if(isset($slotEvent['slotEvent'])){
                                    $slotSettings->SetBalance(-1 * ($betline * $lines), $slotEvent['slotEvent']);
                                    
                                }

                                $_sum = ($betline * $lines) / 100 * $slotSettings->GetPercent();
                                
                                $slotSettings->SetBank($slotEvent['slotEvent'], $_sum, $slotEvent['slotEvent']);
                                $slotSettings->SetGameData($slotSettings->slotId . 'InitBalance', $slotSettings->GetBalance());
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                $roundstr = sprintf('%.4f', microtime(TRUE));
                                $roundstr = str_replace('.', '', $roundstr);
                                $roundstr = '657' . substr($roundstr, 3, 9);
                                $slotSettings->SetGameData($slotSettings->slotId . 'GamePlaySerialNumber', $roundstr);
                            }

                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,$packet_id);
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
                                if(isset($stack['AccumlateWinAmt'])){
                                    $result_val['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                                }
                                
                                $result_val['AccumlateJPAmt'] = 0;
                                $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                                if(isset($stack['MaxRound'])){
                                    $result_val['MaxRound'] = $stack['MaxRound'];
                                }else{
                                    $result_val['MaxRound'] = 1;
                                }
                                
                                $result_val['AwardRound'] = $stack['AwardRound'];
                                $result_val['CurrentRound'] = $stack['CurrentRound'];
                                if(isset($stack['MaxSpin'])){
                                    $result_val['MaxSpin'] = $stack['MaxSpin'];
                                }else{
                                    $result_val['MaxSpin'] = 100;
                                }
                                
                                $result_val['AwardSpinTimes'] = $stack['AwardSpinTimes'];
                                $result_val['Multiple'] = 0;
                                $result_val['GameExtraData'] = "";
                            }else{
                                $slotSettings->SetGameData($slotSettings->slotId . 'CurrentBalance', $slotSettings->GetBalance());
                                if($packet_id == 32){
                                    if($slotSettings->GetGameData($slotSettings->slotId . 'NextRoundAction') == 1){
                                        $slotSettings->SetGameData($slotSettings->slotId . 'GameRounds', $slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1);
                                        $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 0);
                                        
                                        $nextRoundAction = false;
                                        $slotSettings->SetGameData($slotSettings->slotId . 'NextRoundAction', 0);
                                    }
                                    /*if($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') == 5){
                                        $slotSettings->SetGameData($slotSettings->slotId . 'LastLevelSpinCount',$slotSettings->GetGameData($slotSettings->slotId . 'LastLevelSpinCount') + 1);
                                        if($slotSettings->GetGameData($slotSettings->slotId . 'LastLevelSpinCount') >= 1000){
                                            $slotSettings->SetGameData($slotSettings->slotId . 'GameRounds', 1);
                                            $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 0);
                                            $slotSettings->SetGameData($slotSettings->slotId . 'LastLevelSpinCount',0);
                                        }
                                    }*/
                                }
                            }
                        }else if($packet_id == 44){
                            $slotEvent['slotEvent'] = 'bet';
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['PlayerBet'] = $betline;
                            if(isset($stack['AccumlateWinAmt'])){
                                $result_val['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                            }
                            
                            $result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $slotSettings->SetGameData($slotSettings->slotId . 'ScatterGameValue', $result_val['ScatterPayFromBaseGame']);
                            $result_val['MaxRound'] = $stack['MaxRound'];
                            $result_val['CurrentRound'] = $stack['CurrentRound'];
                            $result_val['udcDataSet']['SelExtraData'] = [];
                            $result_val['udcDataSet']['SelMultiplier'] = [];
                            $result_val['udcDataSet']['SelSpinTimes'] = [];
                            $result_val['udcDataSet']['SelWin'] = [];
                            $result_val['udcDataSet']['PlayerSelected'] = [0];
                            //$result_val['udcDataSet'] = ["SelExtraData"=>[],"SelMultiplier"=>[],"SelSpinTimes"=>[],"SelWin"=>[],"PlayerSelected"=>[0]];
                        }else if($packet_id == 45){
                            $slotEvent['slotEvent'] = 'bet';
                            $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', []);
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 0);
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',$gameData->PlayerSelectIndex);
                            $result_val = $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, 1, $originalbet,$packet_id);
                           
                        }else if($packet_id == 46){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $result_val['PlayerBet'] = $betline;
                            //$result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            //$tempWinAmt = ($stack['TotalWin'])
                            $result_val['TotalWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                            //$result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['ScatterPayFromBaseGame'] = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterGameValue');
                            $result_val['NextModule'] = 20;
                        }else if($packet_id == 43){
                            $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                            //$slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                            $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                            $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                            //$result_val['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                            $result_val['TotalWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                            //$result_val['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                            $result_val['ScatterPayFromBaseGame'] = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterGameValue');
                            $result_val['NextModule'] = 0;
                            $result_val['GameExtraData'] = "";
                            $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',-1);
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
                    $betline = $slotSettings->GetGameData($slotSettings->slotId . 'PlayBet');
                    $lines = 88;
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 44 || $slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 45){
                        $pur_level = -1;
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',0);
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bet', ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds'),$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'));
                        if($tumbAndFreeStacks == null){
                            $response = 'unlogged';
                            exit( $response );
                        }
                        $stack = $tumbAndFreeStacks[0];
                        $freespinNum = $stack['udcDataSet']['SelSpinTimes'][0];
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 3);
                        if(isset($stack['TotalWinAmt'])){
                            $stack['TotalWinAmt'] = $stack['TotalWinAmt'] / $originalbet * $betline;
                        }
                        if(isset($stack['ScatterPayFromBaseGame'])){
                            $stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                        }
                        $slotSettings->SetGameData($slotSettings->slotId . 'ScatterGameValue', $stack['ScatterPayFromBaseGame']);
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'PackID') == 31 && $slotSettings->GetGameData($slotSettings->slotId . 'TriggerFree')>0){
                        //$slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 10);
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeIndex',0);
                        $tumbAndFreeStacks= $slotSettings->GetReelStrips('bet', ($betline /  $this->demon) * $lines, $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds'),$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'));
                        $stack = $tumbAndFreeStacks[0];
                        $freespinNum = $stack['udcDataSet']['SelSpinTimes'][0];
                        $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', $freespinNum);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                        $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 3);
                    }
                    if($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                        // FreeSpin Balance add
                        $slotEvent['slotEvent'] = 'freespin';
                        
                       
                        $count = 0;
                        while($slotSettings->GetGameData($slotSettings->slotId . 'FreeGames') > 0){
                            $result_val = [];
                            $result_val['Type'] = 3;
                            $result_val['ID'] = 145;
                            $result_val['Version'] = 0;
                            $result_val['ErrorCode'] = 0;
                            $result_val['EmulatorType'] = 0;
                            $this->generateResult($slotSettings, $result_val, $slotEvent['slotEvent'], $betline, $lines, $originalbet,30);
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
        public function generateResult($slotSettings, $result_val, $slotEvent, $betline, $lines, $originalbet,$packetID){
            $_spinSettings = $slotSettings->GetSpinSettings($slotEvent, $betline * $lines, $lines);
            $winType = $_spinSettings[0];
            $_winAvaliableMoney = $_spinSettings[1];
            //$winType = 'win';
            // $_winAvaliableMoney = $slotSettings->GetBank($slotEvent);

            if($slotEvent == 'freespin'){
                $tumbAndFreeStacks = $slotSettings->GetGameData($slotSettings->slotId . 'TumbAndFreeStacks');
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', $slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount') + 1);
                
                
            }else{
                $tumbAndFreeStacks= $slotSettings->GetReelStrips($winType, $betline * $lines, $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds'),$slotSettings->GetGameData($slotSettings->slotId . 'FreeIndex'));
                if($tumbAndFreeStacks == null){
                    $response = 'unlogged';
                    exit( $response );
                }
                $slotSettings->SetGameData($slotSettings->slotId . 'TumbAndFreeStacks', $tumbAndFreeStacks);
                $stack = $tumbAndFreeStacks[$slotSettings->GetGameData($slotSettings->slotId . 'TotalSpinCount')];
                if($packetID == 31){
                    $slotSettings->SetGameData($slotSettings->slotId . 'TotalSpinCount', 1);
                    $stack = $tumbAndFreeStacks[0];
                }
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
                $stack['TotalWin'] = $stack['TotalWin'] / $originalbet * $betline;
                $totalWin = $stack['TotalWin'];
            }
            if(isset($stack['AccumlateWinAmt']) && $stack['AccumlateWinAmt'] > 0){
                //$stack['AccumlateWinAmt'] = $stack['AccumlateWinAmt'] / $originalbet * $betline;
                $stack['AccumlateWinAmt'] = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + $totalWin;
            }
            if(isset($stack['AccumlateJPAmt']) && $stack['AccumlateJPAmt'] > 0){
                $stack['AccumlateJPAmt'] = $stack['AccumlateJPAmt'] / $originalbet * $betline;
            }
            if(isset($stack['ScatterPayFromBaseGame']) && $stack['ScatterPayFromBaseGame'] > 0){
                //$stack['ScatterPayFromBaseGame'] = $stack['ScatterPayFromBaseGame'] / $originalbet * $betline;
                $stack['ScatterPayFromBaseGame'] = $slotSettings->GetGameData($slotSettings->slotId . 'ScatterGameValue');
            }

            if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                $slotSettings->SetGameData($slotSettings->slotId . 'TriggerFree', 1);
            }
            //$slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 13);
            $newExtraSymbolCount = 0; 
            if(isset($stack['SymbolResult'])){
                $gameRound = $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds');
                if($slotEvent == 'freespin'){

                }else{
                    //if()
                    for($i = 2; $i < 5;$i++){
                        $tempSymbol = $stack['SymbolResult'][$i];
                        if(str_contains($tempSymbol,'W1') ){
                            $newExtraSymbolCount += 1; 
                        }
                    }
                    if($newExtraSymbolCount > 0){
                        for($i = 0;$i<2;$i++){
                            $tempSymbol = $stack['SymbolResult'][$i];
                            if(str_contains($tempSymbol,'W1') ){
                                $newExtraSymbolCount += 1; 
                            }
                        }
                    }
                }
                
            }


            if($newExtraSymbolCount > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') + $newExtraSymbolCount);
            }
            $stack['ExtraData'][0] = $slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount');
            
            
            if($slotSettings->GetGameData($slotSettings->slotId. 'GameRounds') != 5){
                $stack['ExtendFeatureByGame'] = [["Name"=>"AccumulateUpCount","Value"=>$slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount')],["Name"=>"LevelUpCount","Value"=>3],["Name"=>"CurrentLevel","Value"=>$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds')],["Name"=>"NextLevel","Value"=>($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1)]];
            }else{
                $stack['ExtendFeatureByGame'] = [["Name"=>"AccumulateUpCount","Value"=>0],["Name"=>"LevelUpCount","Value"=>3],["Name"=>"CurrentLevel","Value"=>$slotSettings->GetGameData($slotSettings->slotId . 'GameRounds')],["Name"=>"NextLevel","Value"=>($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1)]];
                $slotSettings->SetGameData($slotSettings->slotId . 'SymbolCount', 0);
                $stack['ExtraData'][0] = 0;
            }
            $stack['NextSTable'] = 0;
            if($slotSettings->GetGameData($slotSettings->slotId . 'SymbolCount') == 3 && $slotSettings->GetGameData($slotSettings->slotId. 'GameRounds') != 5){   
                /*if($slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1 > 3){
                    $stack['ExtendFeatureByGame'][3]['Value'] = 1;
                }else{
                    $stack['ExtraData'][3]['Value'] = $slotSettings->GetGameData($slotSettings->slotId . 'GameRounds') + 1;
                }*/
                $stack['NextSTable'] = 1;
                //$stack['SpecialAward'] = 1;
                $slotSettings->SetGameData($slotSettings->slotId . 'NextRoundAction', 1);
            }

            $awardSpinTimes = 0;
            $currentSpinTimes = 0;
            if($slotEvent == 'freespin'){
                if(isset($stack['AwardSpinTimes'])){
                    $awardSpinTimes = $stack['AwardSpinTimes']; 
                    if(isset($stack['CurrentSpinTimes'])){
                        $currentSpinTimes = $stack['CurrentSpinTimes'];
                    }                       
                }
                    
            }
            if(isset($stack['udsOutputWinLine']) && count($stack['udsOutputWinLine'])>0){
                foreach($stack['udsOutputWinLine'] as $index => $value){
                    if($value['LinePrize'] > 0){
                        $value['LinePrize'] = $value['LinePrize'] / $originalbet * $betline;
                    }
                    $stack['udsOutputWinLine'][$index] = $value;
                }
            }
            
            if($slotEvent != 'freespin' && isset($stack['IsTriggerFG'])){
                $isTriggerFG = $stack['IsTriggerFG'];
            }
            $freespinNum = 0;
            if(isset($stack['FreeSpin']) && count($stack['FreeSpin']) > 0){
                $freespinNum = $stack['FreeSpin'][0];
            }
            if(isset($stack['IsTriggerFG']) && $stack['IsTriggerFG'] == true){
                $freespinNum = 15;
            }
            $stack['Type'] = $result_val['Type'];
            $stack['ID'] = $result_val['ID'];
            $stack['Version'] = $result_val['Version'];
            $stack['ErrorCode'] = $result_val['ErrorCode'];
            $result_val = $stack;
            
            if($totalWin > 0){
                $slotSettings->SetBalance($totalWin);
                if($winType == 'bonus'){
                    $slotSettings->SetBank('bonus', -1 * $totalWin);   
                }else{
                    $slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                }
                //$slotSettings->SetBank((isset($slotEvent) ? $slotEvent : ''), -1 * $totalWin);
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin') + ($totalWin));
            }

            $result_val['Multiple'] = 0;

            if($freespinNum > 0)
            {
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
                if($awardSpinTimes > 0 && $awardSpinTimes == $currentSpinTimes){
                    $slotSettings->SetGameData($slotSettings->slotId . 'FreeGames', 0);
                    $isState = true;
                }
            }
            
            
            if($packetID == 44 || $packetID == 45){
                
            }else{
                $gamelog = $this->parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines);
                if($isState == true){
                    $slotSettings->SaveLogReport(json_encode($gamelog), $betline * $lines, $lines, $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin'), $slotEvent, $slotSettings->GetGameData($slotSettings->slotId . 'GamePlaySerialNumber'), $isState);
                }
            }
            

            if($slotEvent != 'freespin' && $freespinNum > 0){
                $slotSettings->SetGameData($slotSettings->slotId . 'TotalWin', $totalWin);
            }
            return $result_val;
        }
        public function parseLog($slotSettings, $slotEvent, $result_val, $betline, $lines){
            $currentTime = $this->getCurrentTime();
            $proof = [];
            $proof['win_line_data']             = [];
            if(isset($result_val['SymbolResult'])){
                $proof['symbol_data']               = $result_val['SymbolResult'];
            }
            
            $proof['symbol_data_after']         = [];
            $proof['extra_data']                = $result_val['ExtraData'];
            if(isset($result_val['ReellPosChg'])){
                $proof['reel_pos_chg']              = $result_val['ReellPosChg'];
            }
            if(isset($result_val['ReelLenChange'])){
                $proof['reel_len_change']           = $result_val['ReelLenChange'];
            }
            
            if(isset($result_val['ReelPay'])){
                $proof['reel_pay']                  = $result_val['ReelPay'];
            }
            if(isset($result_val['RespinReels'])){
                $proof['respin_reels']              = $result_val['RespinReels'];
            }
            if(isset($result_val['BonusType'])){
                $proof['bonus_type']                = $result_val['BonusType'];
            }
            if(isset($result_val['SpecialAward'])){
                $proof['special_award']             = $result_val['SpecialAward'];
            }
            if(isset($result_val['SpecialSymbol'])){
                $proof['special_symbol']            = $result_val['SpecialSymbol'];
            }
            if(isset($result_val['IsRespin'])){
                $proof['is_respin']                 = $result_val['IsRespin'];
            }
            if(isset($result_val['FreeSpin'])){
                $proof['fg_times']                  = $result_val['FreeSpin'];
            }
            
            $proof['fg_rounds']                 = floor($slotSettings->GetGameData($slotSettings->slotId . 'CurrentFreeGame'));
            if(isset($result_val['NextSTable'])){
                $proof['next_s_table']              = $result_val['NextSTable'];
            }
            
            //$proof['extend_feature_by_game']    = $result_val['ExtendFeatureByGame'];
            $proof['extend_feature_by_game'][0]['name']    = $result_val['ExtendFeatureByGame'][0]['Name'];
            $proof['extend_feature_by_game'][0]['value']    = $result_val['ExtendFeatureByGame'][0]['Value'];
            $proof['extend_feature_by_game'][1]['name']    = $result_val['ExtendFeatureByGame'][1]['Name'];
            $proof['extend_feature_by_game'][1]['value']    = $result_val['ExtendFeatureByGame'][1]['Value'];
            $proof['extend_feature_by_game'][2]['name']    = $result_val['ExtendFeatureByGame'][2]['Name'];
            $proof['extend_feature_by_game'][2]['value']    = $result_val['ExtendFeatureByGame'][2]['Value'];
            $proof['extend_feature_by_game'][3]['name']    = $result_val['ExtendFeatureByGame'][3]['Name'];
            $proof['extend_feature_by_game'][3]['value']    = $result_val['ExtendFeatureByGame'][3]['Value'];
            $proof['extend_feature_by_game2']   = [];
            $proof['denom_multiple'] = 100;
            $proof['l_v']                       = "2.4.32.1";
            $proof['s_v']                       = "5.27.1.0";
            if(isset($result_val['udsOutputWinLine'])){
                foreach( $result_val['udsOutputWinLine'] as $index => $outWinLine) 
                {
                    $lineData = [];
                    $lineData['line_extra_data']    = $outWinLine['LineExtraData'];
                    $lineData['line_multiplier']    = $outWinLine['LineMultiplier'];
                    $lineData['line_prize']         = $outWinLine['LinePrize'];
                    $lineData['line_type']          = $outWinLine['LineType'];
                    $lineData['symbol_id']          = $outWinLine['SymbolId'];
                    $lineData['symbol_count']       = $outWinLine['SymbolCount'];
                    $lineData['num_of_kind']        = $outWinLine['NumOfKind'];
                    $lineData['win_line_no']        = $outWinLine['WinLineNo'];
                    $lineData['win_position']       = $outWinLine['WinPosition'];
                    array_push($proof['win_line_data'], $lineData);
                }
            }
            
            if($slotEvent == 'freespin'){
                $log = $slotSettings->GetGameData($slotSettings->slotId . 'GameLog');
                $log['actionlist'][1]['amount']     = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $log['actionlist'][1]['eventtime']  = $currentTime;
                
                $log['detail']['wager']['order_time']   = $currentTime;
                $log['detail']['wager']['end_time']     = $currentTime;
                $log['detail']['wager']['total_win']    = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                if(isset($result_val['LockPos'])){
                    $proof['lock_position']         = $result_val['LockPos'];
                }
                

                $sub_log = [];
                $sub_log['sub_no']              = count($log['detail']['wager']['sub']) + 1;
                $sub_log['game_type']           = 53;
                if(isset($result_val['RngData'])){
                    $sub_log['rng']                 = $result_val['RngData'];
                }
                
                $sub_log['multiple']            = $result_val['Multiple'];
                if(isset($result_val['TotalWin'])){
                    $sub_log['win']                 = $result_val['TotalWin'];
                }
                if(isset($result_val['WinLineCount'])){
                    $sub_log['win_line_count']      = $result_val['WinLineCount'];
                }
                
                if(isset($result_val['WinType'])){
                    $sub_log['win_type']            = $result_val['WinType'];
                }
                
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
                $bet_action['amount']           = $betline * $lines;
                $bet_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $bet_action);
                $win_action = [];
                $win_action['action']           = 'win';
                $win_action['amount']           = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                $win_action['eventtime']        = $currentTime;
                array_push($log['actionlist'], $win_action);

                $wager= [];
                if(isset($result_val['GamePlaySerialNumber'])){
                    $wager['seq_no']                = $result_val['GamePlaySerialNumber'];
                }
                
                $wager['order_time']            = $currentTime;
                $wager['end_time']              = $currentTime;
                $wager['user_id']               = $slotSettings->playerId;
                $wager['game_id']               = 117;
                $wager['platform']              = 'web';
                $wager['currency']              = 'KRW';
                $wager['start_time']            = $currentTime;
                $wager['server_ip']             = '10.9.16.17';
                $wager['client_ip']             = '10.9.16.17';
                $wager['play_bet']              = $betline * $lines;
                $wager['play_denom']            = 100;
                $wager['bet_multiple']          = $betline;
                if(isset($result_val['RngData'])){
                    $wager['rng']                   = $result_val['RngData'];
                }
                
                $wager['multiple']              = $result_val['Multiple'];
                if(isset($result_val['TotalWin'])){
                    $wager['base_game_win']         = $result_val['TotalWin'];
                }
                
                $wager['win_over_limit_lock']   = 0;
                $wager['game_type']             = 0;
                if(isset($result_val['WinType'])){
                    $wager['win_type']              = $result_val['WinType'];
                }
                
                $wager['settle_type']           = 0;
                $wager['wager_type']            = 0;
                $wager['total_win']             = $slotSettings->GetGameData($slotSettings->slotId . 'TotalWin');
                if(isset($result_val['WinLineCount'])){
                    $wager['win_line_count']        = $result_val['WinLineCount'];
                }
                
                if(isset($result_val['GamePlaySerialNumber'])){
                    $wager['bet_tid']               =  'pro-bet-' . $result_val['GamePlaySerialNumber'];
                    $wager['win_tid']               =  'pro-win-' . $result_val['GamePlaySerialNumber'];
                }
                
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
