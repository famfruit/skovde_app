package com.example.google;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.MotionEvent;
import android.webkit.CookieManager;
import android.webkit.CookieSyncManager;
import android.webkit.URLUtil;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Toast;

import androidx.annotation.RequiresApi;
import androidx.appcompat.app.AppCompatActivity;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import java.io.UnsupportedEncodingException;
import java.net.URLDecoder;

public class MainActivity extends AppCompatActivity {
    private WebView webView;
    private Context mContext;
    SwipeRefreshLayout swipeRefreshLayout;
    String url;
    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        webView = (WebView) findViewById(R.id.webview);
        url = getString(R.string.l_url);
        webView.setWebViewClient(new WebViewClient(){
            // Prevents cookie from NOT being saved
            // Always get update the cookie storage before proceeding due to app layout
            // TACK TILL: The korovay @ stackoverflow för hjälpen.
            // users/11535782/the-korovay
            @Override
            public void onPageFinished(WebView webView, String url) {
                super.onPageFinished(webView, url);
                if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
                    CookieManager.getInstance().flush();
                } else {
                    CookieSyncManager.getInstance().sync();
                }
            }
            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url){
                //Toast.makeText(mContext, "Old Method",Toast.LENGTH_SHORT).show();

                 if(url.startsWith("sms:")){
                     Log.d("TEST", "PAST FIRST IF STATEMENT");
                    // Handle the sms: link
                    handleSMSLink(url);

                    // Return true means, leave the current web view and handle the url itself
                    return true;
                } else if( URLUtil.isNetworkUrl(url) ) {
                                return false;
                           }
                           Intent intent = new Intent(Intent.ACTION_DIAL, Uri.parse(url));
                            startActivity( intent );
                            return true;


            }
         //   @Override
         //   public boolean shouldOverrideUrlLoading(WebView webView, String url) {
         //       if( URLUtil.isNetworkUrl(url) ) {
         //           return false;
         //       }
         //       Intent intent = new Intent(Intent.ACTION_DIAL, Uri.parse(url));
        //        startActivity( intent );
        //        return true;
         //   }
        });

        webView.getSettings().setJavaScriptEnabled(true);
        webView.getSettings().setDomStorageEnabled(true);
        webView.getSettings().setLoadsImagesAutomatically(true);
        webView.getSettings().setMixedContentMode(WebSettings.MIXED_CONTENT_ALWAYS_ALLOW);
        webView.getSettings().setAppCacheEnabled(false); // TOGGLE FROM FALSE TO TRUE
        webView.getSettings().setCacheMode(WebSettings.LOAD_NO_CACHE);
        webView.loadUrl(url);

        swipeRefreshLayout = (SwipeRefreshLayout) findViewById(R.id.swipeToRefresh);
        swipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                Log.d("POSITION", "val" + webView.getScrollY());
                webView.reload();
                webView.scrollTo(0,0);
                swipeRefreshLayout.setRefreshing(false);
            }
        });


    }

        @Override
        public void onBackPressed(){
            if(webView.canGoBack()){
                // Decline user to exit app through GOBACK
                // goBack only works if user has history
                webView.goBack();
            }
        }
    protected void handleSMSLink(String url){
        /*
            If you want to ensure that your intent is handled only by a text messaging app (and not
            other email or social apps), then use the ACTION_SENDTO action
            and include the "smsto:" data scheme
        */

        // Initialize a new intent to send sms message
        Intent intent = new Intent(Intent.ACTION_SENDTO);

        // Extract the phoneNumber from sms url
        String phoneNumber = url.split("[:?]")[1];

        if(!TextUtils.isEmpty(phoneNumber)){
            // Set intent data
            // This ensures only SMS apps respond
            intent.setData(Uri.parse("smsto:" + phoneNumber));

            // Alternate data scheme
            //intent.setData(Uri.parse("sms:" + phoneNumber));
        }else {
            // If the sms link built without phone number
            intent.setData(Uri.parse("smsto:"));

            // Alternate data scheme
            //intent.setData(Uri.parse("sms:" + phoneNumber));
        }


        // Extract the sms body from sms url
        if(url.contains("body=")){
            String smsBody = url.split("body=")[1];

            // Encode the sms body
            try{
                smsBody = URLDecoder.decode(smsBody,"UTF-8");
            }catch (UnsupportedEncodingException e){
                e.printStackTrace();
            }

            if(!TextUtils.isEmpty(smsBody)){
                // Set intent body
                intent.putExtra("sms_body",smsBody);
            }
        }

        if(intent.resolveActivity(getPackageManager())!=null){
            // Start the sms app
            startActivity(intent);
        }else {
            Toast.makeText(mContext,"No SMS app found.",Toast.LENGTH_SHORT).show();
        }
    }
    }
