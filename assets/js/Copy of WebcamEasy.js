'use strict';
class Webcam{ 
constructor(e,t,s,i){this._webcamElement=e,this._webcamElement.width=this._webcamElement.width||640,this._webcamElement.height=this._webcamElement.height||.75*this._webcamElement.width,this._facingMode=t,this._webcamList=[],this._streamList=[],this._selectedDeviceId="",this._canvasElement=s,this._snapSoundElement=i}  facingMode(){return this._facingMode} facingMode(e){this._facingMode=e} webcamList(){return this._webcamList} webcamCount(){return this._webcamList.length}  selectedDeviceId(){return _selectedDeviceId}  getVideoInputs(e){var m_=this;return m_._webcamList=[],e.forEach(function(e){"videoinput"===e.kind&& m_._webcamList.push(e)}),1==m_._webcamList.length&&(m_._facingMode="user"),m_._webcamList} getMediaConstraints(){var e={};return""==this._selectedDeviceId?e.facingMode=this._facingMode:e.deviceId={exact:this._selectedDeviceId},{video:e,audio:!1}}  selectCamera(){for(let e of this._webcamList)if("user"==this._facingMode&&e.label.toLowerCase().includes("front")||"enviroment"==this._facingMode&&e.label.toLowerCase().includes("back")){_selectedDeviceId=e.deviceId;break}}  flip(){this._facingMode="user"==this._facingMode?"enviroment":"user",this._webcamElement.style.transform="",this.selectCamera()} errBack(e){
	console.log(e);
} start(e){ var m_=this;
	return new Promise(function(t,s){m_.stop();
	if(navigator.mediaDevices === undefined)
		navigator.mediaDevices = {};
	
	if(navigator.mediaDevices.getUserMedia === undefined)
		navigator.mediaDevices.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
	
	navigator.mediaDevices.getUserMedia(m_.getMediaConstraints()).then(function(i){m_._streamList.push(i),m_.info().then(function(i){m_.selectCamera(),e?m_.stream().then(function(e){t(m_._facingMode)}).catch(function(e){s(e)}):t(m_._selectedDeviceId)}).catch(function(e){s(e)})}).catch(function(e){s(e)})})} 
	
	info(){var m_=this;return new Promise(function(e,t){navigator.mediaDevices.enumerateDevices().then(function(t){m_.getVideoInputs(t),e(m_._webcamList)}).catch(function(e){t(e)})})}  
	
stream(){
		
		var m_=this; return new Promise(function(e,t){
			navigator.mediaDevices.getUserMedia(m_.getMediaConstraints()).then(
			function(t){
				
				m_._streamList.push(t);
/*
if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
	 if ('srcObject' in m_._webcamElement) {
            m_._webcamElement.srcObject = t;
        } else {
            // Avoid using this in new browsers, as it is going away.
            m_._webcamElement.src = URL.createObjectURL(t);
        }
}else if(navigator.getUserMedia) { 
        m_._webcamElement.src = t;
} else if(navigator.webkitGetUserMedia) {  
         m_._webcamElement.src = window.webkitURL.createObjectURL(t);
} else if(navigator.mozGetUserMedia) {  
         m_._webcamElement.srcObject = t;}*/

				m_._webcamElement.srcObject=t;
				
				"user"==m_._facingMode&&(m_._webcamElement.style.transform="scale(-1,1)");m_._webcamElement.play();e(m_._facingMode)}).catch(function(e){console.log(e),t(e)})})
		
		
		
		
		}  stop(){this._streamList.forEach(function(e){e.getTracks().forEach(function(e){e.stop()})})}  snap(){if(null!=this._canvasElement){null!=this._snapSoundElement && this._snapSoundElement.play(),this._canvasElement.height=this._webcamElement.scrollHeight,this._canvasElement.width=this._webcamElement.scrollWidth;let e=this._canvasElement.getContext("2d");return"user"==this._facingMode&&(e.translate(this._canvasElement.width,0),e.scale(-1,1)),e.clearRect(0,0,this._canvasElement.width,this._canvasElement.height),e.drawImage(this._webcamElement,0,0,this._canvasElement.width,this._canvasElement.height),this._canvasElement.toDataURL("image/png")}throw"canvas element is missing"}}