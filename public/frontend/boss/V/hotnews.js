function MsgScroll() {
    this.name = "MsgScroll";  //占쏙옙크占쏙옙 占쏙옙(占쏙옙체)
	this.msgs = new Array(); // 占쌨쇽옙占쏙옙 占썼열占쏙옙占쏙옙
	this.msgcnt =0;  //占쌨쇽옙占쏙옙 占썼열占쏙옙호 占쏙옙占쏙옙
	this.stop = 0; // 占쏙옙占쏙옙 占쏙옙占쏙옙
    this.height = 100; // 占쏙옙占싱억옙 占쏙옙占쏙옙
	this.width = 100;  //占쏙옙占싱억옙 占쏙옙占쏙옙
	this.speed = 10; // 占쏙옙占쏙옙 占쏙옙占쏙옙(占쌈듸옙)
	this.currentSpeed = 0; // 占쏙옙占쏙옙 占쏙옙占쏙옙 占쏙옙占쏙옙(占쌈듸옙)	
	this.pauseDelay = 3000; // 占쏙옙占쏙옙 占시곤옙
	this.pauseMouseover = false; //占쏙옙占쎌스占쏙옙 占시뤄옙占쏙옙占쏙옙 占쏙옙占쏙옙 占쏙옙占쏙옙
    this.add = function(str) {  //占쌨쇽옙占쏙옙 첨占쏙옙 占쌨쇽옙占쏙옙			       
				   this.msgs[this.msgcnt] = str;
				   this.msgcnt = this.msgcnt + 1;
               }
    this.start = function() {
					 this.init();
					 setTimeout(this.name+'.scroll()',this.speed);
				 }
	this.init =function() {
					  document.write('<div id="'+this.name+'" style="height:'+this.height+';width:'+this.width+';position:relative;overflow:hidden;" OnMouseOver="'+this.name+'.onmouseover();" OnMouseOut="'+this.name+'.onmouseout();">');
					  for(var i = 0; i < this.msgcnt; i++) {
						  document.write('<div id="'+this.name+'msg'+i+'"style="left:0px;width:'+this.width+';position:absolute;top:'+(this.height*i+1)+'px;">');
						  document.write(this.msgs[i]);
						  document.write('</div>');
                      }
				  }
     this.scroll = function() {
		               if (!this.stop) { 
	                       this.speed = this.currentSpeed;
						   for (i = 0; i < this.msgcnt; i++) {
	                           obj = document.getElementById(this.name+'msg'+i).style;
		            		   obj.top = parseInt(obj.top) - 1;							   
			                   if (parseInt(obj.top) <= this.height*(-1)) obj.top = this.height * (this.msgcnt-1);
			                   if (parseInt(obj.top) == 0) this.speed = this.pauseDelay							  							   
		                   } 
	                   }
	                   window.setTimeout(this.name+".scroll()",this.speed);
	                }
      this.onmouseover = function() {
	                         if (this.pauseMouseover) this.stop = true;
						 }
	  this.onmouseout = function() {
	                        if (this.pauseMouseover) this.stop = false;
						 }
}
