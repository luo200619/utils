<?php
 namespace szj\utils\wxSdk;  Class WechatMsg {  Protected static $CustomMessageURL = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=%s';  Protected static $addQrcodeURL = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s';  Public static function sendMsgCustom($openid ='' ,$type = '',$content = null){ try{ $msgHanadle = self::msgDataHanadle($openid,$type,$content); if($msgHanadle['err']) return $msgHanadle; $result = curl_post(sprintf(self::$CustomMessageURL,getAccessToken()),$msgHanadle['data']); $data = json_decode($result,true); if(!$data) return appResult('未知错误,请检查...'); if(empty($data['errcode'])) return appResult('SUCCESS',$data['errmsg'],false); else return appResult($data['errmsg']); } catch(\Exception $err){ return appResult($err->getMessage()); } }  Public static function QrcodeTemp($content,$defaultTime = 1*24*60*60){ $data = ['expire_seconds'=>$defaultTime]; $request = self::checkVarType($content); if($request['err']) return $request; $result = curl_post(sprintf(self::$addQrcodeURL,getAccessToken()),json_encode(array_merge($data,$request['data']),JSON_UNESCAPED_UNICODE)); return appResult('SUCCESS',json_decode($result,true),false); }  Public static function QrcodeLong($content){ $request = self::checkVarType($content); if($request['err']) return $request; $result = curl_post(sprintf(self::$addQrcodeURL,getAccessToken()),json_encode($request['data'],JSON_UNESCAPED_UNICODE)); return appResult('SUCCESS',json_decode($result,true),false); }  Protected static function checkVarType(&$content,$scene = 'temp'){ if(empty($content)) return appResult('参数不能为空,请检查'); $type = false; if(is_int($content)) $type = 'int'; if(is_string($content)) $type = 'string'; if(is_array($content)) $type = 'array'; $data = []; switch ($type) { case 'int': $data['action_name'] = $scene == 'temp'?'QR_SCENE':'QR_LIMIT_SCENE'; $data['action_info'] = ['scene'=>['scene_id'=>$content]]; break; case 'string': $data['action_name'] = $scene == 'temp'?'QR_STR_SCENE':'QR_LIMIT_STR_SCENE'; $data['action_info'] = ['scene'=>['scene_str'=>$content]]; break; case 'array': $data['action_name'] = $scene == 'temp'?'QR_STR_SCENE':'QR_LIMIT_STR_SCENE'; $data['action_info'] = ['scene'=>['scene_str'=>json_encode($content,JSON_UNESCAPED_UNICODE)]]; } if(empty($data)){ return appResult('参数类型错误，只能为整型、字符串、数组类型,请检查'); } return appResult('SUCCESS',$data,false); }  Protected static function msgDataHanadle($openid = '',$type = '',$content = ''){ if(empty($openid)) return appResult('发送人的openid不能为空'); if(empty($type)) return appResult('发送类型不能为空'); if(empty($content)) return appResult('发送的数据不能为空'); $data = ['touser'=>$openid,'msgtype'=>$type]; $tmp = []; switch($type){ case 'image': case 'voice': case 'mpnews': $tmp = ['media_id'=>$content]; break; case 'text': $tmp = ['content'=>$content]; break; case 'video': case 'music': case 'news': case 'msgmenu': $tmp = is_array($content)?$content:[]; break; } $data[$type] = $tmp; return appResult('SUCCESS',json_encode($data,JSON_UNESCAPED_UNICODE),false); } }