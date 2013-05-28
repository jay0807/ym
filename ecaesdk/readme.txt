安装说明

1 将本文件夹放到 ECstore/shopex485 根目录下 (也可以是其它php程序的根目录) 将sdk目录下的data目录设置成0777
2 修改config/config.php 文件 在头部载入本目录里的ecae-sdk.php文件( 使用ECOS框架的产品可以从 index.php文件头部载入)
3 就可以在代码里调用ECAE提供的API接口了
4 如果使用到数据库,请修改ecae-sdk.php 文件中相关数据库常量的定义
5 如果要使用ecae_file_*相关方法,请在sdk里设置一下ECAESDK_ROOT_URL,即你用浏览器能访问到ecaesdk目录的地址如 "http://127.0.0.1/ecaesdk" 那么设置ECAESDK_ROOT_URL 为 "http://127.0.0.1"

A&Q
A: 如果ECOS框架中出现function declaring的错误
Q: 请删除app/base/目录下的ego.php文件

A: 其它程序如果修改到ECAE上
Q: ECAE禁止本地目录写,涉及到本地目录写的操作将要使用ECAE提供的Cache,Storage,KVDB相关服务作为解决方案,按需求选用相关服务

A: 本地使用SDK修改程序的小技巧
Q: 将程序内的所有目录设置成0644,禁止写入,(ecaesdk目录下的data除外)
