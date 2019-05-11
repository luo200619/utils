<?php
 namespace szj\utils; use szj\utils\SzjDb; use think\facade\Config;  Class DbBack {  Public static $actions = [];  protected static $bakFileName = '../sqlbak/sqlbak';  protected static $szjdb = null;  protected static $isCallback = false;  protected static $bakPath = null;  Public static function run(){ self::_begin_run(); if(empty(self::$actions)) return false; try{ $callback = function($val,$key){ call_user_func([&$val['obj'],trim($val['action'])],$val['options']); }; array_walk(self::$actions, $callback); self::$actions = []; if(self::$isCallback) self::_after_run(); } catch(\Exception $err){ error_log($err->getMessage().PHP_EOL,3,'error.log'); } }  Public static function setActions($object = null,$callback = null,$options = null){ if(empty($object)) $object = new self; if(empty($callback)) $callback = 'sqlListen'; if(!empty($object) && !empty($callback) && is_object($object) && method_exists($object,$callback)){ self::$actions[] = ['obj'=>($object),'action'=>$callback,'options'=>($options)]; } }  Public static function setBakFileName($fileName = ''){ if(!empty($fileName)) self::$bakFileName = $fileName; }  Private static function _after_run(){ $callback = Config::get('sqlbak_conf.callback'); if(empty($callback) || empty($callback['class']) || empty($callback['action'])) return false; try{ $result = self::getCurDataLog(); if(!empty($result) && class_exists($callback['class'])) { call_user_func([new $callback['class'],$callback['action']],$result); } self::$szjdb->close(); if(is_dir(self::$bakPath)) self::delTree(self::$bakPath); } catch(\Exception $err){ if(self::$szjdb != null) self::$szjdb->close(); error_log($err->getMessage().PHP_EOL,3,'error.log'); } }  public static function delTree($dir) { try{ $files = array_diff(scandir($dir), array('.','..')); foreach ($files as $file) { @unlink("$dir/$file"); } } catch(\Exception $err){ error_log($err->getMessage().PHP_EOL,3,'error.log'); } }  Private static function getCurDataLog(){ $result = []; try{ self::$szjdb->moveHead(); while(true){ $data = self::$szjdb->next(); if(empty($data)) break; $result[] = $data[1]; } } catch(\Exception $err){ error_log($err->getMessage().PHP_EOL,3,'error.log'); } return $result; }  Private static function _begin_run(){ self::$bakPath = dirname(self::$bakFileName); }  public function sqlListen($sqlMsg = ''){ if(!empty($sqlMsg)) { $total = Config::get('sqlbak_conf.total'); if(empty($total)) return false; try{ if(null == self::$szjdb) { self::$szjdb = new SzjDb(); !is_dir(self::$bakPath) && mkdir(self::$bakPath, 0755, true); self::$szjdb->open(self::$bakFileName); } $curTotal = $this->nextTotal($total); if($curTotal === false) return false; self::$szjdb->set($curTotal,$sqlMsg); } catch(\Exception $err){ error_log($err->getMessage().PHP_EOL,3,'error.log'); } } }  Protected function nextTotal($total = 0){ self::$szjdb->moveTail(); $data = self::$szjdb->next(); if($data === false) return 1; if(is_array($data) && count($data) == 2){ $curTotal = intval($data[0]); if($curTotal >= $total) self::$isCallback = true; return ++$curTotal; } return false; } } 