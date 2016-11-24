<?php
class YLAActions
{
    private $config_file='config.php';
    private $token=false;
    private $counter=false;
    private $config=[];

    public function setToken($token)
    {
        \Shell::info("Set token:".$token);
        $this->token=$token;
    }

    public function setCounter($counter)
    {
        \Shell::info("Set counter_id:".$counter);
        $this->counter=$counter;
    }

    public function setConfig($config)
    {
        \Shell::info("Set config file:".$config);
        $this->config_file=$config;
    }


    private function init()
    {
        if (!is_file($this->config_file)) throw new Exception('no config file:'.$this->config_file);

        $config=include_once $this->config_file;
        if (!is_array($config))
        {
            throw new Exception('bad content in config file,need return array');
        }

        //
        $need_keys=['visits','hits','clickhouse'];

        foreach ($need_keys as $key)
        {
            if (empty($config[$key]))
            {
                throw new Exception('Not set key:'.$key.' in config');
            }
            $this->config[$key]=$config[$key];
        }

        if (! $this->token && !empty($config['token'])) $this->token=$config['token'];
        if (!$this->counter && !empty($config['counter_id'])) $this->counter=$config['counter_id'];


        if (! $this->token) throw new Exception('Not set token, use --token=XXX or config');
        if (! $this->counter) throw new Exception('Not set counter_id, use --counter=XXX or config');

        $this->msg("Use counter : ".$this->counter);
        return true;
    }

    public function msg($message,$color=[])
    {
        \Shell::msg($message,$color);
    }

    /**
     * Создать таблицы
     *
     * @return bool
     */
    public function dbcreateCommand()
    {
        $this->init();


        foreach ($this->config['hits'] as $col)
        {
            $type=\yaLogsApi\ChTypeFields::getFieldType($col);

            $colName=ucwords(explode(":",$col)[2]);
            $cols[]="\t$colName \t $type";

        }
        echo "CREATE TABLE visits_fields (\n";
        echo implode(",\n",$cols);
        echo ") ENGINE=StripeLog\n";
        return true;
    }


    /**
     * Удалить данные
     *
     * @return bool
     */
    public function dbdropCommand()
    {
        $this->init();
        return true;
    }


    /**
     * Создвть запрос, даты
     *
     * @param $date1 string Дата-С
     * @param $date2 string Дата-По
     * @return bool
     */
    public function newCommand($date1,$date2)
    {
        $date1=strtotime($date1);
        $date2=strtotime($date2);

        $this->msg("Create request");
        $this->msg("\tDate 1\t:".date('Y-m-d',$date1));
        $this->msg("\tDate 2\t:".date('Y-m-d',$date2));


        $this->init();
        $n=new \yaLogsApi\Connector($this->counter,$this->token);


        $ishist=true;


        if ($ishist)
        {
            $config_key='hits';
        }
        else
        {
            $config_key='visits';
        }
        $evaluate=$n->evaluate($this->config[$config_key],$config_key,date('Y-m-d',$date1),date('Y-m-d',$date2));
        var_dump($evaluate);
        if ($evaluate)
        {
            $this->msg("evaluate = ".$evaluate);
            $n->makeNew($this->config[$config_key],$config_key,date('Y-m-d',$date1),date('Y-m-d',$date2));
        }

        return true;
    }

    /**
     * Отменить запрос
     *
     * @param $requestid string id-запроса
     * @return bool
     */
    public function dropCommand($requestid=0,$all=false)
    {


        $this->init();
        $n=new \yaLogsApi\Connector($this->counter,$this->token);


        $listRequests=$n->getList();
        if ($listRequests) {
            foreach ($listRequests as $request) {
                if ($request->getRequestId()==$requestid || $all)
                {
                    $this->msg("Try cancel, request_id = ".$request->getRequestId());

                    $n->clean($request);
                    $n->cancel($request);


                    $request=$n->info($request);
                    $this->msg("Request_Id:".$request->getRequestId()."\t in status=".$request->getStatus(),\Shell::bold);
                }

            }
        }
        return true;
    }

    private function streamDownload(\yaLogsApi\Connector $n,\yaLogsApi\logRequest $request,$partsNumber,$gzip)
    {
        $start_time=microtime(true);
        $this->msg("Download.... part = $partsNumber , size ".$request->getPartSize($partsNumber).' GZ='.($gzip?'true':'false'));


//        $fn=$n->downloadPart($request,$partsNumber,$gzip);
        $this->msg('done, stream part ,use time '.round(microtime(true)-$start_time,1),[Shell::bold]);

    }

    /**
     * Загрузить данные
     *
     * @return bool
     */
    public function downloadCommand($gzip=false)
    {
        $this->init();
        $n=new \yaLogsApi\Connector($this->counter,$this->token);

        $listRequests=$n->getList();
        if ($listRequests)
        {
            foreach ($listRequests as $request)
            {
                $this->msg("Request_Id:".$request->getRequestId()."\t in date1=".$request->getDate1().' '.$request->getDate2());

                $request=$n->info($request);
                $this->msg("Request_Id:".$request->getRequestId()."\t in status=".$request->getStatus().' ',\Shell::bold);

                if ($request->isProcessed())
                {
                    foreach ($request->getPartsNumbers() as $partsNumber)
                    {
                        $this->streamDownload($n,$request,$partsNumber,$gzip);
                    }
                    break;
                }

            }
        }

        return true;
    }


    public function sendCommand()
    {
        $file_name='/tmp/YAM_16750087_29_20161119_20161119_04db_58122561_part0.tsv.deflated';
        $n=new ClickHouseDB\Client(['host'=>'192.168.1.20','username'=>'default','password'=>'','port'=>'8123']);
        print_r($n->select('SELECT * FROM hits_fields LIMIT 4')->rows());
        return true;
    }



}