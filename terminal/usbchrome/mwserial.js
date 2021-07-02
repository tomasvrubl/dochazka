
var MWRFID = {
      path: '/dev/ttyUSB0',
      conn: null,
      call: null,
      MIN_RESPONSE_LENGTH: 12,
      _buff: "",

      init : function(){
        let t = this;

        this.close();
        /*
        Baud rate: 9600
        Data bits: 8
        Stop bits: 1
        Parity: none
        Flow control: none
        Forward: none
        Transmitted text: Append CR-LF
        */
        chrome.serial.connect(this.path, {
          bitrate: 9600,
          bufferSize: t.MIN_RESPONSE_LENGTH,
          stopBits: "one",
          dataBits: "eight",
          parityBit: "no"
        }, function(connectionInfo) {
          console.log('Pripojeno...');
          t.conn = connectionInfo;
        });

    

        chrome.serial.onReceive.addListener(function(data){

          t._buff += t.formatCode(data.data);          

          if (t._buff.length < t.MIN_RESPONSE_LENGTH) {
              return;
          }

          try{
            if(t.call!= null){
              t.call(t._buff);
            }
          }catch(ex){}          
          t._buff = "";

        });

      },


      onReceive : function(callfce){
          this.call = callfce;
      },

      formatCode : function(buffer){

        var str = "";
        var bf = new Uint8Array(buffer);
      
        for(var i=0; i < bf.length; ++i){
          str += String.fromCharCode(bf[i]);
        }
        
        return str;
        
      },

      close: function(){
        if(this.conn != null){
          chrome.serial.disconnect(conn.connectionId);
        }
      }



}

var wokno = null;
window.onload = function(){        

  
  var btn = document.getElementById('btn_reset');
  
  if(btn){
    btn.addEventListener("click", function(){
      chrome.runtime.reload();
    });
  }
  
  wokno = document.getElementById('wokno');  

  MWRFID.onReceive(function(code) {
      wokno.executeScript({ code: "var evt = new CustomEvent('rfid_ctecka', {detail: '"+code+"'}); window.dispatchEvent(evt);" });
  });

  MWRFID.init();
}

