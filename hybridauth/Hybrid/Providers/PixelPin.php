<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html 
*/

/**
 * Hybrid_Providers_PixelPin provider adapter based on OAuth2 protocol
 * 
 * http://hybridauth.sourceforge.net/userguide/IDProvider_info_PixelPin.html
 */
class Hybrid_Providers_PixelPin extends Hybrid_Provider_Model_OAuth2
{
	public $scope = "openid profile email phone address";

	/**
	* IDp wrappers initializer 
	*/
	function initialize() 
	{
		parent::initialize();

		// Provider apis end-points
		$this->api->api_base_url  = "https://login.pixelpin.io/connect/";
		$this->api->authorize_url = "https://login.pixelpin.io/connect/authorize";
		$this->api->token_url     = "https://login.pixelpin.io/connect/token"; 

		$this->api->sign_token_name = "access_token";
	}

	/**
	* load the user profile from the IDp api client
	*/
	function getUserProfile()
	{
		$data = $this->api->api( "userinfo", "POST" ); 

		if ( ! isset( $data->sub ) ){
			throw new Exception( "User profile request failed! {$this->providerId} returned an invalid response.", 6 );
		}

		$this->user->profile->identifier    	= $data->sub;

		$firstName = (isset($data->given_name) ? $data->given_name : false);
		if($firstName === false)
		{
			$this->user->profile->firstName 		= 'Unavailable';
		}
		else
		{
			$this->user->profile->firstName     	= $data->given_name;
		}

		$lastName = (isset($data->family_name) ? $data->family_name : false);
		if($lastName === false)
		{
			$this->user->profile->lastName      	= 'Unavailable';
		}
		else
		{
			$this->user->profile->lastName      	= $data->family_name;
		}

		$nickname = (isset($data->nickname) ? $data->nickname : false);
		if($nickname === false)
		{
			$this->user->profile->nickname		    = 'Unavailable';
		}
		else
		{
			$this->user->profile->nickname			= $data->nickname;
		}

		$gender = (isset($data->gender) ? $data->gender : false);
		if ($gender === false){
			$this->user->profile->gender 		= 'Unavailable';
		}
		else
		{
			$this->user->profile->gender 		= $data->gender;
		}

		$birthdate = (isset($data->birthdate) ? $data->birthdate : false);
		if ($birthdate === false)
		{
			$this->user->profile->birthdate 	= 'Unavailable';	
		}
		else
		{
			$this->user->profile->birthdate		= $data->birthdate;

		}

		$phone_number = (isset($data->phone_number) ? $data->phone_number : false);
		if ($phone_number === false){
			$this->user->profile->phoneNumber 	= 'Unavailable';
		}
		else
		{
			$this->user->profile->phoneNumber	= $phone_number;
		}

		$displayName = (isset($data->nickname) ? $data->nickname : false);
		if($displayName	=== false){
			$this->user->profile->displayName	    = 'Unavailable';
		}
		else
		{
			$this->user->profile->displayName   	= $data->nickname;
		}
		

		$email = (isset($data->email) ? $data->email : false);
		if($email === false){
			$this->user->profile->email         = 'Unavailable';
		}
		else
		{
			$this->user->profile->email         	= $data->email;
		}

		$emailVerified	 = (isset($data->emailVerified) ? $data->emailVerified : false);
		if($emailVerified === false){
			$this->user->profile->emailVerified	= 'Unavailable';
		}
		else
		{
	    	$this->user->profile->emailVerified 	= $data->emailVerified;
	    }

	    $address = (isset($data->address) ? $data->address : false);
	    if ($address === false){
	        $this->user->profile->address    	= 'Unavailable';
		    $this->user->profile->country       = 'Unavailable';
		    $this->user->profile->region        = 'Unavailable';
		    $this->user->profile->city          = 'Unavailable';
		    $this->user->profile->zip           = 'Unavailable';
	    }
	    else
	    {
		    $address = $data->address;

		    $decodeAddress = json_decode($address);

		    if($decodeAddress->{"street_address"} !== '')
		    {
		    	$streetAddress2 = $decodeAddress->{"street_address"};
		    	$streetAddress = (string)$streetAddress2;
		    	$this->user->profile->address = $streetAddress;
		    }
		    else
		    {
		    	$this->user->profile->address = 'Unavailable';
		    }

		    if($decodeAddress->{"locality"} !== '')
		    {
		    	$townCity2 = $decodeAddress->{"locality"};
		    	$townCity = (string)$townCity2;
		    	$this->user->profile->city = $townCity;

		    }
		    else
		    {
		    	$this->user->profile->city = 'Unavaliable';
		    }

		    if($decodeAddress->{"region"} !== '')
		    {
		    	$region2 = $decodeAddress->{"region"};
		    	$region = (string)$region2;
		    	$this->user->profile->region = $region;
		    }
		    else
		    {
		    	$this->user->profile->region = 'Unavaliable';
		    }

		    if($decodeAddress->{"postal_code"} !== '')
		    {
		    	$postalCode2 = $decodeAddress->{"postal_code"};
		    	$postalCode = (string)$postalCode2;
		    	$this->user->profile->zip = $postalCode;
		    }
		    else
		    {
		    	$this->user->profile->zip = 'Unavaliable';
		    }

		    if($decodeAddress->{"country"} !== '')
		    {
				$country2 = $decodeAddress->{"country"};
				$country = (string)$country2;
				$this->user->profile->country = $country;
		    }
		    else
		    {
		    	$this->user->profile->country = 'Unavailable';
		    }
	    }

		return $this->user->profile;
	}
}
