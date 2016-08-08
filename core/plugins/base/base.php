<?php
// +----------------------------------------------------------------------
// | eventBindPhpFrame [ keep simple try auto ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015~2016 eventBindPhpFrame All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yannanfei <yannanfeiff@126.com>
// +----------------------------------------------------------------------
class basePlugin extends Plugin{

   public  function  init_timezone(){
       @date_default_timezone_set('Asia/Shanghai');
   }

   public  function   init_session(){
       $domain= explode(':',$_SERVER['HTTP_HOST']);
        $config=c();
       // $subdomain_suffix;die; 子域名后缀124.21.206:8090
       //session.name强制定制成PHPSESSID,不请允许更改
       @ini_set('session.name','PHPSESSID');
       // $subdomain_suffix = str_replace('http://','',$domain[0]);

       if ($domain[0] !== 'localhost'&&$domain[0] !== '127.0.0.1') { //不同域的cookie

           $domain_name=$config['domain_name'];
           @ini_set('session.cookie_domain', $domain_name);//
       }

       //开启以下配置支持session信息存信memcache
       if(c('cache_type')==='memcache'){
           @ini_set("session.save_handler", "memcache");
           $memcache_config=$config['memcache'];
           @ini_set("session.save_path",$memcache_config['host'].':'.$memcache_config['port']);
       }
       else{
           $path=ROOT_PATH.'/data/cache/session';
           if(!is_dir($path)){mk_dir($path,0777,true);}
           session_save_path($path); //去掉文件形式路径
       }
      return session_start(); //数据输出到浏览器之前调用
   }

    /**
     * controller 调度
     */
  public   function  run(){
         $config=c();
        $act=$config['act'];
        $op=$config['op'];
        $act_file =BASE_PATH.'/control/'.$act.'.php';
        $class_name = $act.'Control';

      //如果是indexControl  index.php自动创建文件
      if($act=='index'&&$op=='index'&&!file_exists($act_file)){
          mk_dir(dirname($act_file));
          copy(CORE_PATH.'/tpl/indexcontrol.tpl',$act_file);
      }

        if (!@include($act_file)){
            exit("Base Error: $act_file access file isn't exists!");
        }

        if (class_exists($class_name)){
            $main = new $class_name();
            $function = $op;
            //定义act和op
            if (method_exists($main,$function)){
                $main->$function();
            }else {
                $error = "Base Error: function $function not in $class_name!";
                exit($error);
            }
        }else {
            $error = "Base Error: class $class_name isn't exists!";
            exit($error);
        }
    }
  //检测参数是否有plugin参数 plugin=a&method=b  则执行apluin中的b方法，用于测试和plugin的直接处理
  public  function  run_plugin(){
    if(isset($_GET['plugin'])||isset($_POST['plugin'])){
        $plugin=isset($_GET['plugin'])?$_GET['plugin']:$_POST['plugin'];
        $method=isset($_GET['method'])?$_GET['method']:$_POST['method'];
        plugin($plugin)->$method();//指定plugin中的方法
        return true;
    }
      else{
          return false;
      }
  }
}