#####事件驱动框架
在control文件顶部直接添加如下代码可以直接运行control控制器文件，即逆向运行：
if(!defined('ROOT_PATH')){
     $act='index';
     $op='index';

    $path=dirname($_SERVER['PHP_SELF']).'/index.php?';
    header('Location: '.$path.'act='.$act.'&op='.$op);
}

当前实现功能：

* 事件驱动
* 自动生成indexContro.php  和config.ini.php
* 后置驱动生成

###注入事件示例：
/*
//测试注入init_act 事件
plugin('config')->on('on_init_act_op',function(){
    echo 'start init act and op ';
});
plugin('config')->on('end_init_act_op',function(){
    echo 'end init act and op ';
});
*/
//组件初始化

//将第一个文件的目录用于base_path

###加载组件示例

plugin('core/base');  //如果不加前缀代表加载本地app下的组件

###新增自动创建control功能
使用方式create_control('extendmenu');die;
会自动调用core/tpl 下的createcontrol.tpl模板生成指定名称control


###重新改版系统，新系统插件会优先从本地plugins查找；如果查找不到就从core/pluins中查找；
如果也没找到就报错；而公用前端资源插件从core/resource  中查找；app/resource用于应用
特有的部分，如前端control；以最大限度的重复利用资源.

新增model类库，core/model.php  core/tpl/model.tpl  实现MVC的分离，主要是为了方便一些代码公用，统一的接口获取数据，或公共方法处理数据
修改create_form 中boot.tpl  引用资源路径为从core/resource中引用

Model3更新，添加获取表和字段
###control.php 新增display_layout 函数，可以直接
使用layout-parjs-content这种布局模式
  core/plugin/upload_image   修改，防止同名文件覆盖； ，upload_image新增jquery批量上传组件
  jquerydatatable 插件新增readme.md  一些方法技巧更新
  更新，前端util组件位置：

新增公历农历转化组件 date_switch
新增百度地图坐标解析插件 baidu_map_api
upload_image 上传临时文件夹更改
修改control.php  layout位置更灵活
create_form plugins 修改上传文件大小为10MB    
upload_image  修改上传文件大小最大为10MB
resource 新增jquerychose插件，用于下拉框美化
diyupload插件更新
修改core/lib/Blitz 插件可以直接获取数组的key或者value
基于bootstrap的树形列表 treeview
jquery 图片点击放大插件 simplezoom
jqeury 图片延迟加载插件  jquery_lazyload
create_form调整宽度
blitz新增支持php原生函数，使用count()方法  新增数组形式例如{{IF $_value['class2']}}    $_value代表当前循环变量，不会再2去搜索全局变量
更新resource/util插件  增加simpleT函数
plugins/baidu_map_api将坐标系进行转换  gps坐标转化为百度坐标
BLItz支持{{select_employee['employee_name']}} 这样读取变量，全局变量
