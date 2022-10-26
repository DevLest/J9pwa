<?php
/**
 * 日志处理类
 * @author Shawn
 * @version 1.0.0
 * @name log
 * @date 2016年4月21日 
 */

class log
{
    //传入的文件名
    private $file_name;
    //日志文件路径
    private $log_path = "/log/";
    //日志文件名
    private $log_name;
    //日志文件最大长度
    private $log_max_len = 5242880;
    //日志文件前缀
    private $log_name_pre = "";
    //日志文件后缀
    private $log_name_suf = ".log";
    
    /**
     * 构造函数
     * 初始化 文件名和路径
     */
    public function __construct($name,$path = "")
    {
        $this->file_name = $name;
        $this->log_name = $this->log_name_pre.$name.$this->log_name_suf;
        if($path != "")
        {
            $this->log_path = $path;
        }else{
            $this->log_path = (__DIR__) .$this->log_path;
        }
        $this->create_log();
    }
    
    
    /**
     * 把内容写入到日志文件
     */
    public function write_log($content="",$type=3)
    {
        $content = date("Ymd H:i:s")."--".$content."-- \r\n";
        error_log($content,$type,$this->log_path.$this->log_name);
    }
    
    /**
     * 创建日志文件 如果超过大小则重命名再创建
     */
    private function create_log()
    {
        $file = $this->log_path.$this->log_name;
        $dir = $this->log_path;
        $status = $this->check_log_size();
        if($status == 102)
        {
            if (is_dir($dir))
            {
                $file_array = array();
                $dh = opendir($dir);
                if ($dh)
                {
                    while (($file_list = readdir($dh)) !== false)
                    {
                        if(strpos($file_list,$this->file_name) !== false)
                        {
                            $file_list = str_replace($this->log_name,"",$file_list);
                            $file_array[]=$file_list;
                        }
                    }
                    closedir($dh);
                }
                rsort($file_array);
                $new_num = $file_array[0]+1;
            }
            if(rename($file, $file.$new_num))
            {
                $this->write_log("create");
            }
        }elseif($status == 100){
            $this->write_log("create");
        }
    }
    /**
     * 检测日志文件是否超过了 定义的大小
     */
    private function check_log_size()
    {
        $file = $this->log_path.$this->log_name;
        $dir = $this->log_path;
        if(file_exists($file))
        {
            $file_size = filesize($file);
            if($file_size > $this->log_max_len)
            {
                return 102;//日志超出
            }else{
                return 101;//日志已创建，但未超出
            }
        }else{
            return 100;//日志文件未创建
        }
    }
}






















?>