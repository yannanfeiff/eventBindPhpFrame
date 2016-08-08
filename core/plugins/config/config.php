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
//配置函数
class configPlugin  extends Plugin{
  protected   $appConfig=array(); //app和系统的配置，不同于组件的配置
  //加载配置文件
  public function  init(){
      $config=$this->config;
      $config_ini_path=BASE_PATH.'/config/config.ini.php';
      //自动创建config.ini.php
      if(!file_exists($config_ini_path)){
          mk_dir(dirname($config_ini_path));
          //

          $server=$_SERVER['HTTP_HOST'];
          $app_name=basename(BASE_PATH);
           $tpl=$config['plugin_path'].'/tpl/config.ini.tpl';
          $source= str_replace(array('{{server}}','{{app_name}}'),array($server,$app_name),file_get_contents($tpl));

          file_put_contents($config_ini_path,$source);
      }

        //如果有配置文件则加载
         $config=include($config_ini_path);

         if(is_array($config)){
             $this->appConfig=array_merge( $this->appConfig,$config);

         }
  }

  public  function  init_act_op(){

      //post 或get的act和op都存入act和op中
      $act= $_GET['act'] ? strtolower($_GET['act']) : ($_POST['act'] ? strtolower($_POST['act']) : null);
      $op = $_GET['op'] ? strtolower($_GET['op']) : ($_POST['op'] ? strtolower($_POST['op']) : null);

      /*  这里可以做静态路由解析的工作
      if (empty($_GET['act'])){
          require_once(BASE_CORE_PATH.'/framework/core/route.php');
          new Route($config);
      }
      */
//统一ACTION  默认赋值index
      $act = $act ?  $act : 'index';
      $op=$op? $op : 'index';

      $this->appConfig['act']=$act;
      $this->appConfig['op']=$op;
     return  $act.'|'.$op;
   }
    //配置数据库
    public  function  config_db(){
        //$config=c('db');
        $db_config=$this->appConfig['db']['master'];

        Model3::set_link($db_config['dbname'],$db_config['dbprefix'],$db_config['dbhost'],$db_config['dbport'],$db_config['dbuser'],$db_config['dbpwd']);//设置数据库
    }

  //外部调用获取配置函数
  public  function  c($key){
        if($key){
            return  $this->appConfig[$key];
        }
        else{
            return  $this->appConfig;
        }

    }

}
//获取config中的配置
function c($key=''){
   return plugin('core/config')->c($key);
}