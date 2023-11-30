(function(e){
	//var t;function a(){var e=Error.apply(this,arguments);e.name=this.name="FlashError";this.stack=e.stack;this.message=e.message}
var Webcam={version:"1.0.0",protocol:location.protocol.match(/https/i)?"https":"http",loaded:false,errBack:function(e){
	console.log(e);
},init:function(e,t,s,i){this._webcamElement=e;this._webcamElement.width=this._webcamElement.width||640;this._webcamElement.height=this._webcamElement.height||.75*this._webcamElement.width;this._facingMode=t;this._webcamList=[];this._streamList=[];this._selectedDeviceId="";this._canvasElement=s;this._snapSoundElement=i}
,facingMode:function(){return this._facingMode}
,facingMode:function(e){this._facingMode=e}
,webcamList:function(){return this._webcamList}
,webcamCount:function(){return this._webcamList.length},
selectedDeviceId:function(){return this._selectedDeviceId}
,getVideoInputs:function(e){this._webcamList=[];
for(var b in e){ "videoinput"===b.kind&&this._webcamList.push(b)};1==this._webcamList.length&&(this._facingMode="user");return this._webcamList}
,getMediaConstraints:function(){
	var gt = this;
	var e={};return ""==this._selectedDeviceId?e.facingMode=this._facingMode:e.deviceId={exact:this._selectedDeviceId},{video:e,audio:!1}}
,selectCamera:function(){for(var e in this._webcamList)if("user"==this._facingMode&&e.label.toLowerCase().includes("front")||"enviroment"==this._facingMode&&e.label.toLowerCase().includes("back")){this._selectedDeviceId=e.deviceId;break}}
,flip:function(){
	this._facingMode="user"==this._facingMode?"enviroment":"user";
	this._webcamElement.style.transform="";
	this.selectCamera()
	}
,start:function(e){var m=this;
e=!0;
if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) { 
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) { 
         m._webcamElement.srcObject = stream;
         m._webcamElement.play();
    });
} 
else if(navigator.getUserMedia) { // Standard
    navigator.getUserMedia({ video: true }, function(stream) {
        m._webcamElement.src = stream;
         m._webcamElement.play();
    },  m.errBack);
} else if(navigator.webkitGetUserMedia) {  
    navigator.webkitGetUserMedia({ video: true }, function(stream){
         m._webcamElement.src = window.webkitURL.createObjectURL(stream);
         m._webcamElement.play();
    },  m.errBack);
} else if(navigator.mozGetUserMedia) {  
    navigator.mozGetUserMedia({ video: true }, function(stream){
         m._webcamElement.srcObject = stream;
         m._webcamElement.play();
    },  m.errBack);
} 

return new Promise(function(t,s){
	m.stop();
 
if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) { 
    navigator.mediaDevices.getUserMedia(m.getMediaConstraints()).then( 
       
function(i){m._streamList.push(i);m.info().then(
function(x){m.selectCamera();e?m.stream().then(
function(e){t(m._facingMode)}).catch(function(e){s(e)}):t(m._selectedDeviceId)}).catch(function(e){s(e)})});
    
}

// Legacy code below: getUserMedia 
else if(navigator.getUserMedia) { // Standard
    navigator.getUserMedia(m.getMediaConstraints(), 
function(i){m._streamList.push(i);m.info().then(
function(x){m.selectCamera();e?m.stream().then(
function(e){t(m._facingMode)}).catch(function(e){s(e)}):t(m._selectedDeviceId)}).catch(function(e){s(e)})}, errBack);
} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
    // navigator.webkitGetUserMedia(m.getMediaConstraints(), 
    navigator.webkitGetUserMedia({video:true, audio:true}, 
	
function(i){m._streamList.push(i);m.info().then(
function(x){m.selectCamera();e?m.stream().then(
function(e){t(m._facingMode)}).catch(function(e){s(e)}):t(m._selectedDeviceId)}).catch(function(e){s(e)})}, errBack);
} else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
    navigator.mozGetUserMedia(m.getMediaConstraints(),
	
function(i){m._streamList.push(i),m.info().then(
function(x){m.selectCamera(),e?m.stream().then(
function(e){t(m._facingMode)}).catch(function(e){s(e)}):t(m._selectedDeviceId)}).catch(function(e){s(e)})}
, errBack);
} 
})
},info:function(){
	var y=this;
	if (navigator.mediaDevices === undefined) {
      navigator.mediaDevices = {};
    }

    if (navigator.mediaDevices.getUserMedia === undefined) {
      navigator.mediaDevices.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    }
	return new Promise(function(e,t){
	navigator.mediaDevices.enumerateDevices().then(function(t){y.getVideoInputs(t),e(y._webcamList)}).catch(function(e){t(e)})})}
,stream:function(){var r=this;
return new Promise(function(e,t){
	
if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia(r.getMediaConstraints()).then( 
		function(v){
			r._streamList.push(v);
			// if(r._webcamElement.getAttribute("src") !== v){		
		// r._webcamElement.setAttribute("src",v); 
		 if ('srcObject' in r._webcamElement) {
            r._webcamElement.srcObject = v;
        } else {
            // Avoid using this in new browsers, as it is going away.
            r._webcamElement.src = URL.createObjectURL(v);
        }		
		// r._webcamElement.srcObject=t; 
	
			// r._webcamElement.srcObject=v; 
		// r._webcamElement.onloadedmetadata = function(z) {
		// r._webcamElement.play();
		// };
    });
}
else if(navigator.getUserMedia) { // Standard
    navigator.getUserMedia({ video: true }, function(n){r._streamList.push(t);
	if(r._webcamElement.getAttribute("src") !== n){		
		r._webcamElement.setAttribute("src",n); 
		// r._webcamElement.srcObject=t; 
	}
		// r._webcamElement.onloadedmetadata = function(z) {
		// r._webcamElement.play();
		// };
    }, errBack);
} else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
     navigator.webkitGetUserMedia({ video: true }, function(stream){
		 r._streamList.push(stream);
        r._webcamElement.src = window.webkitURL.createObjectURL(stream);
        // r._webcamElement.play();
    }, errBack);
} else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
    navigator.mozGetUserMedia({ video: true }, function(z){r._streamList.push(z);
	r._webcamElement.srcObject=z; 
		// r._webcamElement.onloadedmetadata = function(u) {
		// r._webcamElement.play();
		// };
    }, errBack);
}
"user"==r._facingMode&&(r._webcamElement.style.transform="scale(-1,1)");
r._webcamElement.play(); 
e(r._facingMode);
	/*navigator.mediaDevices.getUserMedia(r.getMediaConstraints()).then(function(u){r._streamList.push(u);r._webcamElement.srcObject=t;
r._webcamElement.onloadedmetadata = function(e) {
    r._webcamElement.play();
  };
})*/
})
}
,stop:function(){	
	// if( this._streamList!=null) 
	 this._streamList.forEach(
		function(e){e.getTracks().forEach(
		function(c){
			c.stop()
			})});
	// Stop all video streams.
	// if( this._webcamElement.srcObject!=null) 
		// this._webcamElement.srcObject.getVideoTracks().forEach(
				// function(track){track.stop()
				// });
	}
,snap:function(){if(null!=this._canvasElement){null!=this._snapSoundElement&&this._snapSoundElement.play();this._canvasElement.height=this._webcamElement.scrollHeight;this._canvasElement.width=this._webcamElement.scrollWidth;var e=this._canvasElement.getContext("2d");"user"==this._facingMode&&(e.translate(this._canvasElement.width,0),e.scale(-1,1))?e.clearRect(0,0,this._canvasElement.width,this._canvasElement.height):e.drawImage(this._webcamElement,0,0,this._canvasElement.width,this._canvasElement.height);this._canvasElement.toDataURL("image/png");}else throw"canvas element is missing";}};if(typeof define==="function"&&define.amd){define(function(){return Webcam})}else if(typeof module==="object"&&module.exports){module.exports=Webcam}else{e.Webcam=Webcam}})(window);