<?php

/**
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */

/**
 * Save search tags
 * 数据来源：（百度地图tag引导体系）http://developer.baidu.com/map/devRes.htm
 */
$tags = array(
    array('美食', 'lbs/searchTags/ico_food.png', 10, 10, array(
        array('中餐馆', '', 0, 0, array(array('鲁菜', '', 0, 0), array('川菜', '', 0, 0), array('粤菜', '', 0, 0), array('徽菜', '', 0, 0), array('台湾菜', '', 0, 0), array('贵州菜', '', 0, 0), array('江浙菜', '', 0, 0), array('湘菜', '', 0, 0), array('湖北菜', '', 0, 0), array('清真菜', '', 0, 0), array('云南菜', '', 0, 0), array('东北菜', '', 0, 0), array('北京菜', '', 0, 0), array('闽南菜', '', 0, 0), array('西北菜', '', 0, 0), array('素菜', '', 0, 0), array('火锅', 'lbs/searchTags/ico_chafingDish.png', 0, 0), array('烤鸭', '', 0, 0), array('海鲜', '', 0, 0), array('家常菜', '', 0, 0))),
        array('西餐厅', '', 0, 0, array(array('意大利菜', '', 0, 0), array('法国菜', '', 0, 0), array('德国菜', '', 0, 0), array('俄罗斯菜', '', 0, 0), array('拉美烧烤', '', 0, 0), array('中东料理', '', 0, 0), array('披萨', '', 0, 0), array('牛排', '', 0, 0))),
        array('日本菜', '', 0, 0, array(array('日本料理', '', 0, 0), array('日式烧烤', '', 0, 0), array('寿司', '', 0, 0))),
        array('韩国菜', '', 0, 0),
        array('东南亚菜', '', 0, 0, array(array('泰国菜', '', 0, 0), array('越南菜', '', 0, 0), array('印度菜', '', 0, 0), array('菲律宾菜', '', 0, 0), array('印尼风味', '', 0, 0))),
        array('外卖', 'lbs/searchTags/ico_takeaway.png', 0, 20),
        array('自助餐', '', 0, 0),
        array('快餐', 'lbs/searchTags/ico_fastFood.png', 20, 30, array(array('中式快餐', '', 0, 0), array('西式快餐', '', 0, 0))),
        array('小吃', '', 30, 40, array(array('粉面馆', '', 0, 0), array('粥店', '', 0, 0), array('饺子馆', '', 0, 0), array('馄饨店', '', 0, 0), array('麻辣烫', '', 0, 0), array('关东煮', '', 0, 0), array('熟食', '', 0, 0), array('零食', '', 0, 0), array('包子', '', 0, 0))),
        array('蛋糕甜点', '', 0, 0, array(array('蛋糕西点', '', 0, 0), array('冰淇淋', '', 0, 0), array('甜点饮品', '', 0, 0))),
        array('烧烤', '', 0, 0)
    )),
    array('交通', 'lbs/searchTags/ico_traffic.png', 0, 0, array(
        array('地铁站', 'lbs/searchTags/ico_subway.png', 40, 50),
        array('公交站', 'lbs/searchTags/ico_bus.png', 50, 60),
        array('出租车', 'lbs/searchTags/ico_taxi.png', 60, 70),
        array('顺风车', '', 70, 80),
        array('飞机场', '', 0, 0),
        array('机场出入口', '', 0, 0),
        array('火车站', '', 0, 0),
        array('长途汽车站', '', 0, 0),
        array('机场巴士', '', 0, 0),
        array('港口', '', 0, 0),
        array('高速公路服务区', '', 0, 0),
        array('收费站', '', 0, 0),
        array('停车场', 'lbs/searchTags/icon_parking.png', 0, 0),
        array('加油站', 'lbs/searchTags/ico_gasStat.png', 0, 0),
        array('红绿灯', '', 0, 0)
    )),
    array('生活服务', '', 0, 0, array(
        array('邮局', 'lbs/searchTags/ico_postOffice.png', 0, 90),
        array('通讯营业厅', '', 0, 0, array(array('电信营业厅', '', 0, 0), array('移动营业厅', '', 0, 0), array('联通营业厅', '', 0, 0), array('铁通营业厅', '', 0, 0), array('网通营业厅', '', 0, 0))),
        array('火车票/机票/汽车票售票点', '', 80, 100, array(array('火车票售票点', '', 0, 0), array('飞机票售票点', '', 0, 0), array('汽车票售票点', '', 0, 0))),
        array('裁缝店/洗衣店', '', 0, 0, array(array('裁缝店', '', 0, 0), array('洗衣店', '', 0, 0))),
        array('图文快印', '', 0, 0, array(array('打印复印', '', 0, 0), array('传真', '', 0, 0), array('快照', '', 0, 0))),
        array('照相馆', '', 0, 0, array(array('艺术写真', '', 0, 0), array('证件照', '', 0, 0))),
        array('房产中介', '', 0, 0),
        array('数码维修', '', 0, 0, array(array('电脑维修', '', 0, 0), array('手机维修', '', 0, 0))),
        array('家政服务', '', 0, 0, array(array('搬家', '', 0, 0), array('保姆', '', 0, 0), array('管道疏通', '', 0, 0), array('钟点工', '', 0, 0), array('家电维修', '', 0, 0), array('送水', '', 0, 0), array('开锁', '', 0, 0))),
        array('彩票', '', 0, 0),
        array('宠物', '', 0, 0, array(array('宠物美容', '', 0, 0), array('宠物用品', '', 0, 0), array('宠物医院', '', 0, 0), array('宠物寄养', '', 0, 0), array('宠物食品', '', 0, 0))),
        array('报刊亭', '', 90, 0),
        array('刻章', '', 0, 0),
        array('车辆维修', '', 0, 0, array(array('摩托车维修', '', 0, 0), array('自行车维修', '', 0, 0))),
        array('公交卡充值点', '', 0, 0),
        array('快递公司', '', 0, 0),
        array('旅行社', '', 0, 0, array(array('国际旅行社', '', 0, 0))),
        array('公共厕所', 'lbs/searchTags/ico_toilet.png', 100, 110),
        array('墓地陵园', '', 0, 0)
    )),
    array('购物', 'lbs/searchTags/ico_shopping.png', 110, 120, array(
        array('购物中心', '', 0, 0),
        array('超市/便利店', 'lbs/searchTags/icon_shopping.png', 0, 0), 
        array('家电', '', 0, 0, array(array('空调', '', 0, 0), array('冰箱', '', 0, 0), array('洗衣机', '', 0, 0), array('微波炉', '', 0, 0))), 
        array('数码', '', 0, 0, array(array('电脑', '', 0, 0), array('摄影', '', 0, 0), array('手机', '', 0, 0))), 
        array('家居建材', '', 0, 0, array(array('家具', '', 0, 0), array('灯饰', '', 0, 0), array('厨具', '', 0, 0), array('卫浴', '', 0, 0), array('五金', '', 0, 0))),
        array('家纺', '', 0, 0, array(array('床上用品', '', 0, 0), array('窗帘', '', 0, 0), array('坐垫', '', 0, 0), array('地毯', '', 0, 0))),
        array('书店/音像店', '', 0, 0, array(array('书店', '', 0, 0), array('音像店', '', 0, 0))),
        array('办公用品', '', 0, 0),
        array('体育用品', '', 0, 0),
        array('户外用品', '', 0, 0), 
        array('服装/鞋帽/箱包', '', 0, 0, array(array('鞋子', '', 0, 0), array('服装', '', 0, 0), array('箱包', '', 0, 0))),
        array('化妆品', '', 0, 0),
        array('母婴用品', '', 0, 0),
        array('珠宝饰品', '', 0, 0, array(array('婚戒', '', 0, 0))),
        array('文物古玩', '', 0, 0),
        array('钟表', '', 0, 0),
        array('眼镜', '', 0, 0),
        array('花店/礼品店', '', 0, 0, array(array('花店', '', 0, 0), array('礼品店', '', 0, 0))),
        array('烟酒', '', 0, 0),
        array('茶叶/茶具', '', 0, 0, array(array('茶叶', '', 0, 0), array('茶具', '', 0, 0))),
        array('劳保用品', '', 0, 0),
        array('乐器行', '', 0, 0, array(array('琴行', '', 0, 0))),
        array('集市/批发市场', '', 0, 0, array(array('农贸市场', '', 0, 0), array('服装批发市场', '', 0, 0), array('轻纺市场', '', 0, 0), array('药材批发市场', '', 0, 0), array('文具批发市场', '', 0, 0), array('小商品市场', '', 0, 0), array('花鸟市场', '', 0, 0), array('二手市场', '', 0, 0)))
    )),
    array('丽人', 'lbs/searchTags/ico_beauty.png', 0, 0, array(
        array('美容', '', 0, 0, array(array('SPA', '', 0, 0), array('面部护理', '', 0, 0))),
        array('美发', '', 0, 0, array(array('洗染烫', '', 0, 0))),
        array('瘦身纤体', '', 0, 0),
        array('美甲', '', 0, 0)
    )),
    array('金融', '', 0, 0, array(
        array('银行', 'lbs/searchTags/ico_bank.png', 120, 130, array(array('ATM', 'lbs/searchTags/ico_atm.png', 130, 140))),
        array('典当行', '', 0, 0),
        array('信用社', '', 0, 0, array(array('农村信用社', '', 0, 0), array('城市信用社', '', 0, 0))),
        array('保险公司', '', 0, 0),
        array('证券公司', '', 0, 0),
        array('信托公司', '', 0, 0),
        array('投资公司', '', 0, 0),
        array('担保公司', '', 0, 0),
        array('证券交易市场', '', 0, 0)
    )),
    array('休闲娱乐', '', 140, 150, array(
        array('度假村/农家院/采摘园', '', 0, 0, array(array('农家院', '', 0, 0), array('度假村', '', 0, 0), array('采摘园', '', 0, 0))),
        array('文化宫', '', 0, 0),
        array('电影院', 'lbs/searchTags/ico_cinema.png', 0, 0),
        array('音乐厅', '', 0, 0),
        array('剧院', '', 0, 0),
        array('KTV', 'lbs/searchTags/icon_ktv.png', 0, 0),
        array('夜总会/歌舞厅/娱乐城/迪厅', '', 0, 0, array(array('夜总会', '', 0, 0), array('歌舞厅', '', 0, 0), array('娱乐城', '', 0, 0), array('迪厅', '', 0, 0))),
        array('商务会馆', '', 0, 0),
        array('洗浴/按摩/足浴/温泉', '', 0, 0, array(array('洗浴', 'lbs/searchTags/ico_bath.png', 0, 0), array('桑拿', 'lbs/searchTags/icon_sauna.png', 0, 0), array('温泉', '', 0, 0), array('按摩', 'lbs/searchTags/icon_massage.png', 0, 0), array('足浴', 'lbs/searchTags/icon_footbath.png', 0, 0))),
        array('网吧', 'lbs/searchTags/icon_internetCafes.png', 0, 0),
        array('游戏', '', 0, 0, array(array('电玩', '', 0, 0), array('棋牌室', '', 0, 0), array('桌游', '', 0, 0), array('真人CS', '', 0, 0))),
        array('酒吧/茶座/咖啡厅', '', 0, 0, array(array('酒吧', 'lbs/searchTags/ico_bar.png', 0, 0), array('茶座', '', 0, 0), array('咖啡厅', 'lbs/searchTags/ico_coffee.png', 0, 0))),
        array('DIY手工', '', 0, 0, array(array('DIY蛋糕', '', 0, 0), array('DIY饰品', '', 0, 0)))
    )),
    array('运动健身', '', 150, 160, array(
        array('体育场馆', '', 0, 0, array(array('游泳馆', '', 0, 0), array('羽毛球馆', '', 0, 0), array('乒乓球馆', '', 0, 0), array('台球馆', '', 0, 0), array('保龄球馆', '', 0, 0), array('武术馆', '', 0, 0), array('体操馆', '', 0, 0), array('网球场', '', 0, 0), array('篮球场', '', 0, 0), array('足球场', '', 0, 0), array('溜冰场', '', 0, 0), array('高尔夫球场', '', 0, 0), array('滑雪场', '', 0, 0), array('赛马场', '', 0, 0))),
        array('健身房', '', 0, 0),
        array('舞蹈', '', 0, 0, array(array('芭蕾', '', 0, 0))),
        array('瑜伽', '', 0, 0),
        array('舍宾', '', 0, 0),
        array('垂钓', '', 0, 0),
        array('极限运动', '', 0, 0, array(array('潜水', '', 0, 0), array('赛车场', '', 0, 0), array('攀岩', '', 0, 0), array('蹦极', '', 0, 0), array('航空滑翔', '', 0, 0), array('冲浪', '', 0, 0), array('定向越野', '', 0, 0))),
        array('卡丁车', '', 0, 0)
    )),
    array('医疗', 'lbs/searchTags/icon_medical.png', 0, 170, array(
        array('医院', '', 0, 0, array(array('三甲医院', '', 0, 0), array('三乙医院', '', 0, 0), array('三丙医院', '', 0, 0), array('二甲医院', '', 0, 0), array('二乙医院', '', 0, 0), array('二丙医院', '', 0, 0), array('一级医院', '', 0, 0), array('综合医院', '', 0, 0), array('妇产科医院', '', 0, 0), array('妇科医院', '', 0, 0), array('儿童医院', '', 0, 0), array('口腔医院', '', 0, 0), array('肿瘤医院', '', 0, 0), array('精神病医院', '', 0, 0), array('糖尿病医院', '', 0, 0), array('牙科医院', '', 0, 0), array('眼科医院', '', 0, 0), array('骨科医院', '', 0, 0), array('男科医院', '', 0, 0), array('皮肤病医院', '', 0, 0), array('心理医院', '', 0, 0), array('传染病医院', '', 0, 0), array('妇幼保健院', '', 0, 0), array('肛肠科医院', '', 0, 0), array('心血管病医院', '', 0, 0), array('五官科医院', '', 0, 0), array('中医院', '', 0, 0))),
        array('诊所', '', 0, 0),
        array('门诊部', '', 0, 0),
        array('急救中心', '', 0, 0),
        array('疗养院', '', 0, 0),
        array('防疫站', '', 0, 0),
        array('保健院', '', 0, 0),
        array('康复中心', '', 0, 0),
        array('体检中心', '', 0, 0),
        array('药店', 'lbs/searchTags/icon_drugStore.png', 0, 0),
        array('整形医院', '', 0, 0)
    )),
    array('酒店', 'lbs/searchTags/ico_hotel.png', 160, 0, array(
        array('星级酒店', 'lbs/searchTags/ico_starHotel.png', 0, 0, array(array('五星级酒店', '', 0, 0), array('四星级酒店', '', 0, 0), array('三星级酒店', '', 0, 0))),
        array('快捷酒店', 'lbs/searchTags/ico_inn.png', 0, 0),
        array('公寓式酒店', '', 0, 0),
        array('宾馆', '', 0, 0, array(array('家庭旅馆', '', 0, 0), array('青年旅舍', '', 0, 0), array('招待所', '', 0, 0)))
    )),
    array('旅游景点', 'lbs/searchTags/ico_view.png', 170, 180, array(
        array('公园', '', 0, 0, array(array('动物园', '', 0, 0), array('植物园', '', 0, 0), array('国家公园', '', 0, 0))),
        array('游乐园', '', 0, 0),
        array('海底世界', '', 0, 0),
        array('博物馆', '', 0, 0),
        array('美术馆', '', 0, 0),
        array('科技馆', '', 0, 0),
        array('展览馆', '', 0, 0),
        array('名胜古迹', '', 0, 0),
        array('海滨浴场', '', 0, 0),
        array('风景区', '', 0, 0, array(array('5A风景区', '', 0, 0), array('4A风景区', '', 0, 0), array('3A风景区', '', 0, 0))),
        array('自然保护区', '', 0, 0),
        array('旅游度假区', '', 0, 0)
    )),
    array('汽车服务', '', 0, 0, array(
        array('汽车销售', '', 0, 0, array(array('4S店', '', 0, 0), array('汽车销售综合店', '', 0, 0))),
        array('汽车美容', '', 0, 0, array(array('汽车保养', '', 0, 0), array('汽车装饰', '', 0, 0))),
        array('洗车', '', 0, 0),
        array('汽车租赁', '', 0, 0),
        array('汽车维修', '', 0, 0),
        array('汽车配件', '', 0, 0),
        array('汽车检验场', '', 0, 0)
    )),
    array('结婚', '', 0, 0, array(
        array('婚纱摄影', '', 0, 0),
        array('婚纱礼服', '', 0, 0),
        array('婚庆服务', '', 0, 0, array(array('婚庆策划', '', 0, 0), array('庆典用品', '', 0, 0), array('婚庆租车', '', 0, 0), array('司仪督导', '', 0, 0), array('跟妆造型', '', 0, 0))),
        array('婚介', '', 0, 0)
    )),
    array('教育', '', 0, 0, array(
        array('学校', 'lbs/searchTags/ico_school.png', 0, 0, array(array('幼儿园', '', 0, 0), array('小学', '', 0, 0), array('初中', '', 0, 0), array('高中', '', 0, 0), array('中专', '', 0, 0), array('大学', '', 0, 0), array('特殊教育学校', '', 0, 0))),
        array('科研机构', '', 0, 0),
        array('图书馆', '', 0, 0),
        array('留学中介', '', 0, 0),
        array('成人教育', '', 0, 0),
        array('亲子教育', '', 0, 0)
    )),
    array('培训机构', '', 0, 0, array(
        array('技能培训', '', 0, 0, array(array('驾校', '', 0, 0), array('电脑培训', '', 0, 0), array('美容培训', '', 0, 0), array('美发培训', '', 0, 0), array('厨师培训', '', 0, 0))),
        array('艺术培训', '', 0, 0, array(array('美术培训', '', 0, 0), array('吉他培训', '', 0, 0), array('钢琴培训', '', 0, 0), array('小提琴培训', '', 0, 0), array('声乐培训', '', 0, 0))),
        array('语言培训', '', 0, 0, array(array('日语培训', '', 0, 0), array('韩语培训', '', 0, 0), array('英语培训', '', 0, 0), array('法语培训', '', 0, 0), array('德语培训', '', 0, 0))),
        array('职业培训', '', 0, 0),
        array('考试培训', '', 0, 0, array(array('GRE培训', '', 0, 0), array('高考培训', '', 0, 0), array('考研培训', '', 0, 0), array('公务员考试培训', '', 0, 0), array('司法考试培训', '', 0, 0), array('TOFEL培训', '', 0, 0), array('四六级培训', '', 0, 0))),
        array('体育培训', '', 0, 0, array(array('乒乓球培训', '', 0, 0), array('羽毛球培训', '', 0, 0), array('网球培训', '', 0, 0)))
    )),
    array('房地产', '', 0, 0, array(
        array('住宅区', '', 0, 0, array(array('小区', '', 0, 0), array('公寓', '', 0, 0), array('别墅', '', 0, 0))),
        array('写字楼', '', 0, 0)
    )),
    array('自然地物', '', 0, 0, array(
        array('山峰', '', 0, 0),
        array('湖泊', '', 0, 0),
        array('河流', '', 0, 0),
        array('植被', '', 0, 0),
        array('岛屿', '', 0, 0),
        array('海洋', '', 0, 0)
    )),
    array('行政区划', '', 0, 0, array(
        array('省', '', 0, 0),
        array('直辖市', '', 0, 0),
        array('自治区', '', 0, 0),
        array('特别行政区', '', 0, 0),
        array('自治州', '', 0, 0),
        array('地级市', '', 0, 0),
        array('县', '', 0, 0),
        array('市辖区', '', 0, 0),
        array('商圈', '', 0, 0),
        array('乡镇', '', 0, 0),
        array('村庄', '', 0, 0),
        array('科技园', '', 0, 0, array(array('软件园', '', 0, 0))),
        array('工业区', '', 0, 0),
        array('开发区', '', 0, 0, array(array('旅游开发区', '', 0, 0), array('经济开发区', '', 0, 0), array('高新技术开发区', '', 0, 0)))
    )),
    array('政府机构', '', 180, 0, array(
        array('法院', '', 0, 0),
        array('检察院', '', 0, 0),
        array('政府', '', 0, 0, array(array('国务院', '', 0, 0), array('省政府', '', 0, 0), array('市政府', '', 0, 0), array('县政府', '', 0, 0), array('镇政府', '', 0, 0), array('街道办事处', '', 0, 0), array('政府驻地办事处', '', 0, 0))),
        array('村民委员会', '', 0, 0),
        array('居民委员会', '', 0, 0),
        array('公证处', '', 0, 0),
        array('机关单位', '', 0, 0, array(array('公安局', '', 0, 0), array('派出所', '', 0, 0), array('交通局', '', 0, 0), array('司法局', '', 0, 0), array('消防局', '', 0, 0), array('工商局', '', 0, 0), array('地税局', '', 0, 0), array('国税局', '', 0, 0), array('财政局', '', 0, 0), array('民政局', '', 0, 0), array('交管局', '', 0, 0), array('电信局', '', 0, 0), array('海关', '', 0, 0), array('食品局', '', 0, 0), array('地震局', '', 0, 0), array('劳动局', '', 0, 0), array('教育局', '', 0, 0), array('气象局', '', 0, 0), array('物价局', '', 0, 0), array('烟草专卖局', '', 0, 0), array('质监局', '', 0, 0), array('卫生局', '', 0, 0), array('规划局', '', 0, 0), array('水利局', '', 0, 0), array('文化局', '', 0, 0), array('审计局', '', 0, 0), array('旅游局', '', 0, 0), array('体育局', '', 0, 0), array('粮食局', '', 0, 0), array('房管所', '', 0, 0), array('档案馆', '', 0, 0), array('机关驻地办事处', '', 0, 0))),
        array('涉外机构', '', 0, 0, array(array('大使馆', '', 0, 0), array('签证处', '', 0, 0))),
        array('福利机构', '', 0, 0, array(array('敬老院', '', 0, 0), array('福利院', '', 0, 0))),
        array('慈善机构', '', 0, 0, array(array('红十字会', '', 0, 0), array('残疾人联合会', '', 0, 0), array('青少年基金会', '', 0, 0)))
    )),
    array('公司企业', '', 0, 0, array(
        array('IT企业', '', 0, 0, array(array('软件公司', '', 0, 0), array('互联网公司', '', 0, 0))),
        array('传媒公司', '', 0, 0, array(array('广播电视公司', '', 0, 0), array('报社', '', 0, 0), array('杂志社', '', 0, 0), array('广告公司', '', 0, 0))),
        array('公用事业', '', 0, 0, array(array('自来水公司', '', 0, 0), array('电力公司', '', 0, 0), array('燃气公司', '', 0, 0))),
        array('房地产公司', '', 0, 0, array(array('房地产开发公司', '', 0, 0), array('物业管理公司', '', 0, 0), array('售楼处', '', 0, 0))),
        array('物流公司', '', 0, 0, array(array('货运公司', '', 0, 0))),
        array('事务所', '', 0, 0, array(array('律师事务所', '', 0, 0), array('会计事务所', '', 0, 0), array('审计事务所', '', 0, 0))),
        array('出版社', '', 0, 0),
        array('咨询公司', '', 0, 0, array(array('管理咨询公司', '', 0, 0), array('技术咨询公司', '', 0, 0), array('工程咨询公司', '', 0, 0), array('投资咨询公司', '', 0, 0), array('教育咨询公司', '', 0, 0))),
        array('制造业', '', 0, 0, array(array('纺织公司', '', 0, 0), array('食品公司', '', 0, 0), array('制药公司', '', 0, 0), array('通讯设备制造公司', '', 0, 0), array('计算机制造公司', '', 0, 0), array('家电制造公司', '', 0, 0), array('汽车制造公司', '', 0, 0))),
        array('猎头公司', '', 0, 0),
        array('通讯公司', '', 0, 0),
        array('建筑公司', '', 0, 0),
        array('装修公司', '', 0, 0),
        array('经纪公司', '', 0, 0),
        array('殡葬公司', '', 0, 0),
        array('礼仪公司', '', 0, 0),
        array('公关公司', '', 0, 0),
        array('劳务公司', '', 0, 0),
        array('烟草公司', '', 0, 0),
        array('化工公司', '', 0, 0),
        array('拍卖公司', '', 0, 0),
        array('婚庆公司', '', 0, 0),
        array('园艺公司', '', 0, 0),
        array('人才市场', '', 0, 0),
        array('工厂', '', 0, 0),
        array('矿区', '', 0, 0),
        array('影视公司', '', 0, 0),
        array('艺术团  ', '', 0, 0)
    )),
    array('门址', '', 0, 0),
    array('道路', '', 0, 0, array(
        array('高速公路', '', 0, 0),
        array('国道', '', 0, 0),
        array('省道', '', 0, 0),
        array('城市主干道', '', 0, 0),
        array('县道', '', 0, 0)
    )),
    array('交通线', '', 0, 0, array(
        array('公交线路', '', 0, 0),
        array('地铁线路', '', 0, 0),
        array('铁路', '', 0, 0)))
);

function insertTags($tags, $installer, $parentId = 0) {
    $priority = 0;
    foreach ($tags as $tag) {
        $priority += 10;
        $bind = array(
            'default_name'      => $tag[0],
            'img'               => $tag[1],
            'priority'          => $priority,
            'normal_priority'   => $tag[2],
            'near_priority'     => $tag[3],
            'parent_id'         => $parentId,
            'is_active'         => 1,
            'created_at'        => now(),
            'updated_at'        => now(),
        );
        
        $installer->getConnection()->insert($installer->getTable('lbs_search_tag'), $bind);
        $lastId = $installer->getConnection()->lastInsertId($installer->getTable('lbs_search_tag'));
        if (count($tag) > 4) {
            insertTags($tag[4], $installer, $lastId);
        }
    }
}

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
insertTags($tags, $installer);