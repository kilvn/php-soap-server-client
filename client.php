<?php
    header("Content-type: text/html; charset=utf-8");
    ini_set('soap.wsdl_cache_enabled','0'); //关闭缓存
    
    function debug(){
        $args = func_get_args();
        header('Content-type: text/html; charset=utf-8');
        echo "\n<pre>---------------------------------debug调试信息.---------------------------------\n";
        foreach($args as $value){
            if(is_null($value)){
                echo '[is_null]';
            }elseif(is_bool($value) || empty ($value)){
                var_dump($value);
            }else{
                print_r($value);
            }
            echo "\n";
        }
        $trace = debug_backtrace();
        $next = array_merge(
            array (
                'line' => '??',
                'file' => '[internal]',
                'class' => null,
                'function' => '[main]'
            ),$trace[0]
        );
        $path = dirname(dirname(realpath(__DIR__)));
        if (stripos($next['file'], $path) !== false){
            $next['file'] = str_replace($path, '>DOCROOT', $next['file']);
        }
        echo "\n---------------------------------debug调试结束.---------------------------------\n\n文件位置:";
        echo $next['file']."\t第".$next['line']."行.\n";
        if(in_array('debug', $args)){
            echo "\n<pre>";
            print_r($trace);
        }
        //运行时间
        echo "</pre>";
        die;
    }

    try {
        /**
         * 传入的信息
         */
        $array = array(
            "pid" => 1,"accountid" => 131785,
            "keycode" => "test_keycode", //CHECK_KEYCODE为true时，才生效，目的是为了校验约定的keycode是否一致，防止恶意访问接口。
        );

        $_info = json_encode($array); //传入值转为json
        $doAct = 'test'; //方法名

        $type = true;

        if($type){ //post方式调试
            include_once('./op/functions.php');
            $res = method::$doAct($_info);
        }else{ //soap客户端方式调试
            $soap = new SoapClient('http://127.0.0.1:80/soap/op/stdserver.php?wsdl');
            $res = $soap->doAct($doAct, $_info);
        }
        
        $_data = json_decode($res, true);
        debug($_data);
    }catch(Exction $e){
        echo print_r($e->getMessage(), true);
    }
