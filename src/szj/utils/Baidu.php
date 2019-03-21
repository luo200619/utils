<?php
 namespace szj\utils; use think\facade\Cache; use szj\utils\Idcard; Class Baidu {  Protected $config = array( 'set_userimg_name'=>0, 'set_userimg_path'=>'./' );  Protected $apiUrl = array( 'access_token_url' =>'https://aip.baidubce.com/oauth/2.0/token', 'id_card_url' =>'https://aip.baidubce.com/rest/2.0/ocr/v1/idcard', 'user_head_img_url' =>'https://aip.baidubce.com/rest/2.0/face/v2/detect', 'common_orc_url' =>'https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic', 'cust_img_url' =>'https://aip.baidubce.com/rest/2.0/solution/v1/iocr/recognise' );  Protected $idCardStatus = array( 'normal'=>'识别正常', 'reversed_side'=>'身份证正反面颠倒', 'non_idcard'=>'上传的图片中不包含身份证', 'blurred'=>'身份证模糊', 'other_type_card'=>'其他类型证照', 'over_exposure'=>'身份证关键字段反光或过曝', 'unknown'=>'未知状态' );  private $accurateBasicUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/accurate_basic';  private $generalUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general';  private $accurateUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/accurate';  private $generalEnhancedUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/general_enhanced';  private $webImageUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/webimage';  private $bankcardUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/bankcard';  private $drivingLicenseUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/driving_license';  private $vehicleLicenseUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/vehicle_license';  private $licensePlateUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/license_plate';  private $businessLicenseUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/business_license';  private $receiptUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/receipt';  private $formUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/form';  private $tableRecognizeUrl = 'https://aip.baidubce.com/rest/2.0/solution/v1/form_ocr/request';  private $tableResultGetUrl = 'https://aip.baidubce.com/rest/2.0/solution/v1/form_ocr/get_request_result';  private $vatInvoiceUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/vat_invoice';  private $qrcodeUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/qrcode';  private $numbersUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/numbers';  private $lotteryUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/lottery';  private $passportUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/passport';  private $businessCardUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/business_card';  private $handwritingUrl = 'https://aip.baidubce.com/rest/2.0/ocr/v1/handwriting';  Public function __construct($config = array()){ if(!isset($config['set_userimg_name'])){ $this->config['set_userimg_name'] = time().mt_rand(10000,99999).'.jpg'; } $this->config = array_merge($this->config,$config); }  Protected function getAccessToken(){ $access_token = Cache::get($this->config['appid'].'baidu_access_token'); if(empty($access_token)){ $appid = $this->config['appid']; $secret = $this->config['secret']; $postdata = array( 'grant_type'=>'client_credentials', 'client_id'=>$appid, 'client_secret'=>$secret ); $url = $this->buildUrl($this->apiUrl['access_token_url'],$postdata); try{ $result = curl_get($url); if(empty($result)){ $result = appResult('接口请求错误,请检查您的网络是否正确'); } else { $arr = json_decode($result,true); if(!empty($arr['error'])){ $result = appResult($arr['error_description']); } else { $result = appResult('SUCCESS',$arr['access_token'],false); Cache::set($this->config['appid'].'baidu_access_token',$result,$arr['expires_in']-600); } } } catch(\Exception $err){ $result = appResult($err->getMessage()); } } else { $result = $access_token; } return $result; }  Protected function buildUrl($url, $params){ if(!empty($params)){ $str = http_build_query($params); return $url . (strpos($url, '?') === false ? '?' : '&') . $str; }else{ return $url; } }  Public function IdCard($imgurl = '',$userimg = false,$detect = true,$isside = true){ $params = $this->checkImgUrlAndAccessToken($imgurl); if($params['err']) return $params; $url = $this->buildUrl($this->apiUrl['id_card_url'],array('access_token'=>$params['data'])); try{ $postdata = array( 'detect_direction' =>'true', 'id_card_side' =>($isside?'front':'back'), 'image' => base64_encode(file_get_contents($imgurl)), 'detect_risk' =>'"'.$detect.'"' ); $resultJson = curl_post($url,$postdata,array('Content-Type'=>'application/x-www-form-urlencoded')); if(empty($resultJson)){ $result = appResult('接口请求错误,请检查您的网络是否正确'); } else { $arr = json_decode($resultJson,true); if(empty($arr['error_code'])){ if($arr['image_status'] != "normal"){ $result = appResult($this->idCardStatus[$arr['image_status']]); } else { $tmpData = [ 'username' =>$arr['words_result']['姓名']['words'], 'born' =>$arr['words_result']['出生']['words'], 'idcardno' =>$arr['words_result']['公民身份号码']['words'], 'sex' =>$arr['words_result']['性别']['words'], 'nation' =>$arr['words_result']['民族']['words'], 'areaaddr' =>$arr['words_result']['住址']['words'], ]; if($userimg){ $tmpData['userimg'] = $this->getUserImagePath($imgurl); } $risk_type = empty($arr['risk_type'])?false:$arr['risk_type']; $edit_tool = empty($arr['edit_tool'])?false:$arr['edit_tool']; $other = ['risk_type'=>$risk_type,'edit_tool'=>$edit_tool,'direction'=>$arr['direction']]; $result = appResult('身份证识别成功',array_merge($tmpData,$other),false); } } else { $result = appResult($arr['error_msg']); } } } catch(\Exception $err){ $result = appResult($err->getMessage()); } return $result; }  Public function getUserImagePath($imgurl = ''){ $param = $this->getIdCardUserHeadImg($imgurl); if($param['err']){ $result = $param; } else { $userimg = $this->addUserHeadImg($imgurl,$param['data']); $result = $userimg; } return $result; }  Public function addUserHeadImg($imgurl = '',$param = array()){ if(!file_exists($imgurl)){ $result = appResult('图片不存在'); } elseif(empty($param['left']) || empty($param['top']) || empty($param['width']) || empty($param['height'])){ $result = appResult('头像生成参数错误'); }else { $startX = $param['left'] - 30; $startY = $param['top'] - 80; Directory($this->config['set_userimg_path']); $savepath = $this->config['set_userimg_path'].$this->config['set_userimg_name']; $options = [ 'url'=>$imgurl,'width'=>$param['width'] + 22 + 50,'height'=>$param['height'] + 62 + 72, 'start'=>$startX,'end'=>$startY,'save'=>$savepath,'type'=>0 ]; $result = appImageHanadle($options,'crop'); } return $result; }  Public function getIdCardUserHeadImg($imgurl = ''){ $params = $this->checkImgUrlAndAccessToken($imgurl); if($params['err']) return $params; $url = $this->buildUrl($this->apiUrl['user_head_img_url'],array('access_token'=>$params['data'])); $postdata = array( 'image'=>base64_encode(file_get_contents($imgurl)), 'face_fields'=>'age,beauty,qualities' ); $resultJSON = curl_post($url,$postdata,array('Content-Type'=>'application/x-www-form-urlencoded')); if(empty($resultJSON)){ $result = appResult('接口请求错误,请检查您的网络是否正确'); } else { $arr = json_decode($resultJSON,true); if(empty($arr['result']) || $arr['result'][0]['qualities']['type']['human'] < 0.6){ $result = appResult('未能检测到人脸样子'); } else { $result = appResult('SUCCESS',$arr['result'][0]['location'],false); } } return $result; }  Public function CommonOrc($imgurl = '',$options = []){ $map = ['probability'=>'true']; $result = $this->commonHanadle($imgurl,array_merge($map,$options),$this->apiUrl['common_orc_url'],'图片识别成功'); return $result; }  Public function HouseReg($imgurl = '',$options = []){ if(!empty($options)){ if(isset($options['tid'])) $options['templateSign'] = $options['tid']; if(isset($options['cid'])) $options['classifierId'] = $options['cid']; } $map = ['templateSign'=>'232a744776294938469b3c442dcecac0','classifierId'=>1]; $tmp = $this->commonHanadle($imgurl,array_merge($map,$options),$this->apiUrl['cust_img_url'],'户口本个人页识别成功'); if(empty($tmp['err'])){ $temp = $this->customOrcHanadle($tmp['data']); $info = $this->InfoHanadle($temp); $result = empty($info)?appResult('未知错误,请联系管理员'):appResult('户口本个人页识别成功',$info,false); } else { $result = $tmp; } return $result; }  Public function HouseIndex($imgurl = '',$options = []){ if(!empty($options)){ if(isset($options['tid'])) $options['templateSign'] = $options['tid']; if(isset($options['cid'])) $options['classifierId'] = $options['cid']; } $map = ['templateSign'=>'1bc64f846180ac6d768a8a6dfca1151c','classifierId'=>0]; $tmp = $this->commonHanadle($imgurl,array_merge($map,$options),$this->apiUrl['cust_img_url'],'户口本首页识别成功'); if(empty($tmp['err'])){ $info = $this->customOrcHanadle($tmp['data']); $result = empty($info)?appResult('未知错误,请联系管理员'):appResult('户口本首页识别成功',$info,false); } else { $result = $tmp; } return $result; }  Public function InfoHanadle($info = []){ if(empty($info['idcardno']['value'])){ if(!empty($info['born']['value'])){ $info['born']['value'] = $this->HouseRegBornHanadle($info['born']['value']); } return $info; } $info['idcardno']['value'] = Idcard::checkIdCard($info['idcardno']['value']); if(false === $info['idcardno']['value']){ if(!empty($info['born']['value'])){ $info['born']['value'] = $this->HouseRegBornHanadle($info['born']['value']); } return $info; } $born = Idcard::getBorn($info['idcardno']['value']); if(empty($info['born']['value'])){ $info['born']['value'] = $born; } else { $info['born']['value'] = $this->HouseRegBornHanadle($info['born']['value']); } if($info['born']['value'] != $born){ $info['born']['value'] = $born; } $sex = Idcard::getSex($info['idcardno']['value']); if(empty($info['sex']['value'])){ $info['sex']['value'] = $sex; } if($info['sex']['value'] != $sex){ $info['sex']['value'] = $sex; } return $info; }  Private function HouseRegBornHanadle($born = ''){ if(empty($born)){ return $born; } return str_replace(['年','月','日'],['-','-',''],$born); }  Public function gdResideCard($imgurl = '',$options = []){ if(!empty($options)){ if(isset($options['tid'])) $options['templateSign'] = $options['tid']; if(isset($options['cid'])) $options['classifierId'] = $options['cid']; } $map = ['templateSign'=>'e678de49ef660977cd536cfd4522cc43','classifierId'=>0]; $tmp = $this->commonHanadle($imgurl,array_merge($map,$options),$this->apiUrl['cust_img_url'],'居住证识别成功'); if(empty($tmp['err'])){ $info = $this->customOrcHanadle($tmp['data']); $result = empty($info)?appResult('未知错误,请联系管理员'):appResult('居住证识别成功',$info,false); } else { $result = $tmp; } return $result; }  Protected function customOrcHanadle($data = []){ $info = []; if(!empty($data['data']) && !empty($data['data']['ret'])){ $callback = function($val,$key) use(&$info){ $info[$val['word_name']] = ['value'=>$val['word']]; if(isset($val['probability']) && isset($val['probability']['min'])){ $info[$val['word_name']]['reliability'] = $val['probability']['min']; } else { $info[$val['word_name']]['reliability'] = 0; } }; array_walk($data['data']['ret'], $callback); } return $info; }  Public function checkImgUrlAndAccessToken($imgurl = ''){ if(!file_exists($imgurl)){ return appResult('图片不存在,请检查'); } $access_token = $this->getAccessToken(); return $access_token; }  Public function basicAccurate($imgurl = '', $options = []){ $map = ['detect_direction'=>'true','probability'=>'true']; $result = $this->commonHanadle($imgurl,array_merge($map,$options),$this->accurateBasicUrl,'文字识别成功'); return $result; }  Public function general($imgurl = '', $options = []){ $map = ['detect_direction'=>'true','probability'=>'true','vertexes_location'=>'false','recognize_granularity'=>'false']; $result = $this->commonHanadle($imgurl,array_merge($map,$options),$this->generalUrl,'文字识别成功'); return $result; }  Public function accurate($imgurl = '', $options=array()){ $map = ['detect_direction'=>'true','probability'=>'true','vertexes_location'=>'false','recognize_granularity'=>'false']; $result = $this->commonHanadle($imgurl,array_merge($map,$options),$this->accurateUrl,'文字识别成功'); return $result; }  Public function handwriting($imgurl = '', $options = []){ $map = ['recognize_granularity'=>'false']; $result = $this->commonHanadle($imgurl,array_merge($map,$options),$this->handwritingUrl,'文字识别成功'); return $result; }  Public function passport($imgurl = '', $options = []){ $result = $this->commonHanadle($imgurl,$options,$this->passportUrl,'护照识别成功'); return $result; }  Public function vatInvoice($imgurl = '', $options = []){ $map = ['accuracy'=>'normal']; $result = $this->commonHanadle($imgurl,array_merge($map,$options),$this->vatInvoiceUrl,'增值税发票识别成功'); return $result; }  Public function businessLicense($imgurl = '', $options = []){ $map = ['detect_direction'=>'true','accuracy'=>'normal']; $result = $this->commonHanadle($imgurl,array_merge($map,$options),$this->businessLicenseUrl,'营业执照识别成功'); return $result; }  Public function licensePlate($imgurl = '', $options = []){ $map = ['multi_detect'=>'false']; $result = $this->commonHanadle($imgurl,array_merge($map,$options),$this->licensePlateUrl,'车牌号码识别成功'); return $result; }  Public function vehicleLicense($imgurl = '', $options = []){ $map = ['detect_direction'=>'true','accuracy'=>'normal','vehicle_license_side'=>'front']; $result = $this->commonHanadle($imgurl,array_merge($map,$options),$this->vehicleLicenseUrl,'行驶证识别成功'); return $result; }  Public function drivingLicense($imgurl = '', $options = []){ $map = ['detect_direction'=>'true','unified_valid_period'=>'false']; $result = $this->commonHanadle($imgurl,array_merge($map,$options),$this->drivingLicenseUrl,'驾驶证识别成功'); return $result; }  Public function bankcard($imgurl = '', $options = []){ $map = []; $result = $this->commonHanadle($imgurl,array_merge($map,$options),$this->bankcardUrl,'银行卡识别成功'); return $result; }  Protected function commonHanadle($imgurl = '',$options = [],$baseurl = '',$info = '识别成功'){ $params = $this->checkImgUrlAndAccessToken($imgurl); if($params['err']) return $params; $url = $this->buildUrl($baseurl,['access_token'=>$params['data']]); try{ $map = ['image'=>base64_encode(file_get_contents($imgurl))]; $postdata = array_merge($map,$options); $resultJson = curl_post($url,$postdata,['Content-Type'=>'application/x-www-form-urlencoded']); $data = json_decode($resultJson,true); if(empty($data['error_code'])){ $result = appResult($info,$data,false); } else { $result = appResult($data['error_msg']); } } catch(\Exception $err){ $result = appResult($err->getMessage()); } return $result; } }