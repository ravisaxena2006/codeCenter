<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Page Rank Checker</title>
<style type="text/css">
.main_container{border:5px solid #002B55; width:60%; height:400px;margin:100px auto; font-family:Verdana, Arial, Helvetica, sans-serif;}
.header_area{background:#002B55; width:98%; height:60px; color:#FFFFFF; font-size:25px; font-weight:bold; padding:20px 0 0 2%;}
.body_area{margin:40px 0 10px 40px;}
.text_field{width:70%; border:1px solid #666666; padding:5px; height:30px; color:#999999; font-size:14px;}
.cpt_field{width:70px; border:1px solid #666666; padding:5px; height:18px; color:#999999; font-size:14px; margin-right:5px;}
.submit_btn{color:#FFFFFF; font-size:16px; font-weight:bold; border:none; background:#002B55; padding:5px 20px; margin-left:165px;}
#show_result{width:67.5%; height:80px;margin:30px 0 0 165px;}
.pagerank_area{border:1px solid #CCCCCC; font-size:12px; width:100%;height:90px; font-weight:bold;}
.pagerank_area .rank_header{width:100%; height:35px; background:#CCCCCC; clear:both;}
.pagerank_area .rank_body{width:100%; clear:both; color:#666666;}
.pagerank_area .left_box{width:55%; padding:2%; float:left;}
.pagerank_area .right_box{width:35%; padding:2%; float:left;}
.error_msg{color:#F00; font-weight:normal; font-size:12px;}
</style>
<script type="text/javascript">

	var siteURL 	= '';
	function getPageRankData(pageurl, captchaValue){
		var pageurl;
		if(pageurl == '' || pageurl == 'http://'){
			alert('Please enter website url.');
			return false;
		}else if(!is_valid_url(pageurl)){
			alert('Please enter valid website url.');
			return false;
		}else if(captchaValue ==''){
			alert('Please enter captcha value.');
			document.getElementById('captcha').focus();
			return false;
		}else{
			pageurl = pageurl.replace(/^\s*/, "");
			pageurl = pageurl.replace(/\s*$/, "");
			
			xmlHttp = GetXmlHttpObject();
			if (xmlHttp==null)
			{
				alert ("Browser does not support HTTP Request");
				return false;
			} 
			var url = "pagerank.php";
			url		= url + "?pageurl="+pageurl;
			url		= url + "&captchaValue="+captchaValue;
			url		= url + "&rand="+Math.random();
			xmlHttp.onreadystatechange = getPageRankResult;
			xmlHttp.open("GET",url,true);
			xmlHttp.send(null);				
		}
	}

	function getPageRankResult()
	{ 
		document.getElementById('show_result').innerHTML = '<img src="images/loading.gif" style="margin-left:45%;">';
		if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
		{ 			
			if(xmlHttp.responseText == 0){
				document.getElementById('show_result').innerHTML = '';
				alert('Please enter valid captcha value.');
				return false;
			}else{
				document.getElementById('show_result').innerHTML =xmlHttp.responseText;
				document.getElementById('siteurl').value = 'http://';
				document.getElementById('captcha').value = '';
				return false;
			}
		}
	}


	function GetXmlHttpObject()	
	{ 
		var objXMLHttp = null;
		if (window.XMLHttpRequest)	
			objXMLHttp = new XMLHttpRequest();
		else if (window.ActiveXObject)
			objXMLHttp = new ActiveXObject("Microsoft.XMLHTTP");
			
		return objXMLHttp
	}
	
	function is_valid_url(url)
	{
		 return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
	}

</script>
</head>
<body>
    <div class="main_container">
        <div class="header_area">Page Rank Checker</div>
            <div class="body_area">Website URL : <input type="text" id="siteurl" name="siteurl" value="http://"  class="text_field" autocomplete="off" /></div>
            <div style="margin-left:165px; margin-bottom:20px;">
            	<input type="text" name="captcha" id="captcha" maxlength="6" size="6" class="cpt_field" autocomplete="off"/> 
            	<img src="include/captcha.php" style="position:absolute;"/>
            </div>
            <div><input type="submit" name="submit" value="Submit" class="submit_btn" onclick="return getPageRankData(siteurl.value, captcha.value);" /></div>
        <div id="show_result"></div>
    </div>
</body>
</html>
