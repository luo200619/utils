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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/**
 * 项目邮件发送类
 */
Class Mailer{
	/**
	 * [$MailObj 邮件发送类]
	 * @var null
	 */
	Public $mail = null;
	/**
	 * [$config 邮箱发送配置]
	 * @var array
	 */
	Private $config = [
		'SMTPDebug'=>0,
		'Host'=>'smtp.qq.com',
		'SMTPSecure'=>'ssl',
		'Port'=>465,
		'CharSet'=>'UTF-8',
		'FromName'=>'思智捷信息科技有限公司',
		'Username'=>'',
		'Password'=>'',
		'From'=>'',
		'addAddress'=>'',
		'addAttachment'=>'',
		'Subject'=>'',
		'Body'=>''
	];
	/**
	 * [__construct 构造函数]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 */
	Public function __construct($config = []){
		$this->config = array_merge($this->config,$config);
		$this->mail = new PHPMailer(true);
	}
	/**
	 * [Send 发送邮件]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 */
	Public function send($title = '',$content = '',$user = '',$pwd = '',$tomail = '',$port = 465){
		if(!empty($title)){
			$this->config['Subject'] = $title;
		}
		if(!empty($content)){
			$this->config['Body'] = $content;
		}
		if(!empty($user)){
			$this->config['Username'] = $user;
			$this->config['From'] = $user;
		}
		if(!empty($pwd)){
			$this->config['Password'] = $pwd;
		}
		if(!empty($port)){
			$this->config['Port'] = $port;
		}
		if(!empty($tomail)){
			$this->config['addAddress'] = $tomail;
		}
		$result = $this->sendmail();
		return $result;
	}
	/**
	 * [setConfig 发送配置]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     array      $arr [description]
	 */
	Public function setConfig($arr = []){
		$this->config = array_merge($arr);
		return $this;
	}
	/**
	 * [sendMail 发送邮件]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @return    [type]     [description]
	 */
	Public function sendmail(){
		try{
			$this->mail->isSMTP(); 						//使用smtp协议进行发送
			$this->mail->SMTPAuth 	= true;				//授权模式
			$this->mail->isHTML(true);					//html格式发送
			foreach($this->config as $key=>$val){
				if($key == 'addAddress'){
					$this->ParseToAddress();
				} elseif($key == 'addAttachment'){
					$this->ParseToAttachment();
				} else {
					$this->mail->$key = $val;
				}
			}
			//print_r($this->mail);die;
			$status = $this->mail->send();
			if(empty($status)){
				$result = appResult('邮件发送失败');
			} else {
				$result = appResult('邮件发送成功',null,false);
			}
		} catch(Exception $err){
			$result = appResult($err->errorMessage());
		}
		return $result;
	}
	/**
	 * [setAddress 设置发送地址]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $mixed [array/string]
	 */
	Public function setAddress($mixed = ''){
		$this->config['addAddress'] = $mixed;
		return $this;
	}
	/**
	 * [ParseToAddress 分析发送地址]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 */
	Private function ParseToAddress(){
		$arr = [];
		if(!empty($this->config['addAddress'])){
			if(is_string($this->config['addAddress'])){
				$arr = explode(',',$this->config['addAddress']);
			} elseif(is_array($this->config['addAddress'])){
				$arr = $this->config['addAddress'];
			}
			foreach($arr as $key=>$val){
				$this->mail->addAddress($val);
			}
		}
		return $this;
	}
	/**
	 * [ParseToAttachment 设置发送附件]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 */
	Private function ParseToAttachment(){
		$arr = [];
		if(!empty($this->config['addAttachment'])){
			if(is_string($this->config['addAttachment'])){
				$arr = explode(',',$this->config['addAttachment']);
			} elseif(is_array($this->config['addAttachment'])){
				$arr = $this->config['addAttachment'];
			}
			foreach($arr as $key=>$val){
				$this->mail->addAttachment($val,$key);
			}
		}
		return $this;
	}
	/**
	 * [content 需要发送的html内容]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $html [description]
	 * @return    [type]           [description]
	 */
	Public function setContent($html = ''){
		$this->config['Body'] = $html;
		return $this;
	}
	/**
	 * [setAccount 设置发送账号和密码]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $username [description]
	 * @param     string     $password [description]
	 */
	Public function setAccount($username = '',$password = ''){
		$this->config['Username'] = $username;
		$this->config['Password'] = $password;
		$this->config['From'] = $username;
		return $this;
	}
	/**
	 * [setName 设置发件人昵称qq邮箱无效 其它邮箱有效]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $name [description]
	 */
	Public function setName($name = 'como'){
		$this->config['FromName'] = $name;
		return $this;
	}
	/**
	 * [setCharset 设置邮件编码]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 */
	Public function setCharset($charset = 'UTF-8'){
		$this->config['CharSet'] = $charset;
		return $this;
	}
	/**
	 * [setPort 设置发送端口]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     integer    $port [description]
	 */
	Public function setPort($port = 465){
		$this->mail->Port = $port;
		$this->config['Port'] = $port;
		return $this;
	}
	/**
	 * [setSecure 设置加密方式]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $type [description]
	 */
	Public function setSecure($type = 'ssl'){
		$this->config['SMTPSecure'] = $type;
		return $this;
	}
	/**
	 * [setHost 邮箱服务器域名]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $host [description]
	 */
	Public function setHost($host = 'smtp.qq.com'){
		$this->config['Host'] = $host;
		return $this;
	}
	/**
	 * [setTitle 设置发送邮件的标题]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $title [description]
	 */
	Public function setTitle($title = '邮件主题'){
		$this->config['Subject'] = $title;
		return $this;
	}
	/**
	 * [debug 设置调式模式]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     boolean    $type [description]
	 * @return    [type]           [description]
	 */
	Public function setDebug($type = false){
		$this->config['SMTPDebug'] = empty($type)?0:1;
		return $this;
	}
	/**
	 * [setAttachment 设置发送附件]
	 * @Author    como
	 * @DateTime  2019-01-07
	 * @copyright 思智捷管理系统
	 * @version   [1.5.0]
	 * @param     string     $mixed [description]
	 */
	Public function setAttach($mixed = ''){
		$this->config['addAttachment'] = $mixed;
		return $this;
	}

}