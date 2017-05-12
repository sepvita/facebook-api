<?php
//Include FB config file
require_once 'fbConfig.php';

if(isset($accessToken)){
    if(isset($_SESSION['facebook_access_token'])){
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }else{
        // Put short-lived access token in session
        $_SESSION['facebook_access_token'] = (string) $accessToken;
        
        // OAuth 2.0 client handler helps to manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();
        
        // Exchanges a short-lived access token for a long-lived one
        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
        
        // Set default access token to be used in script
        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
    
    //FB post content
    $message = 'Test message from My API';
    //$title = 'Post From Website';
    //$link = 'http://tembem.online.blogspot.com/';
    //$description = 'Blog tutorialku.';
    //$picture = 'picture.png';
            
    $attachment = array(
        'message' => $message,
        //'name' => $title,
        //'link' => $link,
        //'description' => $description,
        //'picture'=>$picture,
    );
    
    try{
        //Post to Facebook
        $fb->post('/me/feed', $attachment, $accessToken);
        
        //Display post submission status
        echo 'The post was submitted successfully to Facebook timeline.';
    }catch(FacebookResponseException $e){
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    }catch(FacebookSDKException $e){
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }
}else{
    //Get FB login URL
    $fbLoginURL = $helper->getLoginUrl($redirectURL, $fbPermissions);
    
    //Redirect to FB login
    header("Location:".$fbLoginURL);
}