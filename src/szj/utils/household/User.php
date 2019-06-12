<?php
 namespace szj\utils\household;  Class User {  Public static $nations = [ '汉族','壮族','满族','回族','苗族','维吾尔族','土家族','彝族','蒙古族','藏族','布依族','侗族','瑶族','朝鲜族','白族','哈尼族','哈萨克族','黎族','傣族','畲族','僳僳族','仡佬族','东乡族','拉祜族','水族','佤族','纳西族','羌族','土族','仫佬族','锡伯族','柯尔克孜族','达翰尔族','景颇族','毛南族','撒拉族','布朗族','塔吉克族','阿昌族','普米族','鄂温克族','怒族','京族','基诺族','德昂族','保安族','俄罗斯族','裕固族','乌孜别克族','门巴族','鄂伦春族','独龙族','塔塔尔族','赫哲族','高山族','珞巴族'];  Public static function HouseRegHanadler($data = []){ try{ $result = []; $idcardno = self::idCardNoHandler($data['idcard']);  $born = self::bornHandler($data['base']);  $relation = self::relationHandler($data['base']);  $gender = self::genderHandler($data['base']);  $nation = self::nationHandler($data['base']);  $place = self::placeHandler($data['place']);  $username = self::usernameHandler($data['name']);  $result = array_merge($idcardno,$born,$relation,$gender,$nation,$place,$username); self::HouseRegAfterHandler($result); return appResult('户口本识别成功',$result,false); } catch(\Exception $err){ return appResult($err->getMessage()); } }  Private static function HouseRegAfterHandler(&$data = []){ if(strlen($data['idcardno']) == 18 || strlen($data['idcardno']) == 17){ $tmpidcard = \szj\utils\Idcard::checkIdCard($data['idcardno']); if(!empty($tmpidcard)){ if(strlen($data['idcardno']) == 17) $data['idcardno'] = $tmpidcard; $isIdCard = self::is_idcard($data['idcardno']); if($isIdCard){ $tmp = \szj\utils\Idcard::getBorn($data['idcardno']); if(!empty($tmp) && !empty($data['born'])){ $borntmp = str_replace(['年','月','日'],['-','-',''],$data['born']); if($tmp != $borntmp){ $data['born'] = $tmp; } else { $data['born'] = $borntmp; } } else { $data['born'] = $tmp; } $sextmp = \szj\utils\Idcard::getSex($data['idcardno']); if($sextmp != $data['gender']) $data['gender'] = $sextmp; $reg = '/(.*?(省|市|县|区|乡|镇|村))/'; $arr = []; preg_match($reg,$data['place'],$arr); if(!empty($arr) && count($arr) >= 2){ $placetmp = self::getProvinceName($data['idcardno']); if(!empty($placetmp) && $placetmp != $arr[0]){ $data['place'] = $placetmp.str_replace($arr[0],'',$data['place']); } } $data['reliability'] = 0.85; } } } $data['born'] = str_replace(['年','月','日'],['-','-',''],$data['born']); $data['username'] = preg_replace("/[a-zA-Z0-9]/",'',$data['username']); if(!empty($data['username'])){ $index = mb_substr($data['username'],0,1); if($index == '一') $data['username'] = mb_substr($data['username'],1); } }  Private static function idCardNoHandler($arr = []){ $result = ['idcardno'=>'']; if(!empty($arr) && !empty($arr['value'])){ $content = str_replace(['市(9','9其'],'',$arr['value']); $reg = '/[0-9a-z]/'; $arr = []; preg_match_all($reg,$content,$arr); if(!empty($arr) && !empty($arr[0])){ $result['idcardno'] = implode('', $arr[0]); } } return $result; }  Private static function nationHandler($arr = []){ $result = ['nation'=>'']; if(!empty($arr) && !empty($arr['value'])){ $content = $arr['value']; $reg = '/民族(.*)出生/'; $arr = []; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ $result['nation'] = $arr[1]; } if(empty($result['nation'])){ $reg = '/民(.*)出生/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ $result['nation'] = $arr[1]; } } if(empty($result['nation'])){ $reg = '/族(.*)出生/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ $result['nation'] = $arr[1]; } } if(empty($result['nation'])){ $reg = '/族(.*)出/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ $result['nation'] = $arr[1]; } } if(empty($result['nation'])){ $reg = '/族(.*)生/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ $result['nation'] = $arr[1]; } } } if(empty($result['nation'])) { $result['nation'] = self::$nations[0]; } else { if(!in_array($result['nation'],self::$nations)){ $result['nation'] = self::$nations[0]; } } return $result; }  Private static function genderHandler($arr = []){ $result = ['gender'=>'']; if(!empty($arr) && !empty($arr['value'])){ $content = $arr['value']; $reg = '/性别(.*)民/'; $arr = []; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ $result['gender'] = str_replace(['一'],'',$arr[1]); } } return $result; }  Private static function relationHandler($arr = []){ $result = ['relation'=>'']; if(!empty($arr) && !empty($arr['value'])){ $content = $arr['value']; $reg = '/关系(.*)性别/'; $arr = []; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ $result['relation'] = $arr[1]; } if(empty($result['relation'])){ $reg = '/关系(.*)性/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ $result['relation'] = $arr[1]; } } if(empty($result['relation'])){ $reg = '/或与(.*)户主/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ $result['relation'] = $arr[1]; } } if(!empty($result['relation'])){ $result['relation'] = str_replace(['与'],'',$result['relation']); } } $tmparr = ['户主','妻子','儿子','长子','次子','孙子','三子','长女','孙女','次女','三女','儿媳','长媳','次媳','长婿','次婿','长孙','次孙','子','女','三孙','四孙']; if(!empty($result['relation']) && !in_array($result['relation'],$tmparr)){ $result['relation'] = ''; } return $result; }  Private static function bornHandler($arr = []){ $result = ['born'=>'']; if(!empty($arr) && !empty($arr['value'])){ $content = $arr['value']; $reg = '/日期(.*)/'; $arr = []; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ $result['born'] = $arr[1]; } if(empty($result['born'])){ $reg = '/[0-9]{4}年[0-9]{1,2}月[0-9]{1,2}日/'; preg_match($reg,$content,$arr); if(!empty($arr) && !empty($arr[0])){ $result['born'] = $arr[0]; } } if(!empty($result['born'])){ $result['born'] = str_replace(['宗教信仰'],'',$result['born']); } } return $result; }  Private static function placeHandler($arr = []){ $result = ['place'=>'']; if(!empty($arr) && !empty($arr['value'])){ $content = $arr['value']; $reg = '/籍贯(.*)/'; $arr = []; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ $result['place'] = $arr[1]; } if(empty($result['place'])){ $reg = '/((.*)市|(.*)县|(.*)区|(.*)镇|(.*)乡|(.*)村)/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) >=2 ){ $result['place'] = $arr[1]; } } if(!empty($result['place'])){ $result['place'] = str_replace(['贯','籍','本市','其他住址','(县)','出生地','一'],'',$result['place']); } } return $result; }  Private static function usernameHandler($arr = []){ $result = ['username'=>'']; if(!empty($arr) && !empty($arr['value'])){ $content = str_replace('一曾','',$arr['value']); $reg = '/姓名(.*)曾用名/'; $arr = []; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2 && mb_strlen($arr[1]) > 1){ if(mb_strlen($arr[1]) > 1) $result['username'] = $arr[1]; } if(empty($result['username'])){ $reg = '/名(.*)曾用名/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ if(mb_strlen($arr[1]) > 1) $result['username'] = $arr[1]; } } if(empty($result['username'])){ $reg = '/姓名(.*)用名/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ if(mb_strlen($arr[1]) > 1) $result['username'] = $arr[1]; } } if(empty($result['username'])){ $reg = '/曾用名(.*)出生/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ if(mb_strlen($arr[1]) > 1) $result['username'] = $arr[1]; } } if(empty($result['username'])){ $reg = '/名(.*)用名/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ if(mb_strlen($arr[1]) > 1) $result['username'] = $arr[1]; } } if(empty($result['username'])){ $reg = '/名(.*)曾/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ if(mb_strlen($arr[1]) > 1) $result['username'] = $arr[1]; } } if(empty($result['username'])){ $reg = '/用名(.*)出生/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ if(mb_strlen($arr[1]) > 1) $result['username'] = $arr[1]; } } if(empty($result['username'])){ $reg = '/(.*)曾用名/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ if(mb_strlen($arr[1]) > 1) $result['username'] = $arr[1]; } } if(empty($result['username'])){ $reg = '/(.*)用名/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ if(mb_strlen($arr[1]) > 1) $result['username'] = $arr[1]; } } if(empty($result['username'])){ $reg = '/(.*)曾/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ if(mb_strlen($arr[1]) > 1) $result['username'] = $arr[1]; } } if(empty($result['username'])){ $reg = '/姓(.*)用/'; preg_match($reg,$content,$arr); if(!empty($arr) && count($arr) == 2){ if(mb_strlen($arr[1]) > 1) $result['username'] = $arr[1]; } } } return $result; }  Private static function getProvinceName($idcardno = ''){ $arr = [11=>"北京市",12=>"天津市",13=>"河北省",14=>"山西省",15=>"内蒙古自治区",21=>"辽宁省",22=>"吉林省",23=>"黑龙江省",31=>"上海市",32=>"江苏省",33=>"浙江省",34=>"安徽省",35=>"福建省",36=>"江西省",37=>"山东省",41=>"河南省",42=>"湖北省",43=>"湖南省",44=>"广东省",45=>"广西壮族自治区",46=>"海南省",50=>"重庆市",51=>"四川省",52=>"贵州省",53=>"云南省",54=>"西藏",61=>"陕西省",62=>"甘肃省",63=>"青海省",64=>"宁夏回族自治区",65=>"新疆维吾尔自治区",71=>"台湾省",81=>"香港特别行政区",82=>"澳门特别行政区",91=>"国外" ]; $index = substr($idcardno,0,2); if(empty($arr[$index])){ return false; } return $arr[$index]; }  Public static function is_idcard( $id ){ $id = strtoupper($id); $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/"; $arr_split = array(); if(!preg_match($regx, $id)){ return FALSE; } if(15==strlen($id)) { $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/"; @preg_match($regx, $id, $arr_split); $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; if(!strtotime($dtm_birth)){ return FALSE; } else { return TRUE; } } else { $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/"; @preg_match($regx, $id, $arr_split); $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4]; if(!strtotime($dtm_birth)) { return FALSE; } else { $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); $sign = 0; for ( $i = 0; $i < 17; $i++ ){ $b = (int) $id{$i}; $w = $arr_int[$i]; $sign += $b * $w; } $n = $sign % 11; $val_num = $arr_ch[$n]; if ($val_num != substr($id,17, 1)){ return FALSE; } else { return TRUE; } } } } }