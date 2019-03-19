<?php
/**
 * |-----------------------------------------------------------------------------------
 * @Copyright (c) 2014-2018, http://www.sizhijie.com. All Rights Reserved.
 * @Website: www.sizhijie.com
 * @Version: 思智捷管理系统 1.5.0
 * @Author : como 
 * 版权申明：szjshop网上管理系统不是一个自由软件，是思智捷科技官方推出的商业源码，严禁在未经许可的情况下
 * 拷贝、复制、传播、使用szjshop网店管理系统的任意代码，如有违反，请立即删除，否则您将面临承担相应
 * 法律责任的风险。如果需要取得官方授权，请联系官方http://www.sizhijie.com
 * |-----------------------------------------------------------------------------------
 */

namespace szj\utils;
use think\facade\Cache;
use szj\utils\Idcard;

Class Baidu {
    /**
     * 配置文件
     * @var array
     */
    Private $config = array(
        'set_userimg_name'=>0,
        'set_userimg_path'=>'./'
    );
    /**
     * 接口请求地址
     * @var array
     */
    Private $apiUrl = array(
        'access_token_url'  =>'https://aip.baidubce.com/oauth/2.0/token',
        'id_card_url'       =>'https://aip.baidubce.com/rest/2.0/ocr/v1/idcard',
        'user_head_img_url' =>'https://aip.baidubce.com/rest/2.0/face/v2/detect',
        'common_orc_url'    =>'https://aip.baidubce.com/rest/2.0/ocr/v1/general_basic',
        'cust_img_url'      =>'https://aip.baidubce.com/rest/2.0/solution/v1/iocr/recognise'
    );
    /**
     * 身份证识别状态
     * @var array
     */
    Private $idCardStatus = array(
        'normal'=>'识别正常',
        'reversed_side'=>'身份证正反面颠倒',
        'non_idcard'=>'上传的图片中不包含身份证',
        'blurred'=>'身份证模糊',
        'other_type_card'=>'其他类型证照',
        'over_exposure'=>'身份证关键字段反光或过曝',
        'unknown'=>'未知状态'
    );


    /**
     * 构造方法
     */
    Public function __construct($config = array()){
        if(!isset($config['set_userimg_name'])){
            $this->config['set_userimg_name'] = time().mt_rand(10000,99999).'.jpg';
        }
        $this->config = array_merge($this->config,$config);
    }

    /**
     * 获取凭证
     * @return [type] [description]
     */
    Private function getAccessToken(){
        $access_token = Cache::get($this->config['appid'].'baidu_access_token');
        if(empty($access_token)){
            $appid = $this->config['appid'];
            $secret = $this->config['secret'];
            $postdata = array(
                'grant_type'=>'client_credentials',
                'client_id'=>$appid,
                'client_secret'=>$secret
            );
            $url = $this->buildUrl($this->apiUrl['access_token_url'],$postdata);
            $result = curl_get($url);
            if(empty($result)){
                $result = appResult('接口请求错误,请检查您的网络是否正确');
            } else {
                $arr = json_decode($result,true);
                if(!empty($arr['error'])){
                    $result = appResult($arr['error_description']);
                } else {
                    $result = appResult('SUCCESS',$arr['access_token'],false);
                    Cache::set($this->config['appid'].'baidu_access_token',$result,$arr['expires_in']-600);
                }
            }
        } else {
            $result = $access_token;
        }
        return $result;
    }

    /**
     * 参数合并
     * @param  string $url
     * @param  array $params 参数
     * @return string
     */
    Private function buildUrl($url, $params){
        if(!empty($params)){
            $str = http_build_query($params);
            return $url . (strpos($url, '?') === false ? '?' : '&') . $str;
        }else{
            return $url;
        }
    }

    /**
     * 获取身份证识别后的结果
     * @param  string $imgurl [description]
     * @return [type]         [description]
     */
    Public function IdCard($imgurl = '',$isside = true,$userimg = false){
        if(!file_exists($imgurl)){
            $result = appResult('身份证件文件不存在,请检查');
        } else {
            $access_token = $this->getAccessToken();
            if($access_token['err']){
                $result = $access_token;
            } else {
                $url = $this->buildUrl($this->apiUrl['id_card_url'],array('access_token'=>$access_token['data']));
                $postdata = array(
                    'detect_direction'  =>true,
                    'id_card_side'      =>($isside?'front':'back'),
                    'image'             => base64_encode(file_get_contents($imgurl)),
                    'detect_risk'       =>false
                );
                $resultJson = curl_post($url,$postdata,array('Content-Type'=>'application/x-www-form-urlencoded'));
                if(empty($resultJson)){
                    $result = appResult('接口请求错误,请检查您的网络是否正确');
                } else {
                    $arr = json_decode($resultJson,true);
                    if(empty($arr['error_code'])){
                        if($arr['image_status'] != "normal"){
                            $result = appResult($this->idCardStatus[$arr['image_status']]);
                        } else {
                            $tmpData = [
                                'username'      =>$arr['words_result']['姓名']['words'],
                                'born'      =>$arr['words_result']['出生']['words'],
                                'idcardno'  =>$arr['words_result']['公民身份号码']['words'],
                                'sex'       =>$arr['words_result']['性别']['words'],
                                'nation'    =>$arr['words_result']['民族']['words'],
                                'areaaddr'   =>$arr['words_result']['住址']['words'],  
                            ];
                            if($userimg){
                                $tmpData['userimg'] = $this->getUserImagePath($imgurl);
                            }
                            $result = appResult('身份证识别成功',$tmpData,false);
                        }
                    } else {
                        $result = appResult($arr['error_msg']);
                    }
                }
            }               
        }
        return $result;
    }

    /**
     * 根据身份证信息获取用户的头像图径
     * @param  string $imgurl [description]
     * @return [type]         [description]
     */
    Public function getUserImagePath($imgurl = ''){
        $param = $this->getIdCardUserHeadImg($imgurl);
        if($param['err']){
            $result = $param;
        } else {
            $userimg = $this->addUserHeadImg($imgurl,$param['data']);
            $result = $userimg; 
        }
        return $result;
    }

    /**
     * 生成用户头像
     * @param string $imgurl [description]
     * @param array  $param  [description]
     */
    Public function addUserHeadImg($imgurl = '',$param = array()){
        if(!file_exists($imgurl)){
            $result = appResult('图片不存在');
        } elseif(empty($param['left']) || empty($param['top']) || empty($param['width']) || empty($param['height'])){
            $result = appResult('头像生成参数错误');
        }else {
            $startX = $param['left'] - 30;
            $startY = $param['top'] - 80;
            Directory($this->config['set_userimg_path']);
            $savepath = $this->config['set_userimg_path'].$this->config['set_userimg_name'];
            $options = [
                'url'=>$imgurl,'width'=>$param['width'] + 22 + 50,'height'=>$param['height'] + 62 + 72,
                'start'=>$startX,'end'=>$startY,'save'=>$savepath,'type'=>0
            ];
            $result = appImageHanadle($options,'crop');
        }
        return $result;
    }


    /**
     * 获取照片中的用户头像
     * @param  string $imgurl [description]
     * @return [type]         [description]
     */
    Public function getIdCardUserHeadImg($imgurl = ''){
        if(!file_exists($imgurl)){
            $result = appResult('图片不存在,请检查');
        } else {
            $access_token = $this->getAccessToken();
            if($access_token['err']){
                $result = $access_token;
            } else {
                $url = $this->buildUrl($this->apiUrl['user_head_img_url'],array('access_token'=>$access_token['data']));
                $postdata = array(
                    'image'=>base64_encode(file_get_contents($imgurl)),
                    'face_fields'=>'age,beauty,qualities'
                );
                $resultJSON = curl_post($url,$postdata,array('Content-Type'=>'application/x-www-form-urlencoded'));
                if(empty($resultJSON)){
                    $result = appResult('接口请求错误,请检查您的网络是否正确');
                } else {
                    $arr = json_decode($resultJSON,true);
                    if(empty($arr['result']) || $arr['result'][0]['qualities']['type']['human'] < 0.6){
                        $result = appResult('未能检测到人脸样子');
                    } else {
                        $result = appResult('SUCCESS',$arr['result'][0]['location'],false);
                    }
                }
            }
        }
        return $result;
    }
    /**
     * [CommonOrcImg 通用文字识别器]
     * @Author    como
     * @DateTime  2019-01-04
     * @copyright 思智捷管理系统
     * @version   [1.5.0]
     */
    Public function CommonOrc($imgurl = ''){
        if(!file_exists($imgurl)){
            $result = appResult('身份证件文件不存在,请检查');
        } else {
            $access_token = $this->getAccessToken();
            $url = $this->buildUrl($this->apiUrl['common_orc_url'],array('access_token'=>$access_token['data']));
            $postdata = array(
                'probability'       =>true,
                'image'             => base64_encode(file_get_contents($imgurl)),
            );
            $resultJson = curl_post($url,$postdata,array('Content-Type'=>'application/x-www-form-urlencoded'));
            return appResult('图片识别成功',json_decode($resultJson,true),false);
        }
    }
    /**
     * [HouseReg 户口本个人页识别器]
     * @Author    como
     * @DateTime  2019-01-10
     * @copyright 思智捷管理系统
     * @version   [1.5.0]
     * @param     string     $imgurl [description]
     */
    Public function HouseReg($imgurl = '',$tid = '232a744776294938469b3c442dcecac0',$cid = 1){
        if(!file_exists($imgurl)){
            $result = appResult('户口本文件图片不存在');
        } else {
            $access_token = $this->getAccessToken();
            if($access_token['err']){
                $result = $access_token;
            } else {
                $url = $this->buildUrl($this->apiUrl['cust_img_url'],['access_token'=>$access_token['data']]);
                $postdata = [
                    'image'=>base64_encode(file_get_contents($imgurl)),
                    'templateSign'=>$tid,
                    'classifierId'=>$cid
                ];
                $resultJson = curl_post($url,$postdata,['Content-Type'=>'application/x-www-form-urlencoded']);
                $data = json_decode($resultJson,true);
                if(empty($data['error_code'])){
                    $temp = $this->customOrcHanadle($data);
                    $info = $this->InfoHanadle($temp);
                    if(empty($info)){
                        $result = appResult('系统出错了,请联系管理员');
                    } else {
                        $result = appResult('户口本个人页识别成功',$info,false);
                    }
                } else {
                    $result = appResult($data['error_msg']);
                }
            }
        }
        return $result;
    }
    /**
     * [HouseIndex 户口本首页信息识别]
     * @Author    como
     * @DateTime  2019-01-24
     * @copyright 思智捷管理系统
     * @version   [1.5.0]
     * @param     string     $imgurl [description]
     * @param     string     $tid    [description]
     * @param     integer    $cid    [description]
     */
    Public function HouseIndex($imgurl = '',$tid = '1bc64f846180ac6d768a8a6dfca1151c',$cid = 0){
        if(!file_exists($imgurl)){
            $result = appResult('户口本首页图片不存在');
        } else {
            $access_token = $this->getAccessToken();
            if($access_token['err']){
                $result = $access_token;
            } else {
                $url = $this->buildUrl($this->apiUrl['cust_img_url'],['access_token'=>$access_token['data']]);
                $postdata = [
                    'image'=>base64_encode(file_get_contents($imgurl)),
                    'templateSign'=>$tid,
                    'classifierId'=>$cid
                ];
                $resultJson = curl_post($url,$postdata,['Content-Type'=>'application/x-www-form-urlencoded']);
                $data = json_decode($resultJson,true);
                if(empty($data['error_code'])){
                    $info = $this->customOrcHanadle($data);
                    if(empty($info)){
                        $result = appResult('未知错误,请联系管理员');
                    } else {
                        $result = appResult('户口本首页识别成功',$info,false);
                    }
                } else {
                    $result = appResult($data['error_msg']);
                }
            }
        }
        return $result;
    }

    /**
     * [InfoHanadle 在返回前处理内容]
     * @Author    como
     * @DateTime  2019-01-10
     * @copyright 思智捷管理系统
     * @version   [1.5.0]
     * @param     array      $info [description]
     */
    Public function InfoHanadle($info = []){
        if(empty($info['idcardno']['value'])){
            if(!empty($info['born']['value'])){
                $info['born']['value'] = $this->HouseRegBornHanadle($info['born']['value']);
            }
            return $info;
        }
        $info['idcardno']['value'] = Idcard::checkIdCard($info['idcardno']['value']);
        if(false === $info['idcardno']['value']){
            if(!empty($info['born']['value'])){
                $info['born']['value'] = $this->HouseRegBornHanadle($info['born']['value']);
            }
            return $info;
        }
        $born = Idcard::getBorn($info['idcardno']['value']);
        if(empty($info['born']['value'])){
            $info['born']['value'] = $born;
        } else {
            $info['born']['value'] = $this->HouseRegBornHanadle($info['born']['value']);
        }
        if($info['born']['value'] != $born){
            $info['born']['value'] = $born;
        }
        $sex = Idcard::getSex($info['idcardno']['value']);
        if(empty($info['sex']['value'])){
            $info['sex']['value'] = $sex;
        }
        if($info['sex']['value'] != $sex){
            $info['sex']['value'] = $sex;
        }
        return $info;
    }

    /**
     * [HouseRegBornHanadle 处理年月日]
     * @Author    como
     * @DateTime  2019-01-10
     * @copyright 思智捷管理系统
     * @version   [1.5.0]
     * @param     string     $data [description]
     */
    Private function HouseRegBornHanadle($born = ''){
        if(empty($born)){
            return $born;
        }
        return str_replace(['年','月','日'],['-','-',''],$born);
    }
    /**
     * [gdResideCard 广东省居民居住证识别器]
     * @Author    como
     * @DateTime  2019-03-19
     * @copyright 思智捷管理系统
     * @version   [1.5.0]
     * @param     string     $imgurl [description]
     * @param     string     $tid    [description]
     * @param     integer    $cid    [description]
     */
    Public function gdResideCard($imgurl = '',$tid = 'e678de49ef660977cd536cfd4522cc43',$cid = 0){
        if(!file_exists($imgurl)){
            $result = appResult('居住证图片不存在');
        } else {
            $access_token = $this->getAccessToken();
            if($access_token['err']){
                $result = $access_token;
            } else {
                $url = $this->buildUrl($this->apiUrl['cust_img_url'],['access_token'=>$access_token['data']]);
                $postdata = [
                    'image'=>base64_encode(file_get_contents($imgurl)),
                    'templateSign'=>$tid,
                    'classifierId'=>$cid
                ];
                $resultJson = curl_post($url,$postdata,['Content-Type'=>'application/x-www-form-urlencoded']);
                $data = json_decode($resultJson,true);
                if(empty($data['error_code'])){
                    $info = $this->customOrcHanadle($data);
                    if(empty($info)){
                        $result = appResult('未知错误,请联系管理员');
                    } else {
                        $result = appResult('居住证识别成功',$info,false);
                    }
                } else {
                    $result = appResult($data['error_msg']);
                }
            }
        }
        return $result;
    }
    /**
     * [customOrcHanadle 自定义orc返回处理]
     * @Author    como
     * @DateTime  2019-03-19
     * @copyright 思智捷管理系统
     * @version   [1.5.0]
     * @param     array      $data [description]
     * @return    [type]           [description]
     */
    Private function customOrcHanadle($data = []){
        $info = [];
        if(!empty($data['data']) && !empty($data['data']['ret'])){
            $callback = function($val,$key) use(&$info){
                $info[$val['word_name']] = ['value'=>$val['word']];
                if(isset($val['probability']) && isset($val['probability']['min'])){
                    $info[$val['word_name']]['reliability'] = $val['probability']['min'];
                } else {
                    $info[$val['word_name']]['reliability'] = 0;
                }
            };
            array_walk($data['data']['ret'], $callback);
        }
        return $info;
    }


}