<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>粉丝万岁（免费智能共享秤，吸粉，广告，内容变现）</title>
    <meta name="description" content="粉丝万岁:免费智能共享秤，健康秤投放、广告、粉丝。"/>
    <meta name="keywords" content="粉丝万岁,智能体重秤,免费智能体重秤 免费智能共享秤 免费智能健康秤，共享秤"/>
    <link href="{{asset('assets/style/css/bootstrap.css')}}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{asset('assets/style/css/style.css')}}" rel="stylesheet" type="text/css" media="all"/>
    <meta name="author" content="design by HIFANS"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <script src="{{ asset('assets/style/js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/style/js/move-top.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/style/js/easing.js') }}"></script>
    <!--[if lt IE 9]>
    <script src="http://www.hifans.vip/style/js/html5shiv.js"></script>
    <script src="http://www.hifans.vip/style/js/respond.min.js"></script>
    <link href="http://www.hifans.vip/style/css/ie.css" rel="stylesheet">
    <![endif]-->
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $(".scroll").click(function (event) {
                event.preventDefault();
                $('html,body').animate({scrollTop: $(this.hash).offset().top}, 1000);
            });
        });
    </script>
    <script>
        function check() {
            yxm = document.getElementById("yxm").value;
            if (yxm == "") {
                alert("请输入姓名！")
                return false;
            }

            yyx = document.getElementById("yyx").value;
            if (yyx == "") {
                alert("请输入邮箱！")
                return false;
            }

            yqq = document.getElementById("yqq").value;
            if (yqq == "") {
                alert("请输入QQ！")
                return false;
            }

            ynr = document.getElementById("ynr").value;
            if (ynr == "") {
                alert("请输入内容！")
                return false;
            }

        }

    </script>
    <meta charset="utf-8">
    <style type="text/css">
        @media (max-width: 640px) {
            .services h3 {
                width: 100%;
            }

            .portfolio-top h3 {
                width: 100%;
            }

            .service-grid .serivce-left {
                width: 67%;
            }

            .service-grid .grid-left-services .phone {
                margin-top: 20%;
            }
        }
    </style>
</head>
<body>
<!--header-->
<div class="header">
    <div class="container">
        <div class="header-matter">
            <div class="logo-head"><a href="/"><img class="img-responsive"
                                                    src="http://www.hifans.vip/style/images/logo.png" alt=""/></a>
                <p>免费·智能·共享</p>
            </div>
            <div class="head-grid">
                <div class="men grid-men  scroll" onclick="location.href='#team';" class="artical">
                    <a href="#team" class="scroll"><img class="img-responsive clock-item"
                                                        src="http://www.hifans.vip/style/images/men.png" alt=""/></a>
                    <h6>广告投放</h6>
                </div>
                <div class="men"><img class="img-responsive" src="http://www.hifans.vip/style/images/pic.jpg" alt=""/>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="grid-header">
            <div class="grid-tv grid-men  scroll" onclick="location.href='#portfolio';" class="artical">
                <a href="#portfolio" class="scroll"><img class="img-responsive clock"
                                                         src="http://www.hifans.vip/style/images/tv.png" alt=""/></a>
                <h6>投放场景</h6>
            </div>
            <div class="grid-tv tv-portfolio  scroll" onclick="location.href='#services';" class="artical">
                <a href="#services" class="scroll"><img class=" clock" src="http://www.hifans.vip/style/images/pen.png"
                                                        alt=""/></a>
                <h6>合作方式</h6>
            </div>
            <div class="grid-tv"><img class="img-responsive" src="http://www.hifans.vip/style/images/pic1.jpg" alt=""/>
            </div>
            <div class="grid-tv"><img class="img-responsive" class="img-responsive"
                                      src="http://www.hifans.vip/style/images/pic2.jpg" alt=""/></div>
            <div class="grid-tv tv-portfolio  scroll" onclick="location.href='#about';" class="artical">
                <a href="#about" class="scroll"><img class=" clock" src="http://www.hifans.vip/style/images/rou.png"
                                                     alt=""/></a>
                <h6>我的设备</h6>
            </div>
            <div class="grid-tv"><img class="img-responsive" src="http://www.hifans.vip/style/images/pic3.jpg" alt=""/>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="grid-head-contact">
            <div class="grid-msg"><img class="img-responsive" src="http://www.hifans.vip/style/images/pic4.jpg" alt=""/>
            </div>
            <div class="grid-msg grid-men  scroll" onclick="location.href='#contact';" class="artical">
                <a href="#contact" class="scroll"><img class=" clock" src="http://www.hifans.vip/style/images/me.png"
                                                       alt=""/></a>
                <h6>联系我们</h6>
            </div>
            <div class="grid-msg msg-head"><img class="img-responsive" src="http://www.hifans.vip/style/images/pic5.jpg"
                                                alt=""/></div>
            <div class="clearfix"></div>
        </div>
        <div class="up"><a href="#about" class="scroll"><img class="up-grid"
                                                             src="http://www.hifans.vip/style/images/up.png"
                                                             alt=""/></a></div>
    </div>
</div>
<div class="header-home">
    <div class="container">
        <div class="fixed-header">
            <div class="logo"><a href="#"><img src="http://www.hifans.vip/style/images/logo-1.png" alt=""/></a></div>
            <div class="top-nav"><span class="menu"> </span>
                <ul>
                    <li><a href="#home" class="scroll">首页</a></li>
                    <li><a href="#about" class="scroll">我的设备</a></li>
                    <li><a href="#team" class="scroll">广告投放</a></li>
                    <li><a href="#services" class="scroll">合作方式</a></li>
                    <li><a href="#portfolio" class="scroll">投放场景</a></li>
                    <li><a href="#contact" class="scroll">联系我们</a></li>
                </ul>
                <!-- script-nav -->
                <script>
                    $("span.menu").click(function () {
                        $(".top-nav ul").slideToggle(500, function () {
                        });
                    });
                </script>
                <script type="text/javascript">
                    jQuery(document).ready(function ($) {
                        $(".scroll").click(function (event) {
                            event.preventDefault();
                            $('html,body').animate({scrollTop: $(this.hash).offset().top}, 1000);
                        });
                    });
                </script>
                <!-- //script-nav -->
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!--script-->
<script>
    $(document).ready(function () {
        $(".top-nav li a").click(function () {
            $(this).parent().addClass("active");
            $(this).parent().siblings().removeClass("active");
        });
    });
</script>
<!-- script-for sticky-nav -->
<script>
    $(document).ready(function () {
        var navoffeset = $(".header-home").offset().top;
        $(window).scroll(function () {
            var scrollpos = $(window).scrollTop();
            if (scrollpos >= navoffeset) {
                $(".header-home").addClass("fixed");
            } else {
                $(".header-home").removeClass("fixed");
            }
        });

    });
</script>
<!-- /script-for sticky-nav -->
<!--//header-->
<!--content-->
<div class="content">
    <div class="container">
        <div class="about" id="about">
            <h3>我<span>们的</span>设备</h3>
            <div class="about-grid">
                <div class="col-md-7 about-left"><img class="img-responsive"
                                                      src="http://www.hifans.vip/style/images/tt.png" alt=""/>
                    <p class="about-para">HIFANS智能广告秤，高颜值，开机率极高，无法被替换！</p>
                    <p>人脸识别、声纹识别，自动播放男女属性的电视广告，自动区别吸粉引流！</p>
                </div>
                <div class="col-md-5 about-right">
                    <h5>核心能力：面部识别，精准定投，精确吸粉！</h5>
                    <div class="green-about">
                        <div class="about-green">
                            <h6>大健康智能机器人，权威检测人体30项指标。全金属机身，能用10年！</h6>
                            <div class="content-green">
                                <div style="width:90%;"></div>
                            </div>
                        </div>
                        <div class="about-green">
                            <h6>物联网实时在线，视频广告、图文广告、客户二维码远程一键切换</h6>
                            <div class="content-green">
                                <div style="width:95%;"></div>
                            </div>
                        </div>
                        <div class="about-green">
                            <h6>智能黑科技，远程一键校准，颠覆传统秤返厂校准弊端。</h6>
                            <div class="content-green">
                                <div style="width:99%;"></div>
                            </div>
                        </div>
                        <div class="about-green">
                            <h6>面部识别，区分男女、年龄、雀斑等，千人千面精准定投广告、精准吸粉</h6>
                            <div class="content-green">
                                <div style="width:90%;"></div>
                            </div>
                        </div>
                        <div class="about-green">
                            <h6>互联网＋智能广告分享，广告主扫扫二维码就可以投自己的广告，高效率快转化</h6>
                            <div class="content-green">
                                <div style="width:80%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!---->
    <div class="team" id="team">
        <div class="container">
            <div class="team-head">
                <h3>广告<span>投</span>放</h3>
                <p>为客户创造价值是我们坚持的原则！我们开放多种广告方式，您可以选择您应用的方式！<br>我们诚邀全国广告公司与我们合作。您可以与我们共同开发本地广告资源，您会获得高额回报！
            </div>
            <div class="team-bottom">
                <div class="col-md-3 team-left left-team"><img class="img-responsive team-one"
                                                               src="http://www.hifans.vip/style/images/4.jpg" alt=""/>
                    <h6>首屏视频广告</h6>
                    <p>百屏轮播</p>
                </div>
                <div class="col-md-3 team-left"><img class="img-responsive team-one"
                                                     src="http://www.hifans.vip/style/images/3.jpg" alt=""/>
                    <h6>引导屏图片广告</h6>
                    <p>多图轮播</p>
                </div>
                <div class="col-md-3 team-left left-team"><img class="img-responsive team-one"
                                                               src="http://www.hifans.vip/style/images/2.jpg" alt=""/>
                    <h6>微信公众号吸粉</h6>
                    <p>真实粉丝</p>
                </div>
                <div class="col-md-3 team-left left-team"><img class="img-responsive team-one"
                                                               src="http://www.hifans.vip/style/images/1.jpg" alt=""/>
                    <h6>体检程序引流曝光</h6>
                    <p>超高粘性</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="services" id="services">
        <div class="container">
            <h3>
                合<span>作方</span>式
            </h3>
            <div class="service-grid">
                <div class="col-md-6 grid-left-services"><a class="phone" href="#"><img class="phone-grid"
                                                                                        src="http://www.hifans.vip/style/images/phone.png"
                                                                                        alt=""/></a>
                    <div class="serivce-left">
                        <h5>锁定区域做代理！</h5>
                        <p>如果您在当地有优质的点位资源，您可以代理我们的设备，锁定区域代理，躺赚收益。粉丝免费体检，每个有效关注，分成0.1-0.3元。设备免维护，广告分成，让您无忧！</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-6 grid-left-services"><a class="phone" href="#"><img class="phone-grid"
                                                                                        src="http://www.hifans.vip/style/images/bo.png"
                                                                                        alt=""/></a>
                    <div class="serivce-left">
                        <h5>视频广告合作</h5>
                        <p>
                            您在当地有广告资源，但是苦于没有更多媒介发布，可以联系我们。我们可以非常方便快捷而且便宜地投放到全国或指定区域设备上。可以精确到某一条街，某一个医院，某几个饭店，某个影城，某个地区，等等。全国将有90万台设备任您选择！</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="service-grid">
                <div class="col-md-6 grid-left-services"><a class="phone" href="#"><img class="phone-grid"
                                                                                        src="http://www.hifans.vip/style/images/ju.png"
                                                                                        alt=""/></a>
                    <div class="serivce-left">
                        <h5>图文静态广告合作</h5>
                        <p>取代传统的框架式广告，我们的设备引导屏上，提供多副静态轮播广告图文。我们可以为广告主提供简单粗暴的广告播出，可以为客户的产品直接达成购买！</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-6 grid-left-services"><a class="phone" href="#"><img class="phone-grid"
                                                                                        src="http://www.hifans.vip/style/images/se.png"
                                                                                        alt=""/></a>
                    <div class="serivce-left">
                        <h5>自媒体粉丝引流</h5>
                        <p>
                            从事自媒体运营却没有粉，令人着急！客户可以把公众号（订阅号、服务号、个人号）给我们。一天，我们可以实现50+万人次的关注导流，通过面部识别，都是真实有效的活生生的称重人！精准粉、定向粉都没问题，可以要机场的粉，可以要医院的粉，可以要电影院的粉，等等。</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="service-grid">
                <div class="col-md-6 grid-left-services"><a class="phone" href="#"><img class="phone-grid"
                                                                                        src="http://www.hifans.vip/style/images/lo.png"
                                                                                        alt=""/></a>
                    <div class="serivce-left">
                        <h5>销售定制设备</h5>
                        <p>您如果想独立运营自己品牌的智能共享秤，您可以选择定制购买我们的设备自行摆放，自主吸粉！我们可以OEM定制您的系统，您的logo，售后维护我们全程负责！</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-6 grid-left-services"><a class="phone" href="#"><img class="phone-grid"
                                                                                        src="http://www.hifans.vip/style/images/cl.png"
                                                                                        alt=""/></a>
                    <div class="serivce-left">
                        <h5>公众号软文广告</h5>
                        <p>如果您想通过软文进行宣传，我们有超过百万人的本地自媒体公众号，例如七彩深圳、七彩南昌、北京粉丝说等地方媒体矩阵，转发量大，阅读量高，转化率令您满意！</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <!---->
    <div class="portifolio" id="portfolio">
        <div class="container">
            <div class="portfolio-top">
                <h3>我们<span>的投放</span>场景</h3>
                <br>
            </div>
            <div class="example">
                <div class="portfolio-item">

                    <div style="margin-bottom:10px;" class=" col-md-4 item">
                        <a href="/case/3.html"> <img width="350" height="260"
                                                     src="http://www.hifans.vip/uploads/190530/1-1Z53011152O53.jpg"
                                                     alt="商场商城等购物场所提供设备" class="grid-item">
                            <div class=" port-head ">
                                <h4>商场商城等购物场所提供设备</h4>
                                <p>设备投放在商场、商城，用户累了可以免费使用。店铺可以因此获得用户的驻足...</p>
                            </div>
                        </a>
                    </div>
                    <div style="margin-bottom:10px;" class=" col-md-4 item">
                        <a href="/case/5.html"> <img width="350" height="260"
                                                     src="http://www.hifans.vip/uploads/190530/1-1Z530110G5T1.jpg"
                                                     alt="电影院场所投放减少等待厌烦" class="grid-item">
                            <div class=" port-head ">
                                <h4>电影院场所投放减少等待厌烦</h4>
                                <p>1、电影院提前购票客户在碎片时间可以用来称重，减少厌烦时间。
                                    2、设备可...</p>
                            </div>
                        </a>
                    </div>
                    <div style="margin-bottom:10px;" class=" col-md-4 item">
                        <a href="/case/2.html"> <img width="350" height="260"
                                                     src="http://www.hifans.vip/uploads/190530/1-1Z53010594ET.jpg"
                                                     alt="展会、集会、会议等场所，吸" class="grid-item">
                            <div class=" port-head ">
                                <h4>展会、集会、会议等场所，吸</h4>
                                <p>展会、集会等场所，可以使用设备来吸引人气，取代传统的发放小礼品吸引粉丝...</p>
                            </div>
                        </a>
                    </div>
                    <div style="margin-bottom:10px;" class=" col-md-4 item">
                        <a href="/case/4.html"> <img width="350" height="260"
                                                     src="http://www.hifans.vip/uploads/190530/1-1Z53010563G43.jpg"
                                                     alt="美容院、诊所、社康、健身房" class="grid-item">
                            <div class=" port-head ">
                                <h4>美容院、诊所、社康、健身房</h4>
                                <p>1、健康周边的机构投放设备，用于为用户提供配套服务；...</p>
                            </div>
                        </a>
                    </div>
                    <div style="margin-bottom:10px;" class=" col-md-4 item">
                        <a href="/case/6.html"> <img width="350" height="260"
                                                     src="http://www.hifans.vip/uploads/190530/1-1Z53010402Ka.jpg"
                                                     alt="餐饮场所摆放设备" class="grid-item">
                            <div class=" port-head ">
                                <h4>餐饮场所摆放设备</h4>
                                <p>1、餐饮场所放置设备，可以减少食客等待的厌烦情绪
                                    2、设备提供充电服务
                                    ...</p>
                            </div>
                        </a>
                    </div>
                    <div style="margin-bottom:10px;" class=" col-md-4 item">
                        <a href="/case/7.html"> <img width="350" height="260"
                                                     src="http://www.hifans.vip/uploads/180518/1-1P51Q53052206.jpg"
                                                     alt="酒店场所投放，为客户提供服" class="grid-item">
                            <div class=" port-head ">
                                <h4>酒店场所投放，为客户提供服</h4>
                                <p>设备投放于餐饮场所，有大量食客排队。...</p>
                            </div>
                        </a>
                    </div>


                    <div class="clearfix"></div>
                </div>
            </div>
            <a class="more" href="/case/" target="_blank">查看更多</a></div>
    </div>
    <!--clients-->
    <div class="clients">
        <div class="container">
            <div class="col-md-3 clients-grid">
                <ul>
                    <li><img src="http://www.hifans.vip/style/images/baidu.png" alt=""/></li>
                </ul>
            </div>
            <div class="col-md-3 clients-grid">
                <ul>
                    <li><img src="http://www.hifans.vip/style/images/taobao.png" alt=""/></li>
                </ul>
            </div>
            <div class="col-md-3 clients-grid">
                <ul>
                    <li><img src="http://www.hifans.vip/style/images/sina.png" alt=""/></li>
                </ul>
            </div>
            <div class="col-md-3 clients-grid">
                <ul>
                    <li><img src="http://www.hifans.vip/style/images/qq.png" alt=""/></li>
                </ul>
            </div>
            <div class="clearfix"></div>
            <div class="clients-top" style="margin-top:40px;">
                <h3>客<span>户评</span>价</h3>
                <br>
            </div>
            <div class="content-bottom">
                <div class="wmuSlider example1">
                    <div class="wmuSliderWrapper">
                        <article style="position: absolute; width: 100%; opacity: 0;">
                            <div class="grid-matter-bottom">
                                <div class="col-md-5 bottom-men "><img class="img-responsive"
                                                                       src="http://www.hifans.vip/style/images/buss.jpg"
                                                                       alt=""/></div>
                                <div class="col-md-7 bottom-matter-left"><span>这是一款精准的好产品</span>
                                    <p>"我们之前不敢做全国性的广告，价格太贵投不起。现在好了，我们只花5000元，就可以做100个城市的广告，而且效果非常好。 "</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </article>
                        <article style="position: absolute; width: 100%; opacity: 0;">
                            <div class="grid-matter-bottom">
                                <div class="col-md-5 bottom-men"><img class="img-responsive"
                                                                      src="http://www.hifans.vip/style/images/bus.jpg"
                                                                      alt=""/></div>
                                <div class="col-md-7 bottom-matter-left"><span>我们只要男粉丝，真给力</span>
                                    <p>"我们是做医疗行业的，需要男性粉丝，其他渠道太不精准了，通过海粉，我们获得了大量男粉丝关注，而且都是胖的人群！"</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </article>
                        <article style="position: absolute; width: 100%; opacity: 0;">
                            <div class="grid-matter-bottom">
                                <div class="col-md-5 bottom-men"><img class="img-responsive"
                                                                      src="http://www.hifans.vip/style/images/bu.jpg"
                                                                      alt=""/></div>
                                <div class="col-md-7 bottom-matter-left"><span>在HIFANS上投放广告</span>
                                    <p>"我们很轻松的获得了10万多的面部有雀斑和青春痘的流量粉丝！而且是不到一周。太厉害了！"</p>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </article>
                    </div>
                    <ul class="wmuSliderPagination">
                        <li><a href="#" class="">0</a></li>
                        <li><a href="#" class="">1</a></li>
                        <li><a href="#" class="">2</a></li>
                    </ul>
                </div>
                <script src="{{ asset('assets/style/js/jquery.wmuSlider.js') }}"></script>
                <script>
                    $('.example1').wmuSlider();
                </script>
            </div>
        </div>
    </div>

    <!--footer-->
    <div class="footer">
        <div class="container">
            <h4 class="footer-class">厂址：深圳市龙岗区坪地坪西东兴路2号。办公：深圳龙岗南联佳业广场302号。0755-28910609/28910196 </h4>

            <p class="class-footer">Copyright &copy; 2017-2019　九瓣花医疗科技有限公司，深圳海粉传媒广告有限公司 版权所有 <a
                        href="http://www.beian.miit.gov.cn" target="_blank"> 粤ICP备18025010号-7</a>
            </p>
            <div class="clearfix"></div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $().UItoTop({easingType: 'easeOutQuart'});

            });
        </script>
        <a href="#" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a></div>
    <!--footer-->
</body>
</html>