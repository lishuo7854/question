jQuery(document).ready(function($) {

    

    $.ajax({ 
        type: "GET", 	
        url: "http://192.168.137.1/php5/public/get_code",
        dataType: "jsonp",
        success: function(data) {
            if (data.success) { 
                console.log(data.msg);
            } else {
                $("#searchResult").html("出现错误：" + data.msg);
            }  
        },
        error: function(jqXHR){     
           alert("发生错误：" + jqXHR.status);  
        },     
    });

    var jsondata='{"questions":[{"contenta":"123456789?","select1":"aaaa","select2":"bbbb","select3":"cccc","select4":"dddd","answer":"0"}]}'
    var jsonobj=JSON.parse(jsondata);
    console.log(jsonobj.questions[0].contenta)


    var chance = 3;        //剩余机会控制
    var a=0;               //分数显示
    var b=0;               //是否摇一摇判断
    
    var c=parseInt(10*Math.random());
    console.log(c);
//倒计时
    
var sec ;
    // var interval=setInterval(countDown,1000);  
        function countDown() {  //倒计时的方法  
                 
            if(sec > -1) {  
                $('.num').html(sec); 
                sec--;
            } else {  
                location = "result.html"; 
                                      }  
        } 


// 成绩表格变换
    $('.rank_top a').click(function() {

        $('.rank_top a').removeClass('active');
        $(this).addClass('active');

    });
    $('.rank_left').click(function() {

        $('.theader .tmid').html('班级名称');

    });
    $('.rank_middle').click(function() {

        $('.theader .tmid').html('用户名称');

    });
    $('.rank_right').click(function() {

        $('.theader .tmid').html('院系名称');

    });
//弹窗部分
    $(function () {
        //单次单选弹框
        $("#onlyChoseAlert").click(function () {
            var onlyChoseAlert = simpleAlert({
                "content":"",
                "buttons":{
                    "123":function () {
                        location = "result.html"; 
                    }
                }
            })
        })
    });

//加载题目
    function begin(){
        sec=100;
        interval=setInterval(countDown,1000);
        $('button')[0].style.background="url(imgs/btn_bg1.png)";
        $('button')[0].style.backgroundSize="100% 100%";
        $('button')[1].style.background="url(imgs/btn_bg1.png)";
        $('button')[1].style.backgroundSize="100% 100%";
        $('button')[2].style.background="url(imgs/btn_bg1.png)";
        $('button')[2].style.backgroundSize="100% 100%";
        $('button')[3].style.background="url(imgs/btn_bg1.png)";
        $('button')[3].style.backgroundSize="100% 100%";
        $('.question_content').html(jsonobj.questions[0].contenta);
        $('.answer1').html(jsonobj.questions[0].select1);
        $('.answer2').html(jsonobj.questions[0].select2);
        $('.answer3').html(jsonobj.questions[0].select3);
        $('.answer4').html(jsonobj.questions[0].select4);
        $('button')[1].classList.add("right_answer");
        $('button')[1].classList.remove("wrong_answer");
        $("button").removeAttr("disabled");
    };
    begin();

//判断题目

$(function () {
    $('button').removeAttr("disabled"); 
    //正确
    $('.right_answer').click(function(){
        clearInterval(interval);
        a++;
        $('.red').html(a);
        document.getElementsByClassName('right_answer')[0].style.background="url(imgs/right_answer.png)";
        document.getElementsByClassName('right_answer')[0].style.backgroundSize="100% 100%";
        $('button').attr("disabled","true");
        setTimeout(begin,2000);
    });
    //错误
    function ahh(){
    $('.wrong_answer').click(function(){
        clearInterval(interval);
        $(this)[0].style.background="url(imgs/wrong_answer.png)";
        $(this)[0].style.backgroundSize="100% 100%";
        $('.right_answer')[0].style.background="url(imgs/right_answer.png)";
        $('.right_answer')[0].style.backgroundSize="100% 100%";
        $('button').attr("disabled","true");
        chance--;
        if(chance>=0){
            
        $('.chances').html(chance);
            setTimeout(begin,2000); 

        }else{  
            if(b==0){
                sec=8;
                alert1();      
                b++;
                $('#alertFram1').click(function(){
                    if(c>5){
                        setTimeout(function(){
                            doOk();
                            alert4();
                            chance=chance+1;
                            $('.chances').html(chance);
                            setTimeout(function(){
                                doOk();
                                begin();
                            },2000);
                        },0);   
                    }
                    else{
                        sec=8;
                        doOk();
                        alert2();
                        setTimeout(function(){
                            doOk();
                            sec=8;
                            alert3()
                            setTimeout(function(){
                                doOk();
                                location="result.html";},2000);
                        },2000);

                    }
                });    
            }
            else{
                sec=8;
                alert3();
                setTimeout(function(){
                    doOk();
                    location="result.html";
                },2000);

            } 

        

        }
        
    });}
    ahh();
});

      
// setTimeout(function(){

//     doOk();
//     alert2();

//     setTimeout(function(){

//         doOk();
//         alert3();

//         setTimeout(function(){

//             doOk();
//             location="result.html";

//         },2000)

//     },2000)

// },2000);   

//判断是否摇一摇
// if(chance<0){
//     var a=0;
//     if(a=0){alert(123);a++;
//     }else{
//         location="result.html";
//     }
    
// }



});
//自定义弹窗1
window.alert1 = function(str)
{
    var shield = document.createElement("DIV");
    shield.id = "shield";
    shield.style.position = "absolute";
    shield.style.left = "50%";
    shield.style.top = "50%";
    shield.style.width = "280px";
    shield.style.height = "150px";
    shield.style.marginLeft = "-140px";
    shield.style.marginTop = "-110px";
    shield.style.zIndex = "25";
    var alertFram1 = document.createElement("DIV");
    alertFram1.id="alertFram1";
    alertFram1.style.position = "absolute";
    alertFram1.style.width = "280px";
    alertFram1.style.height = "150px";
    alertFram1.style.left = "50%";
    alertFram1.style.top = "50%";
    alertFram1.style.marginLeft = "-140px";
    alertFram1.style.marginTop = "-110px";
    alertFram1.style.textAlign = "center";
    alertFram1.style.lineHeight = "150px";
    alertFram1.style.zIndex = "300";

    document.body.appendChild(alertFram1);
    document.body.appendChild(shield);
    this.doOk = function(){
        alertFram1.style.display = "none";
        shield.style.display = "none";
    }
    alertFram1.focus();
    document.body.onselectstart = function(){return false;};
}
//自定义弹窗2
window.alert2 = function(str)
{
    var shield = document.createElement("DIV");
    shield.id = "shield";
    shield.style.position = "absolute";
    shield.style.left = "50%";
    shield.style.top = "50%";
    shield.style.width = "280px";
    shield.style.height = "150px";
    shield.style.marginLeft = "-140px";
    shield.style.marginTop = "-110px";
    shield.style.zIndex = "25";
    var alertFram2 = document.createElement("DIV");
    alertFram2.id="alertFram2";
    alertFram2.style.position = "absolute";
    alertFram2.style.width = "280px";
    alertFram2.style.height = "150px";
    alertFram2.style.left = "50%";
    alertFram2.style.top = "50%";
    alertFram2.style.marginLeft = "-140px";
    alertFram2.style.marginTop = "-110px";
    alertFram2.style.textAlign = "center";
    alertFram2.style.lineHeight = "150px";
    alertFram2.style.zIndex = "300";
    document.body.appendChild(alertFram2);
    document.body.appendChild(shield);
    this.doOk = function(){
        alertFram2.style.display = "none";
        shield.style.display = "none";
    }
    alertFram2.focus();
    document.body.onselectstart = function(){return false;};
}
//自定义弹窗3
window.alert3 = function(str)
{
    var shield = document.createElement("DIV");
    shield.id = "shield";
    shield.style.position = "absolute";
    shield.style.left = "50%";
    shield.style.top = "50%";
    shield.style.width = "280px";
    shield.style.height = "150px";
    shield.style.marginLeft = "-140px";
    shield.style.marginTop = "-110px";
    shield.style.zIndex = "25";
    var alertFram3 = document.createElement("DIV");
    alertFram3.id="alertFram3";
    alertFram3.style.position = "absolute";
    alertFram3.style.width = "280px";
    alertFram3.style.height = "150px";
    alertFram3.style.left = "50%";
    alertFram3.style.top = "50%";
    alertFram3.style.marginLeft = "-140px";
    alertFram3.style.marginTop = "-110px";
    alertFram3.style.textAlign = "center";
    alertFram3.style.lineHeight = "150px";
    alertFram3.style.zIndex = "300";
    document.body.appendChild(alertFram3);
    document.body.appendChild(shield);
    this.doOk = function(){
        alertFram3.style.display = "none";
        shield.style.display = "none";
    }
    alertFram3.focus();
    document.body.onselectstart = function(){return false;};
}

//自定义弹窗4
window.alert4 = function(str)
{
    var shield = document.createElement("DIV");
    shield.id = "shield";
    shield.style.position = "absolute";
    shield.style.left = "50%";
    shield.style.top = "50%";
    shield.style.width = "280px";
    shield.style.height = "150px";
    shield.style.marginLeft = "-140px";
    shield.style.marginTop = "-110px";
    shield.style.zIndex = "25";
    var alertFram4 = document.createElement("DIV");
    alertFram4.id="alertFram4";
    alertFram4.style.position = "absolute";
    alertFram4.style.width = "280px";
    alertFram4.style.height = "150px";
    alertFram4.style.left = "50%";
    alertFram4.style.top = "50%";
    alertFram4.style.marginLeft = "-140px";
    alertFram4.style.marginTop = "-110px";
    alertFram4.style.textAlign = "center";
    alertFram4.style.lineHeight = "150px";
    alertFram4.style.zIndex = "300";
    document.body.appendChild(alertFram4);
    document.body.appendChild(shield);
    this.doOk = function(){
        alertFram4.style.display = "none";
        shield.style.display = "none";
    }
    alertFram4.focus();
    document.body.onselectstart = function(){return false;};
}
