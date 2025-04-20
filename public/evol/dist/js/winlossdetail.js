function getDataTableDetailsEVO(data, username, txnId,prdId)
{
    try
    {
        $("#modalDetails").modal('show'); 
    }
    catch(e)
    {
        
    }
    
    $('#modal-table').show();
    $('#modal-json').hide();
    $('#modal-mg').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $('#modal-dowin').hide();
    $('#modal-dd').hide();
    $('#modal-og').hide();
    $('#modal-rizal').hide();
    $('#modal-arn').hide();
    $('#modal-yes8').hide();
    $('#tableName').hide();
    $('#wheelResult').hide();
    $('#trWheelPosition').hide();
    $('#trWheelType').hide();
    $('#trFinalMultiplier').hide();

    var gameType = data['data']['gameType'];
    var gameTypeList = ['baccarat','eth','trp','csp','holdem','dragontiger','uth','tcp','roulette','rng-roulette','topcard'
                        ,'moneywheel','thb','blackjack','rng-blackjack','rng-moneywheel','freebet','sicbo','lightningdice'
                        ,'sidebetcity','americanroulette','dhp','monopoly','scalableblackjack','dealnodeal','megaball'
                        ,'rng-megaball','rng-dragontiger','rng-baccarat','rng-topcard','instantroulette','powerscalableblackjack'
                        ,'crazytime','craps','rng-craps','gonzotreasurehunt','fantan','cashorcrash','rng-american-roulette','bacbo','andarbahar','crazycoinflip','extrachilliepicspins'
                        ,'teenpatti','monopolybigballer','goldbarroulette','classicfreebet','topdice','deadoralivesaloon','rng-dealnodeal','funkytime','videopoker','powerball','lightninglotto','rng-lightninglotto'
                        ,'gonzotreasuremap','reddoorroulette','rng-videopoker','rng-sicbo','crazypachinko','stockmarket','rng-lightningscalablebj','balloonrace','rng-hilo', 'lightningstorm','lightningball','funfun21scalablebj'
                        ,'betwithstreamer','liveslotdealnodeal','crazyballs','scalablebetstackerbj','easybj','lightningscalablebj'];
    
    if (gameTypeList.includes(gameType) == true ) 
    {
        $('#bjTitle').hide();
        $('#multipleBet').show();
        var participants = data['data']['participants'][0];
        var result = data['data']['result'];
        var resultName = Object.keys(result);
        var totalStake = 0;
        var totalPayout = 0;

        if (gameType == 'videopoker' || gameType == 'rng-videopoker') 
        {
            var gameName = data['data']['table']['name'];
        }
        else
        {
            // var gameName = data['data']['gameName'];
            var gameName = data['data']['table']['name'];
        }

        if( gameType == "blackjack"){
            for(var i=0; i<participants['bets'].length; i++){
                if( participants['bets'][i]['code'].includes("213")){
                    var tmp = participants['bets'][i]['code'].split("Seat");
                    participants['bets'][i]['description']="21+3 Seat "+tmp[1];
                } else if( participants['bets'][i]['code'].includes("PerfectPair")){
                    var tmp = participants['bets'][i]['code'].split("Seat");
                    participants['bets'][i]['description']="Perfect Pair Seat "+tmp[1];                    
                } else if( participants['bets'][i]['code'].includes("Insurance")){
                    var tmp = participants['bets'][i]['code'].split("Seat");
                    participants['bets'][i]['description']="Insurance Seat "+tmp[1];                    
                } else {
                    var tmp = participants['bets'][i]['code'].split("Seat");
                    participants['bets'][i]['description']="Play Seat "+tmp[1];                    
                }
            }
        } else if( gameName.includes( "Blackjack")) {
            for(var i=0; i<participants['bets'].length; i++){
                if( participants['bets'][i]['code'].includes("213")){
                    var tmp = participants['bets'][i]['code'].split("Seat");
                    participants['bets'][i]['description']="21+3 Seat "+tmp[1];
                } else if( participants['bets'][i]['code'].includes("PerfectPair")){
                    var tmp = participants['bets'][i]['code'].split("Seat");
                    participants['bets'][i]['description']="Perfect Pair Seat "+tmp[1];                    
                } else if( participants['bets'][i]['code'].includes("Insurance")){
                    var tmp = participants['bets'][i]['code'].split("Seat");
                    participants['bets'][i]['description']="Insurance Seat "+tmp[1];                    
                } else {
                    var tmp = participants['bets'][i]['code'].split("Seat");
                    participants['bets'][i]['description']="Play Seat "+tmp[1];                    
                }
            }
        }else if( gameType == "sicbo"){
            for(var i=0; i<participants['bets'].length; i++){
                try{
                if( !participants['bets'][i]['payout']){
                    participants['bets'][i]['payout'] =0;
                }
                    
                } catch(e){}
                try{
                if( !participants['bets'][i]['stake']){
                    participants['bets'][i]['stake'] =0;
                }
                    
                } catch(e){}
            }
        }

        $('#images').html('');
        $('#results').html('');
        $('#playerName').html(locale['modal.playername'] + ' : <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
        $('#time').html(gameName+' '+locale['on']+' '+data['data']['startedAt'].replace(/T|Z/gi,' ').split('.')[0]);
        $('#serialNo').html(data['data']['id']);
        $('#tableId').html(data['data']['table']['name'].replace(/K-Korean/gi,'Kplay Korean '));
        var status = (data['data']['status'] == 'Resolved') ? locale['resolved']:'';
        $('#status').html(status);
        $('#endTime').html(data['data']['settledAt'].replace(/T|Z/gi,' ').split('.')[0]);
        $('#txnid').html(locale['txnid']);
        // $('#playerhand').css('background', 'linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)');
        // $('#dealerhand').css({
        //     'background': 'linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)',
        //     'color': 'white'
        // });   
        if(gameName.includes("Blackjack") ){
            gameType = "blackjack";
        }
        if (['blackjack','rng-blackjack','freebet','dhp','scalableblackjack','powerscalableblackjack','classicfreebet','funfun21scalablebj','scalablebetstackerbj','easybj','lightningscalablebj'].includes(gameType) == true) 
        {
            var getSeatNumber;
            $('#hand').show();
            $('#bjTitle').show();
            $('#multipleBet').hide();
            $('#score').hide();
            $('#betResult').hide();
            $('#dealerhand').html(result[resultName[0]]['name']);

            if(gameType != 'easybj'){
                betDetail(gameType, participants, result, resultName);
            }

            //search txn_id which have duplicate bet of the seat number
            for (var i = 0; i < participants['bets'].length; i++) 
                if (participants['bets'][i]['transactionId'] == txnId) 
                    getSeatNumber = participants['bets'][i]['description'].substr(-1);

            //SORT by bet code
            var betDetails =participants['bets'];
            betDetails.sort((a, b) => {
            if (a.code < b.code) {
                return 1;
            }
            if (a.code > b.code) {
                return -1;
            }
            return 0;
            });

            //create bet one by one
            for (var j = 0; j < participants['bets'].length; j++) 
            {
                var chidlSeat = "";
                try{
                chidlSeat = participants['bets'][j]['description'].substr(-1);
                }catch(e){}
                //verify the same bet in the round_id and create the bet
                if (gameType == 'rng-blackjack' || gameType == 'blackjack' || gameType == 'classicfreebet') 
                {
                    //for normal blackjack which is different txn_id with brian request()david
                    // if (getSeatNumber == chidlSeat)
                    // {
                        var getSplit = ((participants['bets'][j]['description'].includes('Split')) ? 'Split' : '');
                        createBetWithImg(participants,j,chidlSeat,resultName,result,getSplit);

                        totalStake = totalStake + participants['bets'][j]['stake'];
                        totalPayout = totalPayout + participants['bets'][j]['payout'];
                    // }
                }
                else if (gameType == 'freebet' || gameType == 'scalableblackjack' || gameType == 'powerscalableblackjack' || gameType == 'funfun21scalablebj' || gameType == 'scalablebetstackerbj' || gameType == 'lightningscalablebj') 
                {
                    $('#multipleBet').show();
                    var tailCode = participants['bets'][j]['code'].split(/_(.+)/)[1];
                    var arrTailCode = ['Main','Insurance','QuadrupleDown','TripleDown','DoubleDown','Split','TripleOnFirst','TripleOnSecond','QuadrupleOnFirst','QuadrupleOnSecond','DoubleOnFirst','DoubleOnSecond'];
                    
                    if (arrTailCode.includes(tailCode))
                        createBet(participants,j);
                    else
                        createSideBetBJ(participants,j,result);

                    totalStake = totalStake + participants['bets'][j]['stake'];
                    totalPayout = totalPayout + participants['bets'][j]['payout'];
                }
                else if (gameType == 'easybj')
                {
                    $('#multipleBet').hide();
                    $('#hand').hide();
                    var tailCode = participants['bets'][j]['code'].split(/_(.+)/)[1];
                    var arrTailCode = ['Main','Insurance','QuadrupleDown','TripleDown','DoubleDown','Split','TripleOnFirst','TripleOnSecond','QuadrupleOnFirst','QuadrupleOnSecond','DoubleOnFirst','DoubleOnSecond'];
                    
                    if (arrTailCode.includes(tailCode)){
                        console.log(1);
                        createBet(participants,j,gameType);
                    }
                    else{
                        console.log(2);
                        createSideBetBJ(participants,j,result);
                    }
                    totalStake = totalStake + participants['bets'][j]['stake'];
                    totalPayout = totalPayout + participants['bets'][j]['payout'];
                }
                else if (getSeatNumber == chidlSeat && gameType == 'dhp') 
                {
                    $('#multipleBet').show();
                    $('#bjTitle').hide();
                    var getSplit = participants['bets'][j]['description'].substr(0,5);
                    createBet(participants,j);

                    totalStake = totalStake + participants['bets'][j]['stake'];
                    totalPayout = totalPayout + participants['bets'][j]['payout'];
                }
                else if (gameType == 'rng-lightningscalablebj') 
                {
                    $('#multipleBet').show();
                    var tailCode = participants['bets'][j]['code'].split(/_(.+)/)[1];
                    console.log(tailCode);
                    var arrTailCode = ['LightningFee','PlayLightning','PlaySeat1','SplitLightning','DoubleDownSeat1','InsuranceSeat1','SplitSeat1','DoubleDownLightning'];
                    
                    if (arrTailCode.includes(tailCode))
                        createBet(participants,j);
                    else
                        createSideBetBJ(participants,j,result);

                    totalStake = totalStake + participants['bets'][j]['stake'];
                    totalPayout = totalPayout + participants['bets'][j]['payout'];
                }

            }
        }
        else
        {
            $('#betResult').show();

            if (gameType == 'sidebetcity') 
            {
                getGameResultSbc(participants, data['data']['result'],resultName);
            }
            else if (gameType == 'megaball' || gameType == 'rng-megaball' || gameType == 'monopolybigballer')
            {
                $('#multipleBet').hide();
                $('#score').hide();
                $('#hand').hide();

                var tdTxnIdName = document.createElement('td');
                var tdTxnIdDetail = document.createElement('td');

                var trTitle = document.createElement('tr');
                var tdCard = document.createElement('td');
                var tdAmount = document.createElement('td');
                var tdNetCash = document.createElement('td');

                var tr = document.createElement('tr');
                var td = document.createElement('td');
                var div = document.createElement('div');
                var table = document.createElement('table');
                var tbody = document.createElement('tbody');

                var number = ['Megaball', 'NormalBall', 'multiplier'];

                $(tdTxnIdName).attr('colspan','6');
                $(tdTxnIdDetail).attr('colspan','6');

                $(tdTxnIdName).html(locale['txn_id']);
                $(tdTxnIdDetail).html(participants['bets'][0]['transactionId']);

                $('#images').append(tdTxnIdName);
                $('#images').append(tdTxnIdDetail);
                $('#images').attr('class','emptyContent');
                                
                $(table).attr('style','width:100%');
                $(tbody).attr('id', 'betDetailMegaBall');
                $(div).attr('style','max-height:350px;overflow:auto; padding:5px');
                $(td).attr('colspan','12');
                $(table).append(tbody);
                $(div).append(table);
                $(td).append(div);
                $(tr).append(td);
                $(tr).attr('class', 'removeClass');
                $('#multipleBet').after(tr);

                $(tdCard).html(locale['card']);
                $(tdAmount).html(locale['amount']);
                $(tdNetCash).html(locale['netcash']);
                $(tdCard).attr('style','font-weight:bold');
                $(tdAmount).attr('style','font-weight:bold');
                $(tdNetCash).attr('style','font-weight:bold');
                $(tdCard).attr('colspan','2');
                $(tdAmount).attr('colspan','2');
                $(tdNetCash).attr('colspan','2');
                $(trTitle).append(tdCard);
                $(trTitle).append(tdAmount);
                $(trTitle).append(tdNetCash);
                $('#betDetailMegaBall').append(trTitle);

                document.getElementById('results').innerHTML = 'NormalBall : {';
                $.each(result['drawnBalls'], function( index, value ) 
                {
                    if (index == 9) 
                        document.getElementById('results').innerHTML += value + '<br>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;';
                    else if (index == 19) 
                        document.getElementById('results').innerHTML += value + ' }<br>';
                    else
                        document.getElementById('results').innerHTML += value + ',';
                });

                $.each(result['drawnMegaBalls'], function( index, value ) 
                {
                    document.getElementById('results').innerHTML += 'Megaball : ' + result['drawnMegaBalls'][index]['number']
                        + ',' + 'Multiplier : ' + result['drawnMegaBalls'][index]['multiplier']+ '<br>';
                });

                if(gameType == 'monopolybigballer')
                {
                    document.getElementById('results').innerHTML = 'DrawnBalls : <br>{';
                    $.each(result['drawnBalls'], function( index, value ) 
                    {
                        if (index == 19) 
                            document.getElementById('results').innerHTML += value + '}<br><br>';
                        else
                            document.getElementById('results').innerHTML += value + ', ';
                    });

                    document.getElementById('results').innerHTML += 'FreeSpaces : <br>{';
                    $.each(result['freeSpaces'], function( index, value ) 
                    {
                        if (index == (result['freeSpaces'].length -1)) 
                            document.getElementById('results').innerHTML += value.card + ' Position' + value.index + '}<br><br>';
                        else
                            document.getElementById('results').innerHTML += value.card + ' Position' + value.index + ', ';
                    });

                    document.getElementById('results').innerHTML += 'Multiplier : <br>{';
                    $.each(result['multipliers'], function( index, value ) 
                    {
                        if(value.index != undefined)
                        {
                            if (index == (result['multipliers'].length -1)) 
                                document.getElementById('results').innerHTML += value.card + ' Position' + value.index + ' Multiplier' + value.value + '}<br>';
                            else
                                document.getElementById('results').innerHTML += value.card + ' Position' + value.index + ' Multiplier' + value.value + ', ';
                        }
                    });
                }

            }
            else if (gameType == 'gonzotreasurehunt' || gameType == 'gonzotreasuremap')
            {
                $('#score').hide();
                $('#hand').hide();
                $('#txnid').html(locale['payout']);
                var tdTxn = document.createElement('td');
                var tdTxnContent = document.createElement('td');
                $(tdTxn).html(locale['txn_id']);
                $(tdTxnContent).html(participants['bets'][0]['transactionId']);
                $(tdTxn).attr('colspan','6');
                $(tdTxnContent).attr('colspan','6');
                $('#cards').append(tdTxn);
                $('#cards').append(tdTxnContent);
                $('#betResult').hide();
            }
            else if (gameType == 'fantan')
            {
                $('#results').html(result['buttonsCount']);
            }
            else if (gameType == 'cashorcrash')
            {
                $('#betResult').hide();
            }
            else if (gameType == 'crazycoinflip')
            {
                createImgCCF(participants, result);
            }
            else if (gameType == 'crazypachinko')
            {
                createImgCPK(participants, result);
            }
            else if (gameType == 'balloonrace')//for balloonrace
            {
                createImgBalloonRace(participants, result);
            } 
            else if (gameType == 'videopoker' || gameType == 'rng-videopoker')
            {
                $('#multipleBet').hide();
                $('#score').hide();
                $('#hand').hide();
                $('#betResult').hide();

                var tdTxnIdName = document.createElement('td');
                var tdTxnIdDetail = document.createElement('td');

                var trTitle = document.createElement('tr');
                var tdCard = document.createElement('td');
                var tdAmount = document.createElement('td');
                var tdType = document.createElement('td');
                var tdNetCash = document.createElement('td');

                var tr = document.createElement('tr');
                var td = document.createElement('td');
                var div = document.createElement('div');
                var table = document.createElement('table');
                var tbody = document.createElement('tbody');


                $(tdTxnIdName).attr('colspan','6');
                $(tdTxnIdDetail).attr('colspan','6');

                $(tdTxnIdName).css({
                    'font-size': '14px',
                    'background-color': '#f0f0f0',
                    'font-weight': 'bold' 
                });
                
                $(tdTxnIdDetail).css({
                    'font-size': '14px'
                });


                $(tdTxnIdName).html(locale['txn_id']);
                $(tdTxnIdDetail).html(participants['bets'][0]['transactionId']);

                $('#images').append(tdTxnIdName);
                $('#images').append(tdTxnIdDetail);
                $('#images').attr('class','emptyContent');
                                
                $(table).attr('style','width:100%');
                $(tbody).attr('id', 'betDetailVideoPoker');
                $(div).attr('style','max-height:350px;overflow:auto; padding:5px');
                $(td).attr('colspan','12');
                $(table).append(tbody);
                $(div).append(table);
                $(td).append(div);
                $(tr).append(td);
                $(tr).attr('class', 'removeClass');
                $('#multipleBet').after(tr);

                $(tdCard).html(locale['result']);
                $(tdType).html(locale['type']);
                $(tdAmount).html(locale['amount']);
                $(tdNetCash).html(locale['netcash']);
                $(tdCard).attr('style','font-weight:bold');
                $(tdType).attr('style','font-weight:bold');
                $(tdAmount).attr('style','font-weight:bold');
                $(tdNetCash).attr('style','font-weight:bold');
                $(tdCard).attr('colspan','2');
                $(tdType).attr('colspan','2');
                $(tdAmount).attr('colspan','2');
                $(tdNetCash).attr('colspan','2');
                $(trTitle).append(tdCard);
                $(trTitle).append(tdType);
                $(trTitle).append(tdAmount);
                $(trTitle).append(tdNetCash);
                $('#betDetailVideoPoker').append(trTitle);

            }
            else if (gameType == 'powerball' || gameType == 'lightninglotto' || gameType == 'rng-lightninglotto')
            {
                document.getElementById('results').innerHTML = 'Regular Ball : <br>{';
                $.each(result['drawnBallNumbers'], function( index, value ) 
                {
                    if (index == 4) 
                        document.getElementById('results').innerHTML += value + '}<br>';
                    else
                        document.getElementById('results').innerHTML += value + ', ';
                });

                var total = 0;
                for (var i = 0; i < result['drawnBallNumbers'].length; i++)
                {
                    total += result['drawnBallNumbers'][i];
                }

                if(gameType == 'powerball')
                {
                    document.getElementById('results').innerHTML += 'Total Ball : <br>{' + total + '}<br>';
                }

                document.getElementById('results').innerHTML += 'Power Ball : <br>{' + result['drawnPowerBallNumber'] + '}<br>';

                if(gameType == 'lightninglotto' || gameType == 'rng-lightninglotto')
                {
                    document.getElementById('results').innerHTML += 'Lightning Numbers : <br>{';
                    $.each(result['lightningNumbers'], function( index, value ) 
                    {
                        if (index < result['lightningNumbers'].length - 1) 
                            document.getElementById('results').innerHTML += value + ', ';
                        else
                            document.getElementById('results').innerHTML += value + '}<br>'; 
                    });
                }
            }
            else if (gameType == 'lightningball')
                {
                    $('#multipleBet').hide();
                    $('#score').hide();
                    $('#hand').hide();

                    var tdTxnIdName = document.createElement('td');
                    var tdTxnIdDetail = document.createElement('td');

                    var trTitle = document.createElement('tr');
                    var tdCard = document.createElement('td');
                    var tdAmount = document.createElement('td');
                    var tdType = document.createElement('td');
                    var tdNetCash = document.createElement('td');

                    var tr = document.createElement('tr');
                    var td = document.createElement('td');
                    var div = document.createElement('div');
                    var table = document.createElement('table');
                    var tbody = document.createElement('tbody');


                    $(tdTxnIdName).attr('colspan','6');
                    $(tdTxnIdDetail).attr('colspan','6');

                    $(tdTxnIdName).css({
                        'font-size': '14px',
                        'background-color': '#f0f0f0',
                        'font-weight': 'bold' 
                    });
                    
                    $(tdTxnIdDetail).css({
                        'font-size': '14px'
                    });


                    $(tdTxnIdName).html(locale['txn_id']);
                    $(tdTxnIdDetail).html(participants['bets'][0]['transactionId']);

                    $('#images').append(tdTxnIdName);
                    $('#images').append(tdTxnIdDetail);
                    $('#images').attr('class','emptyContent');
                                    
                    $(table).attr('style','width:100%');
                    $(tbody).attr('id', 'betDetailVideoPoker');
                    $(div).attr('style','max-height:350px;overflow:auto; padding:5px');
                    $(td).attr('colspan','12');
                    $(table).append(tbody);
                    $(div).append(table);
                    $(td).append(div);
                    $(tr).append(td);
                    $(tr).attr('class', 'removeClass');
                    $('#multipleBet').after(tr);

                    $(tdCard).html(locale['tickets']);
                    $(tdType).html(locale['matchnumbers']);
                    $(tdAmount).html(locale['payoutratio']);
                    $(tdNetCash).html(locale['totalpayout']);
                    $(tdCard).attr('style','font-weight:bold');
                    $(tdType).attr('style','font-weight:bold');
                    $(tdAmount).attr('style','font-weight:bold');
                    $(tdNetCash).attr('style','font-weight:bold');
                    $(tdCard).attr('colspan','2');
                    $(tdType).attr('colspan','2');
                    $(tdAmount).attr('colspan','2');
                    $(tdNetCash).attr('colspan','2');
                    $(trTitle).append(tdCard);
                    $(trTitle).append(tdType);
                    $(trTitle).append(tdAmount);
                    $(trTitle).append(tdNetCash);
                    $('#betDetailVideoPoker').append(trTitle);


                    document.getElementById('results').innerHTML = locale['drawnball'] +': <br>{';
                    $.each(result['drawnBallNumbers'], function( index, value ) 
                    {
                        if (index == 4) 
                            document.getElementById('results').innerHTML += value + '}<br><br>';
                        else
                            document.getElementById('results').innerHTML += value + ', ';
                    });
    
                    var total = 0;
                    for (var i = 0; i < result['drawnBallNumbers'].length; i++)
                    {
                        total += result['drawnBallNumbers'][i];
                    }
    
                    document.getElementById('results').innerHTML += locale['powerball'] + ': ' + result['drawnPowerBall']['number'] 
                        + ',' + locale['multiplier'] + ' : ' + result['drawnPowerBall']['multiplier']+ '<br>';
    
                    // if(gameType == 'lightningball')
                    // {
                    //     document.getElementById('results').innerHTML += 'Lightning Numbers : <br>{';
                    //     $.each(result['lightningNumbers'], function( index, value ) 
                    //     {
                    //         if (index < result['lightningNumbers'].length - 1) 
                    //             document.getElementById('results').innerHTML += value + ', ';
                    //         else
                    //             document.getElementById('results').innerHTML += value + '}<br>'; 
                    //     });
                    // }
                }
            else if (gameType == 'stockmarket')
            {
                createStockChart(data);
            }
            else if (gameType == 'holdem')
            {
                evoCasinoHoldem(data);
            }
            else if (gameType == 'rng-hilo')//for First Person HiLo
            {
                createRngHilo(data);
            }
            else if (gameType == 'trp')
            {
                evoTripleCardPoker(data);
            }
            else if (gameType == 'crazyballs') // For Crazy Balls
            {
                //$('#multipleBet').hide();
                $('#score').hide();
                $('#hand').hide();

                var tdTxnIdName = document.createElement('td');
                var tdTxnIdDetail = document.createElement('td');

                var trTitle = document.createElement('tr');
                var tdCardName = document.createElement('td');
                var tdCard = document.createElement('td');
                var tdMultiplier = document.createElement('td');
                var tdAmount = document.createElement('td');
                var tdNetCash = document.createElement('td');

                var tr = document.createElement('tr');
                var td = document.createElement('td');
                var div = document.createElement('div');
                var table = document.createElement('table');
                var tbody = document.createElement('tbody');

                var number = ['Megaball', 'NormalBall', 'multiplier'];

                $(tdTxnIdName).attr('colspan','6');
                $(tdTxnIdDetail).attr('colspan','6');

                $(tdTxnIdName).html(locale['txn_id']);
                $(tdTxnIdDetail).html(participants['bets'][0]['transactionId']);

                $('#images').append(tdTxnIdName);
                $('#images').append(tdTxnIdDetail);
                $('#images').attr('class','emptyContent');
                                
                $(table).attr('style','width:100%');
                $(tbody).attr('id', 'betDetailMegaBall');
                $(div).attr('style','max-height:350px;overflow:auto; padding:5px');
                $(td).attr('colspan','12');
                $(table).append(tbody);
                $(div).append(table);
                $(td).append(div);
                $(tr).append(td);
                $(tr).attr('class', 'removeClass');
                $('#multipleBet').before(tr);

                $(tdCardName).html(locale['bet']);
                $(tdCard).html(locale['card']);
                $(tdMultiplier).html(locale['multiplier']);
                $(tdAmount).html(locale['amount']);
                $(tdNetCash).html(locale['netcash']);
                $(tdCard).attr('style','font-weight:bold');
                $(tdAmount).attr('style','font-weight:bold');
                $(tdNetCash).attr('style','font-weight:bold');

                $(tdCardName).attr('colspan','2');
                $(tdCard).attr('colspan','3');
                $(tdMultiplier).attr('colspan','2');
                $(tdAmount).attr('colspan','2');
                $(tdNetCash).attr('colspan','2');

                $(trTitle).append(tdCardName);
                $(trTitle).append(tdCard);
                $(trTitle).append(tdMultiplier);
                $(trTitle).append(tdAmount);
                $(trTitle).append(tdNetCash);
                $('#betDetailMegaBall').append(trTitle);

                document.getElementById('results').innerHTML = 'DrawnBalls : {';
                $.each(result['drawnBalls'], function( index, value ) 
                {
                    if (index == 9) 
                        document.getElementById('results').innerHTML += value + '<br>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;';
                    else if (index == 19) 
                        document.getElementById('results').innerHTML += value + ' }<br>';
                    else
                        document.getElementById('results').innerHTML += value + ',';
                });

                // $.each(result['drawnBalls'], function( index, value ) 
                // {
                //     document.getElementById('results').innerHTML += 'Crazyball : ' + result['drawnBalls'][index]['number']
                //         + ',' + 'Multiplier : ' + result['multiplier']['value']+ '<br>';
                // });

            }
            else 
            {
                getGameResult(participants, data['data']['result'],resultName,gameType);
            }
              
            if (gameType == 'megaball' || gameType == 'rng-megaball') 
            {
                for (var i = 0; i < participants['cards'].length; i++) 
                { 
                    betDetailMegaBall(participants,i,result);
                }
                totalStake = participants['bets'][0]['stake'];
                totalPayout = participants['bets'][0]['payout'];
            }
            else if (gameType == 'lightningball')
                {
                    // Descending sort by payout
                    var originalData = participants['cards'];
                    console.log(participants['cards']);
                    originalData.sort((a, b) => Number(b.payout) - Number(a.payout));
    
                    for (var i = 0; i < participants['cards'].length; i++)
                    {
                        betDetailLightningBall(participants,i,result);
                        console.log(participants['cards']);
                    }
                    totalStake = participants['bets'][0]['stake'];
                    totalPayout = participants['bets'][0]['payout'];
                }
            else if (gameType == 'monopolybigballer') 
            {
                betDetailBigBall(participants,result);
                for (var i = 0; i < participants['bets'].length; i++) 
                { 
                    if (participants['bets'][i]['transactionId'] == txnId) 
                    {
                        totalStake = totalStake + participants['bets'][i]['stake'];
                        totalPayout = totalPayout + participants['bets'][i]['payout'];
                    }
                }
            }
            else if (gameType == 'goldbarroulette')
            {
                for (var i = 0; i < participants['bets'].length; i++) 
                { 
                    createBet(participants,i,gameType);
                    if (participants['bets'][i]['equityBet']) 
                    {
                        //stake and payout for gold bar 
                        totalStake = totalStake + (participants['bets'][i]['equityBet']['stake'] * participants['balances']['entryBalances'][0]['cashValue']);
                        totalPayout = totalPayout + (participants['bets'][i]['equityBet']['payout'] * participants['balances']['entryBalances'][0]['cashValue']);
                    }
                    else
                    {
                        //normal stake and payout 
                        totalStake = totalStake + participants['bets'][i]['stake'];
                        totalPayout = totalPayout + participants['bets'][i]['payout'];
                    }   
                }
            }
            else if (gameType == 'crazycoinflip' || gameType == 'crazypachinko' || gameType == 'balloonrace'|| gameType == 'rng-hilo' || gameType == 'lightningstorm' || gameType == 'liveslotdealnodeal')
            {
                for (var i = 0; i < participants['bets'].length; i++) 
                { 
                    createBet(participants,i,gameType);
                    totalStake = totalStake + participants['bets'][i]['stake'];
                    totalPayout = totalPayout + participants['bets'][i]['payout'];
                    
                    if (gameType == 'lightningstorm')
                        {
                            $('#betResult').hide();
                            $('#wheelResult').show();
                            $('#trWheelPosition').show();
                            $('#trWheelType').show();
                            $('#trFinalMultiplier').show();

                            wheelPosition = result['wheelResult']['wheelSector']['position'];
                            wheelType = result['wheelResult']['wheelSector']['type'];
                            finalMultiplier = participants['totalMultiplier'];

                            $('#wheelPosition').html(wheelPosition);
                            $('#wheelType').html(wheelType);
                            $('#finalMultiplier').html(finalMultiplier);
                        }
                }
            }
            else if (gameType == 'videopoker' || gameType == 'rng-videopoker')
            {
                betDetailVideoPoker(participants,result);
                totalStake = participants['bets'][0]['stake'];
                totalPayout = participants['bets'][0]['payout'];
            }
            else if (gameType == 'crazyballs') 
            {
                //Show the card result
                for (var i = 0; i < participants['cards'].length; i++) 
                { 
                    betDetailCrazyBall(participants,i,result);
                }

                // Show No data for Crazy Ball Card Preview
                if (participants['cards'].length == 0)
                {
                    var trEmpty = document.createElement('tr');
                    var tdEmpty = document.createElement('td');

                    $(tdEmpty).attr('colspan','12');
                    $(tdEmpty).html('No data');
                    $(trEmpty).attr('class', 'removeClass text-center');
                    $(trEmpty).append(tdEmpty);
                    $("#betDetailMegaBall").append(trEmpty);

                }

                for (var i = 0; i < participants['bets'].length; i++) 
                {
                    createBet(participants,i,gameType);
                    totalStake += participants['bets'][i]['stake'];
                    totalPayout += participants['bets'][i]['payout'];
                }

            }
            else
            {
                for (var i = 0; i < data['data']['participants'].length; i++) 
                { 
                    for (var j = 0; j < data['data']['participants'][i]['bets'].length; j++) 
                    {
                        // if (data['data']['participants'][i]['bets'][j]['transactionId'] == txnId || prdId == '5001') 
                        if (data['data']['participants'][i]['bets'][j]['transactionId'] || prdId == '5001') 
                        {
                            createBet(data['data']['participants'][i],j,gameType);
                            totalStake = totalStake + data['data']['participants'][i]['bets'][j]['stake'];
                            totalPayout = totalPayout + data['data']['participants'][i]['bets'][j]['payout'];
                        }
                    }
                }
            }
        }

        $('#totalBet').html(utils.formatMoney(totalStake) );
        $('#payment').html(utils.formatMoney(totalPayout) );
        $('#net').html(utils.formatMoney(totalPayout - totalStake) );
    }
    else
    {   
        $('#modal-table').hide();
        $("#modal-json").show();
        $("#modal-json").html(JSON.stringify(data));
    }
}

function betDetail(gameType, participants, result, resultName)
{
    var resultFirstBonus;
    var resultSecondBonus;

    if( gameType == "blackjack"){
        resultName[0]="dealer";
        resultName[1]="seats";
    }

    if (gameType != 'freebet'  && gameType != 'scalableblackjack' && gameType != 'powerscalableblackjack' && gameType != 'rng-lightningscalablebj' && gameType != 'funfun21scalablebj' && gameType != 'scalablebetstackerbj' && gameType != 'lightningscalablebj')
    {
        for (var i = 0; i < result[resultName[0]]['cards'].length ; i++) 
        {
            var img = result[resultName[0]]['cards'][i];
            var imageDealer = document.createElement('img');        
            $(imageDealer).attr('class', 'imgStyle')
            $(imageDealer).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $('#playerhand').append(imageDealer);
        }

        if(gameType == 'blackjack')
        {
            $('#playerhand').css('background', 'linear-gradient(to bottom, white, #ffffff)');
            $('#dealerhand').css({
                'background': 'linear-gradient(to bottom, #f0f0f0, #ffffff)',
                'color': 'black',
            });            
        }


        // if (gameType == 'classicfreebet') 
        // {
        //     $('#dealerhand').html(locale['dealer_hand']);
        // }

        if (gameType == 'dhp') 
        {
            $('#playerhand').append('<br/><label>'+ result[resultName[0]]['type'] +'</label>');

            $('#score').show();
            var thFirstBetTitle = document.createElement('th');
            var thFirstBonusTitle = document.createElement('th');
            var thFirstBet = document.createElement('th');
            var thFirstBonus = document.createElement('th');
            var thSecondBetTitle = document.createElement('th');
            var thSecondBonusTitle = document.createElement('th');

            $(thFirstBetTitle).attr('colspan','6');
            $(thFirstBonusTitle).attr('colspan','6');
            $(thSecondBetTitle).attr('colspan','6');
            $(thSecondBonusTitle).attr('colspan','6');
            $(thFirstBetTitle).attr('style','text-align:center');
            $(thFirstBonusTitle).attr('style','text-align:center');
            $(thSecondBetTitle).attr('style','text-align:center');
            $(thSecondBonusTitle).attr('style','text-align:center');
            $(thFirstBet).attr('colspan','6');
            $(thFirstBonus).attr('colspan','6');
            $(thFirstBet).attr('id','thFirstBet');
            $(thFirstBonus).attr('id','thFirstBonus');

            $(thFirstBetTitle).html(locale['firsthandBet']);
            $(thFirstBonusTitle).html(locale['firsthandBonus']);
            $(thSecondBetTitle).html(locale['secondhandBet']);
            $(thSecondBonusTitle).html(locale['secondhandBonus']);

            $('#images').append(thFirstBetTitle);
            $('#images').append(thFirstBonusTitle);
            $('#cards').append(thSecondBetTitle);
            $('#cards').append(thSecondBonusTitle);
            $('#cardTitle').append(thFirstBet);
            $('#cardTitle').append(thFirstBonus);
            $('#cardTitle').attr('style','text-align: center;');

            if (!participants['sideBetFirstHandBonus']) 
            {
                resultFirstBonus = 'Player No bet On this';
                
                $('#thFirstBonus').append('<br/><label>'+ ' : '+ resultFirstBonus +'</label>');
            }
            else 
                resultFirstBonus = 'Player ' + participants['sideBetFirstHandBonus']['result'];

            if (!participants['sideBetSecondHandBonus']) 
            {
                resultSecondBonus = 'Player No bet On this';
                $('#scoreplayer').append('<br/><label>'+ ' : '+ resultSecondBonus +'</label>');
            }
            else 
                resultSecondBonus = 'Player ' + participants['sideBetSecondHandBonus']['result'];

            //for player 1 bet
            for (var i = 0; i < result[resultName[2]]['player']['cards'].length; i++) 
            {
                var img = result[resultName[2]]['player']['cards'][i];
                var imageBet = document.createElement('img');
                
                $(imageBet).attr('class', 'imgStyle')
                $(imageBet).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
                $('#thFirstBet').append(imageBet);
                

                if (result[resultName[2]]['player']['cards'].length -1 == i) 
                    $('#thFirstBet').append('<br/><label>'+ result[resultName[2]]['player']['type'] +' : '+ result[resultName[2]]['outcome'] +'</label>');
            }
            //for player 1 bonus
            if (result[resultName[2]]['bonus'].hasOwnProperty('holdemBonus') ) 
            {
                for (var i = 0; i < result[resultName[2]]['bonus']['holdemBonus']['cards'].length; i++) 
                {
                    var img = result[resultName[2]]['bonus']['holdemBonus']['cards'][i];
                    var imageBonus = document.createElement('img');        
                    $(imageBonus).attr('class', 'imgStyle')
                    $(imageBonus).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
                    $(thFirstBonus).append(imageBonus);
                    $('#cardTitle').append(thFirstBonus);
                }
            }
            
            //for player 2 bet
            for (var i = 0; i < result[resultName[3]]['player']['cards'].length; i++) 
            {
                var img = result[resultName[3]]['player']['cards'][i];
                var image2Bet = document.createElement('img');        
                $(image2Bet).attr('class', 'imgStyle')
                $(image2Bet).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
                $('#scoredealer').append(image2Bet);

                if (result[resultName[3]]['player']['cards'].length -1 == i) 
                    $('#scoredealer').append('<br/><label>'+ result[resultName[3]]['player']['type'] +' : '+ result[resultName[3]]['outcome'] +'</label>');
            }
            //for player 2 bonus
            if (result[resultName[3]]['bonus'].hasOwnProperty('holdemBonus') ) 
            {
                for (var i = 0; i < result[resultName[3]]['bonus']['holdemBonus']['cards'].length; i++) 
                {
                    var img = result[resultName[3]]['bonus']['holdemBonus']['cards'][i];
                    var image2Bonus = document.createElement('img');        
                    $(image2Bonus).attr('class', 'imgStyle')
                    $(image2Bonus).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
                    $('#scoreplayer').append(image2Bonus);
                }
            }
        }
    }
    else 
    {
        $('#bjTitle').hide();
        $('#betResult').show();
        $('#results').html(locale['dealer']+' '+result['dealerHand']['score']+' '+locale['player']+' '+participants['hands']['hand1']['score']);
        $('#dealerhand').html(locale['dealer_hand']);
        $('#playerhand').html(locale['player_hand']);
        
        var tdImgDealer = document.createElement('td');
        var tdImgPlayer = document.createElement('td');
        $(tdImgDealer).attr('colspan','6');
        $(tdImgPlayer).attr('colspan','6');

        for (var i = 0; i <result['dealerHand']['cards'].length; i++) 
        {
            var img = result['dealerHand']['cards'][i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle')
            $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdImgDealer).append(imageGame);
            $('#images').append(tdImgDealer);
        }

        for (var i = 0; i <participants['hands']['hand1']['cards'].length; i++) 
        {
            var img = participants['hands']['hand1']['cards'][i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle')
            $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdImgPlayer).append(imageGame);
            $('#images').append(tdImgPlayer);
        }

        if (participants['hands']['hand1']['decisions'] != '') 
        {
            var getSplit = participants['hands']['hand1']['decisions'][0].type.substr(-5);
            //define the decision with double and add as normal bet
            if (participants['hands']['hand1']['decisions'][0].type == 'FreeDouble') 
            {
                var tr = document.createElement('tr');
                var tdBet = document.createElement('td');
                var tdAmount = document.createElement('td');
                var tdNet = document.createElement('td');
                var tdTxn = document.createElement('td');
                var tdTime = document.createElement('td');

                $(tdBet).attr('colspan','2');
                $(tdAmount).attr('colspan','2');
                $(tdNet).attr('colspan','2');
                $(tdTxn).attr('colspan','3');
                $(tdTime).attr('colspan','3');

                $(tdBet).html(participants['hands']['hand1']['decisions'][0].type);
                $(tdAmount).html(utils.formatMoney(0) );
                $(tdNet).html(utils.formatMoney(participants['freeBet'].payout) );
                $(tdTxn).html('-');
                $(tdTime).html('-');

                $(tr).append(tdBet);
                $(tr).append(tdAmount);
                $(tr).append(tdNet);
                $(tr).append(tdTxn);
                $(tr).append(tdTime);
                $(tr).attr('class', 'removeClass');
                $('#multipleBet').after(tr);
                // totalPayout = totalPayout + participants['freeBet'].payout;
            }
            //define the decision with split and add as images bet
            else if (getSplit.match('Split')) 
            {
                $('#bjTitle').show();
                var tr = document.createElement('tr');
                var tdBet = document.createElement('td');
                var tdAmount = document.createElement('td');
                var tdNet = document.createElement('td');
                var tdScore = document.createElement('td');
                var tdImg = document.createElement('td');
                var tdResult = document.createElement('td');
                var tdTxn = document.createElement('td');
                var tdTime = document.createElement('td');

                $(tdBet).attr('colspan','2');
                $(tdAmount).attr('colspan','1');
                $(tdNet).attr('colspan','1');
                $(tdScore).attr('colspan','1');
                $(tdResult).attr('colspan','1');
                $(tdImg).attr('colspan','3');
                $(tdTxn).attr('colspan','2');
                $(tdTime).attr('colspan','1');

                //define the amount free or not
                if (participants['hands']['hand1']['decisions'][0].type == 'FreeSplit' )
                {
                    $(tdAmount).html(utils.formatMoney(0) );
                    $(tdNet).html(utils.formatMoney(participants['freeBet'].payout) );
                    $(tdTxn).html('-');
                    $(tdTime).html('-');
                }
                else
                {
                    for (var i = 0; i < participants['bets'].length; i++) 
                    {
                        if (participants['bets'][i]['description'] == 'Split Bet' )
                        {
                            $(tdAmount).html(utils.formatMoney(participants['bets'][i]['stake']) );
                            $(tdNet).html(utils.formatMoney(participants['bets'][i]['payout'] - participants['bets'][i]['stake']) );
                            $(tdTxn).html(participants['bets'][i]['transactionId']);
                            $(tdTime).html(participants['bets'][i]['placedOn'].replace(/T|Z/gi,' ').split('.')[0]);
                        }
                    }
                }

                $(tdBet).html(participants['hands']['hand1']['decisions'][0].type);
                $(tdScore).html(participants['hands']['hand2']['score']);
                $(tdResult).html(participants['hands']['hand2']['outcome']);
                
                $(tr).append(tdBet);
                $(tr).append(tdAmount);
                $(tr).append(tdNet);
                $(tr).append(tdScore);
                $(tr).append(tdResult);
                //get the card for split
                for (var i = 0; i <participants['hands']['hand2']['cards'].length; i++) 
                {
                    var img = participants['hands']['hand2']['cards'][i];
                    var imageGame = document.createElement('img');
                    $(imageGame).attr('class', 'imgStyle')
                    $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
                    $(tdImg).append(imageGame);
                    $(tr).append(tdImg);
                }
                
                $(tr).append(tdTxn);
                $(tr).append(tdTime);
                $(tr).attr('class', 'removeClass');
                $('#bjTitle').after(tr);
            }
        }
    }
}

function createBet(participants,j,gameType)
{
    if (!participants['bets'][j]['code'].includes('Pick_Fee')) 
    {
        var tr = document.createElement('tr');
        var tdBet = document.createElement('td');
        var tdAmount = document.createElement('td');
        var tdNet = document.createElement('td');
        var tdTxn = document.createElement('td');
        var tdTime = document.createElement('td');

        $(tdBet).attr('colspan','2');
        $(tdAmount).attr('colspan','2');
        $(tdNet).attr('colspan','2');
        $(tdTxn).attr('colspan','3');
        $(tdTime).attr('colspan','3');

        if (gameType = 'crazyballs'){

            // Sort by betCode
            var betDetails =participants['bets'];
            betDetails.sort((a, b) => {
              if (a.code < b.code) {
                return 1;
              }
              if (a.code > b.code) {
                return -1;
              }
              return 0;
            });

            $.each(betDetails, function(index, val)
            {
               $(tdBet).html(participants['bets'][j]['code']);
            });

       }

        if (gameType == 'goldbarroulette') 
        {
            if (participants['bets'][j]['equityBet']) 
            {
                $(tdAmount).html(utils.formatMoney(participants['bets'][j]['equityBet']['stake'] * participants['balances']['entryBalances'][0]['cashValue']));
            }
            else
            {
                $(tdAmount).html(utils.formatMoney(participants['bets'][j]['stake']));
            }
        }
        else
        {
            $(tdAmount).html(utils.formatMoney(participants['bets'][j]['stake']));
        }

        if (participants['freeBet'] && participants['bets'][j]['code'] == 'FBBJ_Main') 
        {
            var net = participants['bets'][j]['payout'] - participants['bets'][j]['stake'] - participants['freeBet'].payout;
            $(tdNet).html(utils.formatMoney(net));
        }
        else
        {
            var net = participants['bets'][j]['payout'] - participants['bets'][j]['stake'];
            $(tdNet).html(utils.formatMoney(net));
        }

        if (gameType != 'gonzotreasurehunt' || gameType != 'gonzotreasuremap') 
        {
            $(tdTxn).html(participants['bets'][j]['transactionId']);
            $(tdBet).html(participants['bets'][j]['description']);
        }
        else
        {
            var arrPayout = participants['multipliers'][participants['bets'][j]['code']] ?? 0;
            var totalPayout  = 0;
            var payoutID = participants['bets'][j]['code']+'_payout';
            var betID = participants['bets'][j]['code']+'_bet';
            var totalPick = (arrPayout.length > 0) ? ' = '+ arrPayout.length+ 'picks':'';
            $(tdBet).attr('id', betID);
            $(tdTxn).attr('id', payoutID);

            for (var i = 0; i < arrPayout.length; i++) 
            { 
                totalPayout = totalPayout + arrPayout[i];
            } 
            $(tdTxn).html(totalPayout);
            $(tdBet).html(participants['bets'][j]['description'] + totalPick);
        }

        if (gameType == 'deadoralivesaloon') 
        {
            // 2 Spades
            var img = participants['bets'][j]['description'];

            // var numberOfCard = img.substring(0,1);
            // var cardDetails = img.substring(2,3);
            
            var myArray = img.split(" ");
            var numberCard = myArray[0];
            var cardDetails = myArray[1];
            var cardDetails = cardDetails.substring(0,1);

            if (numberCard == '10') 
            {
                numberCard = 'T';
            }
            else if (numberCard == 'Ace') 
            {
                numberCard = 'A';
            }
            else if (numberCard == 'Jack') 
            {
                numberCard = 'J';
            }
            else if (numberCard == 'King') 
            {
                numberCard = 'K';
            }
            else if (numberCard == 'Queen') 
            {
                numberCard = 'Q';
            }

            var img = numberCard.concat(cardDetails);
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle');
            $(imageGame).attr('style', 'width:50px');
            $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdTxn).html(participants['bets'][j]['transactionId']);
            $(tdBet).html(imageGame);
        }

        if (gameType == 'stockmarket')
        {
            $(tdBet).html(participants['bets'][j]['code']);

            var commission = participants['commission'] ?? 0;

            var trCommission      = document.createElement('tr');
            var tdCommissionTitle = document.createElement('td');
            var tdCommissionData  = document.createElement('td');

            $(tdCommissionTitle).attr('colspan', '6');
            $(tdCommissionData).attr('colspan', '6');

            $(tdCommissionTitle).html(locale['commission']);
            $(tdCommissionData).html(commission);

            $(trCommission).append(tdCommissionTitle);
            $(trCommission).append(tdCommissionData);

            if (j === 0)
            {
                $('#totalNet').after(trCommission);
            }
        }

        if (gameType == 'trp')
        {
            return;
        }

        if (gameType = 'liveslotdealnodeal'){
            $(tdBet).html(participants['bets'][j]['code']);
        }

        var color = (net > 0) ? 'color:blue':'color:red';
        $(tdBet).attr('style','color:black; font-size: 14px;');
        $(tdAmount).attr('style','color:black; font-size: 14px;');
        $(tdNet).attr('style',`${color}; font-size: 14px;`);
        $(tdTxn).attr('style','color:black; font-size: 14px;');
        $(tdTime).attr('style','color:black; font-size: 14px;');

        $(tdTime).html(participants['bets'][j]['placedOn'].replace(/T|Z/gi,' ').split('.')[0]);

        $(tr).append(tdBet);
        $(tr).append(tdAmount);
        $(tr).append(tdNet);
        $(tr).append(tdTxn);
        $(tr).append(tdTime);
        $(tr).attr('class', 'removeClass');
        $(tr).attr('id', participants['bets'][j]['code']);
        $('#multipleBet').after(tr);
    }
}

function createSideBetBJ(participants,j,result)
{
    $('#bjTitle').show();
    var tr = document.createElement('tr');
    var tdBet = document.createElement('td');
    var tdAmount = document.createElement('td');
    var tdNet = document.createElement('td');
    var tdScore = document.createElement('td');
    var tdImg = document.createElement('td');
    var tdResult = document.createElement('td');
    var tdTxn = document.createElement('td');
    var tdTime = document.createElement('td');
    var resultStatus = (Math.sign(participants['bets'][j]['payout'] - participants['bets'][j]['stake']) == 1) ? 'Win':'Lose';
    var tailCode = participants['bets'][j]['code'].split(/_(.+)/)[1];
    var net = participants['bets'][j]['payout'] - participants['bets'][j]['stake'];

    $(tdBet).attr('colspan','2');
    $(tdAmount).attr('colspan','1');
    $(tdNet).attr('colspan','1');
    $(tdScore).attr('colspan','1');
    $(tdResult).attr('colspan','1');
    $(tdImg).attr('colspan','3');
    $(tdTxn).attr('colspan','2');
    $(tdTime).attr('colspan','1');

    $(tdBet).html(participants['bets'][j]['code']);
    $(tdAmount).html(utils.formatMoney(participants['bets'][j]['stake']));
    $(tdNet).html(utils.formatMoney(net));
    $(tdScore).html('-');
    $(tdResult).html(resultStatus);
    $(tdTxn).html(participants['bets'][j]['transactionId']);
    $(tdTime).html(participants['bets'][j]['placedOn'].replace(/T|Z/gi,' ').split('.')[0]);

    var color = (net > 0) ? 'color:blue':'color:red';
    $(tdBet).attr('style','color:black; font-size: 14px;');
    $(tdAmount).attr('style','color:black; font-size: 14px;');
    $(tdNet).attr('style',`${color}; font-size: 14px;`);
    $(tdTxn).attr('style','color:black; font-size: 14px;');
    $(tdTime).attr('style','color:black; font-size: 14px;');

    $(tr).append(tdBet);
    $(tr).append(tdAmount);
    $(tr).append(tdNet);
    $(tr).append(tdScore);
    $(tr).append(tdResult);

    if (tailCode == 'AnyPair') 
    {
        for (var i = 0; i <2; i++) 
        {
            var img = result['dealtToPlayer'][i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle')
            $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdImg).append(imageGame);
            $(tr).append(tdImg);
        }
    }
    else if (tailCode == '21_plus_3' || tailCode == 'Hot3')
    {
        for (var i = 0; i <2; i++) 
        {
            var img = result['dealtToPlayer'][i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle')
            $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdImg).append(imageGame);
            $(tr).append(tdImg);
        }
        var img = result['dealerHand']['cards'][0];
        var imageGame = document.createElement('img');
        $(imageGame).attr('class', 'imgStyle')
        $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
        $(tdImg).append(imageGame);
        $(tr).append(tdImg);
    }
    else if (tailCode == 'BustIt')
    {
        for (var i = 0; i <result['dealerHand']['cards'].length; i++) 
        {
            var img = result['dealerHand']['cards'][i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle')
            $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdImg).append(imageGame);
            $(tr).append(tdImg);
        }
    }
    else if (tailcode = 'MatchDealer'){
        for (var i = 0; i <result['dealerHand']['cards'].length; i++) 
            {
                var img = result['dealerHand']['cards'][i];
                var imageGame = document.createElement('img');
                $(imageGame).attr('class', 'imgStyle')
                $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
                $(tdImg).append(imageGame);
                $(tr).append(tdImg);
            }
    }
    else if (tailcode = 'PerfectPair')
        {
            for (var i = 0; i <2; i++) 
                {
                    var img = result['dealtToPlayer'][i];
                    var imageGame = document.createElement('img');
                    $(imageGame).attr('class', 'imgStyle')
                    $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
                    $(tdImg).append(imageGame);
                    $(tr).append(tdImg);
                }
        }

    $(tr).append(tdTxn);
    $(tr).append(tdTime);
    $(tr).attr('class', 'removeClass');
    $('#bjTitle').after(tr);
}

function createBetWithImg(participants,j,chidlSeat,resultName,result,getSplit)
{
    if (getSplit != 'Split')
        getSplit = '';
    var tr = document.createElement('tr');
    var tdBet = document.createElement('td');
    var tdAmount = document.createElement('td');
    var tdNet = document.createElement('td');
    var tdScore = document.createElement('td');
    var tdImg = document.createElement('td');
    var tdResult = document.createElement('td');
    var tdTxn = document.createElement('td');
    var tdTime = document.createElement('td');
    var ptcp = participants['bets'][j]['code'];
    var net = participants['bets'][j]['payout'] - participants['bets'][j]['stake'];

    $(tdBet).attr('colspan','2');
    $(tdAmount).attr('colspan','1');
    $(tdNet).attr('colspan','1');
    $(tdScore).attr('colspan','1');
    $(tdResult).attr('colspan','1');
    $(tdImg).attr('colspan','4');
    $(tdTxn).attr('colspan','1');
    $(tdTime).attr('colspan','1');
    
    $(tdBet).html(participants['bets'][j]['description']);
    $(tdAmount).html(utils.formatMoney(participants['bets'][j]['stake']) );
    $(tdNet).html(utils.formatMoney(net) );
    $(tdTxn).html(participants['bets'][j]['transactionId']);
    $(tdTime).html(participants['bets'][j]['placedOn'].replace(/T|Z/gi,' ').split('.')[0]);

    var color = (net > 0) ? 'color:blue':'color:red';
    $(tdBet).attr('style','color:black; font-size: 14px;');
    $(tdAmount).attr('style','color:black; font-size: 14px;');
    $(tdNet).attr('style',`${color}; font-size: 14px;`);
    $(tdTxn).attr('style','color:black; font-size: 14px;');
    $(tdTime).attr('style','color:black; font-size: 14px;');
    
    $(tr).append(tdBet);
    $(tr).append(tdAmount);
    $(tr).append(tdNet);
    $(tr).append(tdScore);
    $(tr).append(tdResult);

    if (ptcp.includes( 'Play' ) == true || ptcp.includes( 'DoubleDown' ) == true || ptcp.includes( 'Split' ) == true) 
    {
        $(tdScore).html(result[resultName[1]] ['Seat'+chidlSeat+getSplit]['score']);
        $(tdResult).html(result[resultName[1]] ['Seat'+chidlSeat+getSplit]['outcome']);
        for (var i = 0; i <result[resultName[1]]['Seat'+chidlSeat+getSplit]['cards'].length; i++) 
        {
            var img = result[resultName[1]]['Seat'+chidlSeat+getSplit]['cards'][i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle');
            $(imageGame).attr('style', 'width:50px');
            $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdImg).append(imageGame);
            $(tr).append(tdImg);
        }
    }
    else if (ptcp.includes( 'InsuranceSeat' ) == true) 
    {
        $(tdScore).html('-');
        $(tdResult).html('-');

        $(tdImg).html('-');
        $(tr).append(tdImg);
    }
    else
    {
        var type = (ptcp.includes( 'PerfectPair' ) == true ) ? 'sideBetPerfectPair':'sideBet21p3';
        $(tdScore).html(participants['seats']['Seat'+chidlSeat][type]['score']);
        $(tdResult).html(participants['seats']['Seat'+chidlSeat][type]['result']);
        for (var i = 0; i <participants['seats']['Seat'+chidlSeat][type]['cards'].length; i++) 
        {
            var img = participants['seats']['Seat'+chidlSeat][type]['cards'][i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle');
            $(imageGame).attr('style', 'width:50px');
            $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdImg).append(imageGame);
            $(tr).append(tdImg);
        }
    }
    $(tr).append(tdTxn);
    $(tr).append(tdTime);
    $(tr).attr('class', 'removeClass');
    $('#bjTitle').after(tr);
}

function betDetailLightningBall(participants,j,result)
{
    // var tr = document.createElement('tr');
    // var tdTicket = document.createElement('td');
    // var tdMatchedNumber = document.createElement('td');
    // var tdPayoutRatio = document.createElement('td');
    // var tdPayout = document.createElement('td');
   
    var trTitle = document.createElement('tr');
    var tdCard = document.createElement('td');
    var tdType = document.createElement('td');
    var tdAmount = document.createElement('td');
    var tdNetCash = document.createElement('td');

    $(tdCard).attr('colspan','2');
    $(tdType).attr('colspan','2');
    $(tdAmount).attr('colspan','2');
    $(tdNetCash).attr('colspan','2');

    var table = document.createElement('table');
    var tbody = document.createElement('tbody');

    // Draw Power Balls Ticket
    var trPowerBall = document.createElement('tr');
    for (var i = 0; i < participants['cards'][j]['numbers'].length + 1; i++)
    {
        var td = document.createElement('td');
        var div = document.createElement('div');
        var hitNum = participants['cards'][j]['numbers'][i];
        var powerNum = participants['cards'][j]['powerNumber'];

        var name = j.toString() + i.toString();
        $(div).attr('id', name);

        // Circle out hit numbers
        if (result['drawnBallNumbers'].includes( hitNum ) == true )
        {
            $(div).html(hitNum);
            $(div).attr('style', 'border:1px solid black;text-align: center;border-radius: 50%;padding:2px;background-color: black;color: white;width:22px;');
        }
        else
        {
            $(div).html(hitNum);
            $(div).attr('style','width:22px');
        }

        // For power number
        if (i == 5)
        {
            // Circle out hit numbers
            if (powerNum == result['drawnPowerBall']['number'])
            {
                $(div).html(powerNum);
                $(div).attr('style', 'border:1px solid black;text-align: center;border-radius: 50%;padding:2px;background-color: white;width:22px;');
            }
            else
            {
                $(div).html(powerNum);
                $(div).attr('style','width:22px');
            }
            $(td).attr('style','border:0.5px solid; background-color: #F7F7CF;text-align: center');
        }
        else
        {
            $(td).attr('style','border:0.5px solid; background-color: #EFE09F;text-align: center');
        }
        $(td).append(div);
        $(trPowerBall).append(td);
    }
    $(tbody).append(trPowerBall);

    $(table).attr('style', 'border: 2px solid');
    $(table).append(tbody);
    $(tdCard).append(table);

    $(tdType).html(participants['cards'][j]['matchedNumbers']);
    $(tdAmount).html(participants['cards'][j]['payoutRatio']);
    $(tdNetCash).html( utils.formatMoney(participants['cards'][j]['payout']) );

    $(trTitle).append(tdCard);
    $(trTitle).append(tdType);
    $(trTitle).append(tdAmount);
    $(trTitle).append(tdNetCash);
    $('#betDetailVideoPoker').append(trTitle);
}

function getGameResult(participants, result,resultName,gameType)
{
    var tdPlayer = document.createElement('td');
    var tdDealer = document.createElement('td');

    $(tdDealer).attr('colspan','6');
    $(tdPlayer).attr('colspan','6');
    
    if(gameType == 'betwithstreamer')
    {
        $('#score').hide();
    }
    else if(gameType == 'liveslotdealnodeal')
        {
            $('#score').hide();
        }
    else if (typeof(result[resultName[0]]['score']) !== 'undefined') 
    {
        $('#score').show();
        $('#scoredealer').html(locale['score'] + ' : ' + '<span style="color: blue;">' +  result[resultName[0]]['score'] + '</span>');
        $('#scoreplayer').html(locale['score'] + ' : ' + '<span style="color: blue;">' +  result[resultName[1]]['score'] + '</span>');
    }
    else if ((gameType == 'baccarat' || gameType == 'rng-baccarat') && typeof(result['banker']['score']) !== 'undefined')
    {
        $('#score').show();
        $('#scoredealer').html(locale['score'] + ' : '  + '<span style="color: blue;">' +  result['banker']['score'] + '</span>');
        $('#scoreplayer').html(locale['score'] + ' : '  + '<span style="color: blue;">' +  result['player']['score'] + '</span>');
    }
    else
    {
        $('#score').hide();
    }

    if (result['eliminatedBoxes']) // Deal or Not Deal
    {
        var resultDND = result['eliminatedBoxes'];

        $(tdDealer).attr('colspan','12');
        var number = ['first', 'second', 'third','fourth', 'fifth'];

        for (var i = 0; i < resultDND.length; i++) 
        {
            var div = document.createElement('div');
            var engNumber = number[i];
            var dealNumber = resultDND[i];
            document.getElementById('results').innerHTML += engNumber + ' : (' +dealNumber + ') ';

            for (var j = 0; j < dealNumber.length; j++) 
            {
                var image = document.createElement('img'); 
                $(image).attr('class', 'imgStyle')
                $(image).attr('src','/evol/dist/images/cardsEVO/dnd_' + dealNumber[j] + '.png');
                $(div).attr('style', 'border:1px solid black; width:137px; margin:8px; float:left; height:133px');
                $(div).append(image);
                $(tdDealer).append(div);
                $('#images').append(tdDealer);
            }
        }
    }
    else if (result['gameRoundResult']) // Deal or Not Deal
    {
        
        var resultDND = result['gameRoundResult']['eliminatedBoxes'];

        $(tdDealer).attr('colspan','12');
        var number = ['first', 'second', 'third','fourth', 'fifth'];

        for (var i = 0; i < resultDND.length; i++) 
        {
            var div = document.createElement('div');
            var engNumber = number[i];
            var dealNumber = resultDND[i];
            document.getElementById('results').innerHTML += engNumber + ' : (' +dealNumber + ') ';

            for (var j = 0; j < dealNumber.length; j++) 
            {
                var image = document.createElement('img'); 
                $(image).attr('class', 'imgStyle')
                $(image).attr('src','/evol/dist/images/cardsEVO/dnd_' + dealNumber[j] + '.png');
                $(div).attr('style', 'border:1px solid black; width:137px; margin:8px; float:left; height:133px');
                $(div).append(image);
                $(tdDealer).append(div);
                $('#images').append(tdDealer);
            }
        }
    }
    else if (result['outcome']) 
    { 
        if (typeof(result['outcome']) == 'object') 
        {
            if (result['outcome']['wheelResult'] == '2r' || result['outcome']['wheelResult'] == '4r')
            {
                var length = result['outcome']['boardMoves'].length -1;
                $('#results').html(result['outcome']['wheelResult']+' with Total Multiplier : '+ result['outcome']['boardMoves'][length]['totalMultiplier']);
            }
            else if (result['outcome']['wheelResult']['wheelSector'])
            {
                var image = document.createElement('img'); 
                $(tdDealer).attr('colspan','12');
                var wheelSector = result['outcome']['wheelResult']['wheelSector'];
                $(image).attr('class', 'imgStyle')
                $(image).attr('src','/evol/dist/images/cardsEVO/' + wheelSector + '.png');
                $(tdDealer).append(image);
                $('#images').append(tdDealer);

                var multiplier =  participants['totalMultiplier'] ?? result['outcome']['wheelResult']['wheelSector'];
                document.getElementById('results').innerHTML += '<u><b>TOP SLOT</b></u>';
                document.getElementById('results').innerHTML += '<br>'+ JSON.stringify(result['outcome']['topSlot']).replace(/"|{|}|]/gi, " ");
                document.getElementById('results').innerHTML += '<br>'+ '<u><b>WHEEL RESULT</b></u>';
                document.getElementById('results').innerHTML += '<br>'+ result['outcome']['wheelResult']['wheelSector']+' with Total Multiplier : '+ multiplier;
            }
            else
            {  
                if (typeof(result['outcome']['reSpins'][0]) != 'undefined')
                {
                    var payoutRatio = result['outcome']['reSpins'][0]['totalMultiplier'] * result['outcome']['payoutRatio'];
                    document.getElementById('results').innerHTML += result['outcome']['wheelResult'];
                    document.getElementById('results').innerHTML += '<br>Multiplier : ' + payoutRatio;
                }
                else if (result['outcome']['type'] == 'CashPrize')
                {
                    var payoutRatio = result['outcome']['payoutRatio'];
                    document.getElementById('results').innerHTML += result['outcome']['type'];
                    document.getElementById('results').innerHTML += '<br>Payout Ratio : ' + payoutRatio;
                }
                else
                    $('#results').html(result['outcome']['wheelResult']);
            }
        }
        else
        {
            if (gameType == 'deadoralivesaloon') 
            {
                var img = result['outcome'];
                var img = img.substring(0,2);
                var imageGame = document.createElement('img');
                $(imageGame).attr('class', 'imgStyle');
                $(imageGame).attr('style', 'width:60px');
                $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
                $('#results').html(imageGame);
              
                document.getElementById('results').innerHTML += '<br>';

                if (participants.hasOwnProperty('totalMultiplier')) 
                {
                    document.getElementById('results').innerHTML += locale['total_multiplier'] + ' X '+participants['totalMultiplier'];
                }
            }
            else
            {
                $('#results').html(result['outcome']);
            }
        }

        if (result.hasOwnProperty('multipliers')) 
        {
            document.getElementById('results').innerHTML += '<br>';

            if (gameType == 'dragontiger' && result['multipliers']){
                var suits; 
                document.getElementById('results').innerHTML += '<br>';
                if(result['multipliers']['suit'] == 'S'){
                    suits =locale['spade'];
                }
                else if(result['multipliers']['suit'] == 'H'){
                    suits = locale['heart'];
                }
                else if(result['multipliers']['suit'] == 'C'){
                    suits = locale['club'];
                }
                else
                {
                    suits = locale['diamond'];
                }
                document.getElementById('results').innerHTML += suits + ' x ' + result['multipliers']['value'] ;
            }
            
            $.each(result['multipliers']['cards'], function( index, value ) 
            {
                document.getElementById('results').innerHTML += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+index+'.png"><label>&nbsp;X'+ value +'</label>&emsp;' ;
            });
        }
    }
    else if (result['first'])
    { 
        var diceNumber;
        var number = ['first', 'second', 'third'];
        for (var i = 0; i < 3; i++) 
        {
            var engNumber = number[i];
            var intNumber = result[engNumber];
            var image = document.createElement('img'); 
            diceNumber = result['winningNumbers']['name' + i];
            document.getElementById('results').innerHTML += number[i] + " : (" +intNumber + ") ";

            $(tdDealer).attr('colspan','12');
            intNumber = ('c1_0' + intNumber).slice(-5);
            $(image).attr('class', 'imgStyle')
            $(image).attr('src','/evol/dist/images/cardsEVO/' + intNumber + '.png');
            $(tdDealer).append(image);
            $('#images').append(tdDealer);
        }
        var replace = JSON.stringify(result['luckyNumbers']).replace(/"|{|}|]/gi, " ");
        var multiple = replace.split(',');

        $.each(multiple, function( index, value ) 
        {
            document.getElementById('results').innerHTML += "<br>" + value;
        });
    }
    else if (result['rolls']) // for craps
    {
        var lengthRoll = result['rolls'].length;
        $(tdDealer).attr('colspan','12');
        var number = ['first', 'second'];

        $.each(result['rolls'], function( index, value ) 
        {
            var div = document.createElement('div');
            for (var i = 0; i < 2; i++) 
            {
                var imgDice = document.createElement('img'); 
                var diceNumber = value['result'][number[i]];
                $(imgDice).attr('src','/evol/dist/images/cardsEVO/c1_0' + diceNumber + '.png');
                $('#results').append(imgDice);
            }
        });
    }
    else if (result['totalMultiplier'])
    {
        //extra chilli spin
    }
    else if (result['wheelResult'])
    {
        var totalMultiplier = participants.hasOwnProperty('totalMultiplier') ? ' with Total Multiplier : '+ participants['totalMultiplier']:'';
        $('#results').html(result['wheelResult']['wheelSector']['value'] + totalMultiplier );
    }
    else if (result == '')
    {
        //bet with streamer doesn't have result
        var stake = participants['bets'][0]['stake'];
        var payout = participants['bets'][0]['payout'];

        var streamerResult; 

        if (stake > payout) 
        { 
            streamerResult = locale['lose']; 
        } 
        else if (stake < payout) 
        { 
            streamerResult = locale['win']; 
        } 
        else
        { 
            streamerResult = locale['tie'];
        }

        $('#results').html(streamerResult);
    }
    else  
    { 
        delete result['outcomes'][0]['spin'];
        var outcome = JSON.stringify(result['outcomes']);
        outcome = outcome.replace(/"|{|}|]/gi, " ");
        outcome = outcome.replace('[', " ");
        document.getElementById('results').innerHTML += outcome;

        $('#generatedEquity').hide();

        //Gold Bar Roulette will have this extra field
        if (participants['wonEquity']) 
        {
            $('#generatedEquity').show();
            var wonEquity = JSON.stringify(participants['wonEquity']);      
            wonEquity = wonEquity.replace(/"|{|}|]/gi, " ");
            wonEquity = wonEquity.replace('[', " ");
            document.getElementById('generatedEquityColumn').innerHTML = wonEquity;
        }

        $.each(result['luckyNumbers'], function( index, value ) 
        {
            value = value +1;
            document.getElementById('results').innerHTML += '<br>' + 'Number : ' + index + ' Multiplier : ' + value;
        });
    }

    if(result == '')
    {
        $('#hand').hide();
    }
    else if (result[resultName[0]]['name']) 
    {
        $('#hand').show();
        $('#dealerhand').html(result[resultName[0]]['name']);
        $('#playerhand').html(result[resultName[1]]['name']);
    }
    else if (gameType == 'baccarat' || gameType == 'rng-baccarat')
    {
        $('#hand').show();
        $('#dealerhand').html(result['banker']['name']);
        $('#playerhand').html(result['player']['name']);
    }
    else
        $('#hand').hide();

    if (result == '')
    {
        //bet with streamer doesn't have result
    }
    else if (result[resultName[0]]['card']) 
    {
        var imgDragon = result[resultName[0]]['card'];
        var imgTiger = result[resultName[1]]['card'];
        var imageDragon = document.createElement('img'); 
        var imageTiger = document.createElement('img');

        $(imageDragon).attr('class', 'imgStyle')
        $(imageDragon).attr('src','/evol/dist/images/cardsEVO/' + imgDragon + '.png');
        $(tdDealer).append(imageDragon);
        $('#images').append(tdDealer);

        $(imageTiger).attr('class', 'imgStyle')
        $(imageTiger).attr('src','/evol/dist/images/cardsEVO/' + imgTiger + '.png');
        $(tdPlayer).append(imageTiger);
        $('#images').append(tdPlayer);
    }
    // for baccarat
    else if ((gameType == 'baccarat' || gameType == 'rng-baccarat') && result['banker']['cards'])
    {
        for (var i = 0; i < result['banker']['cards'].length ; i++) 
        {
            var img = result['banker']['cards'][i];
            var imageDealer = document.createElement('img');
            $(imageDealer).attr('class', 'imgStyle')
            $(imageDealer).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdDealer).append(imageDealer);
            $('#images').append(tdDealer);
        }

        for (var i = 0; i < result['player']['cards'].length; i++) 
        {
            var img = result['player']['cards'][i];
            var imagePlayer = document.createElement('img');
            $(imagePlayer).attr('class', 'imgStyle')
            $(imagePlayer).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdPlayer).append(imagePlayer);
            $('#images').append(tdPlayer);
        }
    }
    // for bacbo
    else if (result[resultName[0]]['first'] && result[resultName[0]]['second'])
    {
        var name = ['first','second'];
        for (var i = 0; i < 2 ; i++) 
        {
            var img = result[resultName[0]][name[i]];
            var imageDealer = document.createElement('img');
            $(imageDealer).attr('class', 'imgStyle')
            $(imageDealer).attr('src','/evol/dist/images/cardsEVO/c1_0' + img + '.png');
            $(tdDealer).append(imageDealer);
            $('#images').append(tdDealer);
        }

        for (var i = 0; i < 2; i++) 
        {
            var img = result[resultName[1]][name[i]];
            var imagePlayer = document.createElement('img');
            $(imagePlayer).attr('class', 'imgStyle')
            $(imagePlayer).attr('src','/evol/dist/images/cardsEVO/c1_0' + img + '.png');
            $(tdPlayer).append(imagePlayer);
            $('#images').append(tdPlayer);
        }
    }
    else if (result['joker'])
    {
            var img = result['joker'];
            var image = document.createElement('img');
            $(tdDealer).attr('colspan','12');
            $(image).attr('class', 'imgStyle')
            $(image).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdDealer).append(image);
            $('#images').append(tdDealer);
    }
     else if (result['screenReels'])
    {
            var img = result['screenReels'];
            var image = document.createElement('img');
            $(tdDealer).attr('colspan','12');
            $(image).attr('class', 'imgStyle')
            $(image).attr('src','/images/crazycoinflip/' + img + '.png');
            $(tdDealer).append(image);
            $('#images').append(tdDealer);
    }

    // for teen patti
    else if ((gameType == 'teenpatti' || gameType == 'teenpatti2020') && result['dealer']['cards'])    
    {
       
        for (var i = 0; i < result['cards']['dealer'].length ; i++) 
        {
            var img = result['cards']['dealer'][i];
            var imageDealer = document.createElement('img');
            $(imageDealer).attr('class', 'imgStyle')
            $(imageDealer).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdDealer).append(imageDealer);
            $('#images').append(tdDealer);
        }

        for (var i = 0; i < result['cards']['player'].length; i++) 
        {
            var img = result['player']['cards'][i];
            var imagePlayer = document.createElement('img');
            $(imagePlayer).attr('class', 'imgStyle')
            $(imagePlayer).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdPlayer).append(imagePlayer);
            $('#images').append(tdPlayer);
        }
    }
    
    if (participants['subType'] == 'redenvelopev2')
    {
        if (typeof result['redEnvelopePayoutsV2'] !== 'undefined') 
        {
            $.each(participants['bets'], function( index, value ) 
            {
                if (typeof result['redEnvelopePayoutsV2'][value['code']] !== 'undefined') 
                {
                    if (value['payout'] > value['stake']) 
                    {
                        var angpaoPayout = result['redEnvelopePayoutsV2'][value['code']];
                        var trAngPao = document.createElement('tr');
                        var tdAngPao = document.createElement('td');
                        var tdAngPao1 = document.createElement('td');

                        $(tdAngPao).attr('colspan','6');
                        $(tdAngPao).attr('style','font-size:14px;font-weight: bold;background-color:#f0f0f0');
                        $(tdAngPao1).attr('colspan','6');
                        $(tdAngPao).html(locale['redEnvelopePayouts'] +" "+ value['description']);
                        $(tdAngPao1).html(angpaoPayout);

                        $(trAngPao).attr('class','removeClass');

                        $(trAngPao).append(tdAngPao);
                        $(trAngPao).append(tdAngPao1);
                        $('#betResult').after(trAngPao);
                    }
                }
            });
        }
    }
    else
    {
        if (typeof result['redEnvelopePayouts'] !== 'undefined') 
        {
            $.each(participants['bets'], function( index, value ) 
            {
                if (typeof result['redEnvelopePayouts'][value['code']] !== 'undefined') 
                {
                    if (value['payout'] > value['stake']) 
                    {
                        var angpaoPayout = result['redEnvelopePayouts'][value['code']];
                        var trAngPao = document.createElement('tr');
                        var tdAngPao = document.createElement('td');
                        var tdAngPao1 = document.createElement('td');

                        $(tdAngPao).attr('colspan','6');
                        $(tdAngPao).attr('style','font-size:14px;font-weight: bold;background-color:#f0f0f0');
                        $(tdAngPao1).attr('colspan','6');
                        $(tdAngPao).html(locale['redEnvelopePayouts'] +" "+ value['description']);
                        $(tdAngPao1).html(angpaoPayout);

                        $(trAngPao).attr('class','removeClass');

                        $(trAngPao).append(tdAngPao);
                        $(trAngPao).append(tdAngPao1);
                        $('#betResult').after(trAngPao);
                    }
                }
            });
        }
    }
}

function getGameResultSbc(participants, result,resultName)
{
    $('#hand').hide();
    $('#score').hide();
    var td3Card = document.createElement('td');
    var td5Card = document.createElement('td');
    var td7Card = document.createElement('td');
    var image3Card  = document.createElement('img');
    var image5Card  = document.createElement('img');
    var image7Card  = document.createElement('img');
    
    $(td3Card).attr('colspan','4');
    $(td5Card).attr('colspan','4');
    $(td7Card).attr('colspan','4');

    $(td3Card).attr('id','numberCard0');
    $(td5Card).attr('id','numberCard1');
    $(td7Card).attr('id','numberCard2');

    var th3Card = document.createElement('th');
    var th5Card = document.createElement('th');
    var th7Card = document.createElement('th');
   
    $(th3Card).attr('colspan','4');
    $(th5Card).attr('colspan','4');
    $(th7Card).attr('colspan','4');

    $(th3Card).html(result.outcome['threeCards']['nameOfCard']+' : '+result.outcome['threeCards']['handType']);
    $(th5Card).html(result.outcome['fiveCards']['nameOfCard']+ ' : '+result.outcome['fiveCards']['handType']);
    $(th7Card).html(result.outcome['sevenCards']['nameOfCard']+' : '+result.outcome['sevenCards']['handType']);
    
    $('#images').append(th3Card);
    $('#images').append(th5Card);
    $('#images').append(th7Card);

    for (var i = 0; i < result['cardsDealt'].length; i++) 
    {
        var imageResult = document.createElement('img');
        var imgResult = result['cardsDealt'][i];
        $(imageResult).attr('class', 'imgStyleSbc')
        $(imageResult).attr('src','/evol/dist/images/cardsEVO/' + imgResult + '.png');
        $('#results').append(imageResult);
    }

    for (var i = 0; i < Object.keys(result.outcome).length; i++) 
    {
        var numberOfCard = ['threeCards', 'fiveCards', 'sevenCards'];
        var engNumberCard = numberOfCard[i];
        var getData = result.outcome[engNumberCard]['cards'];

        $('#cardTitle').append(td3Card);
        $('#cardTitle').append(td5Card);
        $('#cardTitle').append(td7Card);

        for (var j = 0; j < result.outcome[engNumberCard]['cards'].length; j++) 
        {
            var imageCard = document.createElement('img');
            var imgCard = result.outcome[engNumberCard]['cards'][j];
            $(imageCard).attr('class', 'imgStyleSbc');
            $(imageCard).attr('src','/evol/dist/images/cardsEVO/' + imgCard + '.png');
            $('#numberCard'+i).append(imageCard);
        }
    }
}

function betDetailMegaBall(participants,j,result)
{
    var tr = document.createElement('tr');
    var tdBet = document.createElement('td');
    var tdAmount = document.createElement('td');
    var tdNet = document.createElement('td');
    var tdTxn = document.createElement('td');
    var tdTime = document.createElement('td');

    $(tdBet).attr('colspan','2');
    $(tdAmount).attr('colspan','2');
    $(tdNet).attr('colspan','2');

    var table = document.createElement('table');
    var tbody = document.createElement('tbody');

    var bingoStrikePattern = {
        'DLR' : ['00','11','22','33','44'],
        'DRL' : ['04','13','22','31','40'],
        'C1' : ['00','01','02','03','04'],
        'C2' : ['10','11','12','13','14'],
        'C3' : ['20','21','22','23','24'],
        'C4' : ['30','31','32','33','34'],
        'C5' : ['40','41','42','43','44'],
        'R1' : ['00','10','20','30','40'],
        'R2' : ['01','11','21','31','41'],
        'R3' : ['02','12','22','32','42'],
        'R4' : ['03','13','23','33','43'],
        'R5' : ['04','14','24','34','44']
    };

    for (var i = 0; i < participants['cards'][j]['grid'].length; i++) 
    {
        var trMegaBall = document.createElement('tr');
        for (var count = 0; count < 5; count++) 
        {
            var td = document.createElement('td');
            var div = document.createElement('div');
            var hitNum = participants['cards'][j]['grid'][count][i];

            var name = j.toString() + count.toString() + i.toString();
            var megaball2nd = (result['drawnMegaBalls'].hasOwnProperty(1)) ? result['drawnMegaBalls'][1]['number']:'';
            $(div).attr('id', name);
            
            if (result['drawnBalls'].includes( hitNum ) == true ) 
            {
                var strike = document.createElement('strike');
                
                $(strike).html(hitNum.padStart(2,'0'));
                $(div).attr('style', 'border:1px solid;text-align: center;border-radius: 50%;padding:2px;background-color: #65BFB6;width:22px;');
                $(div).append(strike);
            }
            else if ( result['drawnMegaBalls'][0]['number'] == hitNum || megaball2nd == hitNum) 
            {
                var strike = document.createElement('strike');
                $(strike).html(hitNum.padStart(2,'0'));
                $(div).attr('class', 'multiplier');
                $(div).append(strike);
            }
            else if (hitNum == '-')
            {
                $(div).html(hitNum);
                $(div).attr('style', 'border:1px solid;text-align: center;border-radius: 50%;padding:2px;background-color: #65BFB6;width:22px;');
            }
            else
            {
                $(div).html(hitNum.padStart(2,'0'));
                $(div).attr('style','color:#BFECE2;width:22px');
            }
            
            $(td).attr('style','background-color: #134242;border:1px solid #0EA9A4');
            $(td).append(div);
            $(trMegaBall).append(td);
        }
        $(tbody).append(trMegaBall);
    }

    $(table).attr('style', 'border: 5px solid #149591');
    $(table).append(tbody);
    $(tdBet).append(table);

    var massageArr = participants['massageArr'][j];
    var netCash = massageArr['payout'] - massageArr['stake'];

    $(tdAmount).html(utils.formatMoney(massageArr['stake']));
    $(tdNet).html(utils.formatMoney(netCash));

    var color = (netCash > 0) ? 'color:blue':'color:red';
    $(tdBet).attr('style','color:black; font-size: 14px;');
    $(tdAmount).attr('style','color:black; font-size: 14px;');
    $(tdNet).attr('style', `${color}; font-size: 14px;`);
    $(tdTxn).attr('style','color:black; font-size: 14px;');
    $(tdTime).attr('style','color:black; font-size: 14px;');

    $(tr).append(tdBet);
    $(tr).append(tdAmount);
    $(tr).append(tdNet);
    $('#betDetailMegaBall').append(tr);
    
    $.each(participants['cards'][j]['winCombinations'], function( index, value ) 
    {
        $.each(bingoStrikePattern[value], function( index1, value1 ) 
        {
            var idForTD = j + value1;
            $('#' + idForTD).attr('style', 'border:1px solid;text-align: center;border-radius: 50%;padding:2px;background-color: turquoise;width:22px');
        });
    });
}

function betDetailBigBall(participants,result)
{
    var cardDetails = [];
    var extraDetails = [];
    var cardName = [];

    for (var i = 0; i < participants['cards'].length; i++) 
    {
        cardDetails[participants['cards'][i]['betCode']] = participants['cards'][i];

        extraDetails[participants['cards'][i]['name']] = { number : [], freeSpaces : [], multiplier : [], multiplierMap : [] };
        $.each(participants['cards'][i]['grid'], function( index, value ) 
        {
            $.each(value, function( index1, value1 ) 
            {
                extraDetails[participants['cards'][i]['name']]['number'].push(value1);
            });

        });

        cardName.push(participants['cards'][i]['name']);
    }

    for (var i = 0; i < result['bonusNumbers'].length; i++)
    {
        if(result['bonusNumbers'][i]['card'] == 'Bonus3')
        {
            cardDetails['MBB_3Rolls'] = result['bonusNumbers'][i];
            cardDetails['MBB_3Rolls']['name'] = result['bonusNumbers'][i]['card'];
        }

        if(result['bonusNumbers'][i]['card'] == 'Bonus5')
        {
            cardDetails['MBB_5Rolls'] = result['bonusNumbers'][i];
            cardDetails['MBB_5Rolls']['name'] = result['bonusNumbers'][i]['card'];
        }

        extraDetails[result['bonusNumbers'][i]['card']] = { number : [], freeSpaces : [], multiplier : [], multiplierMap : [] };

        $.each(result['bonusNumbers'][i]['numbers'], function( index1, value1 ) 
        {
            extraDetails[result['bonusNumbers'][i]['card']]['number'].push(value1);
        });
    }

    $.each(result['freeSpaces'], function( index, value ) 
    {
        if($.inArray(value.card, cardName) !== -1)
        {
            var actualIndex = value.index - 1;
            var newValue = extraDetails[value.card]['number'][actualIndex];
            extraDetails[value.card]['freeSpaces'].push(newValue);
        }
    });

    $.each(result['multipliers'], function( index, value ) 
    {
        if($.inArray(value.card, cardName) !== -1)
        {
            var actualIndex = value.index - 1;
            var newValue = extraDetails[value.card]['number'][actualIndex];
            var obj = { 'value' : newValue , 'multiplier' : value.value};
            extraDetails[value.card]['multiplier'].push(newValue);
            extraDetails[value.card]['multiplierMap'][newValue] = value.value;
        }
    });

    var notCardArray = ['MBB_3Rolls','MBB_5Rolls'];

    for (var i = 0; i < participants['bets'].length; i++) 
    {
        var tr = document.createElement('tr');
        var tdCard = document.createElement('td');
        var tdAmount = document.createElement('td');
        var tdNet = document.createElement('td');

        var cardStr = '';
        var boardCenterStr = '';
        var code = participants['bets'][i]['code'];
        var cardName = cardDetails[code]['name'];

        if (!notCardArray.includes(code))
        {
            var grid = cardDetails[code]['grid'];

            for(x = 0; x < 5; x++)
            {
                boardCenterStr += '<div style="display:flex;width:100%;justify-content:space-evenly">';
                for(j = 0; j < 5; j++)
                {
                    var value = grid[j][x];
                    var bgColor = '#f5f3ed';

                    var multiplierStr = '';

                    if(result['drawnBalls'].includes(value))
                    {
                        bgColor = '#d43f42;color:white';
                    }

                    if(value < 0)
                    {
                        value = Math.abs(value);
                        bgColor = '#6fd763';
                    }

                    if(extraDetails[cardName]['multiplier'].includes(value))
                    {
                        multiplierStr += '<div style="position:absolute;top:-5px;left:-5px;width:20px;height:10px;transform:rotate(-30deg);color:#ffc900;">'+ extraDetails[cardName]['multiplierMap'][value] +'x</div>';
                    }

                    boardCenterStr += '<div style="border-radius:50%;background-color:'+bgColor+';width:20px;height:20px;display:flex;align-items:center;justify-content:center;position:relative;">'+ multiplierStr + value+'</div>';
                }
                boardCenterStr += '</div>';
            }
            var boardStr = '<div style="background-color:#a69a80;margin:10px auto;border-radius:10px;width:150px;height:150px;display:flex;justify-content:space-evenly;align-items:center;flex-direction:column">'
                            + boardCenterStr + '</div>';

        }
        else
        {
            var grid = cardDetails[code]['numbers'];

            if(code == 'MBB_3Rolls')
            {   
                boardCenterStr += '<div style="display:flex;width:100%;justify-content:space-evenly;padding-top:10px;">';

                for(j = 0; j < 3; j++)
                {
                    var value = grid[j];
                    var bgColor = '#f5f3ed';

                    if(result['drawnBalls'].includes(value))
                    {
                        bgColor = '#d43f42;color:white';
                    }

                    if(extraDetails[cardName]['freeSpaces'].includes(value))
                    {
                        bgColor = '#6fd763';
                    }
                    boardCenterStr += '<div style="border-radius:50%;background-color:'+bgColor+';width:20px;height:20px;display:flex;align-items:center;justify-content:center;position:relative;">'+value+'</div>';
                }

                boardCenterStr += '</div>';

                var boardStr = '<div style="background:url(/evol/dist/images/cardsEVO/3rolls.png) no-repeat 0 0;background-size:100% 100%;position:relative;margin:10px auto;width:100px;height:200px">'
                            + boardCenterStr + '</div>';
            }

            if(code == 'MBB_5Rolls')
            {
                boardCenterStr += '<div style="display:flex;width:100%;justify-content:space-evenly;padding-top:10px;">';

                for(j = 0; j < 4; j++)
                {
                    var value = grid[j];
                    var bgColor = '#f5f3ed';

                    if(result['drawnBalls'].includes(value))
                    {
                        bgColor = '#d43f42;color:white';
                    }

                    if(extraDetails[cardName]['freeSpaces'].includes(value))
                    {
                        bgColor = '#6fd763';
                    }
                    boardCenterStr += '<div style="border-radius:50%;background-color:'+bgColor+';width:20px;height:20px;display:flex;align-items:center;justify-content:center;">'+value+'</div>';
                }

                boardCenterStr += '</div>';

                var boardStr = '<div style="background:url(/evol/dist/images/cardsEVO/5rolls.png) no-repeat 0 0;background-size:100% 100%;position:relative;margin:10px auto;width:100px;height:200px">'
                            + boardCenterStr + '</div>';
            }
            
        }

        cardStr += boardStr;
        cardStr += '<div style="text-align:center;">'+ participants['bets'][i]['description'] +'</div>';

        var amount = participants['bets'][i]['stake'];
        var net = participants['bets'][i]['payout'] - participants['bets'][i]['stake'];

        var color = (net > 0) ? 'color:blue':'color:red';
        $(tdCard).attr('style','color:black; font-size: 14px;');
        $(tdAmount).attr('style','color:black; font-size: 14px;');
        $(tdNet).attr('style', `${color}; font-size: 14px;`);

        $(tdCard).attr('colspan','2');
        $(tdAmount).attr('colspan','2');
        $(tdNet).attr('colspan','2');

        $(tdCard).html(cardStr);
        $(tdAmount).html(utils.formatMoney(amount));
        $(tdNet).html(utils.formatMoney(net));

        $(tr).append(tdCard);
        $(tr).append(tdAmount);
        $(tr).append(tdNet);
        $('#betDetailMegaBall').append(tr);
    }
}

// This is to create img detail for crazy coin flip
function createImgCCF(participants, result) 
{
    var div1 = document.createElement('div');
    var div2 = document.createElement('div');
    var div3 = document.createElement('div');
    var td = document.createElement('td');
    var coinWinMultiplier = (result != '') ? participants['qualificationResult']['coinWinMultiplier'] : '';

    $(td).addClass('text-center');
    $(td).attr('colspan', '12');

    for (var i = 0; i < participants['qualificationSpin']['screenReels'].length; i++) 
    {
        var img = participants['qualificationSpin']['screenReels'][i];
        var imageReels1 = document.createElement('img');
        var imageReels2 = document.createElement('img');
        var imageReels3 = document.createElement('img');

        var split1 = img.slice(0,1);
        var split2 = img.slice(1,2);
        var split3 = img.slice(2,3);
        
        $(imageReels1).attr('class', 'imgStyle');
        $(imageReels2).attr('class', 'imgStyle');
        $(imageReels3).attr('class', 'imgStyle');

        $(imageReels1).attr('src','/images/crazycoinflip/' + split1 + '.png');
        $(imageReels2).attr('src','/images/crazycoinflip/' + split2 + '.png');
        $(imageReels3).attr('src','/images/crazycoinflip/' + split3 + '.png');

        /* First row */
        $(div1).append(imageReels1);
        $(td).append(div1);
        /* Second row */
        $(div2).append(imageReels2);
        $(td).append(div2);
        /* Third row */
        $(div3).append(imageReels3);
        $(td).append(div3);
    }

    $('#images').append(td);

    if (coinWinMultiplier != '')
    {
        $('#results').html(result['outcome']['coinFlipResult'] + " X" + coinWinMultiplier);
    }
    else
    {
        $('#betResult').hide();
    }
}

// This is to create img detail for ballon race
function createImgBalloonRace(participants, result) 
{
    var div1 = document.createElement('div');
    var div2 = document.createElement('div');
    var div3 = document.createElement('div');
    var td = document.createElement('td');
    //var coinWinMultiplier = (result != '') ? participants['qualificationResult']['winMultiplier']['value'] : '';
    var coinWinMultiplier = '';

    if (result != ''){
        if ('winMultiplier' in participants['qualificationResult']){
            coinWinMultiplier = participants['qualificationResult']['winMultiplier']['value'];
        }
        else{
            coinWinMultiplier = ''
        }
    }
    else{
        coinWinMultiplier = '';
    }
    
    $(td).addClass('text-center');
    $(td).attr('colspan', '12');

    for (var i = 0; i < participants['qualificationSpin']['screenReels'].length; i++) 
    {
        var img = participants['qualificationSpin']['screenReels'][i];
 
        var imageReels1 = document.createElement('img');
        var imageReels2 = document.createElement('img');
        var imageReels3 = document.createElement('img');

        var split1 = img.slice(0,1);
        var split2 = img.slice(1,2);
        var split3 = img.slice(2,3);
        
        $(imageReels1).attr('class', 'imgStyle');
        $(imageReels2).attr('class', 'imgStyle');
        $(imageReels3).attr('class', 'imgStyle');

        $(imageReels1).attr('src','/images/balloonrace/' + split1 + '.png');
        $(imageReels2).attr('src','/images/balloonrace/' + split2 + '.png');
        $(imageReels3).attr('src','/images/balloonrace/' + split3 + '.png');

        /* First row */
        $(div1).append(imageReels1);
        $(td).append(div1);
        /* Second row */
        $(div2).append(imageReels2);
        $(td).append(div2);
        /* Third row */
        $(div3).append(imageReels3);
        $(td).append(div3);
    }

    $('#images').append(td);

    if (coinWinMultiplier != '')
    {
        $('#results').html(participants['qualificationResult']['winMultiplier']['color'] + " X " + coinWinMultiplier);
    }
    else
    {
        $('#betResult').hide();
    }
}

// Create img details for crazy pachinko
function createImgCPK(participants, result) 
{
    var div1 = document.createElement('div');
    var div2 = document.createElement('div');
    var div3 = document.createElement('div');
    var td = document.createElement('td');
    var coinWinMultiplier = (result != '') ? participants['qualificationResult']['coinWinMultiplier'] : '';

    $(td).addClass('text-center');
    $(td).attr('colspan', '12');

   for (var i = 0; i < participants['qualificationSpin']['screenReels'].length; i++) 
    {
        var row = participants['qualificationSpin']['screenReels'][i].length;
        var img = participants['qualificationSpin']['screenReels'][i];

        var imageReels1 = document.createElement('img');
        var imageReels2 = document.createElement('img');
        var imageReels3 = document.createElement('img');
        var imageReels4 = document.createElement('img');
        var imageReels5 = document.createElement('img');
        
        var split1 = img.slice(0,1);
        var split2 = img.slice(1,2);
        var split3 = img.slice(2,3);
        var split4 = img.slice(3,4);
        var split5 = img.slice(4,5);
        
        $(imageReels1).attr('class', 'imgStyle');
        $(imageReels2).attr('class', 'imgStyle');
        $(imageReels3).attr('class', 'imgStyle');
        $(imageReels4).attr('class', 'imgStyle');
        $(imageReels5).attr('class', 'imgStyle');

        $(imageReels1).attr('src','/images/crazypachinko/' + split1 + '.png');
        $(imageReels2).attr('src','/images/crazypachinko/' + split2 + '.png');
        $(imageReels3).attr('src','/images/crazypachinko/' + split3 + '.png');
        $(imageReels4).attr('src','/images/crazypachinko/' + split4 + '.png');
        $(imageReels5).attr('src','/images/crazypachinko/' + split5 + '.png');

        if(i == 0) 
        {
            $(div1).append(imageReels1);
            $(div1).append(imageReels2);
            $(div1).append(imageReels3);
            $(div1).append(imageReels4);
            $(div1).append(imageReels5);
            $(td).append(div1);
        }   
        else if(i == 1)
        {
            $(div2).append(imageReels1);
            $(div2).append(imageReels2);
            $(div2).append(imageReels3);
            $(div2).append(imageReels4);
            $(div2).append(imageReels5);
            $(td).append(div2);
        }
        else
        {   
            $(div3).append(imageReels1);
            $(div3).append(imageReels2);
            $(div3).append(imageReels3);
            $(div3).append(imageReels4);
            $(div3).append(imageReels5);
            $(td).append(div3);
        }

    }

    $('#images').append(td);
    $('#betResult').hide();
}

function betDetailVideoPoker(participants,result)
{ 
    for (var i = 0; i < participants['playerHands'].length; i++)
    {
        var tr = document.createElement('tr');
        var tdBet = document.createElement('td');
        var tdType = document.createElement('td');
        var tdAmount = document.createElement('td');
        var tdNet = document.createElement('td');

        $(tdBet).attr('colspan','2');
        $(tdType).attr('colspan','2');
        $(tdAmount).attr('colspan','2');
        $(tdNet).attr('colspan','2');

        var trPlayerHand = document.createElement('tr');
        var handType = participants['playerHands'][i]['handType'];
        var payout = participants['playerHands'][i]['payout'];
        var netCash = payout - participants['betPerHand'];
        
        for (var x = 0; x <  participants['playerHands'][i]['cardCodes'].length; x++)
        {
            var td = document.createElement('td');
            var div = document.createElement('div');
            var img = participants['playerHands'][i]['cardCodes'][x];
            var imageDealer = document.createElement('img');
            var tdImgDealer = document.createElement('td'); 
            
            $(imageDealer).attr('class', 'imgStyle')
            $(imageDealer).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
            $(tdBet).append(imageDealer);
            

        }
            $(tr).append(tdBet);
            $(tdType).html(handType);
            $(tdAmount).html(participants['betPerHand']);
            $(tdNet).html(netCash);

            var color = (netCash > 0) ? 'color:blue':'color:red';
            $(tdBet).attr('style','color:black; font-size: 14px;');
            $(tdAmount).attr('style','color:black; font-size: 14px;');
            $(tdType).attr('style','color:black; font-size: 14px;');
            $(tdNet).attr('style', `${color}; font-size: 14px;`);

            $(tr).append(tdType);
            $(tr).append(tdAmount);
            $(tr).append(tdNet);
            $('#betDetailVideoPoker').append(tr);
    }
}

// create image details for crazy balls
function betDetailCrazyBall(participants,j,result)
{
    var tr = document.createElement('tr');
    var tdBet = document.createElement('td');
    var tdAmount = document.createElement('td');
    var tdNet = document.createElement('td');
    var tdTxn = document.createElement('td');
    var tdTime = document.createElement('td');

    var tdCardName = document.createElement('td');
    var tdCard = document.createElement('td');
    var tdMultiplier = document.createElement('td');

    $(tdCardName).attr('colspan','2');
    $(tdBet).attr('colspan','3');
    $(tdMultiplier).attr('colspan','2');
    $(tdAmount).attr('colspan','2');
    $(tdNet).attr('colspan','2');

    var table = document.createElement('table');
    var tbody = document.createElement('tbody');

    var bingoStrikePattern = {
        'DLR' : ['00','11','22','33','44'],
        'DRL' : ['04','13','22','31','40'],
        'C1' : ['00','01','02','03','04'],
        'C2' : ['10','11','12','13','14'],
        'C3' : ['20','21','22','23','24'],
        'C4' : ['30','31','32','33','34'],
        'C5' : ['40','41','42','43','44'],
        'R1' : ['00','10','20','30','40'],
        'R2' : ['01','11','21','31','41'],
        'R3' : ['02','12','22','32','42'],
        'R4' : ['03','13','23','33','43'],
        'R5' : ['04','14','24','34','44']
    };

     // Evo card data upside down
     var ticketResult = participants['cards'][j]['grid'];
     ticketResult = ticketResult.map((_, index) => [
       ticketResult[0][index],
       ticketResult[1][index],
       ticketResult[2][index],
       ticketResult[3][index],
       ticketResult[4][index]
     ]);

    for (var i = 0; i < 5; i++) 
    {
        var trCrazyBall = document.createElement('tr');
        for (var count = 0; count < 5; count++) 
        {
            var td = document.createElement('td');
            var div = document.createElement('div');
            var hitNum = ticketResult[count][i];
            //paddedHitNum = String(hitNum).padStart(2, '0');

            var name = j.toString() + count.toString() + i.toString();
            var crazyball2nd = (result['drawnBalls'].hasOwnProperty(1)) ? result['drawnBalls'][1]['number']:'';
            $(div).attr('id', name);
            
            //Circle out hit numbers or negative numbers
            if (result['drawnBalls'].includes( hitNum ) == true || hitNum < 0 ) 
            {
                var strike = document.createElement('strike');
                
                $(div).html(hitNum);
                //$(strike).html(paddedHitNum);
                $(div).attr('style', 'border:1px solid;text-align: center;border-radius: 50%;padding:2px;background-color: #65BFB6;width:25px;font-size:10px;');
                
            }
            // else if ( result['drawnBalls'][0]['number'] == hitNum || crazyball2nd == hitNum) 
            // {
            //     var strike = document.createElement('strike');
            //     //$(strike).html(hitNum.padStart(2,'0'));
            //     $(strike).html(paddedHitNum);
            //     $(div).attr('class', 'multiplier');
            //     $(div).append(strike);
            // }
            else if (hitNum == '-')
            {
                $(div).html(hitNum);
                $(div).attr('style', 'border:1px solid;text-align: center;border-radius: 50%;padding:2px;background-color: #65BFB6;width:25px;font-size:10px;');
            }
            else
            {
                $(div).html(hitNum);
                $(div).attr('style','color:#BFECE2;width:25px;font-size:10px;');
            }
            
            $(td).attr('style','background-color: #134242;border:1px solid #0EA9A4');
            $(td).append(div);
            $(trCrazyBall).append(td);
        }
        $(tbody).append(trCrazyBall);
    }

    $(table).attr('style', 'border: 5px solid #149591');
    $(table).append(tbody);
    $(tdBet).append(table);

    
    var netCash = 0;

    $(tdCardName).html(participants['cards'][j]['betCode']);
    $(tdMultiplier).html(participants['cards'][j]['payoutRatio']);

    // Show Stake and Payout
    var calResult = participants['bets'];
    $.each(calResult, function( index, val )
    {
        if (val['code'] == participants['cards'][j]['betCode'])
        {
            netCash = val['payout'] - val['stake'];
            $(tdAmount).html( utils.formatMoney(val['stake']) );
            $(tdNet).html( utils.formatMoney(netCash) );
        }
    });

    var color = (netCash > 0) ? 'color:blue':'color:red';
    $(tdBet).attr('style','color:black; font-size: 14px;');
    $(tdAmount).attr('style','color:black; font-size: 14px;');
    $(tdNet).attr('style', `${color}; font-size: 14px;`);
    $(tdTxn).attr('style','color:black; font-size: 14px;');
    $(tdTime).attr('style','color:black; font-size: 14px;');

    $(tr).append(tdCardName);
    $(tr).append(tdBet);
    $(tr).append(tdMultiplier);
    $(tr).append(tdAmount);
    $(tr).append(tdNet);
    $('#betDetailMegaBall').append(tr);
    
    $.each(participants['cards'][j]['winCombinations'], function( index, value ) 
    {
        $.each(bingoStrikePattern[value], function( index1, value1 ) 
        {
            var idForTD = j + value1;
            $('#' + idForTD).attr('style', 'border:1px solid;text-align: center;border-radius: 50%;padding:2px;background-color: turquoise;width:22px');
        });
    });
}

function getDataTableDetailsBG(data, username)
{
    try
    {
        $("#modalDetails").modal('show'); 
    }
    catch(e)
    {
        
    }
    $('#modal-table').show();
    $('#modal-json').hide();
    $('#modal-mg').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $('#modal-dowin').hide();
    $('#modal-dd').hide();
    $('#modal-og').hide();
    $('#modal-rizal').hide();
    $('#modal-arn').hide();
    $('#modal-yes8').hide();
    $('#modal-scepb').hide();
    $('#tableName').hide();
    $('#images').show();

    if ([1, 2, 3, 4, 7, 10, 11, 13, 14, 6, 8, 15, 5].includes(data['gameType']) == true)
    {
        var tdBet = document.createElement('td');
        var tdAmount = document.createElement('td');
        var tdNet = document.createElement('td');
        var tdTxn = document.createElement('td');
        var tdTime = document.createElement('td');

        $(tdBet).attr('colspan','2');
        $(tdAmount).attr('colspan','2');
        $(tdNet).attr('colspan','2');
        $(tdTxn).attr('colspan','3');
        $(tdTime).attr('colspan','3');

        $(tdBet).html(data['betTypeName']);
        $(tdAmount).html(utils.formatMoney(data['amount']) );
        $(tdNet).html(utils.formatMoney(data['payment']) );
        $(tdTxn).html(data['orderId']);
        $(tdTime).html(data['time']);

        var netCash = data['payment'] - data['amount'];
        var color = (netCash > 0) ? 'color:blue':'color:red';
        $(tdBet).attr('style','color:black; font-size: 14px;');
        $(tdAmount).attr('style','color:black; font-size: 14px;');
        $(tdNet).attr('style',color);
        $(tdTxn).attr('style','color:black; font-size: 14px;');
        $(tdTime).attr('style','color:black; font-size: 14px;');
    
        $('#yourBet').append(tdBet);
        $('#yourBet').append(tdAmount);
        $('#yourBet').append(tdNet);
        $('#yourBet').append(tdTxn);
        $('#yourBet').append(tdTime);

        $('#images').html('');
        $('#playerName').html(locale['modal.playername'] +' : ' + ' <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
        $('#time').html(data['gameName'] +' '+locale['on']+' '+ data['openingTime']);
        $('#serialNo').html(data['serialNo']);
        $('#tableId').html(data['tableId']);

        if (data['state']== 1)
            $('#status').html(locale['resolved']);

        $('#endTime').html(data['paymentTime']);
        $('#results').html(data['fmtBetResults']);
        $('#totalBet').html(utils.formatMoney(data['amount']) );
        $('#payment').html(utils.formatMoney(data['amount'] + data['payment']) );
        $('#net').html(utils.formatMoney(data['payment']) );

        if (data['gameType'] == 5) 
        {
            $('#dealerhand').html("Red");
            $('#playerhand').html("Black");
        }
        else
        {
            $('#dealerhand').html(data['masterName']);
            $('#playerhand').html(data['clusterName']);
        }
        
        //image of the cards
        var tdPlayer = document.createElement('td');
        var tdDealer = document.createElement('td');
        $(tdDealer).attr('colspan','6');
        $(tdPlayer).attr('colspan','6');

        if (data['gameType'] == 11)
            var foldername = 'colorCardBG/';
        else
            var foldername = '';

        if (data['gameType'] != 2 && data['gameType'] != 8) 
        {
            for (var i = 0; i < data['resultMasterPics'].length; i++) 
            {
                var item = data['resultMasterPics'][i];
                var lastItem = item.split("/").pop();
                lastItem = ('000' + lastItem).slice(-4);
                lastItem = 'c'+ lastItem;
                var imageDealer = document.createElement('img');
                
                $(imageDealer).attr('class', 'imgStyle')
                $(imageDealer).attr('src','/images/cardsBG/'+ foldername + lastItem + '.png');
                $(tdDealer).append(imageDealer);
                $('#images').append(tdDealer);
            }
        }

        if ( !data['resultCluserPics']) 
        {
            $(tdDealer).attr('colspan','12');
            $('#hand').hide();
            $('#dealerhand').attr('colspan', '12');
        }
        else
        {
            $(tdDealer).attr('colspan','6');
            $('#hand').show();
            $('#dealerhand').attr('colspan', '6');

            for (var i = 0; i < data['resultCluserPics'].length; i++) 
            {
                var item = data['resultCluserPics'][i];
                var lastItem = item.split("/").pop();
                lastItem = ('000' + lastItem).slice(-4);
                lastItem = 'c'+ lastItem;
                var imagePlayer = document.createElement('img');
                
                $(imagePlayer).attr('class', 'imgStyle')
                $(imagePlayer).attr('src','/images/cardsBG/'+ foldername  + lastItem + '.png');
                $(tdPlayer).append(imagePlayer);
                $('#images').append(tdPlayer);
            }
        }

        if (data['gameType'] == 13) 
        {
            $('#playerhand').html(data['clusterName'] + ' 1');
            for (var k =2; k <4; k++) 
            {
                var th = document.createElement('th');
                var thplayer = document.createElement('th');
                $(th).attr('colspan', '6');
                $(thplayer).attr('colspan', '6');
                $(thplayer).attr('style', 'text-align:center;font-size:15px');
                $(thplayer).html(data['clusterName'+ k] + ' '+k);
                $('#cardTitle').append(thplayer);

                for (var i = 0; i < data['resultCluser'+k+'Pics'].length; i++) 
                {
                    var item = data['resultCluser'+k+'Pics'][i];
                    var lastItem = item.split("/").pop();
                    lastItem = ('000' + lastItem).slice(-4);
                    lastItem = 'c'+ lastItem;
                    var image = document.createElement('img');
                    
                    $(image).attr('class', 'imgStyle')
                    $(image).attr('src','/images/cardsBG/'+ foldername  + lastItem + '.png');
                    $(th).append(image);
                    $('#cards').append(th);
                }
            }
        }

        if(data['gameType'] == 8)
        {
            $('#images').hide();
        }
    }
    else
    {
        $('#modal-table').hide();
        $("#modal-json").show();
        $("#modal-json").html(JSON.stringify(data));
    }
}

function getDataTableDetailsASG(data,username,txnId)
{
    try
    {
        $("#modalDetails").modal('show'); 
    }
    catch(e)
    {
        
    }
    
    $('#modal-table').show();
    $('#modal-json').hide();
    $('#betResult').hide();
    $('#modal-mg').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $('#modal-dowin').hide();
    $('#modal-dd').hide();
    $('#modal-og').hide();
    $('#modal-rizal').hide();
    $('#modal-arn').hide();
    $('#modal-yes8').hide();
    $('#modal-scepb').hide();
    $('#tableName').show();

    var totalBet = 0;
    var netAmount = 0;
    var totalPayout = 0;

    for (var i = 0; i < Object.keys(data).length-3; i++) 
    {
        var record = data[i]['Record'];
        var gameType = record['gametype'];
        if (['BAC','BF','SHB','DT','ZJH','LBAC','CBAC','SBAC','NN','SG'].includes(gameType) == true ) 
        {
            $('#bjTitle').hide();
            $('#multipleBet').show();
            $('#images').html('');
            $('#images').show('');

            var result = record['gameResult'].split(':');
            for (var i = 0; i < result.length; i++) 
            {
                var card = result[i].split(';');
                
                if (gameType == 'BF' && card[0] == 'B') 
                    card[0] =  'H';
                else if (gameType == 'ZJH' && card[0] == 'P') 
                    card[0] =  'F';

                if (gameType == 'SHB') 
                    createDiceIMG(card);
                else if ( (gameType == 'NN' && card[0] != 'F') || gameType == 'SG')
                    createCardBull(card);
                else if (gameType != 'NN')
                    createCardASG(card);
            }
            
            totalBet = record['validBetAmount'];
            netAmount = record['netAmount'];
            totalPayout = record['totalPayout'];
                        
            createBetASG(record, txnId);
        }
        else
        {
            $('#images').hide();
            $('#images').html('');
            $('#hand').hide();
            totalBet += parseInt(record['validBetAmount']);
            netAmount += parseInt(record['netAmount']);
            totalPayout += parseInt(record['totalPayout']);
            createBetASG(record, txnId);
        }
    }
    var status = (record['status'] == 'Resolved') ? locale['resolved']:'';
   
    $('#playerName').html(locale['modal.playername'] +' : ' + '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
    $('#time').html(record['gameName']+' '+locale['on']+' '+record['settletime']);
    $('#serialNo').html(record['gameCode']);
    $('#status').html(status);
    $('#endTime').html(record['settletime']);
    $('#tableId').html(record['tableId']);

    // Check if 'tableName' row already exists
    var $existingTableNameRow = $('#tableName');

    if ($existingTableNameRow.length === 0) {
        // Append a new row for 'tableName' if it doesn't exist
        var $tableIdRow = $('#tableId').parent();
        var $tableNameRow = $('<tr id="tableName">');
        $tableNameRow.append('<td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">' + locale['table_name'] + '</td>');
        $tableNameRow.append('<td colspan="6" style="font-size:14px">' + record['tableName'] + '</td>');
        $tableIdRow.after($tableNameRow);
    }
    else {
        // If 'tableName' row exists, update its content with the latest data['tableId']
        $existingTableNameRow.find('td:last-child').html(record['tableName']);
    }

    $('#totalBet').html(utils.formatMoney(totalBet) );
    $('#payment').html(utils.formatMoney(totalPayout) );
    $('#net').html(utils.formatMoney(netAmount) );
}

function createBetASG(card, txnId)
{
    var tr = document.createElement('tr');
    var tdBet = document.createElement('td');
    var tdAmount = document.createElement('td');
    var tdNet = document.createElement('td');
    var tdTxn = document.createElement('td');
    var tdTime = document.createElement('td');

    if (card['gametype'] == 'ROU') 
    {
        var red = ['1','3','5','7','9','12','14','16','18','19','21','23','25','27','30','32','34','36'];
        $('#betResult').show();
        var gameResult = card['gameResult'];

        if (gameResult != 0) 
        {
            var type = (gameResult % 2 == 0) ? 'Even':'Odd';
            var color = (red.includes(gameResult)) ? 'Red':'Black';
            $('#results').html('number : ' + gameResult +' , type : '+type+ ' , color : '+color);
        }
        else
        {
            var color = 'Green';
            $('#results').html('number : ' + gameResult + ' , color : '+color);
        }
    }

    $(tdBet).attr('colspan','2');
    $(tdAmount).attr('colspan','2');
    $(tdNet).attr('colspan','2');
    $(tdTxn).attr('colspan','3');
    $(tdTime).attr('colspan','3');

    $(tdBet).html(card['playtypeName'] +' '+ (card['val'] || ''));
    $(tdAmount).html(utils.formatMoney(card['validBetAmount']));
    $(tdNet).html(utils.formatMoney(card['netAmount']));
    $(tdTxn).html(txnId);    
    $(tdTime).html(card['placeTime']);

    var netCash = card['netAmount'] - card['validBetAmount'];
    var color = (netCash > 0) ? 'color:blue':'color:red';
    $(tdBet).attr('style','color:black; font-size: 14px;');
    $(tdAmount).attr('style','color:black; font-size: 14px;');
    $(tdNet).attr('style', `${color}; font-size: 14px;`);
    $(tdTxn).attr('style','color:black; font-size: 14px;');
    $(tdTime).attr('style','color:black; font-size: 14px;');

    $(tr).append(tdBet);
    $(tr).append(tdAmount);
    $(tr).append(tdNet);
    $(tr).append(tdTxn);
    $(tr).append(tdTime);
    $(tr).attr('class', 'removeClass');
    $('#multipleBet').after(tr);
}

function createCardASG(card, result=0)
{
    $('#hand').show();
    
    if (card[0].match(/P.*/)) 
        $('#dealerhand').html(locale['player_hand']);
    else if (card[0].match(/B.*/))
        $('#playerhand').html(locale['banker_hand']);
    else if (card[0].match(/D.*/))
        $('#dealerhand').html(locale['dragon_hand']);
    else if (card[0].match(/T.*/))
        $('#playerhand').html(locale['tiger_hand']);
    else if (card[0].match(/H.*/))
        $('#dealerhand').html(locale['black_bull']);
    else if (card[0].match(/R.*/))
        $('#playerhand').html(locale['red_bull']);
    else if (card[0].match(/F.*/))
        $('#playerhand').html(locale['phoenix_hand']);

    var tdImg = document.createElement('td');
    $(tdImg).attr('colspan','6');

    for (var i = 1; i <card.length; i++) 
    {
        var img = card[i];
        var imageGame = document.createElement('img');
        $(imageGame).attr('class', 'imgStyle')
        $(imageGame).attr('src','/images/cardsASG/' + img + '.png');
        $(tdImg).append(imageGame);
        $('#images').append(tdImg);

        // var resultCard = card[i].slice(-1);
        // if (resultCard == 'K' || resultCard == 'Q' || resultCard == 'J' || resultCard == '0') 
        // {
        //     resultCard = 10;
        // }
        // else if(resultCard == 'A')
        // {
        //     resultCard = 1;
        // }
        // else
        // {
        //     resultCard = parseInt(card[i].slice(-1));
        // }
        // result += resultCard;
    }
}

function createDiceIMG(card)
{
    var diceNumber;
    var tdDealer = document.createElement('td');
    $('#hand').hide();
    $('#betResult').show();
    $('#results').html('');

    var number = ['first', 'second', 'third'];
    for (var i = 0; i < 3; i++) 
    {
        var engNumber = number[i];
        var image = document.createElement('img'); 
        diceNumber = card[i];
        document.getElementById('results').innerHTML += engNumber + ' : (' +diceNumber + ') ';

        $(tdDealer).attr('colspan','12');
        diceNumber = ('c1_0' + diceNumber);
        $(image).attr('class', 'imgStyle')
        $(image).attr('src','/images/cardsASG/' + diceNumber + '.png');
        $(tdDealer).append(image);
        $('#images').append(tdDealer);
    }
}

function createCardBull(card)
{
    $('#hand').show();
    var tdImg = document.createElement('td');
    $(tdImg).attr('colspan','6');

    if (card[0] == 'B' || card[0] == 'P1') 
    {
        if (card[0].match(/B.*/)) 
            $('#dealerhand').html(locale['dealer_hand']);
        else if (card[0].match(/P1.*/))
            $('#playerhand').html(locale['player_hand'] +' '+1);

        for (var i = 1; i <card.length; i++) 
        {
            var img = card[i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle')
            $(imageGame).attr('src','/images/cardsASG/' + img + '.png');
            $(tdImg).append(imageGame);
            $('#images').append(tdImg);
        }
    }
    else if (card[0] == 'P2' || card[0] == 'P3')
    {
        for (var i = 1; i <card.length; i++) 
        {
            var img = card[i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle')
            $(imageGame).attr('src','/images/cardsASG/' + img + '.png');
            $(tdImg).append(imageGame);
            $('#cards').append(tdImg);
        }

        var th = document.createElement('th');
        $(th).attr('colspan', '6');
        $(th).attr('style', 'text-align:center;font-size:15px');

        if (card[0].match(/P2.*/)) 
            $(th).html(locale['player_hand'] +' '+2);
        else if (card[0].match(/P3.*/))
            $(th).html(locale['player_hand'] +' '+3);

        $('#cardTitle').append(th);
    }
}

function getDataTableDetailsDG(data,username,txnId)
{
    try
    {
        $("#modalDetails").modal('show'); 
        
        $('#modal-table').show();
        $('#modal-json').hide();
        $('#betResult').hide();
        $('#modal-mg').hide();
        $('#modal-im').hide();
        $('#modal-pinnacle').hide();
        $('#modal-dowin').hide();
        $('#modal-dd').hide();
        $('#modal-og').hide();
        $('#modal-rizal').hide();
        $('#modal-arn').hide();
        $('#modal-yes8').hide();
        $('#modal-scepb').hide();
        $('#tableName').show();

        var totalBet = 0;
        var netAmount = 0;
        var totalPayout = 0;
        var gameId = data['gameId'];
        
        if ([1,2,3,5,7,8,11,12,41].includes(gameId) == true ) 
        {
            $('#bjTitle').hide();
            $('#multipleBet').show();
            $('#images').html('');
            $('#images').show('');

            var result = JSON.parse(data['result']);
            
            if (gameId == 5 || gameId == 12) 
                createDiceIMGDG(result);
            else if ([1,2,3,8,11,41].includes(gameId) == true)
            {
                var poker = result['poker'];
                for (var i = 0; i < Object.keys(poker).length; i++) 
                    createCardDG(poker,i);
            }
            else if (gameId == 7) 
            {
                var poker = result['poker'];
                for (var i = 1; i < Object.keys(poker).length; i++) 
                    createCardBullDG(poker, i);
            }
            
            totalBet = data['betPoints'];
            totalPayout = data['winOrLoss'];
            // netAmount = data['availableBet'];
            netAmount = parseInt(totalPayout - totalBet);
                        
            createBetDG(data);
        }
        else
        {
            $('#images').hide();
            $('#images').html('');
            $('#hand').hide();
            totalBet += parseInt(data['betPoints']);
            totalPayout += parseInt(data['winOrLoss']);
            // netAmount += parseInt(data['availableBet']);
            netAmount = parseInt(totalPayout - totalBet);
            
            createBetDG(data);
        }    
        $('#playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
        $('#time').html(data['gameName']+' '+locale['on']+' '+data['betTime']);
        $('#serialNo').html(data['playId']);
        $('#status').html(locale['resolved']);
        $('#endTime').html(data['calTime']);
        $('#tableId').html(data['tableId']);


        // Check if 'tableName' row already exists
        var $existingTableNameRow = $('#tableName');

        if ($existingTableNameRow.length === 0) {
            // Append a new row for 'tableName' if it doesn't exist
            var $tableIdRow = $('#tableId').parent();
            var $tableNameRow = $('<tr id="tableName">');
            $tableNameRow.append('<td colspan="6" style="font-size:14px;font-weight: bold;background-color:#f0f0f0">' + locale['table_name'] + '</td>');
            $tableNameRow.append('<td colspan="6" style="font-size:14px">' + data['tableName'] + '</td>');
            $tableIdRow.after($tableNameRow);
        }
        else {
            // If 'tableName' row exists, update its content with the latest data['tableId']
            $existingTableNameRow.find('td:last-child').html(data['tableName']);
        }

        $('#totalBet').html(utils.formatMoney(totalBet) );
        $('#payment').html(utils.formatMoney(totalPayout) );
        $('#net').html(utils.formatMoney(netAmount) );
    }
    catch(e)
    {
        
    }
}

function createBetDG(data)
{
    var urBet = JSON.parse(data['betDetail']);
    var objectKey = Object.keys(urBet);
    var valueToRemove = "info";
    objectKey = objectKey.filter(function (key){
        return key !== valueToRemove;
    });
    var gameId = JSON.parse(data['gameId']);

        if ([1,2,3,8,11,41].includes(gameId) == true)
        {
            for (var i = 0; i < objectKey.length; i++) 
                {
                        if (!objectKey[i].includes('W')) 
                        {
                            var tr = document.createElement('tr');
                            var tdBet = document.createElement('td');
                            var tdAmount = document.createElement('td');
                            var tdNet = document.createElement('td');
                            var tdTxn = document.createElement('td');
                            var tdTime = document.createElement('td');
            
                            $(tdBet).html(objectKey[i]);
                            $(tdAmount).html(utils.formatMoney(urBet[objectKey[i]]));
            
                            var betWinOrLose = objectKey[i] + 'W';
            
                            if (urBet[betWinOrLose] == null ) 
                            {
                                var net = 0;
                                $(tdNet).html(utils.formatMoney(0));    
                            }
                            else
                            {
                                var net = urBet[betWinOrLose];
                                $(tdNet).html(utils.formatMoney(net));
                            }
                            
                            $(tdBet).attr('colspan','2');
                            $(tdAmount).attr('colspan','2');
                            $(tdNet).attr('colspan','2');
                            $(tdTxn).attr('colspan','3');
                            $(tdTime).attr('colspan','3');
            
                            var netCash = net - urBet[objectKey[i]];
                            var color = (netCash > 0) ? 'color:blue':'color:red';
                            $(tdBet).attr('style','color:black; font-size: 14px;');
                            $(tdAmount).attr('style','color:black; font-size: 14px;');
                            $(tdNet).attr('style', `${color}; font-size: 14px;`);
                            $(tdTxn).attr('style','color:black; font-size: 14px;');
                            $(tdTime).attr('style','color:black; font-size: 14px;');
            
                            $.each(data['type'], function( index, value ) 
                            {
                                $(tdTxn).append(value + '<br>');
                            });
                            $(tdTime).html(data['calTime']);
            
                            $(tr).append(tdBet);
                            $(tr).append(tdAmount);
                            $(tr).append(tdNet);
                            $(tr).append(tdTxn);
                            $(tr).append(tdTime);
                            $(tr).attr('class', 'removeClass');
                            $('#multipleBet').after(tr);
                        }
                    
                }
        }

        else
        {
                for (var i = 0; i < objectKey.length; i++) 
                {
                    if (typeof(urBet[objectKey[i]]) != 'number') 
                    {
                        // for sicbo and speed sicbo only
                        var nestedArrName = objectKey[i];
                        nestedObjectKey = Object.keys(urBet[nestedArrName]);

                        $.each(nestedObjectKey, function( index, value ) 
                        {
                            if (nestedArrName.substr(nestedArrName.length - 1) != 'W') 
                            {
                                var tr = document.createElement('tr');
                                var tdBet = document.createElement('td');
                                var tdAmount = document.createElement('td');
                                var tdNet = document.createElement('td');
                                var tdTxn = document.createElement('td');
                                var tdTime = document.createElement('td');
                                
                                $(tdBet).html(value + ' ' +nestedArrName);
                                $(tdAmount).html(utils.formatMoney(urBet[nestedArrName][value] ));

                                var betWinOrLose = nestedArrName + 'W';

                                if (urBet[betWinOrLose] == null ) 
                                {
                                    var net = 0;
                                    $(tdNet).html(utils.formatMoney(net));    
                                }
                                else
                                {
                                    var net = urBet[betWinOrLose][value];
                                    $(tdNet).html(utils.formatMoney(net));
                                }
                                
                                $(tdBet).attr('colspan','2');
                                $(tdAmount).attr('colspan','2');
                                $(tdNet).attr('colspan','2');
                                $(tdTxn).attr('colspan','3');
                                $(tdTime).attr('colspan','3');

                                var netCash = net - urBet[nestedArrName][value];
                                var color = (netCash > 0) ? 'color:blue':'color:red';
                                $(tdBet).attr('style','color:black; font-size: 14px;');
                                $(tdAmount).attr('style','color:black; font-size: 14px;');
                                $(tdNet).attr('style', `${color}; font-size: 14px;`);
                                $(tdTxn).attr('style','color:black; font-size: 14px;');
                                $(tdTime).attr('style','color:black; font-size: 14px;');

                                $.each(data['type'], function( index, value ) 
                                {
                                    $(tdTxn).append(value + '<br>');
                                });
                                $(tdTime).html(data['calTime']);

                                $(tr).append(tdBet);
                                $(tr).append(tdAmount);
                                $(tr).append(tdNet);
                                $(tr).append(tdTxn);
                                $(tr).append(tdTime);
                                $(tr).attr('class', 'removeClass');
                                $('#multipleBet').after(tr);
                            }
                        });
                    }
                    else
                    {
                        if (!objectKey[i].includes('W')) 
                        {
                            var tr = document.createElement('tr');
                            var tdBet = document.createElement('td');
                            var tdAmount = document.createElement('td');
                            var tdNet = document.createElement('td');
                            var tdTxn = document.createElement('td');
                            var tdTime = document.createElement('td');

                            $(tdBet).html(objectKey[i]);
                            $(tdAmount).html(utils.formatMoney(urBet[objectKey[i]]));

                            var betWinOrLose = objectKey[i] + 'W';

                            if (urBet[betWinOrLose] == null ) 
                            {
                                var net = 0;
                                $(tdNet).html(utils.formatMoney(0));    
                            }
                            else
                            {
                                var net = urBet[betWinOrLose];
                                $(tdNet).html(utils.formatMoney(net));
                            }
                            
                            $(tdBet).attr('colspan','2');
                            $(tdAmount).attr('colspan','2');
                            $(tdNet).attr('colspan','2');
                            $(tdTxn).attr('colspan','3');
                            $(tdTime).attr('colspan','3');

                            var netCash = net - urBet[objectKey[i]];
                            var color = (netCash > 0) ? 'color:blue':'color:red';
                            $(tdBet).attr('style','color:black; font-size: 14px;');
                            $(tdAmount).attr('style','color:black; font-size: 14px;');
                            $(tdNet).attr('style', `${color}; font-size: 14px;`);
                            $(tdTxn).attr('style','color:black; font-size: 14px;');
                            $(tdTime).attr('style','color:black; font-size: 14px;');

                            $.each(data['type'], function( index, value ) 
                            {
                                $(tdTxn).append(value + '<br>');
                            });
                            $(tdTime).html(data['calTime']);

                            $(tr).append(tdBet);
                            $(tr).append(tdAmount);
                            $(tr).append(tdNet);
                            $(tr).append(tdTxn);
                            $(tr).append(tdTime);
                            $(tr).attr('class', 'removeClass');
                            $('#multipleBet').after(tr);
                        }
                    }
                }
    }

    if (data['gameId'] == 4) 
    {
        var red = ['1','3','5','7','9','12','14','16','18','19','21','23','25','27','30','32','34','36'];
        $('#betResult').show();
        var rouJson = JSON.parse(data['result']);
        var rouResult = rouJson['result'];

        if (rouResult != 0) 
        {
            var type = (rouResult % 2 == 0) ? 'Even':'Odd';
            var color = (red.includes(rouResult)) ? 'Red':'Black';
            $('#results').html('number : ' + rouResult +' , type : '+type+ ' , color : '+color);
        }
        else
        {
            var color = 'Green';
            $('#results').html('number : ' + rouResult + ' , color : '+color);
        }
    }
    else if (data['gameId'] == 14) 
    {
        const lines = [
          { 
            0: '4 White',
            1: '3 White 1 Red',
            2: '2 White 2 Red',
            3: '3 Red 1 White',
            4: '4 Red',
          }
        ];

        $('#betResult').show();
        var sedieJson = JSON.parse(data['result']);
        var number = sedieJson['result'];

        $('#results').html(lines[0][number]);
    }
}

function createCardDG(poker, rowNum)
{
    $('#hand').show();
    var pokerKey = Object.keys(poker);
    var tdImg = document.createElement('td');
    $(tdImg).attr('colspan','6');

    if (pokerKey[rowNum].match(/player.*/)) 
        $('#dealerhand').html(locale['banker_hand']);
    else if (pokerKey[rowNum].match(/banker.*/))
        $('#playerhand').html(locale['player_hand']);
    else if (pokerKey[rowNum].match(/dragon.*/))
        $('#dealerhand').html(locale['dragon_hand']);
    else if (pokerKey[rowNum].match(/tiger.*/))
        $('#playerhand').html(locale['tiger_hand']);
    else if (pokerKey[rowNum].match(/black.*/))
        $('#dealerhand').html(locale['black_bull']);
    else if (pokerKey[rowNum].match(/red.*/))
        $('#playerhand').html(locale['red_bull']);

    var cardArr = poker[pokerKey[rowNum]].split('-');
    for (var j = 0; j < cardArr.length; j++) 
    {
        if (cardArr[j] != 0) 
        {
            var img = cardArr[j];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle')
            $(imageGame).attr('src','/images/cardsDG/' + img + '.png');
            $(tdImg).append(imageGame);
            $('#images').append(tdImg);
        }
    }    
}

function createDiceIMGDG(result)
{
    var bunchResultDice = result['result'];
    var diceArr = bunchResultDice.split('');
    var tdDealer = document.createElement('td');
    $('#hand').hide();
    $('#betResult').show();
    $('#results').html('');

    var number = ['first', 'second', 'third'];
    for (var i = 0; i < diceArr.length; i++) 
    {
        var engNumber = number[i];
        var image = document.createElement('img'); 
        var diceNumber = diceArr[i];
        document.getElementById('results').innerHTML += engNumber + ' : (' +diceNumber + ') ';

        $(tdDealer).attr('colspan','12');
        diceNumber = ('c1_0' + diceNumber);
        $(image).attr('class', 'imgStyle')
        $(image).attr('src','/images/cardsDG/' + diceNumber + '.png');
        $(tdDealer).append(image);
        $('#images').append(tdDealer);
    }
}

function createCardBullDG(poker, rowNum)
{
    var playerKey = Object.keys(poker);
    $('#hand').show();
    var tdImg = document.createElement('td');
    $(tdImg).attr('colspan','6');
    var cardArr = poker[playerKey[rowNum]].split('-');

    if (playerKey[rowNum] == 'banker' || playerKey[rowNum]== 'player1') 
    {
        if (playerKey[rowNum] == 'banker') 
            $('#dealerhand').html(locale['dealer_hand']);
        else if (playerKey[rowNum] == 'player1')
            $('#playerhand').html(locale['player_hand'] +' '+1);

        for (var i = 0; i <cardArr.length; i++) 
        {
            var img = cardArr[i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle')
            $(imageGame).attr('src','/images/cardsDG/' + img + '.png');
            $(tdImg).append(imageGame);
            $('#images').append(tdImg);
        }
    }
    else if (playerKey[rowNum] == 'player2' || playerKey[rowNum] == 'player3')
    {
        for (var i = 0; i <cardArr.length; i++) 
        {
            var img = cardArr[i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle')
            $(imageGame).attr('src','/images/cardsDG/' + img + '.png');
            $(tdImg).append(imageGame);
            $('#cards').append(tdImg);
        }

        var th = document.createElement('th');
        $(th).attr('colspan', '6');
        $(th).attr('style', 'text-align:center;font-size:15px');

        if (playerKey[rowNum] == 'player2') 
            $(th).html(locale['player_hand'] +' '+2);
        else if (playerKey[rowNum] == 'player3')
            $(th).html(locale['player_hand'] +' '+3);

        $('#cardTitle').append(th);
    }
}

function getDataTableDetailsAV(data,username,txnId)
{
    try
    {
        $("#modalDetails").modal('show'); 
    }
    catch(e)
    {
        
    }

    $('#modal-table').show();
    $('#modal-json').hide();
    $('#betResult').hide();
    $('#modal-mg').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $('#modal-dowin').hide();
    $('#modal-dd').hide();
    $('#modal-og').hide();
    $('#modal-rizal').hide();
    $('#modal-arn').hide();
    $('#modal-yes8').hide();
    $('#modal-scepb').hide();
    $('#tableName').hide();

    var totalBet = 0;
    var netAmount = 0;
    var totalPayout = 0;

    $('#bjTitle').hide();
    $('#multipleBet').show();
    $('#images').html('');
    $('#images').show('');

    createCardAV(data);

    $.each(data['participants']['bets'], function( index, value ) 
    {
        var stake = parseInt(value['stake']);
        var payout = parseInt(value['payout']);
        totalBet += stake;
        totalPayout += payout;

        createBetAV(value);
    });
    
    netAmount = parseInt(totalPayout - totalBet);
                    
    $('#playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
    $('#time').html(data['gameName']+' '+locale['on']+' '+ data['created_at']);
    $('#serialNo').html(data['id'] + ' (' +data['company'] + ')' );
    $('#status').html(locale['resolved']);
    $('#endTime').html(data['settled_at']);
    $('#tableId').html(data['table_name']);

    $('#totalBet').html(utils.formatMoney(totalBet) );
    $('#payment').html(utils.formatMoney(totalPayout) );
    $('#net').html(utils.formatMoney(netAmount) );
}

function createBetAV(value)
{
    var tr = document.createElement('tr');
    var tdBet = document.createElement('td');
    var tdAmount = document.createElement('td');
    var tdNet = document.createElement('td');
    var tdTxn = document.createElement('td');
    var tdTime = document.createElement('td');
    
    $(tdBet).attr('colspan','2');
    $(tdAmount).attr('colspan','2');
    $(tdNet).attr('colspan','2');
    $(tdTxn).attr('colspan','1');
    $(tdTime).attr('colspan','3');

    var netCash = parseInt(value['payout'] - value['stake']);
    $(tdBet).html(value['type']);
    $(tdAmount).html(utils.formatMoney(value['stake']));
    $(tdNet).html(utils.formatMoney(netCash));
    $(tdTxn).html(value['transactionId']);
    $(tdTime).html(value['placedOn']);

    var color = (netCash > 0) ? 'color:blue':'color:red';
    $(tdBet).attr('style','color:black');
    $(tdAmount).attr('style','color:black');
    $(tdNet).attr('style',color);
    $(tdTxn).attr('style','color:black');
    $(tdTime).attr('style','color:black');

    $(tr).append(tdBet);
    $(tr).append(tdAmount);
    $(tr).append(tdNet);
    $(tr).append(tdTxn);
    $(tr).append(tdTime);
    $(tr).attr('class', 'removeClass');
    $('#multipleBet').after(tr);
}

function createCardAV(data)
{
    $('#hand').show();
    $('#score').show();
    var tdImgB = document.createElement('td');
    var tdImgP = document.createElement('td');
    $(tdImgB).attr('colspan','6');
    $(tdImgP).attr('colspan','6');

    $('#dealerhand').html(locale['banker_hand']);
    $('#playerhand').html(locale['player_hand']);

    for (var i = 1; i < 4; i++) 
    {
        if (data['b_card_'+ i] != '-') 
        {
            var img = data['b_card_'+ i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle')
            $(imageGame).attr('src','/images/cardsAV/' + img + '.png');
            $(tdImgB).append(imageGame);
            $('#images').append(tdImgB);
            $('#scoredealer').html(data['banker_sum']);
        }
    }  

    for (var i = 1; i < 4; i++) 
    {
        if (data['p_card_'+ i] != '-') 
        {
            var img = data['p_card_'+ i];
            var imageGame = document.createElement('img');
            $(imageGame).attr('class', 'imgStyle')
            $(imageGame).attr('src','/images/cardsAV/' + img + '.png');
            $(tdImgP).append(imageGame);
            $('#images').append(tdImgP);
            $('#scoreplayer').html(data['player_sum']);
        }
    }   
}

function getDataTableDetailsBota(data, username, roundId, txnId, prdId)
{
    try
    {
        $("#modalDetails").modal('show'); 
    }
    catch(e)
    {
        
    }

    $('#modal-table').show();
    $('#modal-json').hide();
    $('#modal-mg').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $('#modal-dowin').hide();
    $('#modal-dd').hide();
    $('#modal-og').hide();
    $('#modal-rizal').hide();
    $('#modal-arn').hide();
    $('#modal-yes8').hide();
    $('#modal-scepb').hide();
    $('#tableName').hide();

    var playerBet = JSON.parse(data['player_bet']);
    var betResult = JSON.parse(data['bet_result']);

    if (prdId == 5018)
    {
        var betAmount    = betResult['c_bet_money'];
        var payoutAmount = betResult['c_result_money'];
        var netAmount    = parseInt(payoutAmount - betAmount);
        var gameResult   = betResult['c_game_result'];

        switch(gameResult)
        {
            case '1':
            var results = locale['banker']+' '+locale['win'];
            break;

            case '2':
            var results = locale['player']+' '+locale['win'];
            break;

            case '3':
            var results = locale['tie'];
            break;
        }

        var localeArr   = [locale['banker'],locale['player'],locale['tie'],locale['banker_pair'],locale['player_pair']];
        var placeAt     = localeArr[playerBet['c_bet_type'] - 1];
        var placeAmount = betAmount;

        createBetBota(playerBet, betResult, txnId, data['created_at'], placeAt, placeAmount, netAmount, prdId);
        $('#playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
        $('#time').html(data['gameName']+' '+locale['on']+' '+ data['created_at']);
        $('#serialNo').html(playerBet['c_game_idx'] + ' (' +playerBet['c_casino'] + ')' );
        $('#status').html(locale['resolved']);
        $('#endTime').html(data['updated_at']);
        $('#tableId').html(playerBet['c_table_idx']);

        $('#totalBet').html(utils.formatMoney(betAmount));
        $('#payment').html(utils.formatMoney(payoutAmount));
        $('#net').html(utils.formatMoney(netAmount));
        $('#results').html(results);
    }
    else
    {
        var gameResult = betResult['detail']['gameResult'];
        var gameResultArr = gameResult.split("|");

        switch(gameResultArr[0])
        {
            case '1':
            var results = locale['banker']+' '+locale['win'];
            break;

            case '2':
            var results = locale['player']+' '+locale['win'];
            break;

            case '3':
            var results = locale['tie'];
            break;
        }

        if (gameResultArr[1] == '1') 
        {
            results += locale['banker_pair']+' '+locale['win'];
        }

        if (gameResultArr[2] == '1') 
        {
            results += locale['player_pair']+' '+locale['win'];
        }

        var placeonDetailArr = ['B', 'BP', 'P', 'PP', 'T'];
        var localeArr = [locale['banker'], locale['banker_pair'], locale['player'], locale['player_pair'], locale['tie']];

        for (var i = 0; i < 5; i++) 
        {
            if (playerBet['detail'][placeonDetailArr[i]] > 0) 
            {
                var placeAt = localeArr[i];

                var placeAmount = playerBet['detail'][placeonDetailArr[i]];
                var payoutAmount = betResult['price'];
                if (gameResultArr[0] == '1' && placeonDetailArr[i] == 'B') 
                {
                    var netAmount = parseInt(payoutAmount - placeAmount);
                }
                else if (gameResultArr[0] == '2' && placeonDetailArr[i] == 'P') 
                {
                    var netAmount = parseInt(payoutAmount - placeAmount);
                }
                else if (gameResultArr[0] == '3' && placeonDetailArr[i] == 'T') 
                {
                    var netAmount = parseInt(payoutAmount - placeAmount);
                }
                else
                {
                    var netAmount = parseInt(0 - placeAmount);
                }

                createBetBota(playerBet, betResult, txnId, data['created_at'], placeAt, placeAmount, netAmount, prdId);
            }
            netAmount = 0;
        }
                      
        $('#playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
        $('#time').html(data['gameName']+' '+locale['on']+' '+ data['created_at']);
        $('#serialNo').html(playerBet['detail']['gameNo'] + ' (' +playerBet['detail']['casino'] + ')' );
        $('#status').html(locale['resolved']);
        $('#endTime').html(data['updated_at']);
        $('#tableId').html(playerBet['detail']['tableNo']);

        $('#totalBet').html(utils.formatMoney(betResult['bet']) );
        $('#payment').html(utils.formatMoney(betResult['price']) );
        var totalNet = parseInt(betResult['price'] - betResult['bet'])
        $('#net').html(utils.formatMoney(totalNet) );
        $('#results').html(results);
    }
}

function createBetBota(playerBet, betResult, txnId, placeOn, placeAt, placeAmount, netAmount, prdId)
{
    var tr       = document.createElement('tr');
    var tdBet    = document.createElement('td');
    var tdAmount = document.createElement('td');
    var tdNet    = document.createElement('td');
    var tdTxn    = document.createElement('td');
    var tdTime   = document.createElement('td');
    var tdPlayer = document.createElement('td');
    var tdDealer = document.createElement('td');

    if (prdId == '5018')
    {
        $('#score').show();
        $('#scoredealer').html('Banker');
        $('#scoreplayer').html('Player');

        $('#images').html('');

        for (var i = 0; i < 3 ; i++) 
        {
            var bankerImg = betResult['bc'+ (i + 1)];
            var playerImg = betResult['pc'+ (i + 1)];
            
            if (bankerImg != 0)
            {
                var imageDealer = document.createElement('img');
                $(imageDealer).attr('class', 'imgStyle')
                $(imageDealer).attr('src','/images/cardsBota/' + bankerImg + '.png');
                $(tdDealer).attr('colspan','6');
                $(tdDealer).append(imageDealer);
               
            }
            if (playerImg != 0)
            {
                var imagePlayer = document.createElement('img');
                $(imagePlayer).attr('class', 'imgStyle')
                $(imagePlayer).attr('src','/images/cardsBota/' + playerImg + '.png');
                 $(tdPlayer).attr('colspan','6');
                $(tdPlayer).append(imagePlayer);
                
            }
            $('#images').append(tdDealer);
            $('#images').append(tdPlayer);           
        }
    }
    else
    {
        $('#images').html('');
    }
    
    $(tdBet).attr('colspan','2');
    $(tdAmount).attr('colspan','2');
    $(tdNet).attr('colspan','2');
    $(tdTxn).attr('colspan','3');
    $(tdTime).attr('colspan','3');

    $(tdBet).html(placeAt);
    $(tdAmount).html(utils.formatMoney(placeAmount));
    $(tdNet).html(utils.formatMoney(netAmount));
    $(tdTxn).html(txnId);
    $(tdTime).html(placeOn);

    var color = (netAmount > 0) ? 'color:blue':'color:red';
    $(tdBet).attr('style','color:black; font-size: 14px;');
    $(tdAmount).attr('style','color:black; font-size: 14px;');
    $(tdNet).attr('style', `${color}; font-size: 14px;`);
    $(tdTxn).attr('style','color:black; font-size: 14px;');
    $(tdTime).attr('style','color:black; font-size: 14px;');

    $(tr).append(tdBet);
    $(tr).append(tdAmount);
    $(tr).append(tdNet);
    $(tr).append(tdTxn);
    $(tr).append(tdTime);
    $(tr).attr('class', 'removeClass');
    $('#multipleBet').after(tr);
}

function getDataTableDetailsHilton(data, username, roundId, txnId, prdId)
{
    try
    {
        $("#modalDetails").modal('show'); 
    }
    catch(e)
    {
        
    }

    $('#modal-table').hide();
    $('#modal-json').hide();
    $('#modal-mg').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $('#modal-dowin').hide();
    $('#modal-dd').hide();
    $('#modal-og').hide();
    $('#modal-rizal').hide();
    $('#modal-arn').show();
    $('#modal-yes8').hide();
    $('#modal-scepb').hide();
    $('#tableName').hide();

    var response = JSON.parse(data['response']);
    var betTime = data['created_at'];

    if(prdId == 39)
    {
        var betDetail = response['message'];
        var betResult = response['message']['detail'];
        var betOption = response['message']['detail']['bet'];
        
        var totalBetAmount    = betDetail['money'];
        var totalPayoutAmount = betDetail['benefit'];
        var netAmount    = parseInt(totalPayoutAmount - totalBetAmount);
        var gameResult    = betResult['card']['outcome'];

        createBetHilton(betDetail, betResult, txnId, betOption, netAmount, prdId);

        switch(gameResult)
        {
            case 'BANKER':
            var results = locale['banker']+' '+locale['win']+' ';
            break;

            case 'PLAYER':
            var results = locale['player']+' '+locale['win']+' ';
            break;

            case 'TIE':
            var results = locale['tie'];
            break;

            case 'PLAYER,PLAYER PAIR':
            var results = locale['player']+' '+locale['win']+', '+locale['player_pair'];
            break;

            case 'PLAYER,BANKER PAIR':
            var results = locale['player']+' '+locale['win']+', '+locale['banker_pair'];
            break;

            case 'BANKER,BANKER PAIR':
            var results = locale['banker']+' '+locale['win']+', '+locale['banker_pair'];
            break;

            case 'BANKER,PLAYER PAIR':
            var results = locale['banker']+' '+locale['win']+', '+locale['player_pair'];
            break;
        }

        $('#modal-arn #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
        $('#modal-arn #roundId').html(betDetail['round']);
        $('#modal-arn #placetime').html(betTime);
        $('#modal-arn #results').html(results);

        $('#modal-arn #totalBet').html(utils.formatMoney(totalBetAmount));
        $('#modal-arn #payment').html(utils.formatMoney(totalPayoutAmount));
        $('#modal-arn #net').html(utils.formatMoney(netAmount));
    }
    else
    {
        var betDetail = JSON.parse(response['detail'])
        var tdPlayer = document.createElement('td');
        var tdDealer = document.createElement('td');
        var dealerScore = betDetail['banker']['score'];
        var playerScore = betDetail['player']['score'];

        $('#modal-arn #score').show();
        $('#modal-arn #scoredealer').html(dealerScore);
        $('#modal-arn #scoreplayer').html(playerScore);
        $('#modal-arn #images').html('');

        for (var i = 0; i < betDetail['banker']['cards'].length ; i++) 
        {
            var imageDealer = document.createElement('img');
            var bankerImg = betDetail['banker']['cards'][i];
            $(imageDealer).attr('class', 'imgStyle')
            $(imageDealer).attr('src','/evol/dist/images/cardsEVO/' + bankerImg + '.png');
            $(tdDealer).attr('colspan','6');
            $(tdDealer).append(imageDealer);
            $('#modal-arn #images').append(tdDealer);
        }

        for (var i = 0; i < betDetail['player']['cards'].length ; i++) 
        {
            var imagePlayer = document.createElement('img');
            var playerImg = betDetail['player']['cards'][i];
            $(imagePlayer).attr('class', 'imgStyle')
            $(imagePlayer).attr('src','/evol/dist/images/cardsEVO/' + playerImg + '.png');
            $(tdPlayer).attr('colspan','6');
            $(tdPlayer).append(imagePlayer);
            $('#modal-arn #images').append(tdPlayer);
        }

        var txnId = response['tid'];
        var betType = response['option'];
        var betAmount = response['money'];
        var betPayout = response['benefit'];
        var netAmount    = parseInt(betPayout - betAmount);
        var gameResult    = betDetail['outcome'];

        var tr       = document.createElement('tr');
        var tdBet    = document.createElement('td');
        var tdTxn    = document.createElement('td');
        var tdAmount = document.createElement('td');
        var tdNet    = document.createElement('td');

        $(tdTxn).attr('colspan','3');
        $(tdBet).attr('colspan','3');
        $(tdAmount).attr('colspan','3');
        $(tdNet).attr('colspan','3');

        $(tdTxn).html(txnId);
        $(tdBet).html(betType);
        $(tdAmount).html(utils.formatMoney(betAmount));
        $(tdNet).html(utils.formatMoney(betPayout));

        var color = (betPayout > 0) ? 'color:blue':'color:red';
        $(tdTxn).attr('style','color:black; font-size: 14px;');
        $(tdBet).attr('style','color:black; font-size: 14px;');
        $(tdAmount).attr('style','color:black; font-size: 14px;');
        $(tdNet).attr('style', `${color}; font-size: 14px;`);

        $(tr).append(tdTxn);
        $(tr).append(tdBet);
        $(tr).append(tdAmount);
        $(tr).append(tdNet);
        $(tr).attr('class', 'removeClass');
        $('#modal-arn #multipleBet').after(tr);

        switch(gameResult)
        {   
            case 'BANKER':
            var results = locale['banker']+' '+locale['win']+' ';
            break;

            case 'PLAYER':
            var results = locale['player']+' '+locale['win']+' ';
            break;

            case 'TIE':
            var results = locale['tie'];
            break;

            case 'PLAYER,PLAYER PAIR':
            var results = locale['player']+' '+locale['win']+', '+locale['player_pair'];
            break;

            case 'PLAYER,BANKER PAIR':
            var results = locale['player']+' '+locale['win']+', '+locale['banker_pair'];
            break;

            case 'BANKER,BANKER PAIR':
            var results = locale['banker']+' '+locale['win']+', '+locale['banker_pair'];
            break;

            case 'BANKER,PLAYER PAIR':
            var results = locale['banker']+' '+locale['win']+', '+locale['player_pair'];
            break;
        }
                      
        $('#modal-arn #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
        $('#modal-arn #roundId').html(response['round']);
        $('#modal-arn #placetime').html(betTime);
        $('#modal-arn #results').html(results);

        $('#modal-arn #totalBet').html(utils.formatMoney(betAmount));
        $('#modal-arn #payment').html(utils.formatMoney(betPayout));
        $('#modal-arn #net').html(utils.formatMoney(netAmount));
    }
}

function createBetHilton(betDetail, betResult, txnId, betOption, netAmount, prdId)
{
    var tdPlayer = document.createElement('td');
    var tdDealer = document.createElement('td');
    var dealerScore = betResult['card']['banker']['score'];
    var playerScore = betResult['card']['player']['score'];

    $('#modal-arn #score').show();
    $('#modal-arn #scoredealer').html(dealerScore);
    $('#modal-arn #scoreplayer').html(playerScore);

    $('#modal-arn #images').html('');

    for (var i = 0; i < betResult['card']['banker']['cards'].length ; i++) 
    {
        var imageDealer = document.createElement('img');
        var bankerImg = betResult['card']['banker']['cards'][i];
        $(imageDealer).attr('class', 'imgStyle')
        $(imageDealer).attr('src','/evol/dist/images/cardsEVO/' + bankerImg + '.png');
        $(tdDealer).attr('colspan','6');
        $(tdDealer).append(imageDealer);
        $('#modal-arn #images').append(tdDealer);
    }

    for (var i = 0; i < betResult['card']['player']['cards'].length ; i++) 
    {
        var imagePlayer = document.createElement('img');
        var playerImg = betResult['card']['player']['cards'][i];
        $(imagePlayer).attr('class', 'imgStyle')
        $(imagePlayer).attr('src','/evol/dist/images/cardsEVO/' + playerImg + '.png');
        $(tdPlayer).attr('colspan','6');
        $(tdPlayer).append(imagePlayer);
        $('#modal-arn #images').append(tdPlayer);
    }


    $.each(betOption, function(index, val)
    { 
        var txnId = val['bet_tid'];
        var betType = val['bet_option'];
        var betAmount = val['bet_money'];
        var betPayout = val['bet_benefit'];
        var tr       = document.createElement('tr');
        var tdBet    = document.createElement('td');
        var tdTxn    = document.createElement('td');
        var tdAmount = document.createElement('td');
        var tdNet    = document.createElement('td');

        $(tdTxn).attr('colspan','3');
        $(tdBet).attr('colspan','3');
        $(tdAmount).attr('colspan','3');
        $(tdNet).attr('colspan','3');

        $(tdTxn).html(txnId);
        $(tdBet).html(betType);
        $(tdAmount).html(utils.formatMoney(betAmount));
        $(tdNet).html(utils.formatMoney(betPayout));

        var color = (betPayout > 0) ? 'color:blue':'color:red';
        $(tdTxn).attr('style','color:black; font-size: 14px;');
        $(tdBet).attr('style','color:black; font-size: 14px;');
        $(tdAmount).attr('style','color:black; font-size: 14px;');
        $(tdNet).attr('style', `${color}; font-size: 14px;`);

        $(tr).append(tdTxn);
        $(tr).append(tdBet);
        $(tr).append(tdAmount);
        $(tr).append(tdNet);
        $(tr).attr('class', 'removeClass');
        $('#modal-arn #multipleBet').after(tr);

    });
}

function getDataTableDetailSXG(data,username,txnId)
{
    try
    {
        $("#modalDetails").modal('show');  
    }
    catch(e)
    {
        
    }

    $('#modal-table').show();
    $("#modal-json").hide();
    $('#modal-mg').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $('#modal-dowin').hide();
    $('#modal-dd').hide();
    $('#modal-og').hide();
    $('#modal-rizal').hide();
    $('#modal-arn').hide();
    $('#modal-yes8').hide();
    $('#modal-scepb').hide();
    $('#tableName').hide();

    var game_code = ['MX-LIVE-001','MX-LIVE-003','MX-LIVE-006','MX-LIVE-007','MX-LIVE-009','MX-LIVE-010','MX-LIVE-014','MX-LIVE-016','MX-LIVE-017'];
    var foldername = 'cardsSXG/';

    if (game_code.includes(data[0]['gameCode']) == true)
    {
        var totalBet = 0;
        var totalWin = 0;
        var totalNet = 0;
        $.each(data, function( index, value ) 
        {
            var tr = document.createElement('tr');
            var tdBet = document.createElement('td');
            var tdAmount = document.createElement('td');
            var tdNet = document.createElement('td');
            var tdTxn = document.createElement('td');
            var tdTime = document.createElement('td');
            totalBet += value['betAmount'];
            totalWin += value['winAmount'];
            totalNet += value['gameInfo']['winLoss'];

            $(tdBet).attr('colspan','2');
            $(tdAmount).attr('colspan','2');
            $(tdNet).attr('colspan','2');
            $(tdTxn).attr('colspan','3');
            $(tdTime).attr('colspan','3');
            $(tr).attr('class','emptyContent');

            $(tdBet).html(value['betType']);
            $(tdAmount).html(utils.formatMoney(value['betAmount']) );
            $(tdNet).html(utils.formatMoney(value['gameInfo']['winLoss']) );
            $(tdTxn).html(value['platformTxId']);
            $(tdTime).html(value['betTime']);

            var netCash = value['gameInfo']['winLoss'] - value['betAmount'];
            var color = (netCash > 0) ? 'color:blue':'color:red';
            $(tdBet).attr('style','color:black; font-size: 14px;');
            $(tdAmount).attr('style','color:black; font-size: 14px;');
            $(tdNet).attr('style', `${color}; font-size: 14px;`);
            $(tdTxn).attr('style','color:black; font-size: 14px;');
            $(tdTime).attr('style','color:black; font-size: 14px;');
        
            $(tr).append(tdBet);
            $(tr).append(tdAmount);
            $(tr).append(tdNet);
            $(tr).append(tdTxn);
            $(tr).append(tdTime);
            $('#yourBet').after(tr);

            $('#images').html('');
            $('#playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
            $('#time').html(value['gameName'] +' '+locale['on']+' '+ value['gameInfo']['roundStartTime']);
            $('#serialNo').html(value['platform']);
            $('#tableId').html(value['gameInfo']['tableId']);
            $('#status').html(locale['resolved']);

            $('#results').html(value['gameInfo']['winner']);
            $('#endTime').html(value['updateTime']);
            $('#totalBet').html(utils.formatMoney(totalBet) );
            $('#payment').html(utils.formatMoney(totalWin) );
            $('#net').html(utils.formatMoney(totalNet) );

            if (value['gameCode'] == 'MX-LIVE-009') // roulette
            {
                $('#hand').hide();
                var rouResult = value['gameInfo']['winner'];
                var red = ['1','3','5','7','9','12','14','16','18','19','21','23','25','27','30','32','34','36'];
                var type = (rouResult % 2 == 0) ? 'Even':'Odd';
                var color = (red.includes(rouResult)) ? 'Red':'Black';
                $('#results').html('number : ' + rouResult +' , type : '+type+ ' , color : '+color);
            }
            else if (['MX-LIVE-007','MX-LIVE-014','MX-LIVE-016'].includes(value['gameCode']) ) // sicbo
            {
                $('#hand').hide();

                //image of the cards
                var td = document.createElement('td');
                $(td).attr('colspan','12');
                var split = value['gameInfo']['result'][0].split(',');

                for (var i = 0; i < split.length; i++) 
                {
                    var image = document.createElement('img');
                    
                    $(image).attr('class', 'imgStyle')
                    $(image).attr('src','/images/'+ foldername  + split[i] + '.png');
                    $(td).append(image);
                    $('#images').append(td);
                }
            }
            else if (value['gameCode'] == 'MX-LIVE-010') // red blue duel
            {
                $('#hand').show();

                $('#dealerhand').html('RED');
                $('#playerhand').html('BLUE');

                //image of the cards
                var tdRed = document.createElement('td');
                var tdBlue = document.createElement('td');

                $(tdRed).attr('colspan','6');
                $(tdBlue).attr('colspan','6');
                var split = value['gameInfo']['result'][0].split(',');
                var tdArr = [tdRed,tdBlue];

                for (var i = 0; i < 2; i++) 
                {
                    var image = document.createElement('img');
                    
                    $(image).attr('class', 'imgStyle')
                    $(image).attr('src','/images/'+ foldername  + split[i] + '.png');
                    $(tdArr[i]).append(image);
                    $('#images').append(tdArr[i]);
                }
            }
            else if (value['gameCode'] == 'MX-LIVE-017') // Sedie
            {
                $('#results').html(value['gameInfo']['winner'] + '<br>' + 'W = White, R = Red');
            }
            else // baccarat and dragon tiger
            {
                //image of the cards
                var tdPlayer = document.createElement('td');
                var tdDealer = document.createElement('td');
                $(tdDealer).attr('colspan','6');
                $(tdPlayer).attr('colspan','6');

                $('#hand').show();
                $('#dealerhand').html(locale['banker_hand']);
                $('#playerhand').html(locale['player_hand']);

                $('#dealerhand').attr('colspan', '6');

                for (var i = 5; i >= 3; i--) 
                {
                    var item = value['gameInfo']['result'][i];
                    if (item) 
                    {
                        var imageBanker = document.createElement('img');
                        
                        $(imageBanker).attr('class', 'imgStyle')
                        $(imageBanker).attr('src','/images/'+ foldername  + item + '.png');
                        $(tdDealer).append(imageBanker);
                        $('#images').append(tdDealer);
                    }
                }

                for (var i = 2; i >= 0; i--) 
                {
                    var item = value['gameInfo']['result'][i];
                    if (item) 
                    {
                        var imagePlayer = document.createElement('img');
                        
                        $(imagePlayer).attr('class', 'imgStyle')
                        $(imagePlayer).attr('src','/images/'+ foldername  + item + '.png');
                        $(tdPlayer).append(imagePlayer);
                        $('#images').append(tdPlayer);
                    }
                }
            }
        });
    }
    else
    {
        $('#modal-table').hide();
        $("#modal-json").show();
        $("#modal-json").html(JSON.stringify(data));
    }
}

function getDataTableDetailIM(data,username,txnId)
{
    $("#modalDetails").modal('show'); 
    $('#modal-im').show();
    $("#modal-json").hide();
    $("#modal-im #gameInfo").empty();

    $('#modal-im #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');

    $('#modal-im #txnId').html(data['txn_id']);
    $('#modal-im #status').html(locale['resolved']);
    $('#modal-im #placetime').html(data['debit_date']);
    $('#modal-im #endTime').html(data['credit_date']);
    $('#modal-im #results').html(data['prize_desc']);
    $('#modal-im #totalBet').html(utils.formatMoney(data['stake']));
    $('#modal-im #results').html(data['prize_status']);
    $('#modal-im #odds').html(data['gameOdds']);
    $('#modal-im #type').html(data['type']);
    $('#modal-im #payment').html(utils.formatMoney(data['payout']));
    $('#modal-im #net').html(utils.formatMoney(data['total_net']));

    var events =  data['match'];
    var flag = false; //check type is single or parlay
    var div = "";
    var carouselIndicator = "";
    var maxEvent = events.length;
    var prdType = data['product_type'];

    for (a = 0; a < maxEvent; a++) 
    {
        if(maxEvent == 1)
        {
            var result = events[a]['score']; 
            var resultValue = "";

            if(typeof result === 'string') 
            {
                resultValue = result;
            }
            else
            {
                //bold the winner team or result
                for(r = 0; r < result.length; r++)
                {
                    if(result[r]['Outcome'] == 1)
                    {
                        resultValue = '<b>'+result[r]['Option']+'</b> -';
                    }
                }
                
                //rtrim ' -'
                resultValue = resultValue.slice(0, -2);
            }

            var teamName = "";

            if(prdType == 1)
            {
                teamName = '<tr><td style="width:50%">'+locale['game']+'</td><td>'+events[a]["gameName"]+'</td></tr><tr><td colspan="12" style="text-align:center;">'+
            events[a]["team1"]+'<b><span style="color:red">&nbsp;&nbsp;&nbsp;&nbsp; -VS- &nbsp;&nbsp;&nbsp;&nbsp;</span></b>'+events[a]["team2"]+'</td></tr>';
            }
            else
            {
                teamName = '<tr><td colspan="12" style="text-align:center;">'+events[a]["event"]+'</td><tr>';
            }

            dateHeader = events[a]["bet"].includes('Live Odds') ? locale['estBetSettlementTime'] : locale['matchDate']; //if bet type included 'Live Odds' in there, EventDateTime represent estimate wager settlement date

            var drawDiv = '<table style="width:100%;"><tbody class="table-md mb-4;"><tr><td colspan="12" style="background-color:#a4b7c1;color:black;text-align:center;">'+
            events[a]["competition"]+'</td></tr>'+teamName+'<tr><td style="width:50%">'+locale['result']+'</td><td>'+resultValue+'</td></tr>'+
            '<td>'+locale['betType']+'</td><td>'+events[a]["bet"]+'</td></tr><tr><td>'+dateHeader+'</td><td>'+events[a]["EventDateTime"]+
            '</td></tr><tr><td>'+locale['selection']+'</td><td>'+events[a]["selection"]+'</td></tr></tbody></table>';

            $("#gameInfo").prepend(drawDiv);
        }
        else
        {
            flag = true;
            var resultValue = "";
            var active = "";
            var drawIndicator = "";

            //give the first event as active page
            if(a == 0)
            {
                active = "active";
            }
            
            // drawIndicator = '<li data-target="#gameInfo" data-slide-to="'+a+'" class="'+active+'" ></li>';

            // carouselIndicator = carouselIndicator.concat(drawIndicator);

            var result = events[a]['score']; 
            var resultValue = "";

            if(typeof result != "string")
            {
                //bold the winner team or result
                for(r = 0; r < result.length; r++)
                {
                    if(result[r]['Outcome'] == 1)
                    {
                        resultValue = '<b>'+result[r]['Option']+'</b> - ';
                    }
                }
            
                //rtrim '-'
                resultValue = resultValue.slice(0, -2);
            }
            else
            {
                resultValue = result;
            }

            dateHeader = events[a]["bet"].includes('Live Odds') ? locale['estBetSettlementTime'] : locale['matchDate']; //if bet type included 'Live Odds' in there, EventDateTime represent estimate wager settlement date 

            var drawDiv = '<div class="carousel-item '+active+'"><table style="width:100%;"><span>'+locale['event']+' '+(a+1)+'/'+maxEvent+'</span><tbody class="table-md mb-4;"><tr><td colspan="12" style="background-color:#a4b7c1;color:black;text-align:center;">'+
            events[a]["competition"]+'</td></tr><tr><td style="width:50%">'+locale['game']+'</td><td>'+events[a]["gameName"]+'</td></tr><tr><td colspan="12" style="text-align:center;"><b>'+
            events[a]["team1"]+'<span style="color:red">&nbsp;&nbsp;&nbsp;&nbsp; -VS- &nbsp;&nbsp;&nbsp;&nbsp;</span>'+events[a]["team2"]+'</b></td></tr>'+
            '<tr><td>'+locale['result']+'</td><td>'+resultValue+'</td></tr>'+
            '<td>'+locale['betType']+'</td><td>'+events[a]["bet"]+'</td></tr><tr><td>'+dateHeader+'</td><td>'+events[a]["EventDateTime"]+
            '</td></tr><tr><td>'+locale['selection']+'</td><td>'+events[a]["selection"]+'</td></tr></tbody></table></div>';

            div = div.concat(drawDiv);
        }

        if(a == (events.length-1))
        {
            //concat all the event in carousel
            if(flag)
            {   
                var carousel = '<div class="carousel-inner">'+div+'</div><a class="carousel-control-prev" href="#gameInfo" data-slide="prev">'+
                '<span class="carousel-control-prev-icon"></span></a><a class="carousel-control-next" href="#gameInfo" data-slide="next">'+
                '<span class="carousel-control-next-icon"></span></a>';

                // var carousel = '<div class="carousel-inner">'+div+'</div><ol class="carousel-indicators">'+carouselIndicator+'</ol><a class="carousel-control-prev" href="#gameInfo" role="button" data-slide="prev">'+
                // '<span class="carousel-control-prev-icon" aria-hidden="true"></span></a><a class="carousel-control-next" href="#gameInfo" role="button" data-slide="next">'+
                // '<span class="carousel-control-next-icon" aria-hidden="true"></span></a>';

                $("#gameInfo").prepend(carousel);
            }

            break;
        }
    }
}

function getDataTableDetailUKT(data,txnId)
{
    $("#modalDetails").modal('show'); 
    $('#modal-ukt').show();
    $("#modal-json").hide();

    var betAmount = data['purchase_price'];
    var winAmount = data['payout'];

    //Translate
    var translate = data['asset_symbol'].replace(/[/ ]/g, '').toLowerCase();
    if(translate == "dowjones" || translate == "silver")
    {
        var symbol = locale[translate];
    }
    else
    {
        var symbol = data['asset_symbol'];
    }

    if(data['binary_type'] == 1)
    {
        //Nonstop
        var binaryType = locale['nonstop'];
    }
    else
    {
        //1min
        var binaryType =  locale['1min'];
    }

    if(data['contract_type'] == 1)
    {
        //Nonstop
        var contractType =  locale['higher'];
    }
    else
    {
        //1min
        var contractType =  locale['lower'];
    }

    switch(data['status'])
    {
        case 0:
        var status = locale['lose'];
        var netAmount = "-"+betAmount;
        var totalPayout = 0;
        break;
        //Lose

        case 1:
        //Win
        var status = locale['win'];
        var netAmount = "+"+winAmount;
        var totalPayout = betAmount + winAmount;
        break;

        case 3:
        //Draw
        var status = locale['tie'];
        var netAmount = 0;
        var totalPayout = betAmount;
        break;

        case 4:
        //Cancel "so called"
        if(winAmount > 0)
        {
            var status = locale['win'];
            var netAmount = "+"+winAmount;
            var totalPayout = betAmount + winAmount;
        }
        else
        {
            var status = locale['lose'];
            var netAmount = "-"+betAmount;
            var totalPayout = 0;
        }
        break;
    }

    var openPrice = parseFloat(data['open_price'].toFixed(5));

    $('#modal-ukt #txnId').html(locale['txn_id']+": "+data['id']);
    $('#modal-ukt #status').html(status);
    $('#modal-ukt #symbol').html(symbol);
    $('#modal-ukt #binaryType').html(binaryType);
    $('#modal-ukt #contractType').html(contractType);
    $('#modal-ukt #totalBet').html(data['purchase_price']);
    $('#modal-ukt #payout').html(totalPayout);
    $('#modal-ukt #exitSpot').html(data['exit_spot']);
    $('#modal-ukt #openPrice').html(openPrice);
    $('#modal-ukt #entrySpot').html(data['entry_spot']);
    $('#modal-ukt #net').html(netAmount);

}

function getDataTableDetailPinnacle(data,username,txnId)
{
    $("#modalDetails").modal('show'); 
    $('#modal-pinnacle').show();
    $("#modal-json").hide();
    $("#modal-pinnacle #pinGameInfo").empty();

    $('#modal-pinnacle #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');

    $('#modal-pinnacle #txnId').html(data['wagerId']);
    $('#modal-pinnacle #status').html(locale['resolved']);
    $('#modal-pinnacle #placetime').html(data['wagerDateFm']);
    $('#modal-pinnacle #endTime').html(data['settleDateFm']);
    $('#modal-pinnacle #results').html(data['prize_desc']);
    $('#modal-pinnacle #totalBet').html(utils.formatMoney(data['turnover']));
    $('#modal-pinnacle #odds').html(data['odds']);
    $('#modal-pinnacle #payment').html(utils.formatMoney(parseFloat(data['winLoss'] + data['toRisk'])));
    $('#modal-pinnacle #net').html(utils.formatMoney(data['winLoss']));


    if(data['handicap'] == 0)
    {
        displayType = "";
    }
    else
    {
        displayType = "";
    }

    sportId = data['sportId'];


    switch(data['result'])
    {
        case 'WIN':
        var result = locale['win'];
        break;

        case 'LOSE':
        var result = locale['lose'];
        break;

        case 'DRAW':
        var result = locale['tie'];
        break;
    }

     switch(data['wagerType'])
    {
        case 'single':
        var wagerType = locale['single'];
        break;

        case 'parlay':
        var wagerType = locale['parlay'];
        break;

        case 'teaser':
        var wagerType = locale['teaser'];
        break;

        default:
        var wagerType = "N/A";
        break;
    }

    switch(data['oddsFormat'])
    {
        case 0:
        var oddsFormat = 'AM';
        break;

        case 1:
        var oddsFormat = 'EU';
        break;

        case 2:
        var oddsFormat = 'HK';
        break;

        case 3:
        var oddsFormat = 'ID';
        break;

        case 4:
        var oddsFormat = 'MY';
        break;

        default:
        var oddsFormat = "N/A";
        break;
    }
    $('#modal-pinnacle #results').html(result);
    $('#modal-pinnacle #type').html(wagerType);
    $('#modal-pinnacle #oddsFormat').html(oddsFormat);

    if(data['wagerType'] == 'single')
    {
        var inPlay = (data["inPlay"]) ? locale['yes']:locale['no'];

        if (data["selection"] == 'Under') 
            var overUnder = locale['under'];
        else if (data["selection"] == 'Over') 
            var overUnder = locale['over'];
        else
            var overUnder = data["selection"];

        var drawDiv = '<table style="width:100%;"><tbody class="table-md mb-4;"><tr><td colspan="12" style="background-color:#a4b7c1;color:black;text-align:center;">'+
        data["eventName"]+'</td></tr><tr><td style="width:50%">'+locale['league']+'</td><td>'+data["league"]+'</td></tr><tr><td colspan="12" style="text-align:center;">'+
        data['homeTeam']+'<b><span style="color:red">&nbsp;&nbsp;&nbsp;&nbsp; -VS- &nbsp;&nbsp;&nbsp;&nbsp;</span></b>'+data['awayTeam']+'</td></tr>'+
         '<tr><td>'+locale['game']+'</td><td>'+sportId+'</td></tr><tr><td>'+locale['result']+'</td><td>'+result+'</td><tr><td>'+locale['matchDate']+'</td><td>'+data["eventDateFm"]+
        '</td></tr><tr><td>'+locale['selection']+'</td><td>'+overUnder+
        '</td></tr><tr><td>'+locale['inplayScore']+'</td><td>'+data["inplayScore"]+
        '</td></tr><tr><td>'+locale['inPlay']+'</td><td>'+inPlay+'</td></tr><tr id="handicapRow" style="'+displayType+'"><td>'+locale['handicap']+'</td><td id="handicapAmt">'+data['handicap']+'</td></tr></tbody></table>';
        
        $("#modal-pinnacle #pinGameInfo").prepend(drawDiv);
    }
    if(data['wagerType'] == 'parlay' || data['wagerType'] == 'teaser')
    {
        var events =  data['parlaySelections'];
        var maxEvent = events.length;
        var div = "";

        for (a = 0; a < maxEvent; a++) 
        {
            var resultValue = "";
            var active = "";
            var drawIndicator = "";
            var carouselIndicator = "";
            flag = true;

            //give the first event as active page
            if(a == 0)
            {
                active = "active";
            }

            switch(data["betType"])
            {
                case 1:
                    var betType = locale["1X2"];
                    break;

                case 2:
                    var betType = locale["handicap"];
                    break;

                case 3:
                    var betType = locale["overunder"];
                    break;

                case 4:
                    var betType = locale["hometotalbet"];
                    break;

                case 5:
                    var betType = locale["awaytotalbet"];
                    break;

                case 6:
                    var betType = locale["mixparlay"];
                    break;

                case 7:
                    var betType = locale["teaser"];
                    break;

                case 8:
                    var betType = locale['manualplay'];
                    break;

                case 97:
                    var betType = locale['oddeven'];
                    break;

                case 99:
                    var betType = locale['special'];
                    break;

                default:
                    var betType = "N/A";
                    break;
            }

            switch(events[a]['legStatus'])
            {
                case 'WON':
                var legStatus = locale['win'];
                break;

                case 'WIN':
                var legStatus = locale['win'];
                break;

                case 'LOSE':
                var legStatus = locale['lose'];
                break;

                case 'DRAW':
                var legStatus = locale['tie'];
                break;

                case 'ACCEPTED':
                var legStatus = locale['accepted'];
                break;

                default:
                var legStatus = "N/A";
                break;

            }

            var drawDiv = '<div class="carousel-item '+active+'"><table style="width:100%;"><span>'+locale['event']+' '+(a+1)+'/'+maxEvent+'</span><tbody class="table-md mb-4;"><tr><td colspan="12" style="background-color:#a4b7c1;color:black;text-align:center;">'+
            events[a]["league"]+'</td></tr><tr><td style="width:50%">'+locale['game']+'</td><td>'+events[a]["sport"]+'</td></tr><tr><td colspan="12" style="text-align:center;"><b>'+
            events[a]["homeTeam"]+'<span style="color:red">&nbsp;&nbsp;&nbsp;&nbsp; -VS- &nbsp;&nbsp;&nbsp;&nbsp;</span>'+events[a]["awayTeam"]+'</b></td></tr>'+
            '<tr><td>'+locale['result']+'</td><td>'+legStatus+'</td></tr>'+
            '<td>'+locale['betType']+'</td><td>'+betType+'</td></tr><tr><td>'+locale['matchDate']+'</td><td>'+events[a]["eventDateFm"]+
            '</td></tr><tr><td>'+locale['selection']+'</td><td>'+events[a]["selection"]+'</td></tr><tr id="handicapRow" style="'+displayType+'"><td>'+locale['handicap']+'</td><td id="handicapAmt">'+data['handicap']+'</td></tbody></table></div>';


            div = div.concat(drawDiv);

            if(a == (events.length-1))
            {
                //concat all the event in carousel
                if(flag)
                {   
                    var carousel = '<div class="carousel-inner">'+div+'</div><a class="carousel-control-prev" href="#pinGameInfo" data-slide="prev">'+
                    '<span class="carousel-control-prev-icon"></span></a><a class="carousel-control-next" href="#pinGameInfo" data-slide="next">'+
                    '<span class="carousel-control-next-icon"></span></a>';

                    $("#modal-pinnacle #pinGameInfo").prepend(carousel);
                }

                break;
            }
        }


    }
}

function getDataTableDetailsRCG(data,username,roundId,txnId)
{
    try
    {
        $("#modalDetails").modal('show');
    }
    catch(e)
    {
        
    }

    $('#modal-table').show();
    $('#modal-json').hide();
    $('#modal-mg').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $('#modal-dowin').hide();
    $('#modal-dd').hide();
    $('#modal-og').hide();
    $('#modal-rizal').hide();
    $('#modal-arn').hide();
    $('#modal-yes8').hide();
    $('#modal-scepb').hide();
    $('#tableName').hide();
    $('#playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
    $('#serialNo').html(roundId);
    $('#status').html(locale['resolved']);
    $('#time').html(data[0]['gameId']+' '+locale['on']+' '+data[0]['dateTime'].replace(/T|Z/gi,' ').split('.')[0]);
    $('#tableId').html(data[0]['desk']);
    $('#tableNameRCG').html(data[0]['tableName']);
    $('#endTime').html(data[0]['reportDT'].replace(/T|Z/gi,' ').split('.')[0]);
    $('#multipleBet').show();
    $('#betResult').hide();
    $('#hand').hide();
    $('#images').hide();
    $('#cardTitle').hide();
    $('#cards').hide();
    $('#score').hide();

    var bet = 0;
    var payout = 0;
    var net = 0;
    
    $.each(data, function( index, value ) 
    {
        var tr = document.createElement('tr');
        var tdBet = document.createElement('td');
        var tdAmount = document.createElement('td');
        var tdNet = document.createElement('td');
        var tdTxn = document.createElement('td');
        var tdTime = document.createElement('td');
        var multiTxnId = '';

        $(tdBet).attr('colspan','2');
        $(tdAmount).attr('colspan','2');
        $(tdNet).attr('colspan','2');
        $(tdTxn).attr('colspan','3');
        $(tdTime).attr('colspan','3');

        
        $(tr).append(tdBet);
        $(tr).append(tdAmount);
        $(tr).append(tdNet);
        $(tr).append(tdTxn);
        $(tr).append(tdTime);
        $(tr).attr('class', 'removeClass');
        $('#multipleBet').after(tr);
        $(tdBet).html(data[index]['betArea']);
        $(tdAmount).html(utils.formatMoney(data[index]['bet']) );
        $(tdNet).html(utils.formatMoney(data[index]['winLose']));
        if(data[index]['transactions'] === null){
            $.each(data[index], function(txn_key,txn_value){
                    multiTxnId = data[index]['id'];
            });
        }
        else
        { 
            $.each(data[index]['transactions'], function(txn_key,txn_value){
                if(txn_key == 0)
                {
                    multiTxnId = txn_value.id;
                }
                else
                {
                    multiTxnId = multiTxnId + ',' + txn_value.id;
                }
            });
        }
        $(tdTxn).html(multiTxnId ?? txnId);
        // $(tdTxn).html((data[index]['transactions']) ? data[index]['transactions'][0]['id'] : txnId);
        $(tdTime).html(data[index]['dateTime']);
        bet += data[index]['bet'];
        payout += (data[index]['winLose'] + data[index]['bet']);
        net += data[index]['winLose'];
    });

    $('#totalBet').html(utils.formatMoney(bet));
    $('#payment').html(utils.formatMoney(payout));
    $('#net').html(utils.formatMoney(net));
}

function getDataTableDetailRizal(data,txnId)
{
    $("#modalDetails").modal('show'); 
    $('#modal-rizal').show();
    $("#modal-json").hide();

    var rizalBetDetailClass = document.getElementsByClassName("rizal-bet-detail");
    while(rizalBetDetailClass.length > 0)
    {
        rizalBetDetailClass[0].parentNode.removeChild(rizalBetDetailClass[0]);
    }

    switch(data['result'])
    {
        case '1':
        var results = locale['cancel'];
        break;

        case '2':
        var results = locale['lose'];
        break;

        case '3':
        var results = locale['win'];
        break;

        case '4':
        var results = locale['tie'];
        break;
    }

    $('#modal-rizal #roundId').html(locale['roundId']+": "+data['uuid']);
    $('#modal-rizal #totalDebit').html(data['payin']);
    $('#modal-rizal #totalCredit').html(data['payout']);
    $('#modal-rizal #results').html(results);
    $('#modal-rizal #txnId').html(txnId);


    var tbl = document.getElementById("table-rizal");
    if(parseInt(data['bets']["pw"]) > 0)
    {
        var row = document.createElement("tr");

        var betTypeCell = document.createElement("td");
        betTypeCell.setAttribute("colspan", 6);
        betTypeCell.setAttribute("class", "rizal-bet-detail");

        betTypeCell.innerHTML = locale['player'];

        var stakeCell = document.createElement("td");
        stakeCell.setAttribute("colspan", 6);
        stakeCell.setAttribute("class", "rizal-bet-detail");
        stakeCell.innerHTML = data['bets']["pw"];

        row.appendChild(betTypeCell);
        row.appendChild(stakeCell);
        tbl.appendChild(row);
    }
    if(parseInt(data['bets']["bw"]) > 0)
    {
        var row = document.createElement("tr");

        var betTypeCell = document.createElement("td");
        betTypeCell.setAttribute("colspan", 6);
        betTypeCell.setAttribute("class", "rizal-bet-detail");

        betTypeCell.innerHTML = locale['banker'];

        var stakeCell = document.createElement("td");
        stakeCell.setAttribute("colspan", 6);
        stakeCell.setAttribute("class", "rizal-bet-detail");
        stakeCell.innerHTML = data['bets']["bw"];

        row.appendChild(betTypeCell);
        row.appendChild(stakeCell);
        tbl.appendChild(row);

    }
    if(parseInt(data['bets']["tw"]) > 0)
    {
        var row = document.createElement("tr");

        var betTypeCell = document.createElement("td");
        betTypeCell.setAttribute("colspan", 6);
        betTypeCell.setAttribute("class", "rizal-bet-detail");

        betTypeCell.innerHTML = locale['tie'];

        var stakeCell = document.createElement("td");
        stakeCell.setAttribute("colspan", 6);
        stakeCell.setAttribute("class", "rizal-bet-detail");
        stakeCell.innerHTML = data['bets']["tw"];

        row.appendChild(betTypeCell);
        row.appendChild(stakeCell);
        tbl.appendChild(row);

    }
    if(parseInt(data['bets']["pp"]) > 0)
    {
        var row = document.createElement("tr");

        var betTypeCell = document.createElement("td");
        betTypeCell.setAttribute("colspan", 6);
        betTypeCell.setAttribute("class", "rizal-bet-detail");

        betTypeCell.innerHTML = locale['player_pair'];

        var stakeCell = document.createElement("td");
        stakeCell.setAttribute("colspan", 6);
        stakeCell.setAttribute("class", "rizal-bet-detail");
        stakeCell.innerHTML = data['bets']["pp"];

        row.appendChild(betTypeCell);
        row.appendChild(stakeCell);
        tbl.appendChild(row);

    }
    if(parseInt(data['bets']["bp"]) > 0)
    {
        var row = document.createElement("tr");

        var betTypeCell = document.createElement("td");
        betTypeCell.setAttribute("colspan", 6);
        betTypeCell.setAttribute("class", "rizal-bet-detail");

        betTypeCell.innerHTML = locale['banker_pair'];

        var stakeCell = document.createElement("td");
        stakeCell.setAttribute("colspan", 6);
        stakeCell.setAttribute("class", "rizal-bet-detail");
        stakeCell.innerHTML = data['bets']["bp"];

        row.appendChild(betTypeCell);
        row.appendChild(stakeCell);
        tbl.appendChild(row);

    }
}

function getDataTableDetailBti(data,username,txnId)
{
    var resultStatus = (data['status']);

    if(resultStatus == '1')
    {
        var dataResult = data['bet_result'];
        var purchases = dataResult['Purchases']['Purchase'];
        var selections = purchases['Selections']['Selection'];
        var type = 0;
        
        if (selections.hasOwnProperty('Changes')) 
        {
            var changes = selections['Changes']['Change'];
            var changeStatus = selections['Changes']['Change'];
            selections = purchases['Selections']['Selection'];
        }
        else if (selections.hasOwnProperty('Bet')) 
        {
            var changes = selections[0]['Changes']['Change'];
            var changeStatus = selections[0]['Changes']['Change'];
            selections = purchases['Selections']['Selection'][0];
        }
        else
        {
            var changes = selections[1]['Changes']['Change'];
            var changeStatus = selections[0]['Changes']['Change'];
            selections = purchases['Selections']['Selection'][1];
            type = 1;
        }

        var bets = changes['Bets']['Bet'];

        switch(changeStatus['@attributes']['NewStatus'])
        {
            case 'Won':
            var result = locale['win'];
            var color = 'color:#53ff1b';
            break;

            case 'Lost':
            var result = locale['lose'];
            var color = 'color:red';
            break;

            case 'Cashout':
            var result = locale['cashout'];
            var color = 'color:#000000';
            break;

            case 'Draw':
            var result = locale['draw'];
            var color = 'color:#000000';
            break;

            case 'Canceled':
            var result = locale['cancel'];
            var color = 'color:#000000';
            break;

            case 'Half Won':
            var result = locale['halfwon'];
            var color = 'color:#000000';
            break;

            case 'Half Lost':
            var result = locale['halflose'];
            var color = 'color:#000000';
            break;

            case 'Opened':
            var result = locale['open'];
            var color = 'color:#000000';
            break;

            default:
            var result = bets['@attributes']['NewStatus'];
            var color = 'color:#000000';
            break;
        }

        switch(bets['@attributes']['NewStatus'])
        {
            case 'Won':
            var betResult = locale['win'];
            var color = 'color:#53ff1b';
            break;

            case 'Lost':
            var betResult = locale['lose'];
            var color = 'color:red';
            break;

            case 'Half Lost':
            var betResult = "1/2 "+locale['lose'];
            var color = 'color:#000000';
            break;

            case 'Cashout':
            var betResult = locale['cashout'];
            var color = 'color:#000000';
            break;

            case 'Opened':
            var betResult = locale['cashout'];
            var color = 'color:#000000';
            break;

            default:
            var betResult = bets['@attributes']['NewStatus'];
            var color = 'color:#000000';
            break;
        }

        var betSettledDate = bets['@attributes']['BetSettledDate'];
        var net = parseFloat(data['credit']) - data['debit'];
        $('#modal-pinnacle #net').html(utils.formatMoney(net));

        $("#modalDetails").modal('show'); 
        $('#modal-pinnacle').show();
        $("#modal-json").hide();
        $("#modal-pinnacle #pinGameInfo").empty();

        $('#modal-pinnacle #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
        $('#modal-pinnacle #txnId').html(txnId);
        $('#modal-pinnacle #status').html(locale['resolved']);
        $('#modal-pinnacle #placetime').html(data['created_at']);
        $('#modal-pinnacle #endTime').html(data['endDate'].replace(/T|Z/gi,' '));
        $('#modal-pinnacle #totalBet').html(utils.formatMoney(data['debit']));
        $('#modal-pinnacle #payment').html(utils.formatMoney(parseFloat(data['credit'])));
        $('#modal-pinnacle #results').html(result);
        $('#modal-pinnacle #results').attr('style',color);
        $('#modal-pinnacle #type').html(bets['@attributes']['BetType']);
        $('#modal-pinnacle #odds').html(purchases['@attributes']['PurchaseID']);
        $('#modal-pinnacle #oddsName').html(locale['roundId']);
        $('#modal-pinnacle #oddsFormat').html('EU');

        if(data['handicap'] == 0)
        {
            displayType = "display: none;";
        }
        else
        {
            displayType = "";
        }

        var inPlay = (data["inPlay"]) ? locale['yes']:locale['no'];
        
        $("#modal-pinnacle #pinGameInfo").prepend(drawDiv);

        // for cashout betdetail
        if (bets['@attributes']['BetTypeID'] == 2 && purchases['Selections']['Selection'].length > 1) 
        {
            var div = "";
            for (var a = 0; a < purchases['Selections']['Selection'].length; a++) 
            {
                selections = purchases['Selections']['Selection'][a];
                var resultValue = "";
                var active = "";
                var drawIndicator = "";
                var carouselIndicator = "";
                flag = true;

                //give the first event as active page
                if(a == 0)
                {
                    active = "active";
                }

                var drawDiv = '<div class="carousel-item '+active+'"><table style="width:100%;"><tbody class="table-md mb-4;"><tr><td colspan="12" style="background-color:#a4b7c1;color:black;text-align:center;">'+
                selections['@attributes']["LeagueName"]+'</td></tr><tr><td style="width:50%">'+locale['game']+'</td><td>'+selections['@attributes']["BranchName"]+'</td></tr><tr><td colspan="12" style="text-align:center;">'+
                selections['@attributes']['HomeTeam']+'<b><span style="color:red">&nbsp;&nbsp;&nbsp;&nbsp; -VS- &nbsp;&nbsp;&nbsp;&nbsp;</span></b>'+selections['@attributes']['AwayTeam']+'</td></tr>'+
                '<tr><td>'+locale['matchDate']+'</td><td>'+selections['@attributes']["EventDateUTC"]+
                '</td></tr><tr><td>'+locale['selection']+'</td><td>'+selections['@attributes']["YourBet"]+
                '</td></tr><tr><td>'+locale['result']+'</td><td>'+changes['@attributes']["NewStatus"]+
                '</td></tr><tr><td>'+locale['odd']+'</td><td>'+selections['@attributes']["DecimalOdds"]+
                '</td></tr><tr><td>'+locale['eventName']+'</td><td>'+selections['@attributes']["EventTypeName"]+
                '</td></tr></tbody></table></div>';

                div = div.concat(drawDiv);

                if(a == (purchases['Selections']['Selection'].length-1))
                {
                    //concat all the event in carousel
                    if(flag)
                    {   
                        var carousel = '<div class="carousel-inner">'+div+'</div><a class="carousel-control-prev" href="#pinGameInfo" data-slide="prev">'+
                        '<span class="carousel-control-prev-icon"></span></a><a class="carousel-control-next" href="#pinGameInfo" data-slide="next">'+
                        '<span class="carousel-control-next-icon"></span></a>';

                        $("#modal-pinnacle #pinGameInfo").prepend(carousel);
                    }

                    break;
                }
            }
        }
        else
        {
            // for system which have multiple row in cache betdetail
            if (data.hasOwnProperty('bet_result1') && type == 0) 
            {
                var div = "";
                for (var a = 0; a < data['length']; a++) 
                {
                    var num = (a == 0) ? '':a;
                    var betResultName = 'bet_result'+ num;
                    var dataResult = data[betResultName];
                    var purchases = dataResult['Purchases']['Purchase'];
                    var selections = purchases['Selections']['Selection'];
                    var changes = selections['Changes']['Change'];
                    var bets = changes['Bets']['Bet'];

                    var resultValue = "";
                    var active = "";
                    var drawIndicator = "";
                    var carouselIndicator = "";
                    flag = true;

                    //give the first event as active page
                    if(a == 0)
                    {
                        active = "active";
                    }
                    
                    var drawDiv = '<div class="carousel-item '+active+'"><table style="width:100%;"><tbody class="table-md mb-4;"><tr><td colspan="12" style="background-color:#a4b7c1;color:black;text-align:center;">'+
                    selections['@attributes']["LeagueName"]+'</td></tr><tr><td style="width:50%">'+locale['game']+'</td><td>'+selections['@attributes']["BranchName"]+'</td></tr><tr><td colspan="12" style="text-align:center;">'+
                    selections['@attributes']['HomeTeam']+'<b><span style="color:red">&nbsp;&nbsp;&nbsp;&nbsp; -VS- &nbsp;&nbsp;&nbsp;&nbsp;</span></b>'+selections['@attributes']['AwayTeam']+'</td></tr>'+
                    '<tr><td>'+locale['matchDate']+'</td><td>'+selections['@attributes']["EventDateUTC"]+
                    '</td></tr><tr><td>'+locale['selection']+'</td><td>'+selections['@attributes']["YourBet"]+
                    '</td></tr><tr><td>'+locale['result']+'</td><td>'+changes['@attributes']["NewStatus"]+
                    '</td></tr><tr><td>'+locale['odd']+'</td><td>'+selections['@attributes']["DecimalOdds"]+
                    '</td></tr><tr><td>'+locale['eventName']+'</td><td>'+selections['@attributes']["EventTypeName"]+
                    '</td></tr></tbody></table></div>';

                    div = div.concat(drawDiv);

                    if(a == (data['length']-1))
                    {
                        //concat all the event in carousel
                        if(flag)
                        {   
                            var carousel = '<div class="carousel-inner">'+div+'</div><a class="carousel-control-prev" href="#pinGameInfo" data-slide="prev">'+
                            '<span class="carousel-control-prev-icon"></span></a><a class="carousel-control-next" href="#pinGameInfo" data-slide="next">'+
                            '<span class="carousel-control-next-icon"></span></a>';

                            $("#modal-pinnacle #pinGameInfo").prepend(carousel);
                        }

                        break;
                    }
                }
            }
            else
            {
                var status = (type == 0) ? changes['@attributes']["NewStatus"] : purchases['Selections']['Selection'][0]['Changes']['Change']['@attributes']["NewStatus"];

                var drawDiv = '<table style="width:100%;"><tbody class="table-md mb-4;"><tr><td colspan="12" style="background-color:#a4b7c1;color:black;text-align:center;">'+
                selections['@attributes']["LeagueName"]+'</td></tr><tr><td style="width:50%">'+locale['game']+'</td><td>'+selections['@attributes']["BranchName"]+'</td></tr><tr><td colspan="12" style="text-align:center;">'+
                selections['@attributes']['HomeTeam']+'<b><span style="color:red">&nbsp;&nbsp;&nbsp;&nbsp; -VS- &nbsp;&nbsp;&nbsp;&nbsp;</span></b>'+selections['@attributes']['AwayTeam']+'</td></tr>'+
                '<tr><td>'+locale['matchDate']+'</td><td>'+selections['@attributes']["EventDateUTC"]+
                '</td></tr><tr><td>'+locale['selection']+'</td><td>'+selections['@attributes']["YourBet"]+
                '</td></tr><tr><td>'+locale['odd']+'</td><td>'+selections['@attributes']["DecimalOdds"]+
                '</td></tr><tr><td>'+locale['eventName']+'</td><td>'+selections['@attributes']["LineTypeName"]+
                '</td></tr><tr><td>'+locale['result']+'</td><td>'+ status +
                '</td></tr></tbody></table>';

                $("#modal-pinnacle #pinGameInfo").prepend(drawDiv);
            }
        }
    }
    else if(resultStatus == '0')
    {
        var dataResult = data['response'];
        var betData = dataResult['Bet'];
        var selections = betData;
        var type = 0;

        var result = locale['open'];
        var color = 'color:#000000';

        // var betSettledDate = bets['@attributes']['BetSettledDate'];
        var net = '-';
        $('#modal-pinnacle #net').html(net);

        $("#modalDetails").modal('show'); 
        $('#modal-pinnacle').show();
        $("#modal-json").hide();
        $("#modal-pinnacle #pinGameInfo").empty();

        $('#modal-pinnacle #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
        $('#modal-pinnacle #txnId').html(txnId);
        $('#modal-pinnacle #status').html(locale['open']);
        $('#modal-pinnacle #placetime').html(data['created_at']);
        $('#modal-pinnacle #endTime').html(data['endDate'].replace(/T|Z/gi,' '));
        $('#modal-pinnacle #totalBet').html(utils.formatMoney(data['debit']));
        $('#modal-pinnacle #payment').html(data['credit']);
        $('#modal-pinnacle #results').html(result);
        $('#modal-pinnacle #results').attr('style',color);
        $('#modal-pinnacle #type').html(data['betType']);
        $('#modal-pinnacle #odds').html(data['txn_id']);
        $('#modal-pinnacle #oddsName').html(locale['roundId']);
        $('#modal-pinnacle #oddsFormat').html('EU');

        if(data['handicap'] == 0)
        {
            displayType = "display: none;";
        }
        else
        {
            displayType = "";
        }

        // var inPlay = (data["inPlay"]) ? locale['yes']:locale['no'];
        
        $("#modal-pinnacle #pinGameInfo").prepend(drawDiv);
        //for multiple line
        if(betData.hasOwnProperty('Lines'))
        {
            var div = "";
            
            for (var a = 0; a < betData['Lines'].length; a++) 
            {
                selections = betData['Lines'][a];
                var resultValue = "";
                var active = "";
                var drawIndicator = "";
                var carouselIndicator = "";
                var eventDate = selections['@attributes']["EventDateUTC"] ?? selections['@attributes']["EventDate"];
                flag = true;

                //give the first event as active page
                if(a == 0)
                {
                    active = "active";
                }
                var drawDiv = '<div class="carousel-item '+active+'"><table style="width:100%;"><tbody class="table-md mb-4;"><tr><td colspan="12" style="background-color:#a4b7c1;color:black;text-align:center;">'+
                selections['@attributes']["LeagueName"]+'</td></tr><tr><td style="width:50%">'+locale['game']+'</td><td>'+selections['@attributes']["BranchName"]+'</td></tr><tr><td colspan="12" style="text-align:center;">'+
                selections['@attributes']['HomeTeam']+'<b><span style="color:red">&nbsp;&nbsp;&nbsp;&nbsp; -VS- &nbsp;&nbsp;&nbsp;&nbsp;</span></b>'+selections['@attributes']['AwayTeam']+'</td></tr>'+
                '<tr><td>'+locale['matchDate']+'</td><td>'+eventDate+
                '</td></tr><tr><td>'+locale['selection']+'</td><td>'+selections['@attributes']["YourBet"]+
                '</td></tr><tr><td>'+locale['result']+'</td><td>'+selections['@attributes']["Status"]+
                '</td></tr><tr><td>'+locale['odd']+'</td><td>'+selections['@attributes']["OddsDec"]+
                '</td></tr><tr><td>'+locale['eventName']+'</td><td>'+selections['@attributes']["EventTypeName"]+
                '</td></tr></tbody></table></div>';

                div = div.concat(drawDiv);

                if(a == (betData['Lines'].length-1))
                {
                    //concat all the event in carousel
                    if(flag)
                    {   
                        var carousel = '<div class="carousel-inner">'+div+'</div><a class="carousel-control-prev" href="#pinGameInfo" data-slide="prev">'+
                        '<span class="carousel-control-prev-icon"></span></a><a class="carousel-control-next" href="#pinGameInfo" data-slide="next">'+
                        '<span class="carousel-control-next-icon"></span></a>';

                        $("#modal-pinnacle #pinGameInfo").prepend(carousel);
                    }

                    break;
                }
            }
        }
        else
        {
            var drawDiv = '<table style="width:100%;"><tbody class="table-md mb-4;"><tr><td colspan="12" style="background-color:#a4b7c1;color:black;text-align:center;">'+
                selections['@attributes']["LeagueName"]+'</td></tr><tr><td style="width:50%">'+locale['game']+'</td><td>'+selections['@attributes']["BranchName"]+'</td></tr><tr><td colspan="12" style="text-align:center;">'+
                selections['@attributes']['HomeTeam']+'<b><span style="color:red">&nbsp;&nbsp;&nbsp;&nbsp; -VS- &nbsp;&nbsp;&nbsp;&nbsp;</span></b>'+selections['@attributes']['AwayTeam']+'</td></tr>'+
                '<tr><td>'+locale['matchDate']+'</td><td>'+selections['@attributes']["EventDate"]+
                '</td></tr><tr><td>'+locale['selection']+'</td><td>'+selections['@attributes']["YourBet"]+
                '</td></tr><tr><td>'+locale['odd']+'</td><td>'+selections['@attributes']["OddsDec"]+
                '</td></tr><tr><td>'+locale['eventName']+'</td><td>'+selections['@attributes']["LineTypeName"]+
                '</td></tr><tr><td>'+locale['result']+'</td><td>'+ selections['@attributes']["Status"] +
                '</td></tr></tbody></table>';

                $("#modal-pinnacle #pinGameInfo").prepend(drawDiv);
        }
    }
    else
    {
        alert(locale['game_not_found']);
    }
}

function getDataTableDetailDoWin(data, username, txnId)
{
    $("#modalDetails").modal('show'); 
    $("#modal-json").hide();

    $('#modal-table').hide();
    $('#modal-mg').hide();
    $('#modal-vegas').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $("#modal-dd").hide();
    $('#modal-dowin').show();
    $('#modal-og').hide();
    $('#modal-arn').hide();

    $('#modal-dowin #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');

    $('#modal-dowin #txnId').html(data['txn_id']);
    $('#modal-dowin #roundId').html(data['round_id']);
    $('#modal-dowin #placetime').html(data['created_at']);

    $('#modal-dowin #bet-placed').html(data['bet_type']);
    $('#modal-dowin #results').html(data['result']);

    $('#modal-dowin #player-hand').empty();
    $('#modal-dowin #banker-hand').empty();

    for (var i = 0; i < data['player_cards'].length; i++) 
    {
        var playerCard = data['player_cards'][i];
        var imagePlayer = document.createElement('img');
        $(imagePlayer).attr('class', 'imgStyle');
        $(imagePlayer).attr('src', '/images/cardsTS/' + playerCard + '.png');
        $('#modal-dowin #player-hand').append(imagePlayer);
    }

    for (var i = 0; i < data['banker_cards'].length; i++) 
    {
        var bankerCard = data['banker_cards'][i];
        var imageBanker = document.createElement('img');
        $(imageBanker).attr('class', 'imgStyle');
        $(imageBanker).attr('src', '/images/cardsTS/' + bankerCard + '.png');
        $('#modal-dowin #banker-hand').append(imageBanker);
    }

    var payoutColor = (data['total_payout'] > 0) ? 'color:blue' : 'color:red';
    $('#modal-dowin #payment').attr('style', `${payoutColor}; font-size: 14px;`);

    var netColor = (data['total_net'] > 0) ? 'color:blue' : 'color:red';
    $('#modal-dowin #net').attr('style', `${netColor}; font-size: 14px;`);

    $('#modal-dowin #totalBet').html(utils.formatMoney(data['total_bet']));
    $('#modal-dowin #payment').html(utils.formatMoney(data['total_payout']));
    $('#modal-dowin #net').html(utils.formatMoney(data['total_net']));
}

function getDataTableDetailDD(data,username,txnId)
{
    $("#modalDetails").modal('show'); 
    $("#modal-json").hide();

    $('#modal-table').hide();
    $('#modal-mg').hide();
    $('#modal-vegas').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $('#modal-dowin').hide();
    $('#modal-dd').show();
    $('#modal-og').hide();
    $('#modal-arn').hide();

    $('#modal-dd #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');

    //0-banker, 1-player, 2-tie, 3-banker pair, 4-player pair

    $('#modal-dd #txnId').html(data['txnId']);
    $('#modal-dd #placetime').html(data['placeOn']);

    $('#modal-dd #bet-placed').html(data['betPlaced']);
    $('#modal-dd #results').html(data['result']);

    var tdImgDealer = document.createElement('td');
    var tdImgPlayer = document.createElement('td');
    $(tdImgDealer).attr('colspan','6');
    $(tdImgPlayer).attr('colspan','6');
    $('#modal-dd #images').attr('class','emptyContent');

    for (var i = 0; i < data['playerHand'].length ; i++) 
        {
            if(data['playerHand'][i] != '00')
            { 
                var img = data['playerHand'][i];
                var imageDealer = document.createElement('img');
                $(imageDealer).attr('class', 'imgStyle');
                $(imageDealer).attr('src','/images/cardsDD/' + img + '.png');
                $(tdImgDealer).append(imageDealer);
                $('#modal-dd #images').append(tdImgDealer);
            }
            
        }

        for (var i = 0; i < data['bankerHand'].length; i++) 
        {
            if(data['bankerHand'][i] != '00')
            {
                var img = data['bankerHand'][i];
                var imagePlayer = document.createElement('img');
                $(imagePlayer).attr('class', 'imgStyle');
                $(imagePlayer).attr('src','/images/cardsDD/' + img + '.png');
                $(tdImgPlayer).append(imagePlayer);
                $('#modal-dd #images').append(tdImgPlayer);
            }
            
        }

    $('#modal-dd #banker-hand').html(data['bankerHand']);
    $('#modal-dd #player-hand').html(data['playerHand']);
    $('#modal-dd #totalBet').html(utils.formatMoney(data['debit']));

    $('#modal-dd #payment').html(utils.formatMoney(data['credit']));
    $('#modal-dd #net').html(utils.formatMoney(data['netCash']));
}

//for transfer OG
function getDataTableDetailOG(data,username,txnId)
{
    $("#modalDetails").modal('show'); 
    $("#modal-json").hide();

    $('#modal-table').hide();
    $('#modal-mg').hide();
    $('#modal-vegas').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $("#modal-dd").hide();
    $('#modal-dowin').hide();
    $('#modal-og').show();
    $('#modal-arn').hide();

    $('#modal-og #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');

    $('#modal-og #time').html(data['gameName']+' '+locale['on']+' '+ data['placeOn']);

    $('#modal-og #txnId').html(data['txnId']);
    $('#modal-og #placetime').html(data['placeOn']);
    $('#modal-og #bet-placed').html(data['betPlaced']);
    $('#modal-og #results').html(data['result']);

    $('#modal-og #gameInfo,#modal-og #images').html('');

    if(data['game_id'] == 'SPEED BACCARAT' || data['game_id'] == 'BACCARAT' || data['game_id'] == 'BIDDING BACCARAT' || data['game_id'] == 'NO COMMISSION BACCARAT')
    {
        var drawTitle = '<th colspan="6" class="draw-title">Player</th>\
        <th colspan="6" class="draw-title">Banker</th>';

        $('#modal-og #gameInfo').append(drawTitle);

        var card = data['gameInfo']['playerCards'].split(',');

        var drawCard = '<td colspan="6">';

        $.each(card, function( index, value ) 
        {
            if(value != '')
                drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });

        drawCard += '</td>';

        var card = data['gameInfo']['bankerCards'].split(',');

        drawCard += '<td colspan="6">';

        $.each(card, function( index, value ) 
        {
            if(value != '')
                drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });

        drawCard += '</td>';

        $('#modal-og #images').append(drawCard);
    }
    else if(data['game_id'] == 'THREE CARDS' || data['game_id'] == 'GOLDEN FLOWER')
    {
        var drawTitle = '<th colspan="6" class="draw-title">Dragon</th>\
        <th colspan="6" class="draw-title">Phoenix</th>';

        $('#modal-og #gameInfo').append(drawTitle);

        var card = data['gameInfo']['dragonCards'].split(',');

        var drawCard = '<td colspan="6"><div>'+data['gameInfo']['dragonResult']+'</div>';

        $.each(card, function( index, value ) 
        {
            drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });

        drawCard += '</td>';

        var card = data['gameInfo']['phoenixCards'].split(',');

        drawCard += '<td colspan="6"><div>'+data['gameInfo']['phoenixResult']+'</div>';

        $.each(card, function( index, value ) 
        {
            drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });

        drawCard += '</td>';

        $('#modal-og #images').append(drawCard);
    }
    else if(data['game_id'] == 'SICBO')
    {
        var dice = data['gameInfo']['values'].split(',');

        var drawDice = '<td colspan="12">';

        $.each(dice, function( index, value ) 
        {
            drawDice += '<img class="imgStyle" src="/images/cardsSXG/'+value+'.png">';
        });

        drawDice += '</td>';

        $('#modal-og #images').append(drawDice);
    }
    else if(data['game_id'] == 'NEW DT' || data['game_id'] == 'CLASSIC DT')
    {
        var drawTitle = '<th colspan="6" class="draw-title">Dragon</th>\
        <th colspan="6" class="draw-title">Tiger</th>';

        $('#modal-og #gameInfo').append(drawTitle);

        var drawCard = '<td colspan="6"><img class="imgStyle" src="/evol/dist/images/cardsEVO/'+data['gameInfo']['dragonCards']+'.png"></td>\
        <td colspan="6"><img class="imgStyle" src="/evol/dist/images/cardsEVO/'+data['gameInfo']['tigerCards']+'.png"></td>';


        $('#modal-og #images').append(drawCard);
    }
    else if(data['game_id'] == 'BULL BULL' || data['game_id'] == 'NIUNIU')
    {
        var drawTitle = '<th colspan="1" class="draw-title">First</th>\
        <th colspan="2" class="draw-title">Banker</th>\
        <th colspan="3" class="draw-title">Player1</th>\
        <th colspan="3" class="draw-title">Player2</th>\
        <th colspan="3" class="draw-title">Player3</th>';

        $('#modal-og #gameInfo').append(drawTitle);

        var drawCard = '<td colspan="1"><img class="imgStyle" src="/evol/dist/images/cardsEVO/'+data['gameInfo']['first']+'.png"></td>';

        var card = data['gameInfo']['banker'].split(',');
        drawCard += '<td colspan="2">';
        $.each(card, function( index, value ) 
        {
            drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });
        drawCard += '</td>';

        var card = data['gameInfo']['player1'].split(',');
        drawCard += '<td colspan="3">';
        $.each(card, function( index, value ) 
        {
            drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });
        drawCard += '</td>';

        var card = data['gameInfo']['player2'].split(',');
        drawCard += '<td colspan="3">';
        $.each(card, function( index, value ) 
        {
            drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });
        drawCard += '</td>';

        var card = data['gameInfo']['player3'].split(',');
        drawCard += '<td colspan="3">';
        $.each(card, function( index, value ) 
        {
            drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });
        drawCard += '</td>';

        $('#modal-og #images').append(drawCard);
    }
    else if(data['game_id'] == 'ROULETTE' || data['game_id'] == 'MONEYWHEEL')
    {
        $('#modal-og #results').html(data['gameInfo']['value']);
    }

    $('#modal-og #totalBet').html(utils.formatMoney(data['debit']));

    $('#modal-og #payment').html(utils.formatMoney(data['credit']));
    $('#modal-og #net').html(utils.formatMoney(data['netCash']));
}

// for seamless OG
function getDataTableDetailSwOG(data,username,txnId)
{
    $("#modalDetails").modal('show'); 
    $("#modal-json").hide();

    $('#modal-table').hide();
    $('#modal-mg').hide();
    $('#modal-vegas').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $("#modal-dd").hide();
    $('#modal-dowin').hide();
    $('#modal-og').show();
    $('#modal-arn').hide();

    $('#modal-og #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');

    $('#modal-og #time').html(data['gameName']+' '+locale['on']+' '+ data['placeOn']);

    $('#modal-og #txnId').html(data['txnId']);
    $('#modal-og #placetime').html(data['placeOn']);
    $('#modal-og #bet-placed').html(data['betPlaced']);
    $('#modal-og #results').html(data['result']);

    $('#modal-og #gameInfo,#modal-og #images').html('');

    if(data['game_id'] == 'M1_baccarat' || data['game_id'] == 'M2_baccarat' || data['game_id'] == 'M3_baccarat' || data['game_id'] == 'M5_baccarat'
        || data['game_id'] == 'M6_baccarat' || data['game_id'] == 'M7_baccarat' || data['game_id'] == 'C1_baccarat' || data['game_id'] == 'C2_baccarat'
        || data['game_id'] == 'C3_baccarat' || data['game_id'] == 'C5_baccarat' || data['game_id'] == 'C6_baccarat' || data['game_id'] == 'C7_baccarat'
        || data['game_id'] == 'E1_baccarat' || data['game_id'] == 'E2_baccarat' || data['game_id'] == 'E3_baccarat' || data['game_id'] == 'E5_baccarat'
        || data['game_id'] == 'P1_baccarat' || data['game_id'] == 'P2_baccarat' || data['game_id'] == 'P9_baccarat' || data['game_id'] == 'P10_baccarat'
        || data['game_id'] == 'P11_baccarat' || data['game_id'] == 'P12_baccarat' || data['game_id'] == 'P9_baccarat' || data['game_id'] == 'P10_baccarat'
        || data['game_id'] == 'RNG001_baccarat' || data['game_id'] == 'RNG002_baccarat' || data['game_id'] == 'RNG003_baccarat' || data['game_id'] == 'RNG005_baccarat'
        || data['game_id'] == 'RNG006_baccarat' || data['game_id'] == 'RNG007_baccarat' || data['game_id'] == 'RNG0012_baccarat' || data['game_id'] == 'RNG0013_baccarat'
        || data['game_id'] == 'RNG0015_baccarat' || data['game_id'] == 'RNG0016_baccarat' || data['game_id'] == 'RNG0017_baccarat' || data['game_id'] == 'RNG0018_baccarat'
        
    )
    {
        var drawTitle = '<th colspan="6" class="draw-title">Player</th>\
        <th colspan="6" class="draw-title">Banker</th>';

        $('#modal-og #gameInfo').append(drawTitle);

        var card = data['gameInfo']['playerCards'].split(',');

        var drawCard = '<td colspan="6">';

        $.each(card, function( index, value ) 
        {
            if(value != '')
                drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });

        drawCard += '</td>';

        var card = data['gameInfo']['bankerCards'].split(',');

        drawCard += '<td colspan="6">';

        $.each(card, function( index, value ) 
        {
            if(value != '')
                drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });

        drawCard += '</td>';

        $('#modal-og #images').append(drawCard);
    }
    else if(data['game_id'] == 'P15_3cards' || data['game_id'] == 'P5_3cards')
    {
        var drawTitle = '<th colspan="6" class="draw-title">Dragon</th>\
        <th colspan="6" class="draw-title">Phoenix</th>';

        $('#modal-og #gameInfo').append(drawTitle);

        var card = data['gameInfo']['dragonCards'].split(',');

        var drawCard = '<td colspan="6"><div>'+data['gameInfo']['dragonResult']+'</div>';

        $.each(card, function( index, value ) 
        {
            drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });

        drawCard += '</td>';

        var card = data['gameInfo']['phoenixCards'].split(',');

        drawCard += '<td colspan="6"><div>'+data['gameInfo']['phoenixResult']+'</div>';

        $.each(card, function( index, value ) 
        {
            drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });

        drawCard += '</td>';

        $('#modal-og #images').append(drawCard);
    }
    else if(data['game_id'] == 'C12_sicbo' || data['game_id'] == 'M8_sicbo')
    {
        var dice = data['gameInfo']['values'].split(',');

        var drawDice = '<td colspan="12">';

        $.each(dice, function( index, value ) 
        {
            drawDice += '<img class="imgStyle" src="/images/cardsSXG/'+value+'.png">';
        });

        drawDice += '</td>';

        $('#modal-og #images').append(drawDice);
    }
    else if(data['game_id'] == 'C8_DT' || data['game_id'] == 'C9_DT' || data['game_id'] == 'C10_DT' || data['game_id'] == 'C11_DT' 
            || data['game_id'] == 'P6_DT' || data['game_id'] == 'P7_DT' || data['game_id'] == 'P16_DT' || data['game_id'] == 'P18_DT' || data['game_id'] == 'P19_DT'
            || data['game_id'] == 'RNG008_DT' || data['game_id'] == 'RNG009_DT' || data['game_id'] == 'RNG0010_DT' || data['game_id'] == 'RNG0011_DT'
            || data['game_id'] == 'RNG019_Classic_DT' || data['game_id'] == 'RNG020_Classic_DT' || data['game_id'] == 'RNG021_Classic_DT' || data['game_id'] == 'RNG022_Classic_DT'
            || data['game_id'] == 'RNG011_DT' || data['game_id'] == 'RNG010_DT' || data['game_id'] == 'RNG021_DT' || data['game_id'] == 'RNG022_DT'
    )
    {
        var drawTitle = '<th colspan="6" class="draw-title">Dragon</th>\
        <th colspan="6" class="draw-title">Tiger</th>';

        $('#modal-og #gameInfo').append(drawTitle);

        var drawCard = '<td colspan="6"><img class="imgStyle" src="/evol/dist/images/cardsEVO/'+data['gameInfo']['dragonCards']+'.png"></td>\
        <td colspan="6"><img class="imgStyle" src="/evol/dist/images/cardsEVO/'+data['gameInfo']['tigerCards']+'.png"></td>';


        $('#modal-og #images').append(drawCard);
    }
    else if(data['game_id'] == 'P3_bull-bull' || data['game_id'] == 'P13_bull-bull')
    {
        var drawTitle = '<th colspan="1" class="draw-title">First</th>\
        <th colspan="2" class="draw-title">Banker</th>\
        <th colspan="3" class="draw-title">Player1</th>\
        <th colspan="3" class="draw-title">Player2</th>\
        <th colspan="3" class="draw-title">Player3</th>';

        $('#modal-og #gameInfo').append(drawTitle);

        var drawCard = '<td colspan="1"><img class="imgStyle" src="/evol/dist/images/cardsEVO/'+data['gameInfo']['first']+'.png"></td>';

        var card = data['gameInfo']['banker'].split(',');
        drawCard += '<td colspan="2">';
        $.each(card, function( index, value ) 
        {
            drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });
        drawCard += '</td>';

        var card = data['gameInfo']['player1'].split(',');
        drawCard += '<td colspan="3">';
        $.each(card, function( index, value ) 
        {
            drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });
        drawCard += '</td>';

        var card = data['gameInfo']['player2'].split(',');
        drawCard += '<td colspan="3">';
        $.each(card, function( index, value ) 
        {
            drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });
        drawCard += '</td>';

        var card = data['gameInfo']['player3'].split(',');
        drawCard += '<td colspan="3">';
        $.each(card, function( index, value ) 
        {
            drawCard += '<img class="imgStyle" src="/evol/dist/images/cardsEVO/'+value+'.png">';
        });
        drawCard += '</td>';

        $('#modal-og #images').append(drawCard);
    }
    else if(data['game_id'] == 'P8_roulette' || data['game_id'] == 'P20_roulette' || data['game_id'] == 'MW1_money-wheel' || data['game_id'] == 'MW2_money-wheel' )
    {
        $('#modal-og #results').html(data['gameInfo']['value']);
    }

    $('#modal-og #totalBet').html(utils.formatMoney(data['debit']));

    $('#modal-og #payment').html(utils.formatMoney(data['credit']));
    $('#modal-og #net').html(utils.formatMoney(data['netCash']));
}

function getDataTableDetailARN(data,username,txnId)
{
    $("#modalDetails").modal('show'); 
    $("#modal-json").hide();

    $('#modal-table').hide();
    $('#modal-mg').hide();
    $('#modal-vegas').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $('#modal-dowin').hide();
    $('#modal-dd').hide();
    $('#modal-og').hide();
    $('#modal-arn').show();

    $('#modal-arn #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');

    $('#modal-arn #txnId').html(data['txn_id']);
    $('#modal-arn #placetime').html(data['placeOn']);
    $('#modal-arn #bet-placed').html(data['betPlaced']);
    $('#modal-arn #results').html(data['result']);

    var tdImgBanker = document.createElement('td');
    var tdImgPlayer = document.createElement('td');
    $(tdImgBanker).attr('colspan','6');
    $(tdImgPlayer).attr('colspan','6');
    $('#modal-arn #images').attr('class','emptyContent');

    for (var i = 0; i < data['playerCards'].length; i++) 
        {
            if(data['playerCards'][i] != '')
            {
                var img = data['playerCards'][i]['card'];
                var imagePlayer = document.createElement('img');
                $(imagePlayer).attr('class', 'imgStyle');
                $(imagePlayer).attr('src','/images/cardsARN/' + img + '.png');
                $(tdImgPlayer).append(imagePlayer);
                $('#modal-arn #images').append(tdImgPlayer);
            }
            
        }

    for (var i = 0; i < data['bankerCards'].length ; i++) 
        {
            if(data['bankerCards'][i] != '')
            { 
                var img = data['bankerCards'][i]['card'];
                var imageBanker = document.createElement('img');
                $(imageBanker).attr('class', 'imgStyle');
                $(imageBanker).attr('src','/images/cardsARN/' + img + '.png');
                $(tdImgBanker).append(imageBanker);
                $('#modal-arn #images').append(tdImgBanker);
            }
            
        }

    $('#modal-arn #banker-cards').html(data['bankerCards']);
    $('#modal-arn #player-cards').html(data['playerCards']);
    $('#modal-arn #totalBet').html(utils.formatMoney(data['debit']));

    $('#modal-arn #payment').html(utils.formatMoney(data['credit']));
    $('#modal-arn #net').html(utils.formatMoney(data['netCash']));
}

function getDataTableDetailHoldem(data, username, prdId)
{
    try
    {
        if (prdId == 37) // Map Hidden Poker PT to P2P Hidden Poker.
        {
            prdId = 10002;
        }

        $("#modalDetails").modal('show'); 
        $('#modal-holdem').show();
        $('#modal-json').hide();
        $('#modal-mg').hide();
        $('#modal-im').hide();
        $('#modal-pinnacle').hide();
        $('#modal-dowin').hide();
        $('#modal-dd').hide();
        $('#modal-og').hide();
        $('#modal-rizal').hide();
        $('#modal-arn').hide();
        $('#modal-yes8').hide();
        $('#modal-scepb').hide();
        $(".rit_class").hide();
        $(".all_in_class").hide();

        $("#bet_amt").attr('rowspan',1);
        $("#settle_time").attr('rowspan',1);
        $("#held1").attr('rowspan',1);

        $("#rit_results").html("");
        $('#modal-holdem #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
        $('#time').html(locale['on']+' '+data['bet_time']);

        $('#modal-holdem #endTime').html(data['settle_time']);
        $('#game_no').html(data['gameNo']);
        $('#round_id_val').html(data['round_id']);
        $("#run_it_twice").html(data['rit']);
        $("#modal-holdem #totalBet").html(utils.formatMoney(data['totalBet']));
        $("#modal-holdem #payment").html(utils.formatMoney(data['totalBet'] + (data['totalNetProfit'])));
        $("#modal-holdem #net").html(utils.formatMoney(data['totalNetProfit']));
        $("#rake").html(utils.formatMoney(data['rake']));
        $('#bet_amt').html(utils.formatMoney(data['totalBet']));
        $("#bet_won").html(utils.formatMoney(data['totalBet'] + (data['totalNetProfit'])));
        $('#bet_net').html(utils.formatMoney(data['netProfit1']));
        $('#bet_txn_id').html(data['txn_id']);
        $('#settle_time').html(data['bet_time']);
        // $("#modal-holdem #results").html(data['cardRank1']);

        // For result translation
        var resultTranslate = '';
        if(data['cardRank1'] == 'Straight')
        {
            resultTranslate = locale['straight'];
        }
        else if(data['cardRank1'] == 'OnePair')
        {
            resultTranslate = locale['onepair'];
        }
        else if(data['cardRank1'] == 'TwoPair')
        {
            resultTranslate = locale['twopair'];
        }
        else if(data['cardRank1'] == 'Triple')
        {
            resultTranslate = locale['threeofaKind'];
        }
        else if(data['cardRank1'] == 'FourofAKind')
        {
            resultTranslate = locale['fourofaKind'];
        }
        else if(data['cardRank1'] == 'Top')
        {
            resultTranslate = locale['highcard'];
        }
        else if(data['cardRank1'] == 'flush')
        {
            resultTranslate = locale['flush'];
        }
        else if(data['cardRank1'] == 'FullHouse')
        {
            resultTranslate = locale['fullhouse'];
        }
        else if(data['cardRank1'] == 'StraightFlush')
        {
            resultTranslate = locale['straightflush'];
        }
        else if(data['cardRank1'] == 'RoyalFlush')
        {
            resultTranslate = locale['royalflush'];
        }
        else if(data['cardRank1'] == 'NONE')
        {
            resultTranslate = locale['none'];
        }
        else
        {
            resultTranslate = data['cardRank1'];
        }

        $("#modal-holdem #results").html(resultTranslate);

        if (prdId == 10001 && data['AllinInsurance'] == "true" || prdId == 10002 && data['allinInsurance'] == 1) 
        {
            $(".all_in_class").show();
            $("#all_in").html(data['AllinInsurance'] || data['allinInsurance']);
            $("#all_in_premium").html(utils.formatMoney(data['AllinPremium'] || data['allinPremium']));
            $("#all_in_reward").html(utils.formatMoney(data['AllinReward'] || data['allinReward']));
        }

        if (prdId == 10002) 
        {
            $("#game-row, #fold-row").hide(); // Temporary hide because data don't have this
        }
        var netProfit = (data['netProfit1'] > 0) ? 'color:blue':'color:red';
        var totalNet = (data['totalNetProfit'] > 0) ? 'color:blue':'color:red';
        $('#txnid').html(locale['txnid']);
        $('#held').html(locale['held']);
        $('#comm').html(locale['comm']);
        $('#bet_net').attr("style", netProfit);
        $('#net').attr("style", totalNet);

        //make card img
        imgHeld = [];

        for (var i = 0; i < data['HeldArr'].length; i++) 
        {
            var img = data['HeldArr'][i];
            img     = cardToValue(img);
            var image2Bet = document.createElement('img');      

            $(image2Bet).attr('class', 'imgStyle')
            $(image2Bet).attr('src','/images/cardsAV/' + img + '.png');
            // $('#held1').append(image2Bet);
            imgHeld.push(image2Bet);
        }
        $('#held1').html(imgHeld);

        imgComm1 = [];
        for (var i = 0; i < data['CommCardsArr1'].length; i++) 
        {
            var img = data['CommCardsArr1'][i];
            img     = cardToValue(img);
            var image2Bet = document.createElement('img');      

            $(image2Bet).attr('class', 'imgStyle')
            $(image2Bet).attr('src','/images/cardsAV/' + img + '.png');
            // $('#comm1').append(image2Bet);
            imgComm1.push(image2Bet);
        }
        $('#comm1').html(imgComm1);

        imgComm2 = [];

        if(data['CommCardsArr2'].length > 0)
        {
            $(".rit_class").show();
            $("#held1").attr('rowspan',2);
            $("#bet_amt").attr('rowspan',2);
            $("#settle_time").attr('rowspan',2);

            $("#rit_won").html(utils.formatMoney(data['Won2']));
            $("#rit_net").html(utils.formatMoney(data['NetProfit2']));

            $("#rit_results").html(data['CardRank2']);

            for (var i = 0; i < data['CommCardsArr2'].length; i++) 
            {
                var img = data['CommCardsArr2'][i];
                img     = cardToValue(img);
                var image2Bet = document.createElement('img');      

                $(image2Bet).attr('class', 'imgStyle')
                $(image2Bet).attr('src','/images/cardsAV/' + img + '.png');
                // $('#comm2').append(image2Bet);
                imgComm2.push(image2Bet);
            }
            $('#comm2').html(imgComm2);
        }
        
        page = currentView+1;

        $("#page_no").html(page+"/"+dataSize);

    }
    catch(e)
    {
        
    }
}

function cardToValue(card) // For KHOLDEM2 mapping
{
    let cardMappings = 
    {
        '1♠': 'S1', '2♠': 'S2', '3♠': 'S3', '4♠': 'S4', '5♠': 'S5', '6♠': 'S6', '7♠': 'S7', 
        '8♠': 'S8', '9♠': 'S9', 'T♠': 'S10', 'J♠': 'SJ', 'Q♠': 'SQ', 'K♠': 'SK', 'A♠': 'SA',
        '1♥': 'H1', '2♥': 'H2', '3♥': 'H3', '4♥': 'H4', '5♥': 'H5', '6♥': 'H6', '7♥': 'H7',
        '8♥': 'H8', '9♥': 'H9', 'T♥': 'H10', 'J♥': 'HJ', 'Q♥': 'HQ', 'K♥': 'HK', 'A♥': 'HA',
        '1♣': 'C1', '2♣': 'C2', '3♣': 'C3', '4♣': 'C4', '5♣': 'C5', '6♣': 'C6', '7♣': 'C7',
        '8♣': 'C8', '9♣': 'C9', 'T♣': 'C10', 'J♣': 'CJ', 'Q♣': 'CQ', 'K♣': 'CK', 'A♣': 'CA',
        '1◆': 'D1', '2◆': 'D2', '3◆': 'D3', '4◆': 'D4', '5◆': 'D5', '6◆': 'D6', '7◆': 'D7',
        '8◆': 'D8', '9◆': 'D9', 'T◆': 'D10', 'J◆': 'DJ', 'Q◆': 'DQ', 'K◆': 'DK', 'A◆': 'DA'
    };

    return cardMappings[card];
}

function getDataTableDetailYes8(data,txnId)
{
    $("#modalDetails").modal('show'); 
    $('#modal-yes8').show();
    $("#modal-json").hide();

    var yes8BetDetailClass = document.getElementsByClassName("yes8-bet-detail");
    while(yes8BetDetailClass.length > 0)
    {
        yes8BetDetailClass[0].parentNode.removeChild(yes8BetDetailClass[0]);
    }

    if(data['totalBetAmount'] < data['totalPayoffAmount'])
    {
        result = locale['win'];
    }
    else if(data['totalBetAmount'] == data['totalPayoffAmount'])
    {
        result = locale['tie'];
    }
    else
    {
        result = locale['lose'];
    }

    $('#modal-yes8 #roundId').html(locale['roundId']+": "+data['gameReferenceCode']);
    $('#modal-yes8 #totalDebit').html(data['totalBetAmount']);
    $('#modal-yes8 #totalCredit').html(data['totalPayoffAmount']);
    $('#modal-yes8 #results').html(result);
    $('#modal-yes8 #txnId').html(data.txnId);


    var tbl = document.getElementById("table-yes8");

    for(i = 0; i < data['bets'].length; i++)
    {
        var row = document.createElement("tr");
        currentBet = data['bets'][i];

        console.log(currentBet);

        if(data['gameType'] == "BACCARAT")
        {
            switch(currentBet['betType'])
            {
                case 'BC_PLAYER':
                case 'BC_PLAYER_TIGER':
                    betType = locale['player'];
                    break;
                case 'BC_BANKER_NO_COMMISSION':
                case 'BC_BANKER':
                    betType = locale['banker'];
                    break;
                case 'BC_KINGS':
                    betType = locale['tigerBonus'];
                    break;
                case 'BC_TIE':
                    betType = locale['tie'];
                    break;
                case 'BC_BANKER_PAIR':
                    betType = locale['player_pair'];
                    break;
                case 'BC_PLAYER_PAIR':
                    betType = locale['banker_pair'];
                    break;
            }
        }
        if(data['gameType'] == "BLACKJACK")
        {
            switch(currentBet['betType'])
            {
                case 'BJ_BET':
                    betType = locale['bet'];
                    break;
                case 'BJ_INSURANCE':
                    betType = locale['insurance'];
                    break;
                case 'BJ_DOUBLE':
                    betType = locale['double'];
                    break;
                case 'BJ_SPLIT':
                    betType = locale['split'];
                    break;
            }
        }
        if(data['gameType'] == "ROULETTE")
        {
            splitBetType = currentBet['betType'].split("_");
            switch(splitBetType[1]) //type
            {
                case 'BLACK':
                    betType = locale['black'];
                    break;

                case 'RED':
                    betType = locale['red'];
                    break;

                case 'EVEN':
                    betType = locale['even'];
                    break;

                case 'ODD':
                    betType = locale['odd'];
                    break;

                default:
                    betType = '';
                    break;

            }
            if(betType == '')
            {
                var word = splitBetType[1].match(/[a-zA-Z]+/g); 
                switch(word[0]) //type
                {
                    case 'S':
                        //Straight bet
                        var num = splitBetType[1].match(/\d+/g);
                        betType = locale['straight']+" "+num;
                        break;
                    case 'COR':
                        //Corner bet
                        var num = splitBetType[1].match(/\d+/g);
                        betType = locale['corner']+" "+num;
                        break;
                    case 'ALL':
                        //Alley bet
                        var num = splitBetType[1].match(/\d+/g);
                        betType = locale['alley']+" "+num;
                        break;
                    case 'COL':
                        //Column bet
                        var num = splitBetType[1].match(/\d+/g);
                        betType = locale['column']+" "+num;
                        break;
                    case 'DOZ':
                        //Dozen bet
                        var num = splitBetType[1].match(/\d+/g);
                        betType = locale['dozen']+" "+num;
                        break;
                    case 'SPL':
                        //Split bet
                        betType = locale['split']+" "+splitBetType[1].match(/\d+/g)+", "+splitBetType[2];
                        break;
                    case 'STR':
                        //Street bet
                        var num = splitBetType[1].match(/\d+/g);
                        betType = locale['street']+" "+num;
                        break;
                    case 'BET':
                        //Bet
                        betType = locale['bet']+" "+splitBetType[2]+"-"+splitBetType[3];
                        break;
                }
            }
            
        }

        splitBetType = currentBet['resultType'].split("_");
        switch(splitBetType[1])
        {
            case 'WIN':
                resultType = locale['win'];
                break;
            case 'LOSE':
                resultType = locale['lose'];
                break;
            case 'TIE':
                resultType = locale['tie'];
                break;
            case 'BUST':
                resultType = locale['lose'];
                break;
        }


        var betTypeCell = document.createElement("td");
        betTypeCell.setAttribute("colspan", 3);
        betTypeCell.setAttribute("class", "yes8-bet-detail");
        betTypeCell.innerHTML = betType;

        var debitCell = document.createElement("td");
        debitCell.setAttribute("colspan", 3);
        debitCell.setAttribute("class", "yes8-bet-detail");
        debitCell.innerHTML = currentBet['betAmount'];

        var creditCell = document.createElement("td");
        creditCell.setAttribute("colspan", 3);
        creditCell.setAttribute("class", "yes8-bet-detail");
        creditCell.innerHTML = currentBet['payoffAmount'];
        
        var resultCell = document.createElement("td");
        resultCell.setAttribute("colspan", 3);
        resultCell.setAttribute("class", "yes8-bet-detail");
        resultCell.innerHTML = resultType;

        row.appendChild(betTypeCell);
        row.appendChild(debitCell);
        row.appendChild(creditCell);
        row.appendChild(resultCell);
        tbl.appendChild(row);
    }
}

function getDataTableDetailBtrEsport(data,username,txnId)
{
    var dataResult = data[0];
    var dataResponse = data;
    
    switch(dataResult['resultType'])
    {
        case 'won':
        var result = locale['win'];
        var color = 'color:#53ff1b';
        break;

        case 'lost':
        var result = locale['lose'];
        var color = 'color:red';
        break;

        case 'cashout':
        var result = locale['cashout'];
        var color = 'color:#000000';
        break;

        case 'draw':
        var result = locale['draw'];
        var color = 'color:#000000';
        break;

        case 'canceled':
        var result = locale['cancel'];
        var color = 'color:#000000';
        break;

        case 'half won':
        var result = locale['halfwon'];
        var color = 'color:#000000';
        break;

        case 'half lost':
        var result = locale['halflose'];
        var color = 'color:#000000';
        break;

        default:
        var result = dataResult['resultType'];
        var color = 'color:#000000';
        break;
    }

    var type = (dataResponse['metadata'].length > 1) ? locale['parlay']:locale['single'];

    var net = parseFloat(dataResult['amount']) - dataResponse['amount'];
    $('#modal-pinnacle #net').html(utils.formatMoney(net));

    $("#modalDetails").modal('show'); 
    $('#modal-pinnacle').show();
    $("#modal-json").hide();
    $("#modal-pinnacle #pinGameInfo").empty();

    $('#modal-pinnacle #playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
    $('#modal-pinnacle #txnId').html(txnId);
    $('#modal-pinnacle #status').html(locale['resolved']);
    $('#modal-pinnacle #placetime').html(dataResponse['placeTime']);
    $('#modal-pinnacle #endTime').html(dataResponse['endTime']);
    $('#modal-pinnacle #totalBet').html(utils.formatMoney(dataResponse['amount']));
    $('#modal-pinnacle #payment').html(utils.formatMoney(parseFloat(dataResult['amount'])));
    $('#modal-pinnacle #results').html(result);
    $('#modal-pinnacle #results').attr('style',color);
    $('#modal-pinnacle #type').html(type);
    $('#modal-pinnacle #odds').html(dataResponse['gameType']);
    $('#modal-pinnacle #oddsName').html(locale['game']);
    $('#modal-pinnacle #oddsFormat').html(dataResponse['coefficient']);
    
    var div = "";
    for (var a = 0; a < dataResponse['metadata'].length; a++) 
    {
        var active = "";
        flag = true;
        var metaData = dataResponse['metadata'][a];

        //give the first event as active page
        if(a == 0)
        {
            active = "active";
        }
        
        var drawDiv = '<div class="carousel-item '+active+'"><table style="width:100%;"><tbody class="table-md mb-4;"><tr><td colspan="12" style="background-color:#a4b7c1;color:black;text-align:center;">'+
        metaData['tournament'] +'</td></tr><tr><td style="width:50%">'+locale['game']+'</td><td>'+ metaData['discipline'] +'</td></tr><tr><td colspan="12" style="text-align:center;">'+
        metaData['event'] + '</td></tr>' + '<tr><td>'+locale['matchDate']+'</td><td>'+ metaData['eventDate'] +
        '</td></tr><tr><td>'+locale['selection']+'</td><td>'+ metaData['outcome'] +
        '</td></tr><tr><td>'+locale['result']+'</td><td>'+ dataResult['resultType'] +
        '</td></tr><tr><td>'+locale['odd']+'</td><td>'+ metaData["coefficient"] +
        '</td></tr><tr><td>'+locale['eventName']+'</td><td>'+ metaData['market'] +
        '</td></tr></tbody></table></div>';

        div = div.concat(drawDiv);

        if(a == (dataResponse['metadata'].length-1) && dataResponse['metadata'].length > 1)
        { 
            var carousel = '<div class="carousel-inner">'+div+'</div><a class="carousel-control-prev" href="#pinGameInfo" data-slide="prev">'+
                '<span class="carousel-control-prev-icon"></span></a><a class="carousel-control-next" href="#pinGameInfo" data-slide="next">'+
                '<span class="carousel-control-next-icon"></span></a>';

            $("#modal-pinnacle #pinGameInfo").prepend(carousel);
        }
    }
    $("#modal-pinnacle #pinGameInfo").prepend(drawDiv);
}

function getDataTableDetailSCEPB(data,username,txnId)
{

    $('#modal-table').hide();
    $("#modalDetails").modal('show');
    $("#modal-json").hide();
    $('#modal-mg').hide();
    $('#modal-holdem').hide();
    $('#modal-im').hide();
    $('#modal-pinnacle').hide();
    $('#modal-dowin').hide();
    $('#modal-dd').hide();
    $('#modal-og').hide();
    $('#modal-rizal').hide();
    $('#modal-arn').hide();
    $('#modal-yes8').hide();
    $('#modal-scepb').show();


    $('#modal-scepb #txnId').html(txnId);
    $('#modal-scepb #playerName').html(username);
    $('#modal-scepb #totalDebit').html(utils.formatMoney(data['betamount']));
    $('#modal-scepb #totalCredit').html(utils.formatMoney(data['winamount']));

        var ballsNum = data['result']['values'].join(', ');

    if(data['betamount'] < data['winamount'])
    {
        result = locale['win'];
    }
    else if(data['winamount'] == data['betamount'])
    {
        result = locale['tie'];
    }
    else
    {
        result = locale['lose'];
    }

    // console.log(data.autobet);
    $('#modal-scepb #results').html(result);
    $('#modal-scepb #dividend').html(data['dividend']);
    $('#modal-scepb #status').html(data['status']);
    $('#modal-scepb #marketName').html(data['marketname']);
    $('#modal-scepb #pickName').html(data['pickname']);
    $('#modal-scepb #ballNum').html(ballsNum);
}

function getDataTableDetailXPG(data, username, txnId)
{
    $("#modal-json").hide();
    $("#modalDetails").modal('show');
    $("#hand").hide();

    var gameType    = data['game_type'];
    var gameName    = data['game_name'];
    var betDetails  = data['bet_details'];
    var betTime     = data['created_at'];
    var settledTime = data['settled_at'];
    var result      = data['result'];
    var betDetails  = data['bet_details'];

    $("#playerName").html(locale['modal.playername'] + " : " +  '<span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
    $("#time").html(gameName + " " + locale['on'] + " " + betTime);
    $("#tableId").html(gameName);
    $("#serialNo").html(gameName);
    $("#endTime").html(settledTime);
    $("#status").html(locale['resolved']);

    var totalPayout = 0;
    var totalBet    = 0;

    $.each(betDetails, function(index, val) 
    {
        var tr       = document.createElement('tr');
        var tdBet    = document.createElement('td');
        var tdAmount = document.createElement('td');
        var tdNet    = document.createElement('td');
        var tdTxn    = document.createElement('td');
        var tdTime   = document.createElement('td');
        totalPayout += parseInt(val['win_amount']);
        totalBet    += parseInt(val['bet_amount']);

        $(tdBet).attr('colspan','2');
        $(tdAmount).attr('colspan','2');
        $(tdNet).attr('colspan','2');
        $(tdTxn).attr('colspan','3');
        $(tdTime).attr('colspan','3');

        $(tdBet).html(val['bet_type']);
        $(tdAmount).html(utils.formatMoney(val['bet_amount']));
        $(tdNet).html(utils.formatMoney(val['win_amount']));
        $(tdTxn).html(txnId);
        $(tdTime).html(betTime);

        var color = (val['win_amount'] > 0) ? 'color:blue' : 'color:red';
        $(tdNet).attr('style', `${color}; font-size: 14px;`);
        $(tdBet).attr('style','color:black; font-size: 14px;');
        $(tdAmount).attr('style','color:black; font-size: 14px;');
        $(tdTxn).attr('style','color:black; font-size: 14px;');
        $(tdTime).attr('style','color:black; font-size: 14px;');
        
        $(tr).append(tdBet);
        $(tr).append(tdAmount);
        $(tr).append(tdNet);
        $(tr).append(tdTxn);
        $(tr).append(tdTime);
        $(tr).attr('class', 'removeClass');
        $('#multipleBet').after(tr);
    });
    
    var totalNet = totalPayout - totalBet;

    $("#totalBet").html(utils.formatMoney(totalBet));
    $("#payment").html(utils.formatMoney(totalPayout));
    $("#net").html(utils.formatMoney(totalNet));

    if (["blackjack", "baccarat", "dragonTiger", "singlePoker", "andarBahar", "teenPatti", "32Cards", "teenPatti2020"].includes(gameType))
    {
        var bankerDealerCards = '';
        var playerCards       = data['player_cards'];

        $('#dealerhand').html(locale['banker_hand']);
        $('#playerhand').html(locale['player_hand']);

        switch (gameType) 
        {
            case "blackjack":
            case "singlePoker":
            case "teenPatti":
                bankerDealerCards = data['dealer_cards'];
                break;

            case "andarBahar":
                bankerDealerCards = data['andar_cards'];
                playerCards       = data['bahar_cards'];
                $('#dealerhand').html(locale['andar_hand']);
                $('#playerhand').html(locale['bahar_hand']);
                break;

            case "32Cards":
                bankerDealerCards = data['player8_cards'];
                playerCards       = data['player9_cards'];
                break;

            case "teenPatti2020":
                bankerDealerCards = data['playerA_cards'];
                playerCards       = data['playerB_cards'];
                $('#dealerhand').html(locale['player'] + ' A');
                $('#playerhand').html(locale['player'] + ' B');
                break;

            default:
                bankerDealerCards = data['banker_cards'];
                break;
        }

        $('#hand').show();
        $('#dealerhand').attr('colspan', '6');
        $('#playerhand').attr('colspan', '6');

        var tdImgDealer = document.createElement('td');
        var tdImgPlayer = document.createElement('td');
        $(tdImgDealer).attr('colspan', '6');
        $(tdImgPlayer).attr('colspan', '6');
        $('#images').attr('class','emptyContent text-center');

        if (gameType == "dragonTiger")
        {
            $('#dealerhand').html(locale['dragon_hand']);
            $('#playerhand').html(locale['tiger_hand']);

            var dragonCard  = data['dragon_card'];
            var imageDragon = document.createElement('img');
            $(imageDragon).attr('class', 'imgStyle');
            $(imageDragon).attr('src', '/images/cardsTS/' + dragonCard + '.png');
            $(tdImgDealer).append(imageDragon);
            $('#images').append(tdImgDealer);

            var tigerCard  = data['tiger_card'];
            var imageTiger = document.createElement('img');
            $(imageTiger).attr('class', 'imgStyle');
            $(imageTiger).attr('src', '/images/cardsTS/' + tigerCard + '.png');
            $(tdImgPlayer).append(imageTiger);
            $('#images').append(tdImgPlayer);
        }
        else
        {
            for (var i = 0; i < bankerDealerCards.length; i++) 
            {
                var img = bankerDealerCards[i];
                var imageDealer = document.createElement('img');
                $(imageDealer).attr('class', 'imgStyle');
                $(imageDealer).attr('src', '/images/cardsTS/' + img + '.png');
                $(tdImgDealer).append(imageDealer);
                $('#images').append(tdImgDealer);

                if (gameType == "blackjack")
                {
                    if ((i + 1) % 3 === 0 && i !== 0) // Add new line after every third image
                    {
                        $(imageDealer).after('<br>'); 
                    }
                }
            }

            for (var i = 0; i < playerCards.length; i++) 
            {
                var img = playerCards[i];
                var imagePlayer = document.createElement('img');
                $(imagePlayer).attr('class', 'imgStyle');
                $(imagePlayer).attr('src', '/images/cardsTS/' + img + '.png');
                $(tdImgPlayer).append(imagePlayer);
                $('#images').append(tdImgPlayer);

                if (gameType == "blackjack")
                {
                    if ((i + 1) % 3 === 0 && i !== 0) // Add new line after every third image
                    {
                        $(imagePlayer).after('<br>'); 
                    }
                }
            }

            if (["singlePoker", "andarBahar"].includes(gameType))
            {
                var thCommunityJokerHand = document.createElement('th');
                var translation = (gameType == "singlePoker") ? locale['comm_hand'] : locale['joker_hand'];

                $(thCommunityJokerHand).html(translation);
                $("#dealerhand").attr('colspan', '4');
                $("#playerhand").attr('colspan', '4');
                $(thCommunityJokerHand).attr('colspan', '4');
                $(thCommunityJokerHand).attr('style', 'font-size: 15px');
                $(thCommunityJokerHand).addClass('emptyContent text-center removeClass');
                $("#hand").append(thCommunityJokerHand);

                var communityJokerCards = (gameType == "singlePoker") ? data['community_cards'] : data['joker_cards'];

                var tdImgCommunityJoker = document.createElement('td');
                $(tdImgDealer).attr('colspan', '4');
                $(tdImgPlayer).attr('colspan', '4');
                $(tdImgCommunityJoker).attr('colspan', '4');

                for (var i = 0; i < communityJokerCards.length; i++) 
                {
                    var img = communityJokerCards[i];
                    var imageCommunityJoker = document.createElement('img');
                    $(imageCommunityJoker).attr('class', 'imgStyle');
                    $(imageCommunityJoker).attr('src', '/images/cardsTS/' + img + '.png');
                    $(tdImgCommunityJoker).append(imageCommunityJoker);
                    $('#images').append(tdImgCommunityJoker);
                }
            }

            if (gameType == "32Cards")
            {
                $("#dealerhand").html(locale['player_8'].split(' ')[0] + ' ' + locale['player_8'].split(' ')[1])
                $("#playerhand").html(locale['player_9'].split(' ')[0] + ' ' + locale['player_9'].split(' ')[1])

                var thPlayer10 = document.createElement('th');
                var thPlayer11 = document.createElement('th');

                $(thPlayer10).html(locale['player_10'].split(' ')[0] + ' ' + locale['player_10'].split(' ')[1]);
                $("#dealerhand").attr('colspan', '3');
                $("#playerhand").attr('colspan', '3');
                $(thPlayer10).attr('colspan', '3');
                $(thPlayer10).attr('style', 'font-size: 15px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)');
                $(thPlayer10).addClass('emptyContent text-center removeClass');
                $("#hand").append(thPlayer10);

                $(thPlayer11).html(locale['player_11'].split(' ')[0] + ' ' + locale['player_11'].split(' ')[1]);
                $(thPlayer11).attr('colspan', '3');
                $(thPlayer11).attr('style', 'font-size: 15px;color:white;background:linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%)');
                $(thPlayer11).addClass('emptyContent text-center removeClass');
                $("#hand").append(thPlayer11);

                var player10Cards = data['player10_cards'];
                var player11Cards = data['player11_cards'];

                var tdImgPlayer10 = document.createElement('td');
                var tdImgPlayer11 = document.createElement('td');
                $(tdImgDealer).attr('colspan', '3');
                $(tdImgPlayer).attr('colspan', '3');
                $(tdImgPlayer10).attr('colspan', '3');
                $(tdImgPlayer11).attr('colspan', '3');

                for (var i = 0; i < player10Cards.length; i++) 
                {
                    var img = player10Cards[i];
                    var imagePlayer10 = document.createElement('img');
                    $(imagePlayer10).attr('class', 'imgStyle');
                    $(imagePlayer10).attr('src', '/images/cardsTS/' + img + '.png');
                    $(tdImgPlayer10).append(imagePlayer10);
                    $('#images').append(tdImgPlayer10);
                }

                for (var i = 0; i < player11Cards.length; i++) 
                {
                    var img = player11Cards[i];
                    var imagePlayer11 = document.createElement('img');
                    $(imagePlayer11).attr('class', 'imgStyle');
                    $(imagePlayer11).attr('src', '/images/cardsTS/' + img + '.png');
                    $(tdImgPlayer11).append(imagePlayer11);
                    $('#images').append(tdImgPlayer11);
                }
            }
        }
    }

    if (gameType == "sicbo")
    {
        var diceArr = data['result'].split(',');
        var tdDealer = document.createElement('td');
        $('#images').attr('class','emptyContent text-center');

        for (var i = 0; i < diceArr.length; i++) 
        {
            var image = document.createElement('img'); 
            var diceNumber = diceArr[i];

            $(tdDealer).attr('colspan', '12');
            diceNumber = ('c1_0' + diceNumber);
            $(image).attr('class', 'imgStyle');
            $(image).attr('style', 'border: 0');
            $(image).attr('src', '/images/cardsDG/' + diceNumber + '.png');
            $(tdDealer).append(image);
            $('#images').append(tdDealer);
        }
    }

    if (result == null)
    {
        if (totalPayout > totalBet) 
        {
           $("#results").html(locale['win']);
        }
        else if (totalBet > totalPayout) 
        {
           $("#results").html(locale['lose']);
        }
        else 
        {
           $("#results").html(locale['tie']);
        }
    }
    else
    {
        $("#results").html(result);
    }
}

/* For Evo's game (Stock Market) */
function createStockChart(data)
{
    var tr      = document.createElement('tr');
    var tdChart = document.createElement('td');

    $(tdChart).attr('colspan', '12');

    var canvas = $('<canvas id="stockMarketChart" style="max-width: 100%"></canvas>');
    $(tdChart).append(canvas);

    var labels = [];

    for (var i = 0; i <= data['data']['result']['intermediatePoints'].length; i += 1) // Total got 27 elements
    {
        labels.push(i.toString());
    }

    var intermediatePoints = data['data']['result']['intermediatePoints'];
    intermediatePoints.push(data['data']['result']['percentage']);

    var stockData = {
        labels: labels,
        datasets: [{
            data: intermediatePoints, 
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.5
        }]
    };

    var ctx = canvas[0].getContext('2d');

    var stockChart = new Chart(ctx, {
        type: 'line',
        data: stockData,
        options: {
            scales: {
                xAxes: [{
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 10
                    },
                    display: false
                }],
                yAxes: [{
                    ticks: {
                        min: -100,
                        max: 100,
                        stepSize: 25,
                    },
                    display: true
                }]
            },
            legend: {
                display: false
            }
        }
    });

    $(tr).append(tdChart);
    $(tr).attr('class', 'removeClass');
    $('#multipleBet').before(tr);

    $("#results").html(data['data']['result']['percentage'] + '%');
}

/* For Evo's game (FP Hilo) */
function createRngHilo(data)
{
    $('#score').hide();

    var playerCards = data['data']['result']['stages'];

    var trCards1    = document.createElement('tr');
    var tdStartRow1 = document.createElement('td');
    var tdDrawnRow1 = document.createElement('td');

    var trCards2    = document.createElement('tr');
    var tdStartRow2 = document.createElement('td'); // Start Card
    var tdDrawnRow2 = document.createElement('td'); // Drawn Cards <Choose Up and Down>

    $(tdStartRow1).attr('colspan','6');
    $(tdDrawnRow1).attr('colspan','6');
    $(tdStartRow2).attr('colspan','6');
    $(tdDrawnRow2).attr('colspan','6');

    $(tdStartRow1).attr('style', 'text-align:center;font-size:15px;font-weight:bold');
    $(tdStartRow1).html(locale['start_card']);
    $(tdDrawnRow1).attr('style', 'text-align:center;font-size:15px;font-weight:bold');
    $(tdDrawnRow1).html(locale['drawn_card']);

    // One start card
    createSingleCard(playerCards[0]['cardCode'], tdStartRow2);

    // Display multiple drawn cards
    for (var i = 1; i < playerCards.length; i++)
    {
        createSingleCard(playerCards[i]['cardCode'], tdDrawnRow2);
    }

    $(trCards1).append(tdStartRow1);
    $(trCards1).append(tdDrawnRow1);
    $(trCards1).attr('class', 'removeClass');
    $('#score').before(trCards1);

    $(trCards2).append(tdStartRow2);
    $(trCards2).append(tdDrawnRow2);
    $(trCards2).attr('class', 'removeClass');
    $('#score').before(trCards2);
}

/* For Evo's game (Casino Hold'em) */
function evoCasinoHoldem(data)
{
    // First row header title
    var trTitle         = document.createElement('tr');
    var thPlayerCard    = document.createElement('th');
    var thCommunityCard = document.createElement('th');
    var thDealerCard    = document.createElement('th');

    $(thPlayerCard).attr('colspan', '4');
    $(thCommunityCard).attr('colspan', '4');
    $(thDealerCard).attr('colspan', '4');

    $(thPlayerCard).html(locale['player'] + ' ' + locale['card']);
    $(thCommunityCard).html(locale['community'] + ' ' + locale['card']);
    $(thDealerCard).html(locale['dealer'] + ' ' + locale['card']);   

    $(trTitle).append(thPlayerCard);
    $(trTitle).append(thCommunityCard);
    $(trTitle).append(thDealerCard);
    $(trTitle).attr('class', 'removeClass text-center');
    $('#multipleBet').before(trTitle);

    // Second row data
    var trData          = document.createElement('tr');
    var tdPlayerCard    = document.createElement('td');
    var tdCommunityCard = document.createElement('td');
    var tdDealerCard    = document.createElement('td');

    $(tdPlayerCard).attr('colspan', '4');
    $(tdCommunityCard).attr('colspan', '4');
    $(tdDealerCard).attr('colspan', '4');

    var playerCards = data['data']['result']['cards']['player'];
    var dealerCards = data['data']['result']['cards']['dealer'];
    var flopCards   = data['data']['result']['cards']['flop']; // For Community
    var riverCards  = data['data']['result']['cards']['river']; // For Community

    renderEvoCards(playerCards, tdPlayerCard); // Player Cards
    renderEvoCards(flopCards, tdCommunityCard); // Community Cards
    renderEvoCards(riverCards, tdCommunityCard); // Community Cards
    renderEvoCards(dealerCards, tdDealerCard); // Dealer Cards     

    $(trData).append(tdPlayerCard);
    $(trData).append(tdCommunityCard);
    $(trData).append(tdDealerCard);
    $(trData).attr('class', 'removeClass text-center');
    $('#multipleBet').before(trData);

    // Third row header title
    var trTitle2     = document.createElement('tr');
    var thPlayerHand = document.createElement('th');
    var thBonusGame  = document.createElement('th');
    var thDealerHand = document.createElement('th');

    $(thPlayerHand).attr('colspan', '4');
    $(thBonusGame).attr('colspan', '4');
    $(thDealerHand).attr('colspan', '4');

    $(thPlayerHand).html(locale['player_hand']);
    $(thBonusGame).html(locale['bonus_game']);
    $(thDealerHand).html(locale['dealer_hand']);   

    $(trTitle2).append(thPlayerHand);
    $(trTitle2).append(thBonusGame);
    $(trTitle2).append(thDealerHand);
    $(trTitle2).attr('class', 'removeClass text-center');
    $('#multipleBet').before(trTitle2);

    // 4th row data
    var trData2      = document.createElement('tr');
    var tdPlayerHand = document.createElement('td');
    var tdDealerHand = document.createElement('td');
    var tdBonusHand  = document.createElement('td');

    $(tdPlayerHand).attr('colspan', '4');
    $(tdDealerHand).attr('colspan', '4');
    $(tdBonusHand).attr('colspan', '4');

    var playerHands = data['data']['result']['player']['cards'];
    var dealerHands = data['data']['result']['dealer']['cards'];
    var bonusHands  = data['data']['result']['bonus']['holdemBonus']['cards'] ?? []; // The bonus cards could be empty.

    renderEvoCards(playerHands, tdPlayerHand); // Player hands
    renderEvoCards(dealerHands, tdDealerHand); // Dealer hands

    if (bonusHands.length != 0)
    {
        renderEvoCards(bonusHands, tdBonusHand);
    }
    else
    {
        $(tdBonusHand).html(locale['no_bet_placed']);
    }

    $(trData2).append(tdPlayerHand);
    $(trData2).append(tdBonusHand);
    $(trData2).append(tdDealerHand);
    $(trData2).attr('class', 'removeClass text-center');
    $('#multipleBet').before(trData2);

    $("#results").html(data['data']['result']['outcome']);
}

function createSingleCard(cards, tdCards)
{
    var imageGame = document.createElement('img');
    var img = cards;
    $(tdCards).attr('style', 'text-align:center;');
    $(imageGame).attr('class', 'imgStyle');
    $(imageGame).attr('style', 'width:65px');
    $(imageGame).attr('src','/evol/dist/images/cardsEVO/' + img + '.png');
    $(tdCards).append(imageGame);
}

function evoTripleCardPoker(data)
{
    var trTitle         = document.createElement('tr');
    var thPlayerCard    = document.createElement('th');
    var thDealerCard    = document.createElement('th');

    $(thPlayerCard).attr('colspan', '6');
    $(thDealerCard).attr('colspan', '6');
    $(thPlayerCard).attr('style', 'font-size: 15px');
    $(thDealerCard).attr('style', 'font-size: 15px');

    $(thPlayerCard).html(locale['player'] + ' ' + locale['card']);
    $(thDealerCard).html(locale['dealer'] + ' ' + locale['card']);   

    $(trTitle).append(thPlayerCard);
    $(trTitle).append(thDealerCard);
    $(trTitle).attr('class', 'removeClass text-center');
    $('#multipleBet').before(trTitle);

    var trData          = document.createElement('tr');
    var tdPlayerCard    = document.createElement('td');
    var tdDealerCard    = document.createElement('td');

    $(tdPlayerCard).attr('colspan', '6');
    $(tdDealerCard).attr('colspan', '6');

    var playerCards = data['data']['result']['cards']['player'];
    var dealerCards = data['data']['result']['cards']['dealer'];
    // var flopCards   = data['data']['result']['cards']['flop']; // For Community
    // var riverCards  = data['data']['result']['cards']['river']; // For Community

    renderEvoCards(playerCards, tdPlayerCard); // Player Cards
    renderEvoCards(dealerCards, tdDealerCard); // Dealer Cards     

    $(trData).append(tdPlayerCard);
    $(trData).append(tdDealerCard);
    $(trData).attr('class', 'removeClass text-center');
    $('#multipleBet').before(trData);

    var betDetails = data['data']['participants'][0]['bets'];

    var totalBet = 0;

    $.each(betDetails, function(index, val) 
    {
        var trBet     = document.createElement('tr');
        var tdBet     = document.createElement('td');
        var tdAmount  = document.createElement('td');
        var tdNet     = document.createElement('td');
        var tdTxnId   = document.createElement('td');
        var tdBetTime = document.createElement('td');

        $(tdBet).attr('colspan', '2');
        $(tdAmount).attr('colspan', '2');
        $(tdNet).attr('colspan', '2');
        $(tdTxnId).attr('colspan', '3');
        $(tdBetTime).attr('colspan', '3');

        var net = val['payout'] - val['stake'];

        $(tdBet).html(val['description']);
        $(tdAmount).html(utils.formatMoney(val['stake']));
        $(tdTxnId).html(val['transactionId']);
        $(tdNet).html(utils.formatMoney(net));
        $(tdBetTime).html(val['placedOn']);

        var netColor = (net > 0) ? 'color:blue' : 'color:red';
        $(tdNet).attr('style', netColor);

        $(trBet).append(tdBet);
        $(trBet).append(tdAmount);
        $(trBet).append(tdNet);
        $(trBet).append(tdTxnId);
        $(trBet).append(tdBetTime);

        $(trBet).attr('class', 'removeClass text-center');
        $('#multipleBet').after(trBet);
        
        totalBet += val['stake']; 
    });

    $("#results").html(data['data']['result']['outcome']);
}


function renderEvoCards(cards, container) // Cards for Evo. Dynamic function.
{
    cards.forEach(function(card) 
    {
        var image = document.createElement('img');
        image.classList.add('imgStyle');
        image.src = '/evol/dist/images/cardsEVO/' + card + '.png';
        container.appendChild(image);
    });
}

function getDataTableDetailsGPI(data, username, txnId)
{
    $("#modal-json").hide();
    $("#images").remove();
    $("#hand").remove();
    $("#modalDetails").modal('show'); 

    var gameName    = data['game_name'];
    var betTime     = data['created_at'];
    var settledTime = data['settled_at'];
    var betDetails  = data['bet_details'];
    var gameType    = data['game_type'];

    $('#playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
    $("#time").html(gameName + " " + locale['on'] + " " + betTime);
    $("#tableId").html(gameName);
    $("#serialNo").html(gameName);
    $("#endTime").html(settledTime);
    $("#status").html(locale['resolved']);

    var trTitle = document.createElement('tr');

    if (['baccarat', 'dragontiger', 'threecardpoker'].includes(gameType))
    {
        var thPlayerCard = document.createElement('th');
        var thBankerCard = document.createElement('th');

        $(thPlayerCard).attr('colspan', '6');
        $(thBankerCard).attr('colspan', '6');
        $(thPlayerCard).attr('style', 'text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);');
        $(thBankerCard).attr('style', 'text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);');
    }

    switch(gameType)
    {
        case 'baccarat':
        case 'threecardpoker':
            $(thPlayerCard).html(locale['player'] + ' ' + locale['card']);
            $(thBankerCard).html(locale['banker'] + ' ' + locale['card']);   
            $(trTitle).append(thPlayerCard);
            $(trTitle).append(thBankerCard);
            break;

        case 'dragontiger':
            $(thPlayerCard).html(locale['dragon_hand']);
            $(thBankerCard).html(locale['tiger_hand']);   
            $(trTitle).append(thPlayerCard);
            $(trTitle).append(thBankerCard);
            break;

        case 'sicbo':
        case 'roulette':
        case 'xocdia':
        case 'thai hilo':
            var thResult = document.createElement('th');
            $(thResult).attr('colspan', '12');
            $(thResult).attr('style', 'font-size: 15px');
            $(thResult).html(locale['result']);   
            $(trTitle).append(thResult);
            break;
    }

    $(trTitle).attr('class', 'removeClass text-center');
    $('#multipleBet').before(trTitle);

    var trData = document.createElement('tr');

    switch(gameType)
    {
        case 'baccarat':
        case 'dragontiger':
        case 'threecardpoker':
            var tdPlayerCard = document.createElement('td');
            var tdBankerCard = document.createElement('td');

            $(tdPlayerCard).attr('colspan', '6');
            $(tdBankerCard).attr('colspan', '6');

            var playerCards = data['player_cards'];
            var bankerCards = data['banker_cards'];

            renderEvoCards(playerCards, tdPlayerCard); // Player Cards
            renderEvoCards(bankerCards, tdBankerCard); // Dealer Cards     

            $(trData).append(tdPlayerCard);
            $(trData).append(tdBankerCard);
            break;

        case 'sicbo':
            var tdDice = document.createElement('td');
            $(tdDice).attr('colspan', '12');

            var dices = data['dices'];

            renderEvoCards(dices, tdDice); // Dealer Cards     

            $(trData).append(tdDice);
            break;

        case 'roulette':
            var tdRoulette = document.createElement('td');
            $(tdRoulette).attr('colspan', '12');

            var rouletteResult = data['roulette_result'];
            var rouletteColor  = data['roulette_color'];
            $(tdRoulette).append(rouletteResult + ', ' + rouletteColor);
            $(tdRoulette).css({
                'font-size': '14px',
                'font-weight': 'bold',
                'font-style': 'italic'
            });
            $(trData).append(tdRoulette);
            break;

        case 'xocdia':
            var tdXocdia = document.createElement('td');
            $(tdXocdia).attr('colspan', '12');

            var xocdiaResult = data['xocdia_result'];
            $(tdXocdia).append(xocdiaResult);
            $(tdXocdia).css({
                'font-size': '14px',
                'font-weight': 'bold',
                'font-style': 'italic'
            });
            $(trData).append(tdXocdia);
            break;

        case 'thai hilo':
            var tdThaiHilo = document.createElement('td');
            $(tdThaiHilo).attr('colspan', '12');

            var thaiHiloResult = data['thai_hilo_result'];
            $(tdThaiHilo).append(thaiHiloResult);
            $(tdThaiHilo).css({
                'font-size': '14px',
                'font-weight': 'bold',
                'font-style': 'italic'
            });
            $(trData).append(tdThaiHilo);
            break;
    }

    $(trData).attr('class', 'removeClass text-center');
    $('#multipleBet').before(trData);

    var totalBet    = 0;
    var totalPayout = 0;

    $.each(betDetails, function(index, val) 
    {
        var betAmount = val['bet_amount'];
        var betOption = val['bet_option'];
        var payout    = val['payout'];
        totalBet      += parseFloat(betAmount);
        totalPayout   += parseFloat(val['total_win']);
        var trBet     = document.createElement('tr');
        var tdBet     = document.createElement('td');
        var tdAmount  = document.createElement('td');
        var tdNet     = document.createElement('td');
        var tdTxnId   = document.createElement('td');
        var tdBetTime = document.createElement('td');

        $(tdBet).attr('colspan', '2');
        $(tdAmount).attr('colspan', '2');
        $(tdNet).attr('colspan', '2');
        $(tdTxnId).attr('colspan', '3');
        $(tdBetTime).attr('colspan', '3');

        $(tdBet).html(betOption);
        $(tdAmount).html(utils.formatMoney(betAmount));
        $(tdTxnId).html(txnId);
        $(tdNet).html(utils.formatMoney(payout));
        $(tdBetTime).html(betTime);

        var netColor = (payout > 0) ? 'color:blue' : 'color:red';
        $(tdBet).attr('style','color:black; font-size: 14px;');
        $(tdAmount).attr('style','color:black; font-size: 14px;');
        $(tdNet).attr('style', `${netColor}; font-size: 14px;`);
        $(tdTxnId).attr('style','color:black; font-size: 14px;');
        $(tdBetTime).attr('style','color:black; font-size: 14px;');

        $(trBet).append(tdBet);
        $(trBet).append(tdAmount);
        $(trBet).append(tdNet);
        $(trBet).append(tdTxnId);
        $(trBet).append(tdBetTime);

        $(trBet).attr('class', 'removeClass text-center');
        $('#multipleBet').after(trBet);
    });

    var net = totalPayout - totalBet;

    if (totalPayout > totalBet) 
    {
        $("#results").html(locale['win']);
    }
    else if (totalBet > totalPayout) 
    {
        $("#results").html(locale['lose']);
    }
    else 
    {
        $("#results").html(locale['tie']);
    }

    $('#totalBet').html(utils.formatMoney(totalBet));
    $('#payment').html(utils.formatMoney(totalPayout));
    $('#net').html(utils.formatMoney(net));
}

function getDataTableDetailsDB(data, username, txnId)
{
    $("#modal-json").hide();
    $("#images").remove();
    $("#hand").remove();
    $("#modalDetails").modal('show'); 

    var gameName    = data['game_name'];
    var tableId     = data['table_id'];
    var betTime     = data['created_at'];
    var settledTime = data['settled_at'];
    // var betDetails  = data['bet_details'];
    var gameType    = data['game_type'];
    var betOption   = data['bettingPoint'];

    $('#playerName').html(locale['modal.playername'] +' : ' +  '  <span style="color: #3c5291;font-weight: bold;">' + username + '</span>');
    $("#time").html(gameName + " " + locale['on'] + " " + betTime);
    $("#tableId").html(tableId);
    $("#serialNo").html(gameName);
    $("#endTime").html(settledTime);
    $("#status").html(locale['resolved']);

    var trTitle = document.createElement('tr');

    if (['baccarat', 'dragontiger','winthreecard','blackjack'].includes(gameType))
    {
        var thPlayerCard = document.createElement('th');
        var thBankerCard = document.createElement('th');

        $(thPlayerCard).attr('colspan', '6');
        $(thBankerCard).attr('colspan', '6');
        $(thPlayerCard).attr('style', 'text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);');
        $(thBankerCard).attr('style', 'text-align:center;font-size:15px;color:white;background: linear-gradient(to bottom, #edf0f5 0%, #899abc 66%, #b5c0d3 100%);');
    }

    switch(gameType)
    {
        case 'blackjack':
        case 'baccarat':
            $(thPlayerCard).html(locale['player_hand'] + ' ' + locale['card']);
            $(thBankerCard).html(locale['banker_hand'] + ' ' + locale['card']);   
            $(trTitle).append(thPlayerCard);
            $(trTitle).append(thBankerCard);
            break;

        case 'dragontiger':
            $(thBankerCard).html(locale['dragon_hand'] + ' ' + locale['card']);  
            $(thPlayerCard).html(locale['tiger_hand'] + ' ' + locale['card']); 
            $(trTitle).append(thBankerCard);
            $(trTitle).append(thPlayerCard);
            break;

        case '5D':
        case 'marksix':
        case 'sicbo':
        case 'roulette':
        case 'xocdia':
        case 'thai hilo':
        case '5D':
        case 'sedie':
            var thResult = document.createElement('th');
            $(thResult).attr('colspan', '12');
            $(thResult).attr('style', 'font-size: 15px');
            $(thResult).html(locale['result']);   
            $(trTitle).append(thResult);
            break;

        case 'winthreecard':
            $(thPlayerCard).html(locale['dragon_hand'] + ' ' + locale['card']);
            $(thBankerCard).html(locale['phoenix_hand'] + ' ' + locale['card']);   
            $(trTitle).append(thPlayerCard);
            $(trTitle).append(thBankerCard);
            break;
    }

    $(trTitle).attr('class', 'removeClass text-center');
    $('#multipleBet').before(trTitle);

    var trData = document.createElement('tr');

    switch(gameType)
    {            
        case 'bullbull':
            var topCard = data['top_card'];
            var bankerCards = data['banker_cards'];
            var player1Cards = data['player1_cards'];
            var player2Cards = data['player2_cards'];
            var player3Cards = data['player3_cards'];

            appendCardRow(topCard, locale['top_card']);
            appendCardRow(bankerCards, locale['dealer']);
            appendCardRow(player1Cards, locale['player1']);
            appendCardRow(player2Cards, locale['player2']);
            appendCardRow(player3Cards, locale['player3']);
            break;        

        case 'winthreecard':
            var tdDragonCard = document.createElement('td');
            var tdPhoenixCard = document.createElement('td');

            $(tdDragonCard).attr('colspan', '6');
            $(tdPhoenixCard).attr('colspan', '6');

            var dragonCards = data['dragon_cards'];
            var phoenixCards = data['phoenix_cards'];

            renderDBCards(dragonCards, tdDragonCard); // Player Cards
            renderDBCards(phoenixCards, tdPhoenixCard); // Dealer Cards     

            $(trData).append(tdDragonCard);
            $(trData).append(tdPhoenixCard);
            break;

        case 'baccarat':
            var tdPlayerCard = document.createElement('td');
            var tdBankerCard = document.createElement('td');

            $(tdPlayerCard).attr('colspan', '6');
            $(tdBankerCard).attr('colspan', '6');

            var playerCards = data['player_cards'];
            var bankerCards = data['banker_cards'];

            renderDBCards(playerCards, tdPlayerCard); 
            renderDBCards(bankerCards, tdBankerCard);    
            
            $(trData).append(tdPlayerCard);  
            $(trData).append(tdBankerCard);          
            break;

        case 'dragontiger':
            var tdPlayerCard = document.createElement('td');
            var tdBankerCard = document.createElement('td');

            $(tdPlayerCard).attr('colspan', '6');
            $(tdBankerCard).attr('colspan', '6');

            var playerCards = data['player_cards'];
            var bankerCards = data['banker_cards'];

            renderDBCards(playerCards, tdPlayerCard); // Tiger Cards
            renderDBCards(bankerCards, tdBankerCard); // Dragon Cards     

            $(trData).append(tdBankerCard);
            $(trData).append(tdPlayerCard);            
            break;

        case 'sicbo':
            var tdDice = document.createElement('td');
            $(tdDice).attr('colspan', '12');

            var dices = data['dices'];

            renderDBDices(dices, tdDice); // Dealer Cards     

            $(trData).append(tdDice);
            break;

        case 'roulette':
            var tdRoulette = document.createElement('td');
            $(tdRoulette).attr('colspan', '12');

            var rouletteResult = data['roulette_result'];
            var rouletteColor  = data['roulette_color'];
            $(tdRoulette).append(rouletteResult + ', ' + rouletteColor);
            $(tdRoulette).css({
                'font-size': '14px',
                'font-weight': 'bold',
                'font-style': 'italic'
            });
            $(trData).append(tdRoulette);
            break;
        
        case 'paijiu':
            var houseCards   = data['house_cards'];
            var bankerCards  = data['banker_cards'];
            var player1Cards = data['first_player_cards'];
            var player2Cards = data['last_player_cards'];

            appendPaijiuRow(houseCards, locale['house']+'(P3)');
            appendPaijiuRow(bankerCards, locale['banker']);
            appendPaijiuRow(player1Cards, locale['first_player']+'(P1)');
            appendPaijiuRow(player2Cards, locale['last_player']+'(P2)');
            break; 

        case 'texas':
            var bankerCards    = data['banker_cards'];
            var playerCards    = data['player_cards'];
            var communityCards = data['community_cards'];
            var bankerBesthand = data['banker_besthand'];
            var playerBesthand = data['player_besthand'];

            appendCardRow(bankerCards, locale['banker']);
            appendCardRow(playerCards, locale['player']);
            appendCardRow(communityCards, locale['comm_hand']);
            appendCardRow(bankerBesthand, locale['banker_besthand']);
            appendCardRow(playerBesthand, locale['player_besthan']);
            break; 
        
        case 'andarbahar':
            var jokerCards     = data['joker_cards'];
            var andarCards     = data['andar_cards'];
            var baharCards     = data['bahar_cards'];            

            appendCardRow(jokerCards, locale['joker_hand']);
            appendCardRow(andarCards, locale['andar_hand']);
            appendCardRow(baharCards, locale['bahar_hand']);
            break; 
        
        case 'teenpatti':
            var playerACards     = data['playerA_cards'];
            var playerBCards     = data['playerB_cards'];
            var bonus6Cards     = data['bonus6_cards'];            

            appendCardRow(playerACards, locale['playerA_hand']);
            appendCardRow(playerBCards, locale['playerB_hand']);
            appendCardRow(bonus6Cards, locale['bonus6']);
            break; 

        case 'marksix':
            var tdMarksix = document.createElement('td');
            $(tdMarksix).attr('colspan', '12');

            var marksixNumber  = data['marksix_number'];
            $(tdMarksix).append(marksixNumber);
            $(trData).append(tdMarksix);
            break;        

        case '5D':
            var td5D = document.createElement('td');
            $(td5D).attr('colspan', '12');

            var result5D  = data['5D_number'];
            $(td5D).append(result5D);
            $(trData).append(td5D);
            break;
        
         case 'sedie':
            var tdSedie = document.createElement('td');
            $(tdSedie).attr('colspan', '12');

            var sedieResult  = data['sedie_result'];
            $(tdSedie).append(sedieResult);
            $(trData).append(tdSedie);
            break;

        case 'blackjack':
            var tdPlayerCard = document.createElement('td');
            var tdBankerCard = document.createElement('td');

            $(tdPlayerCard).attr('colspan', '6');
            $(tdBankerCard).attr('colspan', '6');

            var playerCards = data['player_cards'];
            var bankerCards = data['banker_cards'];

            renderDBCards(playerCards, tdPlayerCard); 
            renderDBCards(bankerCards, tdBankerCard);    

            $(trData).append(tdPlayerCard);
            $(trData).append(tdBankerCard);                        
            break;
        
    }

    $(trData).attr('class', 'removeClass text-center');
    $('#multipleBet').before(trData);

    var totalBet    = data['valid_bet_amount'];
    var totalPayout = data['pay_amount'];

    var trBet     = document.createElement('tr');
    var tdBet     = document.createElement('td');
    var tdAmount  = document.createElement('td');
    var tdNet     = document.createElement('td');
    var tdTxnId   = document.createElement('td');
    var tdBetTime = document.createElement('td');

    $(tdBet).attr('colspan', '2');
    $(tdAmount).attr('colspan', '2');
    $(tdNet).attr('colspan', '2');
    $(tdTxnId).attr('colspan', '3');
    $(tdBetTime).attr('colspan', '3');

    $(tdBet).html(betOption);
    $(tdAmount).html(utils.formatMoney(totalBet));
    $(tdTxnId).html(txnId);
    $(tdNet).html(utils.formatMoney(totalPayout));
    $(tdBetTime).html(betTime);

    var netColor = (totalPayout > 0) ? 'color:blue' : 'color:red';
    $(tdBet).attr('style','color:black; font-size: 14px;');
    $(tdAmount).attr('style','color:black; font-size: 14px;');    
    $(tdNet).attr('style', `${netColor}; font-size: 14px;`);
    $(tdTxnId).attr('style','color:black; font-size: 14px;');
    $(tdBetTime).attr('style','color:black; font-size: 14px;');

    $(trBet).append(tdBet);
    $(trBet).append(tdAmount);
    $(trBet).append(tdNet);
    $(trBet).append(tdTxnId);
    $(trBet).append(tdBetTime);

    $(trBet).attr('class', 'removeClass text-center');
    $('#multipleBet').after(trBet);

    var net = totalPayout - totalBet;

    if (totalPayout > totalBet) 
    {
        $("#results").html(locale['win']);
    }
    else if (totalBet > totalPayout) 
    {
        $("#results").html(locale['lose']);
    }
    else 
    {
        $("#results").html(locale['tie']);
    }

    $('#totalBet').html(utils.formatMoney(totalBet));
    $('#payment').html(utils.formatMoney(totalPayout));
    $('#net').html(utils.formatMoney(net));
}


function renderDBCards(cards, container) // Cards for DB. Dynamic function.
{
    cards.forEach(function(card) 
    {
        var image = document.createElement('img');
        image.classList.add('imgStyle');
        image.src = '/images/cardsDB/' + card + '.png';
        container.appendChild(image);
    });
}


function renderDBDices(cards, container) // dices for DB. Dynamic function.
{
    cards.forEach(function(card) 
    {
        var image = document.createElement('img');
        image.classList.add('imgStyle');
        image.src = '/images/cardsDB/sicbo/' + card + '.png';
        container.appendChild(image);
    });
}

function renderDBPaijiu(cards, container) // paijiu for DB. Dynamic function.
{
    cards.forEach(function(card) 
    {
        var image = document.createElement('img');
        image.classList.add('imgStyle');
        image.style.border = 'none'; 
        image.src = '/images/cardsDB/paijiu/' + card + '.png';
        container.appendChild(image);
    });
}

//create and append a new row with cards
function appendCardRow(cards, label)
{
    var tr = document.createElement('tr');
    var tdLabel = document.createElement('td');
    var tdCards = document.createElement('td');
    $(tdLabel).attr('colspan', 3);
    $(tdCards).attr('colspan', 9);
    $(tdLabel).html(label);
    renderDBCards(cards, tdCards);
    $(tr).append(tdLabel);
    $(tr).append(tdCards);
    $(tr).attr('class', 'removeClass text-center');
    $('#multipleBet').before(tr);
}

function appendPaijiuRow(cards, label)
{
    var tr = document.createElement('tr');
    var tdLabel = document.createElement('td');
    var tdCards = document.createElement('td');
    $(tdLabel).attr('colspan', 3);
    $(tdCards).attr('colspan', 9);
    $(tdLabel).html(label);
    renderDBPaijiu(cards, tdCards);
    $(tr).append(tdLabel);
    $(tr).append(tdCards);
    $(tr).attr('class', 'removeClass text-center');
    $('#multipleBet').before(tr);
}