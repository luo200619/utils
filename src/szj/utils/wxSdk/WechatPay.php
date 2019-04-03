<?php
 namespace szj\utils\wxSdk;  Class WechatPay {  Protected static $UnifiedOrderURL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';  Protected static $OrderQueryURL = 'https://api.mch.weixin.qq.com/pay/orderquery';  Protected static $CloseOrderURL = 'https://api.mch.weixin.qq.com/pay/closeorder';  Protected static $RefundOrderURL = 'https://api.mch.weixin.qq.com/secapi/pay/refund';  Protected static $RefundQueryURL = 'https://api.mch.weixin.qq.com/pay/refundquery';  Protected static $DownloadBillURL = 'https://api.mch.weixin.qq.com/pay/downloadbill';  Protected static $MicroPayURL = 'https://api.mch.weixin.qq.com/pay/micropay';  Protected static $ReverseOrderURL = 'https://api.mch.weixin.qq.com/secapi/pay/reverse';  Protected static $SendRedBagsURL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';  Protected static $TransfersURL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';  Protected static $QueryTransInfoURL = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/gettransferinfo';  Protected static $options = [];  Public static function UnifiedOrder($data = [],$defaultFields = []){ if(empty($data)) throw new \Exception("请传入支付参数", 1000003); try{ $options = ['spbill_create_ip','time_start','time_expire','nonce_str','notify_url','trade_type']; $result = self::curlRequset(self::$UnifiedOrderURL,'post',self::getPostBaseData($data,array_merge($options,$defaultFields))); return appResult('SUCCESS',self::xmlToArray($result),false); } catch(\Exception $err){ throw $err; } }  Public static function CloseOrder($data = [],$defaultFields = []){ if(empty($data)) throw new \Exception("请传入支付参数", 1000004); try{ $options = ['nonce_str']; $result = self::curlRequset(self::$CloseOrderURL,'post',self::getPostBaseData($data,array_merge($options,$defaultFields))); return appResult('SUCCESS',self::xmlToArray($result),false); } catch(\Exception $err){ throw $err; } }  Public static function QueryOrder($data = [],$defaultFields = []){ if(empty($data)) throw new \Exception("请传入支付参数", 1000004); try{ $options = ['nonce_str']; $result = self::curlRequset(self::$OrderQueryURL,'post',self::getPostBaseData($data,array_merge($options,$defaultFields))); return appResult('SUCCESS',self::xmlToArray($result),false); } catch(\Exception $err){ throw $err; } }  Public static function BillOrder($data = [],$defaultFields = []){ if(empty($data)) throw new \Exception("请传入支付参数", 1000004); try{ $options = ['bill_date','nonce_str','bill_type']; $result = self::curlRequset(self::$DownloadBillURL,'post',self::getPostBaseData($data,array_merge($options,$defaultFields))); $tmp = self::xmlToArray($result); if($tmp == false) $tmp = array_filter(explode("\n",$result)); return appResult('SUCCESS',$tmp,false); } catch(\Exception $err){ throw $err; } }  Public static function RefundOrder($data = [],$cert = '',$key = '',$defaultFields = []){ if(empty($data)) throw new \Exception("请传入支付参数", 1000004); if(empty($cert)) throw new \Exception('请传入您的商户证书',1000005); if(empty($key)) throw new \Exception('请传入您的商户证书',1000006); try{ $options = ['out_refund_no','nonce_str','notify_url']; $result = self::curlPostSsl(self::$RefundOrderURL,self::getPostBaseData($data,array_merge($options,$defaultFields)),$cert,$key); return appResult('SUCCESS',self::xmlToArray($result),false); } catch(\Exception $err){ throw $err; } }  Public static function RefundQuery($data = [],$defaultFields = []){ if(empty($data)) throw new \Exception("请传入支付参数", 1000004); try{ $options = ['nonce_str']; $result = self::curlRequset(self::$RefundQueryURL,'post',self::getPostBaseData($data,array_merge($options,$defaultFields))); return appResult('SUCCESS',self::xmlToArray($result),false); } catch(\Exception $err){ throw $err; } }  Public static function SendRedBags($data = [],$cert = null,$key = '',$defaultFields = []){ if(empty($data)) throw new \Exception("请传入支付参数", 1000004); if(empty($cert)) throw new \Exception('请传入您的商户证书',1000005); if(empty($key)) throw new \Exception('请传入您的商户证书',1000006); try{ $options = ['wishing','nonce_str','act_name','remark','scene_id']; $result = self::curlPostSsl(self::$SendRedBagsURL,self::getPostBaseData($data,array_merge($options,$defaultFields)),$cert,$key); return appResult('SUCCESS',self::xmlToArray($result),false); } catch(\Exception $err){ throw $err; } }  Public static function PayUserWallet($data = [],$cert = '',$key = '',$defaultFields = []){ if(empty($data)) throw new \Exception("请传入支付参数", 1000004); if(empty($cert)) throw new \Exception('请传入您的商户证书',1000005); if(empty($key)) throw new \Exception('请传入您的商户证书',1000006); try{ $options = ['nonce_str','check_name','spbill_create_ip']; $result = self::curlPostSsl(self::$TransfersURL,self::getPostBaseData($data,array_merge($options,$defaultFields)),$cert,$key); return appResult('SUCCESS',self::xmlToArray($result),false); } catch(\Exception $err){ throw $err; } }  Public static function QueryPayUser($data = [],$cert = '',$key = '',$defaultFields = []){ if(empty($data)) throw new \Exception("请传入支付参数", 1000004); if(empty($cert)) throw new \Exception('请传入您的商户证书',1000005); if(empty($key)) throw new \Exception('请传入您的商户证书',1000006); try{ $options = ['nonce_str']; $result = self::curlPostSsl(self::$QueryTransInfoURL,self::getPostBaseData($data,array_merge($options,$defaultFields)),$cert,$key); return appResult('SUCCESS',self::xmlToArray($result),false); } catch(\Exception $err){ throw $err; } }  Public static function WxPayCallBack($callClass = null,$callback = null,$options = []){ try{ $model = new $callClass; $bool = call_user_func(array(&$model,$callback),$options); return $bool === true?xml(self::arrayToXml(['return_code'=>'SUCCESS','return_msg'=>'OK'])):'error'; } catch(\Exception $err){ return $err->getMessage(); } }  Public static function getPostBaseData($data = [],$options = []){ $arr = []; $callback = function($val,$index) use(&$arr){ switch ($val) { case 'spbill_create_ip': $arr['spbill_create_ip'] = self::getClientIp(); break; case 'device_info': $arr['device_info'] = 'WEB'; break; case 'time_start': $arr['time_start'] = date('YmdHis',szjTime()); break; case 'time_expire': $arr['time_expire'] = date('YmdHis',szjTime() + 2 * 60 * 60); break; case 'nonce_str': $arr['nonce_str'] = self::getRandStr(); break; case 'notify_url': $arr['notify_url'] = (self::IsHttps()?'https':'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].'/index/Wechat/wxcallback'; break; case 'bill_date': $arr['bill_date'] = (string)date('Ymd',szjTime()); break; case 'bill_type': $arr['bill_type'] = 'ALL'; break; case 'trade_type': $arr['trade_type'] = 'JSAPI'; break; case 'out_refund_no': $arr['out_refund_no'] = date('YmdHis').mt_rand(10000,99999); break; case 'wishing': $arr['wishing'] = '感谢您参加思智捷信息科技红包活动，祝您生活愉快！'; break; case 'act_name': $arr['act_name'] = '思智捷科技红包活动'; break; case 'remark': $arr['remark'] = '由思智捷信息科技有限公司提供技术支持'; break; case 'scene_id': $arr['scene_id'] = 'PRODUCT_1'; break; case 'check_name': $arr['check_name'] = 'NO_CHECK'; break; default: break; } }; array_walk($options,$callback); $xml = self::arrayToXml(array_merge($arr,$data)); return $xml; }  Public static function IsHttps() { if ( !empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') { return true; } elseif ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) { return true; } elseif ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') { return true; } return false; }  Public static function curlRequset($url, $method, $postfields = null, $headers = array()){ $method = strtoupper($method); $ci = curl_init();  curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0"); curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60);  curl_setopt($ci, CURLOPT_TIMEOUT, 7);  curl_setopt($ci, CURLOPT_RETURNTRANSFER, true); switch ($method) { case "POST": curl_setopt($ci, CURLOPT_POST, true); if (!empty($postfields)) { $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields; curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr); } break; default: curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method);  break; } $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE; curl_setopt($ci, CURLOPT_URL, $url); if($ssl){ curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);  curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE);  }  curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1); curl_setopt($ci, CURLOPT_MAXREDIRS, 2); curl_setopt($ci, CURLOPT_HTTPHEADER, $headers); curl_setopt($ci, CURLINFO_HEADER_OUT, true);  $response = curl_exec($ci); $requestinfo=curl_getinfo($ci); $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE); curl_close($ci); return $response; }  Public static function curlPostSsl($url, $vars, $cert = '',$key = '',$second = 60,$aHeader=array()){ $ch = curl_init(); curl_setopt($ch,CURLOPT_TIMEOUT,$second); curl_setopt($ch,CURLOPT_URL,$url); curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false); curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false); curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM'); curl_setopt($ch,CURLOPT_SSLCERT,$cert); curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM'); curl_setopt($ch,CURLOPT_SSLKEY,$key); curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: text/xml')); curl_setopt($ch,CURLOPT_POST, 1); curl_setopt($ch,CURLOPT_POSTFIELDS,$vars); $data = curl_exec($ch); if($data){ curl_close($ch); return $data; }else { $error = curl_errno($ch); curl_close($ch); return false; } }  Public static function getRandStr($length = 32){ $chars = "abcdefghijklmnopqrstuvwxyz0123456789"; $str = ""; for ( $i = 0; $i < $length; $i++ ) { $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1); } return $str; }  Public static function getClientIp(){ return \think\facade\Request::ip(); }  Public static function arrayToXml($data = []){ if(empty($data) || !is_array($data)) throw new \Exception('参数为空或参数不是数组', 1000001); $hanadelData = []; $callback = function($val,$key) use(&$hanadelData){ if(is_array($val)) $hanadelData[trim($key)] = json_encode($val,JSON_UNESCAPED_UNICODE); else $hanadelData[trim($key)] = $val; }; array_walk($data, $callback); ksort($hanadelData); if(!isset($hanadelData['sign'])) { $hanadelData['sign'] = self::makeSign($hanadelData); } if(isset($hanadelData['key'])) unset($hanadelData['key']);  return \szj\utils\wxSdk\WechatSignal::arrayToXml($hanadelData); }  Public static function xmlToArray($xml = null){ if(empty($xml)) throw new \Exception('xml数据异常！', 1000002); libxml_disable_entity_loader(true); return json_decode(json_encode(@simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true); }  Private static function makeSign($data = []){ ksort($data); $string = ''; foreach($data as $k=>$v){ if($k != 'key') $string .= $k.'='.$data[$k].'&'; } if(isset($data['key'])) $string = $string.'key='.$data['key']; return strtoupper(md5($string)); } }