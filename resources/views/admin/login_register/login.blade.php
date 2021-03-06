<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>登录 {{$system_name}}</title>

    <meta http-equiv="Content-Language" content="zh-CN" />
    <meta name="csrf-token" content="{{csrf_token()}}">
    <style type="text/css">
        /* 全局样式begin */
        html{color:#000;background:#FFF;}
        body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}
        body{background:#fff;font-size:12px; font-family:"宋体";}
        table{border-collapse:collapse;border-spacing:0;}
        fieldset,img{border:0;}
        ul,ol{list-style-type:none;}
        select,input,img,select{vertical-align:middle;}

        a{text-decoration:underline;}
        a:link{color:#009;}
        a:visited{color:#800080;}
        a:hover,a:active,a:focus{color:#c00;}

        .clearit{clear:both;}
        .clearfix:after{content:"ffff";display:block;height:0;visibility:hidden;clear:both;}
        .clearfix{zoom:1;}
        /* 全局样式 end */

        /* header begin */
        #gog{padding:3px 8px 0;background:#fff}
        #gbar,#guser{padding-top:1px !important}
        #gbar{float:left;height:22px}
        #guser{padding-bottom:7px !important;text-align:right}
        .gbh{border-top:1px solid #c9d7f1;font-size:1px;height:0;position:absolute;top:24px;width:100%}
        .gb1{margin-right:.5em;zoom:1}
        /* header end */

        /* header center footer */
        #header ,#centers ,#footer{ margin:0 auto; clear:both;}

        /* footer */
        .footer{margin: 20px 0;text-align: center;line-height: 24px;color:#333;}
        .footer a{color:#333;}

        #centers{ font-size:16px; margin-top:50px;}
        .btnclass{border: 1px outset #CCCCCC;font-size: 14px;color: #FF6600;background-color: #FFFFFF;}
        .inputclass {font-size: 12px;color: #FF0000;background-color: #FFFFFF; border:1px #FF6600 dotted;border-top-style: none;border-right-style: none;border-left-style: none;}
        input {font-family:'Verdana'}
    </style>
    <script type="text/javascript" language="javascript">
        <!--//
        String.prototype.trim=function(){return this.replace(/(^\s*)|(\s*$)/g,"")};
        if(typeof $=='undefined')$=function(id){return document.getElementById(id)};
        function con_code()
        {
            var qq= Math.round((Math.random()) * 100000000);
            $("check_img").src = '/common/lib/aipic.php?create=yes&r=' + qq;
            $("check_img").style.display = "block";
            $("imgcode").value = qq;
        }
        function  yanzhen(a_obj,a_type,a_must){
            var err = false;
            var old_style_border = a_obj.style.border;
            var val = a_obj.value.trim();

            if ((val == '' || val == '-' || val == 'http://') && a_must == true) err = true;

            if (err == false && val != '')
            {
                if (a_type == 'url' &&
                    val.substr(0,7) != 'http://' &&
                    val.substr(0,8) != 'https://')
                    err = true;

                if (a_type == 'email' &&
                    (val.indexOf('@') < 1 || val.indexOf('@') == (val.length - 1)))
                    err = true;

                if (a_type == 'int+0' && (isNaN(val) || parseInt(val) <= 0))
                    err = true;

                if (a_type == 'float' && (isNaN(val) || parseFloat(val) <= 0) )
                {
                    err = true;
                }
            }


            // Change class
            if (err) a_obj.style.borderColor = "#990000";
            else a_obj.style.borderColor = old_style_border;//"#D2D2D2";

            return (err);

        }

        function chkform(a_obj)
        {
            //var frm = document.forms[num];
            var frm = a_obj;
            var errnum = 0;
            var errsrt = "对不起，有以下问题需要您更正\n\n";


            if( "" == frm["username"].value ){
                errnum++;
                errsrt += "- “用户名”是必填项！\n";
            }
            if( "" == frm["password"].value ){
                errnum++;
                errsrt += "- “密码”是必填项！ \n";
            }
            {{$l_yanzhengma_js}}

            if (errnum>0) {
                alert(errsrt+"\n\n多谢您的支持 :D");
                //return false;
            } else{
                frm.submit();
                //return true;
            }
            return false
        }
        //-->
    </script>
</head>
<body>
{{$header}}
<br />
<br />
<div id="centers">
    <table align="center" bgcolor="#003371" border="1" cellpadding="3" cellspacing="1" width="350"  >
        <form name="loginform" id="loginform" method="post" action="" onsubmit="return chkform(this)" enctype="multipart/form-data">
            <input type="hidden" name="back_url" value="{{$back_url}}" />
            {{csrf_field()}}
            <tbody>
            <tr>
                <td align="center" bgcolor="#118ec9" height="41"><font color="#fafafa"><b>{{$system_name}} </b></font> <font color="#ffffff"> - 登录</font></td>
            </tr>
            <tr>
                <td bgcolor="#ffffff" height="207" valign="middle">
                    <table border="0" cellpadding="1" cellspacing="1" width="99%">
                        <tbody>
                        <tr><td colspan="2" align="center" style="color: #FF0000">{{$action_error_notice}}</td></tr>
                        <tr>
                            <td width="100" align="right" height="30">用户名:</td>
                            <td><input name="username" class="inputclass" id="username" type="text" onblur="yanzhen(this,'string',true)"><span style="display:none" id="msg_username">用户名不存在</span>{{$action_error_username}}</td>
                        </tr>
                        <tr>
                            <td align="right" height="30">密　码:</td>
                            <td><input name="password" class="inputclass" id="password"  type="password" onblur="yanzhen(this,'string',true)">{{$action_error_password}}</td>
                        </tr>
                        <tr>
                            <td align="right" height="30">谷歌验证:</td>
                            <td><input name="googlecode" class="inputclass" id="googlecode"  type="text">{{$action_error_googlecode}}</td>
                        </tr>
                        {{$l_yanzhengma}}
                        <tr>
                            <td>&nbsp;</td>
                            <td height="50">
                                <div style="font-size:12px;"><input class="btnclass" id="button_login" name="Submit" type="submit" value="登录" />&nbsp;&nbsp;<span title="为了确保你的信息安全，请不要在网吧或者公共机房选择此项！&#10;如果今后要取消此选项，只需点击网站右上角的“退出”链接即可"><input type="checkbox" checked="checked" name="remember" value="1"> 保持登录状态</span></div>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td height="30">
                                <div style="font-size:12px;">没有账号? <a href="#" target="_self">立即注册</a></div>
                            </td>
                        </tr>
                        </tbody>
                    </table></td>
            </tr>
            </tbody>
        </form>
    </table>
</div>
<br>
<br>
{{$footer}}
</body>
</html>
