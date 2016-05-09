(function(){
var l,
    doAuth,
    h = ["www.slideshare.net","fr.slideshare.net","de.slideshare.net","staging.slideshare.com","es.slideshare.net","gsar.slideshare.com"],
    valid = false,
    a = "y4wa9oe4c6nu",
    fwk = "http://platform.linkedin.com/js/framework?v=0.0.1198-RC1.31370-1408",
    xtnreg = /extensions=([^&]*)&?/,
    xtn = fwk.match(xtnreg),
    dotRegex = /\./g,
    starRegex = /\*/g,
    selfUrl = window.location.host.replace(/:\d+$/,"").toLowerCase();
window.IN = window.IN || {};
IN.ENV = IN.ENV || {};
IN.ENV.js = IN.ENV.js || {};
IN.ENV.js.xtn = IN.ENV.js.xtn || {};
/*
if !ANONYMOUS_USERSPACE
*/
  if (!a) {  
    throw new Error("API Key is invalid");
  }
  else if (h === "" || h === null) {
    throw new Error("You must specify a valid JavaScript API Domain as part of this key's configuration.");
  }
  /*
  if !LIX_DISABLE_USERSPACE_OAUTH
    */
    for (var i = 0, len = h.length; i < len; i++) {
      var search = new RegExp("^"+h[i].replace(dotRegex, "\\.").replace(starRegex, ".+?")+"$");
      if (selfUrl.match(search)) {
        valid = true;
        break;
      }
    }
    if (!valid) {
      throw new Error("JavaScript API Domain is restricted to "+h.join(", "));
    } else {
  /*
    endif !LIX_DISABLE_USERSPACE_OAUTH
  */
/*
endif !ANONYMOUS_USERSPACE
*/
/*
# --------------------
# ----- FRAGMENT -----
# --------------------
*/
/*
if AUTH_USERSPACE
  else if FULL_USERSPACE || ANONYMOUS_USERSPACE
  */  
    l = IN.ENV.ui = IN.ENV.ui || {};
    l.popup_window_width = 600;
    l.window_width = 100;
    l = IN.ENV.auth = IN.ENV.auth || {};
  /*
  if !LIX_DISABLE_USERSPACE_OAUTH
    */
    l.oauth_token = "";
    l.oauth_expires_in = parseInt("0", 10);
  /*
    endif !LIX_DISABLE_USERSPACE_OAUTH
  */
    l.anonymous_token = "ti51Ru6a0od1xelPaXfXjZKIRJiF-PMU1VOv";
    l.anonymous_expires_in = parseInt("1800", 10);
    l.member_id = "";
    l.api_key = "y4wa9oe4c6nu";
    l = IN.ENV.api = IN.ENV.api || {};
    l.queue_interval = parseInt("300", 10);
    l = IN.ENV.url =  IN.ENV.url || {};
    l.xd_html = "https://platform.linkedin.com/js/xdrpc.html?v=0.0.1198-RC1.31370-1408";
    l.xd_us_html = "http://platform.linkedin.com/js/xdrpc.html?v=0.0.1198-RC1.31370-1408";
    l.api_xd_html = "https://api.linkedin.com/uas/js/xdrpc.html?v=0.0.1198-RC1.31370-1408";
    l.api = "https://api.linkedin.com/v1";
    l.login = "https://www.linkedin.com/uas/connect/user-signin";
    l.authorize = "https://www.linkedin.com/uas/oauth2/authorize?immediate=true";
    l.silent_auth_url = "${SILENT_AUTHORIZE_URL}";
    l.logout = "https://www.linkedin.com/uas/connect/logout?oauth_token={OAUTH_TOKEN}&api_key={API_KEY}&callback={CALLBACK}";
    l.userspace_renew = "https://www.linkedin.com/uas/js/authuserspace?v=0.0.1198-RC1.31370-1408&api_key={API_KEY}";
    l.base_js_url = "${DEFAULT_JS_URL}";
    l.analytics_us_url = "http://www.linkedin.com/analytics/?type=__ETYPE__&trackingInfo=__TRKINFO__&trk=__TINFO__&or=__ORIGIN__&wt=__WTYPE__";
    l.analytics_url = "http://www.linkedin.com/analytics/?type=__ETYPE__&trackingInfo=__TRKINFO__&trk=__TINFO__&or=__ORIGIN__&wt=__WTYPE__";

    l = IN.ENV.widget = IN.ENV.widget || {};
    l.leadgen_url = "http://www.linkedin.com/cws/leadgen";
    l.followmember_url = "http://www.linkedin.com/cws/followmember";
    l.settings_url = "http://www.linkedin.com/cws/settings";
    l.share_url = "http://www.linkedin.com/cws/share";
    l.share_counter_url = "http://www.linkedin.com/countserv/count/share";
    l.recommend_product_url = "http://www.linkedin.com/company/{COMPANY_ID}/product?prdId={PRODUCT_ID}";
    l.recommend_product_counter_url = "http://www.linkedin.com/company/api/recommendation/count?type=PDCT&id={PRODUCT_ID}&callback={CALLBACK}";
    l.company_url = "http://www.linkedin.com/cws/company/profile";
    l.member_profile_url = "http://www.linkedin.com/cws/member/public_profile";
    l.full_member_profile_url = "http://www.linkedin.com/cws/member/full_profile";
    l.referral_center_url= "http://www.linkedin.com/cws/referral";
    l.apply_url= "http://www.linkedin.com/cws/job/apply";
    l.mail_url= "http://www.linkedin.com/cws/mail";
    l.apply_counter_url = "http://www.linkedin.com/countserv/count/job-apply";
    l.company_insider_url = "http://www.linkedin.com/cws/company/insider";
    l.sfdc_member_url = "https://www.linkedin.com/cws/sfdc/member";
    l.sfdc_company_url = "https://www.linkedin.com/cws/sfdc/company";
    l.sfdc_signal_url = "https://www.linkedin.com/cws/sfdc/signal";
    l.cap_recruiter_member_url = "https://www.linkedin.com/cws/cap/recruiter_member";
    l.jymbii_url = "http://www.linkedin.com/cws/jymbii";
    l.today_url = "http://www.linkedin.com/cws/today/today";
    l.followcompany_url = "http://www.linkedin.com/cws/followcompany";
    l.lilaform_url = "http://www.linkedin.com/cws/lilaform";
    l.alumni_facet_url = "http://www.linkedin.com/college/alumni-facet-extension";
    l.csap_beacon_url = "http://www.linkedin.com/cws/csap/beacon";
    l = IN.ENV.images = IN.ENV.images || {};
    l.sprite = "http://s.c.lnkd.licdn.com/scds/common/u/img/sprite/sprite_connect_v13.png";
    l.unsecure_xdswf = "http://platform.linkedin.com/js/easyXDM.swf?v=0.0.1198-RC1.31370-1408";
    l.secure_xdswf = "https://platform.linkedin.com/js/easyXDM.swf?v=0.0.1198-RC1.31370-1408";
    /*
     # Client Side Extensions
     # These are possibly in framework js and need to be loaded
     # via IN.$extensions() instead. This also helps ensure we're under
     # the 2048 limit for URL length in cases where a lot of extensions
     # are being loaded
     */
    if (xtn && xtn[1] && IN.$extensions) {
      IN.$extensions(decodeURIComponent(xtn[1]));
      fwk = fwk.replace(xtn[0], "").replace(/&*$/, "");
    }
/*
endif
*/
/*
if FULL_USERSPACE
*/
  /*
  if !LIX_DISABLE_USERSPACE_OAUTH
    */
    /*
    if IS_SET_CLIENT_AUTH_COOKIE
        */
      IN.Event.on(IN, "frameworkLoaded", function() {
          IN.ENV.auth.is_set_client_auth_cookie = true;
        IN.User.setOauthCookie("");
      });
    /*
        endif
    */
    /*
    if !IS_SET_CLIENT_AUTH_COOKIE
        endif
    */
    /*
    if !IS_SET_CLIENT_AUTH_COOKIE
        endif
    */    
  /*
    endif
  */
  node = document.createElement("script");
  node.type = "text/javascript";
  // TODO - do this in UserspaceGenerator
  var src = "http://platform.linkedin.com/js/framework?v=0.0.1198-RC1.31370-1408";
  node.src = src + ((/\?/.test(src)) ? "&" : "?") + ("lang=" + encodeURIComponent(IN.ENV.js.lang));
  document.getElementsByTagName("head")[0].appendChild(node);
/*
endif
*/
/*
# --------------------
# ----- FRAGMENT -----
# --------------------
*/
/*
if !ANONYMOUS_USERSPACE
*/
  /*
  if !LIX_DISABLE_USERSPACE_OAUTH
    */
    }
  /*
    endif !LIX_DISABLE_USERSPACE_OAUTH
  */
/*
endif !ANONYMOUS_USERSPACE
*/
})();

