var bettingSubmitFlag = 0;

var matchGameWinOver = 0;
var matchGameLoseOver = 0;
var matchGameFbWinOver = 0;
var matchGameFbLoseUnder = 0;
var matchGameFbLoseOver = 0;
var matchGameFbWinloseUnover = 0;

var matchGameWinUnder = 0;
var matchGameLoseUnder = 0;
var matchGameFbWinUnder = 0;

var page_special_type = 0;
var betcon= new Array();
 
var m_min_count = 1;
var m_bonus3	= 0;
var m_bonus4	= 0;
var m_bonus5	= 0;
var m_bonus6	= 0;
var m_bonus7	= 0;
var m_bonus8	= 0;
var m_bonus9	= 0;
var m_bonus10	= 0;
var m_single_max_bet = 0;

var m_betList 	= new BetList();

Array.prototype.remove = function(from, to)
{
	var rest = this.slice((to || from)+1 || this.length);
	this.length = from<0 ? this.length+from : from;
	return this.push.apply(this, rest);
}

Array.prototype.indexOf = function(v)
{
	 if(v==null)
	 {
		return -1;
	 }
	 else
	 {
		  var j= -1;
		  for(var i=0;i<this.length;i++)
		  {
			   if(this[i]==v)
			   {
					j=i;
					break;
			   }
		  }
		  return j;
	 }
}
Array.prototype.del_element = function(v)
{
	var v_index = this.indexOf(v);
	if(v_index>-1)
	{
		if(v_index==this.length-1)
		{
			return this.slice(0,v_index);
		}
		else if(v_index==0)
		{
			return this.slice(v_index+1,this.length);
		}
		else
		{
			return this.slice(0,v_index).concat(this.slice(v_index+1,this.length));
		}
	}
	else
	{
		return this;
	}
}
Array.prototype.add_element=function(v)
{
	var betcon2=this;
	var arrsrc=v.split("|");
	for(var i=0;i<this.length;i++)
	{
		var arrobj=this[i].split("|");
		if(arrsrc[0]==arrobj[0])
		{
			betcon2 = betcon2.del_element(this[i]);
			break;
		}
	}

	betcon2.push(v);
	return betcon2;
}

Array.prototype.del_one=function(m)
{
	var betcon2=this;
	var arrsrc=m;
	for(var i=0;i<this.length;i++)
	{
		var arrobj=this[i].split("|");
		if(arrsrc==arrobj[0])
		{
			betcon2 = betcon2.del_element(this[i]);
			break;
		}
	}
	return betcon2;
}

function initialize($min, $bonus3, $bonus4, $bonus5, $bonus6, $bonus7, $bonus8, $bonus9, $bonus10, $single_max_bet) {
	m_min_count = $min;
	m_bonus3	= $bonus3;
	m_bonus4	= $bonus4;
	m_bonus5	= $bonus5;
	m_bonus6	= $bonus6;
	m_bonus7	= $bonus7;
	m_bonus8	= $bonus8;
	m_bonus9	= $bonus9;
	m_bonus10	= $bonus10;
	m_single_max_bet = $single_max_bet;
}

/*
 * Update Checkbox Attribute and BackGround Css
 * return : 'inserted' or 'deleted'
 */
function toggle($tr, $index, selectedRate)
{
	var toggle_action = "";
	
	$('div[name='+$tr+'] input').each( function(index) {
		if(index!=$index)
		{
			this.checked=false;
		}
	});
	
	//toggle checkbox
	$selectedCheckbox = $('div[name='+$tr+'] input:checkbox:eq('+$index+')');
	if(($selectedCheckbox).is(":checked")==true) 
	{
		$selectedCheckbox.attr("checked", false);
		toggle_action = 'deleted';
	}
	else
	{
		//踰좏똿�� �쒗븳(10��)
		if(isMaxGameCount()==true)
		{
			alert("理쒕� 15寃쎄린源뚯� �좏깮�섏떎�� �덉뒿�덈떎.");
			return false;
		}
/*
		//150諛곕떦 �댁긽�� 諛곕떦 諛고똿 湲덉�		
		else if(m_betList._bet*selectedRate>=150)
		{
			alert("150諛곕떦 �댁긽�� 諛고똿�� 遺덇��� �⑸땲��.");
			return false;
		}
*/
		else
		{
			$selectedCheckbox.prop("checked", true);
			toggle_action = 'inserted';
		}
	}

	//change css class
	$('div[name='+$tr+'] input:checkbox').each(function(index)
	{
		if(this.checked==true) 
		{
			if(index==0) 	  	{$(this).parent().attr('class', 'game_home_name_bg_pickup');}
			else if(index==1) {$(this).parent().attr('class', 'game_tie_bg_pickup');}
			else if(index==2) {$(this).parent().attr('class', 'game_away_name_bg_pickup');}
		}
		else
		{
			if(index==0) 				{$(this).parent().attr('class', 'game_home_name_bg');}
			else if(index==1) 	{$(this).parent().attr('class', 'game_tie_bg');}
			else if(index==2) 	{$(this).parent().attr('class', 'game_away_name_bg');}
		}
	});
	
	return toggle_action;
}

function isMaxGameCount()
{
	return (getBetCount()>=15);
}

function DalPaingYcheckRule($game_date)
{
	for(i=0; i<m_betList._items.length;++i)
	{
		var item = m_betList._items[i];
		if ( item._game_date != $game_date ) {
			return 0;
		}
	}
	return 1;
}

function bonus_in_check() {
	if ( m_betList._items.length >= 3 ) {
		return 1;
	} else {
		return 0;
	}
}

function check_single_only() {
	if ( m_betList._items.length > 1 ) {
		return 0;
	} else {
		return 1;
	}
}

//-> 異뺢뎄 -> �숈씪寃쎄린 [�밸Т��-��]+[�몃뜑�ㅻ쾭-�ㅻ쾭]
function checkRule_win_over() {
	for ( i = 0 ; i < m_betList._items.length ; i++ ) {
		var i_item = m_betList._items[i];
		for ( j = (i+1) ; j < m_betList._items.length ; j++ ) {
			var j_item = m_betList._items[j];
			if ( i_item._game_type == 1 && i_item._checkbox_index == 0 && i_item._sport_name == "異뺢뎄" ) {
				if ( i_item._home_team == j_item._home_team && i_item._away_team == j_item._away_team && j_item._game_type == 4 && j_item._checkbox_index == 0 && j_item._sport_name == "異뺢뎄" ) {
					return 0;
				}
			} else if ( i_item._game_type == 4 && i_item._checkbox_index == 0 && i_item._sport_name == "異뺢뎄" ) {
				if ( i_item._home_team == j_item._home_team && i_item._away_team == j_item._away_team && j_item._game_type == 1 && j_item._checkbox_index == 0 && j_item._sport_name == "異뺢뎄" ) {
					return 0;
				}
			}
		} //-> end FOR J
	} //-> end FOR I
	return 1;
}

//-> 異뺢뎄 -> �숈씪寃쎄린 [�밸Т��-��]+[�몃뜑�ㅻ쾭-�몃뜑]
function checkRule_win_under() {
	for ( i = 0 ; i < m_betList._items.length ; i++ ) {
		var i_item = m_betList._items[i];
		for ( j = (i+1) ; j < m_betList._items.length ; j++ ) {
			var j_item = m_betList._items[j];
			if ( i_item._game_type == 1 && i_item._checkbox_index == 0 && i_item._sport_name == "異뺢뎄" ) {
				if ( i_item._home_team == j_item._home_team && i_item._away_team == j_item._away_team && j_item._game_type == 4 && j_item._checkbox_index == 2 && j_item._sport_name == "異뺢뎄" ) {
					return 0;
				}
			} else if ( i_item._game_type == 4 && i_item._checkbox_index == 2 && i_item._sport_name == "異뺢뎄" ) {
				if ( i_item._home_team == j_item._home_team && i_item._away_team == j_item._away_team && j_item._game_type == 1 && j_item._checkbox_index == 0 && j_item._sport_name == "異뺢뎄" ) {
					return 0;
				}
			}
		} //-> end FOR J
	} //-> end FOR I
	return 1;
}

//-> 異뺢뎄 -> �숈씪寃쎄린 [�밸Т��-��]+[�몃뜑�ㅻ쾭-�몃뜑]
function checkRule_lose_under() {
	for ( i = 0 ; i < m_betList._items.length ; i++ ) {
		var i_item = m_betList._items[i];
		for ( j = (i+1) ; j < m_betList._items.length ; j++ ) {
			var j_item = m_betList._items[j];
			if ( i_item._game_type == 1 && i_item._checkbox_index == 2 && i_item._sport_name == "異뺢뎄" ) {
				if ( i_item._home_team == j_item._home_team && i_item._away_team == j_item._away_team && j_item._game_type == 4 && j_item._checkbox_index == 2 && j_item._sport_name == "異뺢뎄" ) {
					return 0;
				}
			} else if ( i_item._game_type == 4 && i_item._checkbox_index == 2 && i_item._sport_name == "異뺢뎄" ) {
				if ( i_item._home_team == j_item._home_team && i_item._away_team == j_item._away_team && j_item._game_type == 1 && j_item._checkbox_index == 2 && j_item._sport_name == "異뺢뎄" ) {
					return 0;
				}
			}
		} //-> end FOR J
	} //-> end FOR I
	return 1;
}

//-> 異뺢뎄 -> �숈씪寃쎄린 [�밸Т��-��]+[�몃뜑�ㅻ쾭-�ㅻ쾭]
function checkRule_lose_over() {
	for ( i = 0 ; i < m_betList._items.length ; i++ ) {
		var i_item = m_betList._items[i];
		for ( j = (i+1) ; j < m_betList._items.length ; j++ ) {
			var j_item = m_betList._items[j];
			if ( i_item._game_type == 1 && i_item._checkbox_index == 2 && i_item._sport_name == "異뺢뎄" ) {
				if ( i_item._home_team == j_item._home_team && i_item._away_team == j_item._away_team && j_item._game_type == 4 && j_item._checkbox_index == 0 && j_item._sport_name == "異뺢뎄" ) {
					return 0;
				}
			} else if ( i_item._game_type == 4 && i_item._checkbox_index == 0 && i_item._sport_name == "異뺢뎄" ) {
				if ( i_item._home_team == j_item._home_team && i_item._away_team == j_item._away_team && j_item._game_type == 1 && j_item._checkbox_index == 2 && j_item._sport_name == "異뺢뎄" ) {
					return 0;
				}
			}
		} //-> end FOR J
	} //-> end FOR I
	return 1;
}

function checkRule_fb_win_over() {
	for ( i = 0 ; i < m_betList._items.length ; i++ ) {
		var i_item = m_betList._items[i];
		for ( j = (i+1) ; j < m_betList._items.length ; j++ ) {
			var j_item = m_betList._items[j];
			if ( i_item._game_type == 1 && i_item._checkbox_index == 0 && (i_item._home_team.indexOf("1�대떇 �앹젏") > -1 || i_item._home_team.indexOf("1�대떇�앹젏") > -1) ) {
				if ( i_item._home_team.indexOf(j_item._home_team) > -1 && i_item._away_team.indexOf(j_item._away_team) > -1 && j_item._game_type == 4 && j_item._checkbox_index == 0 ) {
					return 0;
				}
			} else if ( i_item._game_type == 4 && i_item._checkbox_index == 0 && j_item._home_team.indexOf(i_item._home_team) > -1 && j_item._away_team.indexOf(i_item._away_team) > -1 ) {
				if ( (j_item._home_team.indexOf("1�대떇 �앹젏") > -1 || j_item._home_team.indexOf("1�대떇�앹젏") > -1) && j_item._game_type == 1 && j_item._checkbox_index == 0 ) {
					return 0;
				}
			}
		} //-> end FOR J
	} //-> end FOR I
	return 1;
}

function checkRule_fb_win_under() {
	for ( i = 0 ; i < m_betList._items.length ; i++ ) {
		var i_item = m_betList._items[i];
		for ( j = (i+1) ; j < m_betList._items.length ; j++ ) {
			var j_item = m_betList._items[j];
			if ( i_item._game_type == 1 && i_item._checkbox_index == 0 && (i_item._home_team.indexOf("1�대떇 �앹젏") > -1 || i_item._home_team.indexOf("1�대떇�앹젏") > -1) ) {
				if ( i_item._home_team.indexOf(j_item._home_team) > -1 && i_item._away_team.indexOf(j_item._away_team) > -1 && j_item._game_type == 4 && j_item._checkbox_index == 2 ) {
					return 0;
				}
			} else if ( i_item._game_type == 4 && i_item._checkbox_index == 2 && j_item._home_team.indexOf(i_item._home_team) > -1 && j_item._away_team.indexOf(i_item._away_team) > -1 ) {
				if ( (j_item._home_team.indexOf("1�대떇 �앹젏") > -1 || j_item._home_team.indexOf("1�대떇�앹젏") > -1) && j_item._game_type == 1 && j_item._checkbox_index == 0 ) {
					return 0;
				}
			}
		} //-> end FOR J
	} //-> end FOR I
	return 1;
}

function checkRule_fb_lose_under() {
	for ( i = 0 ; i < m_betList._items.length ; i++ ) {
		var i_item = m_betList._items[i];
		for ( j = (i+1) ; j < m_betList._items.length ; j++ ) {
			var j_item = m_betList._items[j];
			if ( i_item._game_type == 1 && i_item._checkbox_index == 2 && (i_item._away_team.indexOf("1�대떇 臾대뱷��") > -1 || i_item._away_team.indexOf("1�대떇臾대뱷��") > -1) ) {
				if ( i_item._home_team.indexOf(j_item._home_team) > -1 && i_item._away_team.indexOf(j_item._away_team) > -1 && j_item._game_type == 4 && j_item._checkbox_index == 2 ) {
					return 0;
				}
			} else if ( i_item._game_type == 4 && i_item._checkbox_index == 2 && j_item._home_team.indexOf(i_item._home_team) > -1 && j_item._away_team.indexOf(i_item._away_team) > -1 ) {
				if ( (j_item._away_team.indexOf("1�대떇 臾대뱷��") > -1 || j_item._away_team.indexOf("1�대떇臾대뱷��") > -1) && j_item._game_type == 1 && j_item._checkbox_index == 2 ) {
					return 0;
				}
			}
		} //-> end FOR J
	} //-> end FOR I
	return 1;
}

function checkRule_fb_lose_over() {
	for ( i = 0 ; i < m_betList._items.length ; i++ ) {
		var i_item = m_betList._items[i];
		for ( j = (i+1) ; j < m_betList._items.length ; j++ ) {
			var j_item = m_betList._items[j];
			if ( i_item._game_type == 1 && i_item._checkbox_index == 2 && (i_item._away_team.indexOf("1�대떇 臾대뱷��") > -1 || i_item._away_team.indexOf("1�대떇臾대뱷��") > -1) ) {
				if ( i_item._home_team.indexOf(j_item._home_team) > -1 && i_item._away_team.indexOf(j_item._away_team) > -1 && j_item._game_type == 4 && j_item._checkbox_index == 0 ) {
					return 0;
				}
			} else if ( i_item._game_type == 4 && i_item._checkbox_index == 0 && j_item._home_team.indexOf(i_item._home_team) > -1 && j_item._away_team.indexOf(i_item._away_team) > -1 ) {
				if ( (j_item._away_team.indexOf("1�대떇 臾대뱷��") > -1 || j_item._away_team.indexOf("1�대떇臾대뱷��") > -1) && j_item._game_type == 1 && j_item._checkbox_index == 2 ) {
					return 0;
				}
			}
		} //-> end FOR J
	} //-> end FOR I
	return 1;
}

function checkRule($game_index, $checkbox_index, $game_type, $home_team, $away_team)
{
	for(i=0; i<m_betList._items.length;++i)
	{
		if($game_type==1 && $checkbox_index==1)
		{
			var item = m_betList._items[i];
			if(item._home_team==$home_team && item._away_team==$away_team && item._game_type==4 && item._checkbox_index==2)
			{
				return 0;
			}
		}
		else if($game_type==4 && $checkbox_index==2)
		{
			item = m_betList._items[i];
			if(item._home_team==$home_team && item._away_team==$away_team && item._game_type==1 && item._checkbox_index==1)
			{
				return 0;
			}
		}
		 		
	}
	return 1;
}

function checkRule_over($game_index, $checkbox_index, $game_type, $home_team, $away_team)
{
	for(i=0; i<m_betList._items.length;++i)
	{
		if($game_type==1 && $checkbox_index==1)
		{
			var item = m_betList._items[i];
			if(item._home_team==$home_team && item._away_team==$away_team && item._game_type==4 && item._checkbox_index==0)
			{
				return 0;
			}
		}
		else if($game_type==4 && $checkbox_index==0)
		{
			item = m_betList._items[i];
			if(item._home_team==$home_team && item._away_team==$away_team && item._game_type==1 && item._checkbox_index==1)
			{
				return 0;
			}
		}
		 		
	}
	return 1;
}

function checkRule1($game_index, $checkbox_index, $game_type, $home_team, $away_team)
{
	for(i=0; i<m_betList._items.length;++i)
	{
		if($game_type==1)
		{
			var item = m_betList._items[i];
			if(item._home_team==$home_team && item._away_team==$away_team && item._game_type==2)
			{
				return 0;
			}
		}
		else if($game_type==2)
		{
			item = m_betList._items[i];
			if(item._home_team==$home_team && item._away_team==$away_team && item._game_type==1)
			{
				return 0;
			}
		}
		 		
	}
	return 1;
}

//-> 異뺢뎄�� 寃쎌슦 媛숈�寃쎄린�먯꽌 �밸Т��+�몃뜑�ㅻ쾭 �좏깮 遺덇�.
function checkRule2($game_index, $checkbox_index, $game_type, $home_team, $away_team)
{
	for(i=0; i<m_betList._items.length;++i)
	{
		if($game_type==1)
		{
			var item = m_betList._items[i];
			if(item._home_team==$home_team && item._away_team==$away_team && item._game_type==4)
			{
				return 0;
			}
		}
		else if($game_type==4)
		{
			item = m_betList._items[i];
			if(item._home_team==$home_team && item._away_team==$away_team && item._game_type==1)
			{
				return 0;
			}
		}
	}
	return 1;
}

//-> 異뺢뎄�� 寃쎌슦 媛숈�寃쎄린�먯꽌 �몃뵒��+�몃뜑�ㅻ쾭 �좏깮 遺덇�.
function checkRule3($game_index, $checkbox_index, $game_type, $home_team, $away_team)
{
	for(i=0; i<m_betList._items.length;++i)
	{
		if($game_type==2)
		{
			var item = m_betList._items[i];
			if(item._home_team==$home_team && item._away_team==$away_team && item._game_type==4)
			{
				return 0;
			}
		}
		else if($game_type==4)
		{
			item = m_betList._items[i];
			if(item._home_team==$home_team && item._away_team==$away_team && item._game_type==2)
			{
				return 0;
			}
		}
	}
	return 1;
}

//-> �밸Т��(臾�)+�몃뜑/�ㅻ쾭 �좏깮 遺덇�.
function checkRule4($game_index, $checkbox_index, $game_type, $home_team, $away_team)
{
	for(i=0; i<m_betList._items.length;++i)
	{
		if($game_type==1 && $checkbox_index==1)
		{
			var item = m_betList._items[i];
			if(item._home_team==$home_team && item._away_team==$away_team && item._game_type==4)
			{
				return 0;
			}
		}
		else if($game_type==4)
		{
			item = m_betList._items[i];
			if(item._home_team==$home_team && item._away_team==$away_team && item._game_type==1 && item._checkbox_index==1)
			{
				return 0;
			}
		}
	}
	return 1;
}

//-> �숈씪寃쎄린 �뱁뙣 + �몄삤踰� 議고빀 遺덇�.
function checkRule_winlose_unover() {
	for ( i = 0 ; i < m_betList._items.length ; i++ ) {
		var i_item = m_betList._items[i];
		for ( j = (i+1) ; j < m_betList._items.length ; j++ ) {
			var j_item = m_betList._items[j];
			if ( i_item._game_type == 1 && (i_item._checkbox_index == 0 || i_item._checkbox_index == 2) ) {
				if ( i_item._home_team.indexOf(j_item._home_team) > -1 && i_item._away_team.indexOf(j_item._away_team) > -1 && j_item._game_type == 4 && (j_item._checkbox_index == 0 || j_item._checkbox_index == 2) ) {
					return 0;
				}
			} else if ( i_item._game_type == 4 && (i_item._checkbox_index == 0 || i_item._checkbox_index == 2) && j_item._home_team.indexOf(i_item._home_team) > -1 && j_item._away_team.indexOf(i_item._away_team) > -1 ) {
				if ( j_item._game_type == 1 && (j_item._checkbox_index == 0 || j_item._checkbox_index == 2) ) {
					return 0;
				}
			}
		} //-> end FOR J
	} //-> end FOR I
	return 1;
}

//-> �숈씪寃쎄린 �띻뎄 [泥� 3��], [泥� �먯쑀��], [泥� �앹젏] �쒕줈 臾띠씠吏� �딄쾶. ( ��紐� 遺꾨━ 臾몄옄 = "[" )
function checkRule_bbChecker($game_index, $checkbox_index, $game_type, $home_team, $away_team) {
	var cut_inHomeArr = $home_team.split("[");
	var inHomeTeam = cut_inHomeArr[0].trim();

	if ( ($home_team.indexOf("泥� 3��") > -1 || $home_team.indexOf("泥� �먯쑀��") > -1 || $home_team.indexOf("泥� �앹젏") > -1) && $game_type == 1 ) {			
		for(i=0; i<m_betList._items.length;++i) {
			var item = m_betList._items[i];
			var cut_listHomeArr = item._home_team.split("[");
			var listHomeTeam = cut_listHomeArr[0].trim();

			if ( $home_team != item._home_team ) {
				if ( (item._home_team.indexOf("泥� 3��") > -1 || item._home_team.indexOf("泥� �먯쑀��") > -1 || item._home_team.indexOf("泥� �앹젏") > -1) && inHomeTeam == listHomeTeam && item._game_type == 1 ) return 0;
			}
		}
	}
	return 1;
}

//-> �숈씪寃쎄린 �띻뎄 �몃뵒罹� + [泥� 3��],[泥� �먯쑀��],[泥� �앹젏] �쒕줈 臾띠씠吏� �딄쾶. ( ��紐� 遺꾨━ 臾몄옄 = "[" )
function checkRule_bbChecker2($game_index, $checkbox_index, $game_type, $home_team, $away_team) {
	var cut_inHomeArr = $home_team.split("[");
	var inHomeTeam = cut_inHomeArr[0].trim();

	if ( ($home_team.indexOf("泥� 3��") > -1 || $home_team.indexOf("泥� �먯쑀��") > -1 || $home_team.indexOf("泥� �앹젏") > -1) ) {			
		for(i=0; i<m_betList._items.length;++i) {
			var item = m_betList._items[i];
			var cut_listHomeArr = item._home_team.split("[");
			var listHomeTeam = cut_listHomeArr[0].trim();

			if ( $home_team != item._home_team ) {
				if ( item._game_type == 2 && inHomeTeam == listHomeTeam ) return 0;
			}
		}
	} else if ( $game_type == 2 ) {
		for(i=0; i<m_betList._items.length;++i) {
			var item = m_betList._items[i];
			var cut_listHomeArr = item._home_team.split(" ");
			var listHomeTeam = cut_listHomeArr[0];

			if ( $home_team != item._home_team ) {
				if ( (item._home_team.indexOf("泥� 3��") > -1 || item._home_team.indexOf("泥� �먯쑀��") > -1 || item._home_team.indexOf("泥� �앹젏") > -1) && inHomeTeam == listHomeTeam ) return 0;
			}
		}
	}
	return 1;
}

function filter_team_name($team, $index/*0=home, 1=away*/)
{
	var filtered="";
	
	if( 0==$index)
	{
		pos = $team.indexOf("[");
		if( pos!=-1)
		{
			filtered = $team.substr(0, pos);
			return filtered;
		}
	}
	else
	{
		pos = $team.indexOf("]");
		if( pos!=-1)
		{
			filtered = $team.substr(pos+1, $team.length-pos);
			return filtered;
		}
	}

	return $team;
}

/*
�뱁뙣-�ㅽ럹�� 議고빀

�밸Т�⑥��� 議고빀�� 媛���.
洹몄쇅�� 寃뚯엫 ���낃낵�� 議고빀遺덇�.
*/
function check_specified_special_rule($game_index, $is_specified_special, $game_type, $home_team, $away_team)
{
	if($game_type!=1)
	{
		for(i=0; i<m_betList._items.length;++i)
		{
			var item = m_betList._items[i];
			if(item._is_specified_special==1)
			{
					return 0;
			}
		}
	}
	
	else if(1==$game_type && 1==$is_specified_special)
	{
		$home_team = filter_team_name($home_team, 0);
		$away_team = filter_team_name($away_team, 1);
		
		for(i=0; i<m_betList._items.length;++i)
		{
			var item = m_betList._items[i];
			if(item._game_type!=1)
			{
				return 0;
			}
		}
	}
	return 1;
}

/*
 * $game_index 	: game identify id
 * $index		: checkbox index-start from 0
*/
function onTeamSelected($game_index, $index, $special_type)
{
	//-> 湲�濡쒕쾶 �섏씠吏� ���� ����.
	page_special_type = $special_type;

	$sport_name = $("#"+$game_index+"_sport_name").val();
	$game_type 	= $("#"+$game_index+"_game_type").val();
	$home_team 	= $("#"+$game_index+"_home_team").val();
	$home_rate 	= $("#"+$game_index+"_home_rate").val();
	$draw_rate 	= $("#"+$game_index+"_draw_rate").val();
	$away_team 	= $("#"+$game_index+"_away_team").val();
	$away_rate 	= $("#"+$game_index+"_away_rate").val();
	$sub_sn 	= $("#"+$game_index+"_sub_sn").val();
	$game_date 	= $("#"+$game_index+"_game_date").val();
	$league_sn = $("#"+$game_index+"_league_sn").val();
	$is_specified_special = $("#"+$game_index+"_is_specified_special").val();

	if($index<0 || $index>2)
		return;
		
	if($index==1 && ($draw_rate=='1' || $draw_rate=='1.0' || $draw_rate=='1.00' || $draw_rate=='VS'))
		return;

	//-> 蹂대꼫�� �좏깮�� (2�대뜑 �댁긽�쒕쭔 媛���)
	if ( $home_team.indexOf("蹂대꼫��") > -1 ) {
		if(folder_bonus($home_team) =="0"){
			return;
		}
	}

	if($game_type!=1 && $game_type!=2 && $game_type!=4)
	{
		alert("�щ컮瑜� 諛고똿�� �꾨떃�덈떎.");
		return;
	}

	//-> 愿�由ъ옄�� : 諛고똿諛⑹떇 (�쇳빀諛고똿 = 0 / �낅┰諛고똿 = 1) Bet_Rule

	//-> 愿�由ъ옄�� : �밸Т��+�몃뵒罹� (媛��� = 0 / 遺덇��� = 0) Bet_Rule_vh

	//-> 愿�由ъ옄�� : �밸Т��+�섏씠濡쒖슦 (媛��� = 0 / 遺덇��� = 0) Bet_Rule_vu

	//-> 愿�由ъ옄�� : �몃뵒罹�+�섏씠濡쒖슦 (媛��� = 0 / 遺덇��� = 0) Bet_Rule_hu
	
	//�뱀씠議곌굔- �밸Т�⑥쓽 '臾�'�� 硫��곗쓽 �몃뜑�ㅻ쾭 '�몃뜑'�� �숈떆�� 諛고똿遺덇�
	if($special_type==0)
	{
/*
		if(!checkRule_over($game_index, $index, $game_type, $home_team, $away_team))
		{
			alert("�숈씪寃쎄린 [�밸Т��]-臾�+[�몃뜑�ㅻ쾭]-�ㅻ쾭 議고빀�� 諛고똿 遺덇��ν빀�덈떎.");
			return;
		}
*/
		if(!checkRule1($game_index, $index, $game_type, $home_team, $away_team))
		{
			alert("�숈씪寃쎄린 [�밸Т��]+[�몃뵒罹�] 議고빀�� 諛고똿 遺덇��ν빀�덈떎.");
			return;
		}
	}

	//-> �숈씪寃쎄린 �띻뎄 [泥� 3��], [泥� �먯쑀��], [泥� �앹젏] �쒕줈 臾띠씠吏� �딄쾶.
	if(!checkRule_bbChecker($game_index, $index, $game_type, $home_team, $away_team) && $sport_name == "�띻뎄")
	{
		alert("�숈씪寃쎄린(�띻뎄) [泥� 3��],[泥� �먯쑀��],[泥� �앹젏] 寃쎄린�� 議고빀 諛고똿�� 遺덇��ν빀�덈떎.");
		return;
	}

	//-> �숈씪寃쎄린 �띻뎄 �몃뵒罹� + [泥� 3��],[泥� �먯쑀��],[泥� �앹젏] 議고빀�� 諛고똿�� 遺덇�.
	if(!checkRule_bbChecker2($game_index, $index, $game_type, $home_team, $away_team) && $sport_name == "�띻뎄")
	{
		alert("�숈씪寃쎄린(�띻뎄) [�몃뵒罹�] + [泥� 3�� / 泥� �먯쑀�� / 泥� �앹젏] 寃쎄린�� 議고빀 諛고똿�� 遺덇��ν빀�덈떎.");
		return;
	}
/*
	if(!checkRule($game_index, $index, $game_type, $home_team, $away_team))
	{
		alert("�숈씪寃쎄린 [�밸Т��]-臾�+[�몃뜑�ㅻ쾭]-�몃뜑 議고빀�� 諛고똿 遺덇��ν빀�덈떎.");
		return;
	}
*/
	//-> 異뺢뎄�� 寃쎌슦 媛숈�寃쎄린�먯꽌 �밸Т��+�몃뜑�ㅻ쾭 �좏깮 遺덇�.
	if(!checkRule2($game_index, $index, $game_type, $home_team, $away_team) && $sport_name == "異뺢뎄")
	{
		alert("�숈씪寃쎄린(異뺢뎄) [�밸Т��]+[�몃뜑�ㅻ쾭] 寃쎄린�� 諛고똿�� 遺덇��ν빀�덈떎.");
		return;
	}

	//-> 異뺢뎄 �밸Т��(臾�)+�몃뜑/�ㅻ쾭 �좏깮 遺덇�.
	if(!checkRule4($game_index, $index, $game_type, $home_team, $away_team) && $sport_name == "異뺢뎄")
	{
		alert("�숈씪寃쎄린(異뺢뎄) [臾�]+[�몃뜑/�ㅻ쾭] 寃쎄린�� 諛고똿�� 遺덇��ν빀�덈떎.");
		return;
	}

	//-> 異뺢뎄 �숈씪 寃쎄린 �몃뵒罹�+�몃뜑�ㅻ쾭 �좏깮 遺덇�.
	if(!checkRule3($game_index, $index, $game_type, $home_team, $away_team) && $sport_name == "異뺢뎄")
	{
		alert("�숈씪寃쎄린(異뺢뎄) [�몃뵒罹�]+[�몃뜑�ㅻ쾭] 議고빀�� 寃쎄린�� 諛고똿�� 遺덇��ν빀�덈떎.");
		return;
	}

	//�뱀씠議곌굔- �뱁뙣:�ㅽ럹�쒖� �밸Т�⑤쭔 議고빀媛���
	if(!check_specified_special_rule($game_index, $is_specified_special, $game_type, $home_team, $away_team))
	{
		alert("[�뱁뙣:�ㅽ럹��]�� [�밸Т��]留� 議고빀 媛��ν빀�덈떎.");
		return;
	}
	
	//�밸Т�� �댁쇅�� 臾댁듅遺��� 泥섎━�섏� �딅뒗��.
	if($game_type!=1 && $index==1)
		return;

	//�좏깮�� Checkbox�� 諛곕떦
	var selectedRate = '0';
	if(0==$index) 		selectedRate=$home_rate;
	else if(1==$index)	selectedRate=$draw_rate;
	else if(2==$index)	selectedRate=$away_rate;

	//�좉�
	var toggle_action = toggle($game_index+'_div', $index, selectedRate);
	//insert game
	if (toggle_action=='inserted') 
	{

		var item = new Item($game_index, $home_team, $away_team, $index, selectedRate, $home_rate, $draw_rate, $away_rate, $game_type, $sub_sn, $is_specified_special, $game_date, $league_sn, $sport_name);
		m_betList.addItem(item);
		
		betcon=betcon.add_element($game_index+"|"+$index+"&"+$home_team+"  VS "+$away_team);
		var isdisabled = true;
	}
	//delete game
	else 
	{
		m_betList.removeItem($game_index);
		betcon=betcon.del_element($game_index+"|"+$index+"&"+$home_team+"  VS "+$away_team);
		var isdisabled = false;
	}

	if($game_type=='1' || $game_type=='2') 
	{
		$('#form_'+$game_index+' input:checkbox').each( function(index) {
			if(0==index) { this.disabled = ($home_rate=='0')?true:isdisabled; }
			if(1==index) { this.disabled = ($draw_rate=='0')?true:isdisabled; }
			if(2==index) { this.disabled = ($away_rate=='0')?true:isdisabled; }
		});
	}

	//-> 異뺢뎄 -> �숈씪寃쎄린 [�밸Т��-��]+[�몃뜑�ㅻ쾭-�ㅻ쾭] 議고빀�� 諛고똿 泥댄겕
	if( $sport_name == "異뺢뎄" ) {
		if ( !checkRule_win_over() ) {
			matchGameWinOver = 1;
		} else {
			matchGameWinOver = 0;
		}
	}

	//-> 異뺢뎄 -> �숈씪寃쎄린 [�밸Т��-��]+[�몃뜑�ㅻ쾭-�몃뜑] 議고빀�� 諛고똿 泥댄겕
	if( $sport_name == "異뺢뎄" ) {
		if ( !checkRule_win_under() ) {
			matchGameWinUnder = 1;
		} else {
			matchGameWinUnder = 0;
		}
	}

	//-> 異뺢뎄 -> �숈씪寃쎄린 [�밸Т��-��]+[�몃뜑�ㅻ쾭-�몃뜑] 議고빀�� 諛고똿 泥댄겕
	if( $sport_name == "異뺢뎄" ) {
		if ( !checkRule_lose_under() ) {
			matchGameLoseUnder = 1;
		} else {
			matchGameLoseUnder = 0;
		}
	}

	//-> 異뺢뎄 -> �숈씪寃쎄린 [�밸Т��-��]+[�몃뜑�ㅻ쾭-�ㅻ쾭] 議고빀�� 諛고똿 泥댄겕
	if( $sport_name == "異뺢뎄" ) {
		if ( !checkRule_lose_over() ) {
			matchGameLoseOver = 1;
		} else {
			matchGameLoseOver = 0;
		}
	}

	//-> �쇨뎄 -> �숈씪寃쎄린 "1�대떇 �앹젏" [�밸Т��-��]+[�몃뜑�ㅻ쾭-�몃뜑] 議고빀�� 諛고똿 泥댄겕
	if( $sport_name == "�쇨뎄" ) {
		if ( !checkRule_fb_win_under() ) {
			matchGameFbWinUnder = 1;
		} else {
			matchGameFbWinUnder = 0;
		}
	}

	//-> �쇨뎄 -> �숈씪寃쎄린 "1�대떇 �앹젏" [�밸Т��-��]+[�몃뜑�ㅻ쾭-�ㅻ쾭] 議고빀�� 諛고똿 泥댄겕
	if( $sport_name == "�쇨뎄" ) {
		if ( !checkRule_fb_win_over() ) {
			matchGameFbWinOver = 1;
		} else {
			matchGameFbWinOver = 0;
		}
	}

	//-> �쇨뎄 -> �숈씪寃쎄린 "1�대떇 臾대뱷��" [�밸Т��-��]+[�몃뜑�ㅻ쾭-�몃뜑] 議고빀�� 諛고똿 泥댄겕
	if( $sport_name == "�쇨뎄" ) {
		if ( !checkRule_fb_lose_under() ) {
			matchGameFbLoseUnder = 1;
		} else {
			matchGameFbLoseUnder = 0;
		}
	}

	//-> �쇨뎄 -> �숈씪寃쎄린 "1�대떇 臾대뱷��" [�밸Т��-��]+[�몃뜑�ㅻ쾭-�ㅻ쾭] 議고빀�� 諛고똿 泥댄겕
	if( $sport_name == "�쇨뎄" ) {
		if ( !checkRule_fb_lose_over() ) {
			matchGameFbLoseOver = 1;
		} else {
			matchGameFbLoseOver = 0;
		}
	}

	//-> �쇨뎄 -> �숈씪寃쎄린 [�뱁뙣]+[�몄삤踰�] 議고빀 泥댄겕
	if( $sport_name == "�쇨뎄" ) {
		if ( !checkRule_winlose_unover() ) {
			matchGameFbWinloseUnover = 1;
		} else {
			matchGameFbWinloseUnover = 0;
		}
	}

	bonus_del(); //�쒖젏 愿��� 踰꾧렇
	calc();
}

function del_bet($game_index, bonusFlag)
{
	$("[name="+$game_index+"_div] input").each(function(index) {
		this.checked=false;
		index=index+1;
		$div = $(this).parent();
		if(index==1) 
		{
			$div.attr('class', 'game_home_name_bg');
		}
		else if(index==2) 
		{
			$div.attr('class', 'game_tie_bg');
		}
		else if(index==3) 
		{
			$div.attr('class', 'game_away_name_bg');
		}
	});

	m_betList.removeItem($game_index);
	betcon=betcon.del_one($game_index);

	if ( bonusFlag != 1 )	bonus_del(); //�쒖젏 愿��� 踰꾧렇
	calc();
}

function bet_clear()
{
	var delArray = new Array();
	for(i=0; i<m_betList._items.length;++i)
	{
		delArray[i] = m_betList._items[i]._game_index;
	}

	for(i=0; i<delArray.length; ++i)
	{
		del_bet(delArray[i],1);
	}

	$("#betMoney").val(MoneyFormat(parseInt(eval("VarMinBet"))));
	m_betList._point = parseInt(eval("VarMinBet"));
}


// ------------------------- item -----------------------------------------------------------------
function Item(game_index, home_team, away_team, chckbox_index, select_rate, home_rate, draw_rate, away_rate, game_type, sub_child_sn, is_specified_special, game_date, league_sn, sport_name) 
{
	this._game_index = game_index;
	this._home_team = home_team;
	this._away_team = away_team;
	this._checkbox_index = chckbox_index;
	this._selct_rate = select_rate;
	this._home_rate = home_rate;
	this._draw_rate = draw_rate;
	this._away_rate = away_rate;
	this._game_type = game_type;
	this._lnum = sub_child_sn;
	this._is_specified_special = is_specified_special;
	this._game_date = game_date;
	this._league_sn = league_sn;
	this._sport_name = sport_name;
}

Item.prototype._game_index;
Item.prototype._home_team;
Item.prototype._away_team;
Item.prototype._checkbox_index;
Item.prototype._selct_rate;
Item.prototype._home_rate;
Item.prototype._draw_rate;
Item.prototype._away_rate;
Item.prototype._game_type;
Item.prototype._lnum;
Item.prototype._is_specified_special;
Item.prototype._game_date;
Item.prototype._league_sn;
Item.prototype._sport_name;

function BetList() 
{
	this._items = new Array();
}

BetList.prototype._totalprice;
BetList.prototype._point;
BetList.prototype._bet;
BetList.prototype._items;

BetList.prototype.addItem = function(item) 
{
	this.removeItem(item._game_index);
	this._items.push(item);
	add_bet_list(item);
}

BetList.prototype.removeItem = function(num) 
{
	var pos = -1;

	for (var i = 0; i<this._items.length; i++) 
	{
		if (this._items[i]._game_index == num) 
		{
			pos = i;
			break;
		}
	}
	if (pos>=0) 
	{
		this._items.remove(pos, pos);
		del_bet_list(num);
	}
}