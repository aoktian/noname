# NONAME
实在没有名字可以用了


一款项目管理工具，主要针对中小互联网敏捷开发团队，页面清爽简单，功能精简好用。<br>
基于linux+nignx+php7+mysql开发


**部署本项目还是需要有一定的服务器知识，不是一个前台小姐姐就能搞的定。**


# 如何使用我
团队的工作助手，100人以内的团队使用完全没有问题。


目标是做到最简单，让团队成员知道自己的任务，让团队知道本阶段的任务。


工具不是万能的，只能启动辅助的作用，该人去做的事情还是需要人去做。


## 对个人而言
我的第一优先级是关注自己的任务，安排工作时间。


如果一个人在多个工程队有任务，说明这个人很重要，能力很好，他自己可以管理好自己的任务。


## 对团队，管理者而言
要整体规划进度，看进度，看列表，看每一个人的列表，看每一个人的工作饱和度。


要强调任务列表的重要性，不关注任务列表，忘记看任务列表就像忘记吃饭一样，当大家形成习惯，就会离不开这个东西。


除了BUG是可以直接提给相关人的，需求和优化只有经过讨论后确定要做的时候才能提，不能因为有了工具就省掉沟通，面对面沟通是最有效率的事情。工具实质上只是提供了一个列表而已。


如果是更大组织，任务列表 的形态已经不能满足，需要沉淀信息，需要跨部门合作，需要宏观统计，等等等等，但用Checklist 的理念管理起来的公司，必然也能高效运作。


## 项目开发流程管理，在团队部门使用，可以达到如下效果
效率，每个人工作的完成度都能了解，无需反复汇报


透明，同事在做的事情是不是别人都知道


协作，同事之间为一个项目进行不同阶段的流转


## 清晰任务管理模式
将BUG、需求、改进融合在一起，实现任务的记录和追踪


对个人而言它是向导，每天只需关注自己的任务；


对管理者而言，要整体规划进度，协调其他人的内容，关注项目本身如何完成，如何灵活的组织人来参与项目


我不想让项目管理员对着模块划分发愁（每个人都要一套模块划分方式，没有统一标准，你这么划分我很别扭），另外显示模块也是众口难调，用什么分隔符都不一定能满足要求。模块直接在标题里用自然语言表达是最清楚不过了，统一格式可以解决大部分问题


**为什么不做模块管理？**

模块管理很好啊，只不过有点机械复杂。我不想让项目管理员对着模块划分发愁（每个人都要一套模块划分方式，没有统一标准，你这么划分我很别扭），另外显示模块也是众口难调，用什么分隔符都不一定能满足要求。模块直接在标题里用自然语言表达是最清楚不过了，真的要精确？统一格式就行了。


**为什么不做环境管理？**

想面向一个比较广泛的项目管理领域，不仅仅是软件开发，我希望做智能硬件的，做系统集成，做微信推广的等等做任何中小型项目的团队都来用这个产品，所以我没有对环境字段做过多的限制。环境本身就应该是在特殊情况下才需要的字段，不是每个缺陷都需要填写环境的。所以，自由就好。


**为什么不做权限管理？**

我希望对所有使用的人都是透明的，大家在一起合作完成一个项目，只有分工的不同，要相互信任。权限是一样的，都可以修改任务的状态。还是那句话，工具不是万能的，在使用工具的同时需要多多面对面沟通。


**为什么不加邮件或者弹窗之类的提醒？**

+ 邮件提醒肯定是不会加的，因为违背的简化工具的目的，我的目标是只使用一种工具完成所有事情
+ 一个任务在落实上是线性的，一个人完成了任务是一定要去改状态的，然后去查看下一个任务，极少有同时要做两件事，我更推荐集中精力做好一件事
+ 每个人的任务都是基于任务列表，每天的第一件事一定是打开自己的任务（这个是需要给来的人强调的），对每个任务要有一个评估，比如有a,b,c三个任务，是先做a，还是先做b，这个取决于每个人对自己和对整个项目的认识
+ 任务的状态，这个也是需要给新人讲解的，因为任务状态规范了开发流程

# 安装配置

## 使用 Docker Compose

我们推荐使用 Docker Compose 部署本项目. 首先确保你的机器上已经安装好了 Docker 和 Docker Compose.

执行如下命令创建并运行容器:

```bash
git clone https://github.com/aoktian/noname.git
cd noname
docker-compose up -d
```

然后用你的浏览器打开 `http://your_host/install` 以初始化数据库. 若出现 `PDOException: SQLSTATE[HY000] [2002] Connection refused in /var/www/vendor/workerman/mysql/src/Connection.php:1609` 这样的报错说明数据库正在初始化, 稍后重试即可.

初始账号为 `abc@abc.com`, 密码为 `123456`.

## 直接安装

### 基本配置在 ./config 目录下

### 初始化数据表，可以通过导入sql文件来完成，包括了一点儿基本数据，在目录 tools 下
```shell
sh tools/create.sh
```

### 基本配置在 ./config 目录下
默认帐号 abc@abc.com 密码 123456

### nginx root目录需要配置到 public



### nginx 的 location 配置
```shell
location / {
    try_files $uri $uri/ /index.php?$query_string;
    index  index.php;
}
```

### 我的服务器安装脚本，仅供参考，具体环境根据自己的需求配置
```shell
yum -y install wget net-tools
yum -y install gcc gcc-c++ autoconf automake libtool make cmake

#【安装nginx】
yum -y install zlib zlib-devel openssl openssl-devel pcre-devel

#确保pcre被安装
cd /usr/local/src
wget ftp://ftp.csx.cam.ac.uk/pub/software/programming/pcre/pcre-8.39.tar.gz
tar -zxf pcre-8.39.tar.gz
cd pcre-8.39
./configure
make
make install

cd /usr/local/src
groupadd www
useradd -g www www
wget http://nginx.org/download/nginx-1.10.3.tar.gz
tar zxf nginx-1.10.3.tar.gz
cd nginx-1.10.3
./configure --prefix=/usr/local/services/nginx --user=www --group=www --with-http_stub_status_module --with-pcre --without-mail_pop3_module --without-mail_imap_module --without-mail_smtp_module
make && make install

#配置自行修改

#【安装php】
cd /usr/local/src
yum install -y libxml2 libxml2-devel curl curl-devel
wget http://cn.php.net/get/php-7.1.3.tar.gz/from/this/mirror
mv mirror php-7.1.3.tar.gz
tar zxf php-7.1.3.tar.gz
cd php-7.1.3
./configure --prefix=/usr/local/services/php --with-config-file-path=/usr/local/services/php/etc --with-curl --with-mysqli=mysqlnd --with-pdo-mysql=mysqlnd --with-zlib --with-openssl --enable-fpm --enable-opcache --enable-xml --enable-mbregex --enable-mbstring --enable-zip --enable-inline-optimization
make && make install
cp php.ini-production /usr/local/services/php/etc/php.ini
cp /usr/local/services/php/etc/php-fpm.conf.default /usr/local/services/php/etc/php-fpm.conf
cp /usr/local/services/php/etc/php-fpm.d/www.conf.default /usr/local/services/php/etc/php-fpm.d/www.conf
rm -rf /usr/bin/php
ln -s /usr/local/services/php/bin/php /usr/bin/php

rm -rf /etc/init.d/php-fpm
cp sapi/fpm/init.d.php-fpm /etc/init.d/php-fpm
chmod +x /etc/init.d/php-fpm
chkconfig --add php-fpm
chkconfig php-fpm on
service php-fpm start
```



# 我的邮箱
email: aoktian@foxmail.com

