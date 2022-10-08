/**
##jsRolling

#설명
자식 노드를 롤링 시킨다.
롤링배너를 만든다.

#정보
개발자 : 공대여자(http://mins01.com)
만든날 : 2010-04-05
고친날 : NULL

#제약조건
"공대여자는 이쁘다"는 걸 알아야함.

#테스트
IE7
FF 3.6.x
Crome 4.x
Opera 9.x
*/
/*
주의 
direction 이 2,4일 땐 태그 사이에 줄바꿈, 빈칸 등도 있으면 안된다(IE계열에서 빈칸으로 보일 수 있다.)
*/
var jsRolling = function(target){
	//alert(target);
	this.timer = null; //interval 타이머
	this.timerNextPause = null;
	
	this.direction = 1; //방향
	this.gapTime = 10; //이동딜레이
	this.gapMove = 1; //이동간격
	this.gapNextPause = 1000; //다음 대상 동작에 대한 딜레이, 0이면 사용안함.
	
	this.target = null;
	this.targetW = 0;
	this.chilNodesW = 0;
	
	this.started = false; //현재 동작중인가?
	this.paused = false; //현재 잠시 멈춤 중인가?
	this.nextPaused = false; //다음 개체가 보일때 잠시 멈춤 중인가?
	this.checkStart = true; //start 할때 자식노드들의 너비를 체크해서 start가능한지 체크, 없으면 무조건 동작!
	
	if(target){
		this.init(target);
	}
}
jsRolling.prototype = {
	//초기화, 타겟을 지정하고 타겟의 child를 초기화시킨다.
	'init':function(target){
		this.target = target;

	}
	//--- 타겟 설정 초기화
	,'initChild':function(child){
		child.style.margin = '0';
	}
	//--- 방향과 자식 설정 초기화
	,'setDirection':function(direction){
		this.changeDirection(direction);
		var tNode = [];
		//--- 타겟 초기화
		this.target.style.overflow = 'hidden';
		this.target.style.position = 'relative';
		this.target.onmouseover = function(thisC){
														return function(){
															thisC.onmouseover()
														}
													}(this);
		this.target.onmouseout = function(thisC){
																return function(){
																	thisC.onmouseout()
																}
															}(this);
		
		if(this.direction == 1 || this.direction == 3){
			this.target.style.whiteSpace = 'normal';
			this.targetW = this.target.offsetWidth;
			
			for(var child = this.target.firstChild 	; child 	; child = child.nextSibling){
				if(child.nodeType==1){	
					child.style.margin = 0;
					child.style.display = 'block';
					this.chilNodesW += child.offsetWidth;
				}else{
					tNode.push(child);
				}
			}
		}else if(this.direction == 2 || this.direction == 4){
			this.target.style.whiteSpace = 'nowrap';
			this.targetW = this.target.offsetHeight;
			
			for(var child = this.target.firstChild 	; child 	; child = child.nextSibling){
				if(child.nodeType==1){	
					child.style.margin = 0;
					child.style.display = 'inline-block';
					child.style.cssText += ';/display:inline'; //FOR IE6,7
					this.chilNodesW += child.offsetHeight;
				}else{
					tNode.push(child);
				}
			}
		}
		for(var i=0,m=tNode.length;i<m;i++){
			tNode[i].parentNode.removeChild(tNode[i]); //택스트 노드 제거
			
		}
		tNode = null;
	}
	//--- 방향 변경
	,'changeDirection':function(direction){
		this.direction = direction
	}
	//--- 처음노드 가져오기
	,'getFirst':function(){
		return this.target.firstChild;
	}
	//--- 마지막노드 가져오기
	,'getLast':function(){
		return this.target.lastChild;
	}
	//--- 동작
	,'_act':function(){
		if(this.paused || this.nextPaused){return;}
		switch(this.direction){
			case 1:this._act_up();	break;
			case 2:this._act_right(); break;
			case 3:this._act_down(); break;
			case 4:this._act_left();	break;
		}
	}
	//--- 동작 위 (1)
	,'_act_up':function(){
		//---초기화
		var n = this.getFirst();
		var mt = Math.abs(parseInt(n.style.marginTop));
		var mtg = mt+this.gapMove
		var h = n.offsetHeight;
		if(mtg>=h){
			this.target.appendChild(n);
			this.initChild(n);
			this.nextPause();
			this._act();
			return;
		}else{
			n.style.marginTop = '-'+mtg.toString()+'px';
		}
		//document.title = n.style.marginTop;
	}
	//--- 동작 오른쪽 (2)
	,'_act_right':function(){
		//---초기화
		var n = this.getFirst();
		var ml = Math.abs(parseInt(n.style.marginLeft));
		var mlg = ml-this.gapMove
		if(mlg<=0){
			var l = this.getLast();
			var w = l.offsetWidth;
			l.style.marginLeft = '-'+w+'px';
			this.target.insertBefore(l,n); //마지막 노드를 맨 처음으로
			
			this.initChild(n);
			this.nextPause();
			this._act();
			return;
		}else{
			n.style.marginLeft = '-'+mlg.toString()+'px';
		}
		//document.title = n.style.marginLeft;
	}
	//--- 동작 아래(3)
	,'_act_down':function(){
		//---초기화
		var n = this.getFirst();
		var mt = Math.abs(parseInt(n.style.marginTop));
		var mtg = mt-this.gapMove;
		
		if(mtg<=0){
			var l = this.getLast();
			var h = l.offsetHeight;
			l.style.marginTop = '-'+h+'px';
			this.target.insertBefore(l,n); //마지막 노드를 맨 처음으로
			this.initChild(n);
			this.nextPause();
			this._act();
			return;
		}else{
			n.style.marginTop = '-'+mtg.toString()+'px';
		}
		//document.title = n.style.marginTop;
	}
	//--- 동작 왼쪽 (4)
	,'_act_left':function(){
		//---초기화
		var n = this.getFirst();
		var ml = Math.abs(parseInt(n.style.marginLeft));
		var mlg = ml+this.gapMove
		var w = n.offsetWidth;
		if(mlg>=w){
			this.target.appendChild(n);
			this.initChild(n);
			this.nextPause();
			this._act();
			return;
		}else{
			n.style.marginLeft = '-'+mlg.toString()+'px';
		}
		//document.title = n.style.marginLeft;
	}
	//---  롤링시작
	,'start':function(){
		if(!this.started && this.startAble()){
			this.nextPause();
			var timer22 = this.timer = setInterval(function(thisC){return function(){thisC._act()}}(this),this.gapTime);
			this.started = true;
		}
	}
	//--- 롤링 멈춤
	,'stop':function(){
		clearInterval(this.timer);
		this.started = false;
	}
	//--- 롤링을 시작할 수 있는가? 외각의 1.5배만큼의 자식 노드가 있어야한다.
	,'startAble':function(){
		if(!this.checkStart){return true;}
		if(this.chilNodesW > (this.targetW*1.5)){
			return true;
		}
		return false;
	}
	//--- pause(bool이 true면 멈춘다. false면 멈춤이 해제)
	//stop과 차이점은 clearInterval을 하지 않는다는 것!
	,'pause':function(bool){
		this.paused = bool?true:false;
	}
	//--- 다음 객체가 보일때 잠시 멈춘다.
	,'nextPause':function(){
		if(this.nextPaused){
			clearTimeout(this.timerNextPause);
		}
		this.nextPaused = true;
		this.timerNextPause = setTimeout(function(thisC){
								return function(){
									thisC.nextPaused = false;
								}
							}(this)
							,this.gapNextPause);
	}
	//--- 마우스 오버 이벤트, 보통 잠시 멈춘다.
	,'onmouseover':function(){
		this.pause(true);
	}
	//--- 마우스 아웃 이벤트, 보통 잠시 멈춘을 해제한다.
	,'onmouseout':function(){
		this.pause(false);
	}
	//--- 위로 넘김
	,'up':function(){
		var n = this.getFirst();
		var l = this.getLast();
		this.target.appendChild(n);	
		this.initChild(n);
		this.nextPause();
	}
	//--- 아래로 넘김
	,'down':function(){
		var n = this.getFirst();
		var l = this.getLast();
		this.target.insertBefore(l,n); //마지막 노드를 맨 처음으로
		this.initChild(l);
		this.initChild(n);
		this.nextPause();
	}
	//--- 왼쪽으로 넘김, up과 동작이 같다.
	,'left':function(){
		this.up();
	}
	//--- 오른쪽으로 넘김 , down과 동작이 같다.
	,'right':function(){
		this.down();
	}
}