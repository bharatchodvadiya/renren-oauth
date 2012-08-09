<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<body>
<?php
require_once './class/RenrenRestApiService.class.php';
$token_url='http://graph.renren.com/oauth/token';

$access_token='188504|6.4129286d2d60a07c9d6cc36f35916ab7.2592000.1337835600-453822024';

$restApi = new RenrenRestApiService;
$params = array('fields'=>'uid,name,sex,birthday,mainurl,hometown_location,university_history,tinyurl,headurl','access_token'=>$access_token);
$res = $restApi->rr_post_curl('users.getInfo', $params);
$userId = $res[0]["uid"];
$username = $res[0]["name"];
$birthday = $res[0]["birthday"];
$tinyurl = $res[0]["tinyurl"];
$sex = $res[0]["sex"];
$headurl = $res[0]["headurl"];
$mainurl = $res[0]["mainurl"];
echo "userId:".$userId.'<br>';
echo "username:".$username.'<br>';
echo "sex:".$sex.'<br>';
echo "birthday:".$birthday.'<br>';
echo "tinyurl:".$tinyurl.'<br>';
echo "headurl:".$headurl.'<br>';
echo "mainurl:".$mainurl.'<br>';

echo '<hr>like list';

$params = array('urls'=>'["http://www.renren.com/g?ownerid=453820024"]','access_token'=>$access_token);
echo "<pre>";
print_r($params);
echo "</pre>";

$res = $restApi->rr_post_curl('like.getCount', $params);
echo "<pre>";
print_r($res);

$params = array('url'=>'["http://www.renren.com/g?ownerid=453820024"]','access_token'=>$access_token,'formate'=>'JSON');
echo "<pre>";
print_r($params);
echo "</pre>";

$res = $restApi->rr_post_curl('like.like', $params);
echo "<pre>";
print_r($res);
echo '<hr>friend get list';

$params = array('page'=>'1','count'=>'2','access_token'=>$access_token);
$res = $restApi->rr_post_curl('friends.get', $params);
print_r($res);

echo '<hr>friend get fri list';

$params = array('page'=>'1','count'=>'2','access_token'=>$access_token);
$res = $restApi->rr_post_curl('friends.getFriends', $params);
print_r($res);

echo '<hr>friend get fri list';
$params = array('page'=>'1','count'=>'2','access_token'=>$access_token);
$res = $restApi->rr_post_fopen('friends.getFriends', $params);
print_r($res);
?>
</body>
</html>
		