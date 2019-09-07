<?php

namespace App\Http\Controllers\Tempdef;


use App\Services\Admin\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\ListController;


class TempdefController extends ListController
{
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }


    public function list(Request $a_request)
    {
        // 检查是否登录
        $l_auth = $this->userService->ValidatePerm($a_request);
        $_SESSION = session()->all();
        if (!$l_auth || !isset($_SESSION['user']) || !$_SESSION['user']) {
            return redirect('/admin/login');
        }

        $actionMap = [];
        $actionError = [];
        $response = [];
        $form = [];
        $get = [];
        $cookie = [];
        $files = [];

        $request = $a_request->all();
        $request['do'] = 'tempdef_list';



        // 获取发布主机列表 , 用于ui
        $dbR = new \DBR();
        $a_p_self_ids = array(
            1=>array("ziduan"=>"p_id"),
            2=>array("ziduan"=>"t_id"),
        );
        // 获取到前两级的数据数组
        $p_self_info = \DbHelper::getProTblFldArr($dbR, $request, $a_p_self_ids);
        //print_r($p_self_info);
        if (!isset($p_self_info["p_def"])) {
            return  $response['html_content'] = '项目信息为空！';
        }
        if (!isset($p_self_info["t_def"])) {
            return  $response['html_content'] = '数据表信息为空！';
        }

        // 应该自动获取表定义表和字段定义表,此处省略并人为指定????
        $TBL_def = TABLENAME_PREF."table_def";
        $FLD_def = TABLENAME_PREF."field_def";

        // $dbR = new DBR($p_arr); 不需要此步骤，在上面的DbHelper::getProTblFldArr已经自动切换了
        $dbR->table_name = $table_name = TABLENAME_PREF."field_def";

        $arr = array();
        $arr["dbR"] = $dbR;
        $arr["table_name"] = $table_name;
        $arr["TBL_def"] = $TBL_def;
        $arr["FLD_def"] = $FLD_def;
        $arr["html_title"] = $GLOBALS['language']['TPL_ZIDUAN_STR'].$GLOBALS['language']['TPL_LIEBIAO_STR'];
        $arr["html_name"]  = $p_self_info["t_def"]["name_cn"].$arr["html_title"];
        $arr["default_sqlwhere"]  = "where t_id=".$request["t_id"];
        $arr["sql_order"] = "order by id";
        $arr["parent_ids_arr"] = array(1=>"p_id", 2=>"t_id");  // 父级元素列表,p2, p3...分别表示二、三级父级元素例如项目id，模板id，文档id等
        $arr["a_options"] = array(
            "nav"=>array(
                "p_id"=>array(
                    "script_name"=>"main.php", // 可有可无
                    "do"   =>"project_list",
                    "value"=>$request["p_id"],
                    "name_cn"=>$GLOBALS['language']['TPL_XIANGMU_STR'].$GLOBALS['language']['TPL_LIEBIAO_STR'],
                ),
                "t_id"=>array(
                    "script_name"=>"main.php", // 可有可无
                    "do"   =>"tempdef_list",
                    "value"=>$request["t_id"],
                    "name_cn"=>$GLOBALS['language']['TPL_MOBAN_STR'].$GLOBALS['language']['TPL_DINGYI_STR'].$GLOBALS['language']['TPL_BIAO_STR'],
                )
            )
        );

        $this->Init($request, $arr); // 初始化一下, 需要用到的数据的初始化动作,在parent::之前调用

        parent::getFieldsInfo($arr); // 用于搜索部分
        if(!array_key_exists("f_info",$arr)) {
            $response['ret'] = array('ret'=>1,'msg'=>"the f_info not exist!");
            return "the f_info not exist!";
        }

        $dbR->table_name = $table_name;
        $content = parent::execute($arr,$actionMap,$actionError,$request,$response,$form,$get,$cookie, $files);


        $ziduan_arr = getZiduan("id:ID;name_cn:字段中文名;name_eng:字段名;f_type:字段类型;status_:状态;description:备注");// 需要的字段
        $show_arr = buildH($arr["_arr"],$ziduan_arr);
        $show = $show_arr[0];
        $show_title = $show_arr[1];


        $data_arr = array(
            "show"=>$show,
            "show_title"=>$show_title,
            "get_csrf_token"=>csrf_token(),
        );

        $response['html_content'] = replace_template_para($data_arr,$content);
        $response['ret'] = array('ret'=>0);
        return $response['html_content'];  // 总是返回此结果
    }
}
