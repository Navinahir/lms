/**
 * @author  KBH
 */
var url = authUrl;
var OAuthCode = function(url) {
    this.loginPopup = function (parameter) {
        this.loginPopupUri(parameter);
    }
    this.loginPopupUri = function (parameter) {
        var parameters = "location=1,width=800,height=650";
        parameters += ",left=" + (screen.width - 800) / 2 + ",top=" + (screen.height - 650) / 2;
        var win = window.open(url, 'connectPopup', parameters);
        var pollOAuth = window.setInterval(function () {
            try {
                if (win.document.URL.indexOf("code") != -1) {
                    window.clearInterval(pollOAuth);
                    win.close();
                    location.reload();
                }
            } catch (e) {
            }
        }, 100);
    }
}
var oauth = new OAuthCode(url);
