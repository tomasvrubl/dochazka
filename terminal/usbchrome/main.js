/**
 * Listens for the app launching then creates the window
 *
 * @see http://developer.chrome.com/apps/app.runtime.html
 * @see http://developer.chrome.com/apps/app.window.html
 */
chrome.app.runtime.onLaunched.addListener(function() {
    runApp();
  });
  
  /**
   * Listens for the app restarting then re-creates the window.
   *
   * @see http://developer.chrome.com/apps/app.runtime.html
   */
  chrome.app.runtime.onRestarted.addListener(function() {

    runApp();

  });


  chrome.runtime.onSuspend.addListener(function() {    
      try{    
        MWRFID.close();
      }catch(ex){}
  });



  /**
   * Creates the window for the application.
   *
   * @see http://developer.chrome.com/apps/app.window.html
   */
  function runApp() {

    chrome.app.window.create('index.html', {
        id: "browserWinID",  state: "fullscreen"
    });

  }

