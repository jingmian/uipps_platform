<?php
//namespace app\Libs\Classs;

if (!defined('NEW_LINE_CHAR')) define('NEW_LINE_CHAR', "\r\n");

class DBR extends MysqlR
{
    public $table_name = null;

    public function __construct($_arr=null, $options=false){
        return $this->ConnectDB($_arr, $options);
    }

    public function getExistorNot($id){
        $sql = "select * from ".cString_SQL::FormatField($this->table_name)." where d_id =$id limit 1";
        return parent::GetOne($sql);
    }

    public function getCountNum($where_limit=""){
        $sql = "select count(1) as num from ".cString_SQL::FormatField($this->table_name)." $where_limit ";
        $_t = parent::GetRow($sql);

        return ($_t["num"]+0);
    }

    // get all
    public function getAlls($where_limit="",$ziduan="*",$assoc=true){
        $sql = "select $ziduan from ".cString_SQL::FormatField($this->table_name)."  $where_limit ";
        $this->assoc = $assoc;
        return parent::GetPlan($sql);
    }

    //
    public function getCol($ziduan="*",$where_limit=""){
        $sql = "select $ziduan from ".cString_SQL::FormatField($this->table_name)." $where_limit ";
        return parent::GetCol($sql);
    }

    public function getOne($where_limit="",$ziduan="*",$debug=false,$assoc=true){
        $sql = "select $ziduan from ".cString_SQL::FormatField($this->table_name)." $where_limit ";
        if($debug) echo $sql;
        $this->assoc = $assoc;
        return parent::GetRow($sql);
    }

    public function query_plan($sql,$assoc=true){
        $this->assoc = $assoc;
        return parent::GetPlan($sql);
    }

    /**
     * 获取sql语句
     *
     * @return string or bool
     */
//    public function getSQL(){
//        return parent::getSQL();
//    }

    public function getTblFields($table_name=null,$FULL='FULL',$assoc=true){
        if(null==$table_name) $table_name = $this->table_name;
        $sql = "SHOW $FULL COLUMNS FROM ".cString_SQL::FormatField($table_name)." "; // 或 show FULL fields from table 或 desc table
        $this->assoc = $assoc;
        return parent::GetPlan($sql);
    }

    // 依据数据表结构，获取添加数据时候的字段和默认值，分为必选和全字段
    public function getInSertArr($table_name=null){
        $arr = $this->getTblFields($table_name);
        if (!$arr)
            return array([],[]);

        $fields_full           = array();
        $fields_bixu           = array();
        // 重新
        foreach ($arr as $row ) {
            $l_field = trim($row["Field"]);
            $l_v = ("NULL"==$row["Default"])?"":convCharacter($row["Default"],true);

            // 必须的字段单独用数组存放, 排除掉自增和timestamp
            if ("NO"==strtoupper($row["Null"]) && "auto_increment"!=strtolower($row["Extra"]) && "timestamp" != strtolower($row["Type"])) {
                $fields_bixu[$l_field] = $l_v;
            }

            $fields_full[$l_field] = $l_v;
        }

        return array($fields_full,$fields_bixu);
    }

    public function getTblFields2($table_name=null, $selected=''){
        $arr = $this->getTblFields();


        $fields_names           = array();
        $fields_types           = array();
        foreach ($arr as $row ) {
            $fields_names[]     = $row["Field"];
            // loic1: set or enum types: slashes single quotes inside options
            if (preg_match('@^(set|enum)\((.+)\)$@i', $row['Type'], $tmp)) {
                $tmp[2]         = substr(preg_replace('@([^,])\'\'@', '\\1\\\'', ',' . $tmp[2]), 1);
                $fields_types[] = $tmp[1] . '(' . str_replace(',', ', ', $tmp[2]) . ')';
            } else {
                $fields_types[] = $row['Type'];
            }
        }
        //
        $fields_options = "";
        $add_type = true;
        $index_type = "BTREE";
        foreach($fields_names AS $key => $val) {
            if ($index_type != 'FULLTEXT'
                || preg_match('@^(varchar|text|tinytext|mediumtext|longtext)@i', $fields_types[$key])) {
                $fields_options .= "\n" . '                '
                    . '<option value="' . htmlspecialchars($val) . '"' . (($val == $selected) ? ' selected="selected"' : '') . '>'
                    . htmlspecialchars($val) . (($add_type) ? ' [' . $fields_types[$key] . ']' : '' ) . '</option>' . "\n";
            }
        }

        return array($fields_names,$fields_types,$fields_options);
    }

    public function getTblIndex($table_name=null,$assoc=true){
        if(null==$table_name) $table_name = $this->table_name;
        $sql = "show index from ".cString_SQL::FormatField($table_name);  // or show keys
        $this->assoc = $assoc;
        return parent::GetPlan($sql);
    }

    public function getTblIndex2($table_name=null){
        $arr = $this->getTblIndex();

        $indexes      = array();
        $prev_index   = '';
        $indexes_info = array();
        $indexes_data = array();
        foreach ($arr as $row){
            if ($row['Key_name'] != $prev_index ){
                $indexes[]  = $row['Key_name'];
                $prev_index = $row['Key_name'];
            }
            $indexes_info[$row['Key_name']]['Sequences'][]     = $row['Seq_in_index'];
            $indexes_info[$row['Key_name']]['Non_unique']      = $row['Non_unique'];
            if (isset($row['Cardinality'])) {
                $indexes_info[$row['Key_name']]['Cardinality'] = $row['Cardinality'];
            }

            $indexes_info[$row['Key_name']]['Comment']         = (isset($row['Comment']))
                ? $row['Comment']
                : '';
            $indexes_info[$row['Key_name']]['Index_type']      = (isset($row['Index_type']))
                ? $row['Index_type']
                : '';

            $indexes_data[$row['Key_name']][$row['Seq_in_index']]['Column_name']  = $row['Column_name'];
            if (isset($row['Sub_part'])) {
                $indexes_data[$row['Key_name']][$row['Seq_in_index']]['Sub_part'] = $row['Sub_part'];
            }
        }
        return array($indexes,$indexes_info,$indexes_data);
    }

    public function getDBTbls($db_name=null,$assoc=true){
        if(null==$db_name) $db_name = parent::GetCurrentSchema();
        $sql = "show table status from ".cString_SQL::FormatField($db_name);
        $this->assoc = $assoc;
        return parent::GetPlan($sql);
    }
    public function SHOW_CREATE_TABLE($table_name=null,$assoc=true){
        if(null==$table_name) $table_name = $this->table_name;
        $sql = "SHOW CREATE TABLE ".cString_SQL::FormatField($table_name);  // or show keys
        $this->assoc = $assoc;
        return parent::GetPlan($sql);
    }
    public function SHOW_DATABASES($assoc=true){
        $sql = "SHOW DATABASES";
        //$this->assoc = $assoc;
        return parent::GetPlan($sql);
    }
}
