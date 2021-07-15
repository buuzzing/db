# 《数据库系统原理》课程设计

<p align=center style="font-weight:bold; font-size:18px; color:#c93756">2021年7月5日-19日</p>

### 课程题目：车站售票管理系统

### 页面采用 Bootstrap 样式，全栈采用 PHP 开发，数据库使用 MySQL

### 项目结构与功能：

> index.php 入口页面，登录页
>
> src
>
>> function 功能文件
>>
>>> func_insertPassenger.php 封装功能：插入新的乘客
>>>
>>> func_purchase.php 封装功能：请求购买车票
>>>
>>> func_refundTicker.php 封装功能：退票处理
>>>
>>> func_reNumber.php 封装功能：按车次查询
>>>
>>> func_reOrders.php 封装功能：查询我的订单
>>>
>>> func_reStation.php 封装功能：按车站查询
>>>
>>> func_reTicket.php 封装功能：余票信息查询
>>>
>>> function.php 全局函数
>>>
>>> login.php 封装功能：登录请求与处理
>>>
>>> logout.php 封装功能：插入新的乘客
>>>
>> pages 页面文件
>>
>>> home.php 主页面
>>>
>>> myOrders.php 订单页面
>>>
>>> myProfile.php 个人信息页面
>>>
>>> Re_number.php 按车次查询页面
>>>
>>> Re_station.php 按车站查询页面
>>>
>>> Re_tickets.php 站站余票查询页面和购买车票页面
>>>
> static 全局静态资源
>
>> css
>>
>> fonts
>>
>> icon
>>
>> img
>>
>>js

## 数据库信息

### 表结构

<img src='static\img\表结构.png'>

### 数据库连接信息

数据库名：ticketsystem

数据库地址：localhost:3306

数据库用户名：root

数据库用户密码：12345678

数据库连接相关配置位于 src\function\function.php 的第一个函数。