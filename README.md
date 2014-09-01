getCetScore
===========

http://www.xuyangjie.cn/the-study/getcetscore.html

根据准考证号和姓名，查询大学英语四六级成绩

 - 请求方式： 
    >***GET***

 - 请求参数说明：
    > ***num*** 15位准考证号

    > ***name*** 姓名

 - 响应参数说明：
    > ***msg*** 解释信息`success` 表示请求成功，`error`表示请求失败

    > ***code*** 返回码 `200` 表示请求成功，`400`表示请求失败
 
    > ***content*** 查询结果 `JSON`格式数据

 - 实例（虚拟数据）：

 `http://api.*.com/getcetscore/getcetscore.php?num=211012345678910&name=许杨淼淼`
