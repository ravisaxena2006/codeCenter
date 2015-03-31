<?php 
class MyRailApi {

var $apikey = 'c7aead78d33c317158cea6f2e135067f';
var $apisecret  = '7f31241814514fd091902aa600b930e6';
var $hmac_code = '';
var $base_url = 'http://railpnrapi.com/test/';


function __construct($apikey,$apisecret){

		$this->apikey = $apikey;
		$this->apisecret = $apisecret;
		//$this->hmac_code = $this->hmac_sha1($apikey,$apisecret);
		
}

function getPnrStatus($pnr=null){

    $this->hmac_code = $this->hmac_sha1($apisecret,$pnr.'json'.'c7aead78d33c317158cea6f2e135067f');

    $pnr_service = $this->base_url.'check_pnr/pnr/'.$pnr.'/format/json/pbapikey/'. $this->apikey .'/pbapisign/'.$this->hmac_code;


	return $data = json_decode($this->get_curl($pnr_service));
}



/**
 * HMAC helper
 *
 * $signature = hex2b64(hmac_sha1($key, $data));
 *
 * @package        stensi
 * @category    Helpers
 * @author        stensi
 * @link        http://stensi.com
 */

// ------------------------------------------------------------------------

/**
 * HMAC
 *
 * Calculate HMAC according to RFC 2104, for chosen algorithm.
 * http://www.ietf.org/rfc/rfc2104.txt
 *
 * @access    public
 * @param    string    hash algorithm
 * @param    string    key to sign hash with
 * @param    string    data to be hashed
 * @return    string
 */

    function hmac($hashfunc, $key, $data)
    {
        $blocksize=64;

        if (strlen($key) > $blocksize)
        {
            $key = pack('H*', $hashfunc($key));
        }

        $key = str_pad($key, $blocksize, chr(0x00));
        $ipad = str_repeat(chr(0x36), $blocksize);
        $opad = str_repeat(chr(0x5c), $blocksize);
        $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $data))));

        return bin2hex($hmac);
    }


// ------------------------------------------------------------------------

/**
 * HMAC-SHA1
 *
 * Calculate HMAC-SHA1 according to RFC 2104.
 * http://www.ietf.org/rfc/rfc2104.txt
 *
 * @access    public
 * @param    string    key to sign hash with
 * @param    string    data to be hashed
 * @return    string
 */

    function hmac_sha1($key, $data)
    {
        return $this->hmac('sha1', $key, $data);
    }

// ------------------------------------------------------------------------

/**
 * HMAC-MD5
 *
 * Calculate HMAC-MD5 according to RFC 2104.
 * http://www.ietf.org/rfc/rfc2104.txt
 *
 * @access    public
 * @param    string    key to sign hash with
 * @param    string    data to be hashed
 * @return    string
 */

    function hmac_md5($key, $data)
    {
        return hmac('md5', $key, $data);
    }


// ------------------------------------------------------------------------

/**
 * Hex to Base64
 *
 * Convert hex to base64.
 *
 * @access    public
 * @param    string
 * @return    string
 */

    function hex2b64($str)
    {
        $raw = '';

        for ($i = 0; $i < strlen($str); $i += 2)
        {
            $raw .= chr(hexdec(substr($str, $i, 2)));
        }

        return base64_encode($raw);
    }



function get_curl($url=null){
 $ua = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url); // The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // The number of seconds to wait while trying to connect.
        curl_setopt($ch, CURLOPT_USERAGENT, $ua); // The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE); // To fail silently if the HTTP code returned is greater than or equal to 400.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); // To follow any "Location: " header that the server sends as part of the HTTP header.
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE); // To automatically set the Referer: field in requests where it follows a Location: redirect.
        curl_setopt($ch, CURLOPT_TIMEOUT, 15); // The maximum number of seconds to allow cURL functions to execute.
        curl_setopt($ch, CURLOPT_MAXREDIRS, 1); // The maximum number of redirects

        $result = trim(curl_exec($ch));

        curl_close($ch);

        if (empty($result)) {
            $url = str_replace(' ', '%20', $url);
            $result = trim(file_get_contents($url));
        }

        return $result;

}


}