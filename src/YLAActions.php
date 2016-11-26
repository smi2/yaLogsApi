<?php
class YLAActions
{
    private $config_file='config.php';
    private $token=false;
    private $counter=false;
    private $config=[];
    private $_cl;

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

    private function clearRequest(\yaLogsApi\logRequest $request)
    {
        $n=new \yaLogsApi\Connector($this->counter,$this->token);
        $this->msg("Try cancel, request_id = ".$request->getRequestId());
        $n->clean($request);
        $n->cancel($request);
        $request=$n->info($request);
        $this->msg("Request_Id:".$request->getRequestId()."\t in status=".$request->getStatus(),\Shell::bold);
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
                    $this->clearRequest($request);
                }

            }
        }
        return true;
    }

    protected function getTableName(\yaLogsApi\logRequest $request)
    {
        return $request->getSource();
    }
    protected function getTableColumns(\yaLogsApi\logRequest $request)
    {
        $fields=$request->getFields();
        $cols=[];
        foreach ($fields as $col)
        {
            $type=\yaLogsApi\ChTypeFields::getFieldType($col);

            $colName=ucwords(explode(":",$col)[2]);
            $cols[$colName]=$type;
        }
        return $cols;
    }
    protected function ch_createTable(\yaLogsApi\logRequest $request)
    {
        $typeSource=$this->getTableName($request);
        $cols=$this->getTableColumns($request);






        echo "DROP TABLE IF EXISTS $typeSource\n";
        echo "CREATE TABLE $typeSource (\n";
        echo implode(",\n",$cols);
        echo ") ENGINE=StripeLog\n";
        return true;
    }

    /**
     * @return \ClickHouseDB\Client
     */
    private function clickhouse()
    {
        if (!$this->_cl)
        {

            $this->_cl=new ClickHouseDB\Client(['host'=>'192.168.1.20','username'=>'default','password'=>'','port'=>'8123']);
            $this->_cl->ping();

        }
        return $this->_cl;
    }
    private function streamDownload(\yaLogsApi\Connector $n,\yaLogsApi\logRequest $request,$partsNumber,$nogzip)
    {
        $start_time=microtime(true);
        // получаем имя таблицы

        $tableName=$this->getTableName($request);
        $columns_array=array_keys($this->getTableColumns($request));



        $this->msg("Download.... part = $partsNumber into `$tableName`, size ".$request->getPartSize($partsNumber).' gzip='.($nogzip?'NO':'yes'));

        $encode=($nogzip?'':'gzip');

        // fopen для скачивания
        $resourceRead=$n->streamPart($request,$partsNumber,$encode);
        // включить сжатие
        $this->clickhouse()->enableHttpCompression(true);
        $this->clickhouse()->setTimeout(12341234);




        // поток на вставку данных
        $stream=$this->clickhouse()->insertBatchStream($tableName,$columns_array,'TabSeparatedWithNames');

        // класс вставки из потока в поток
        $si=new ClickHouseDB\Transport\StreamInsert($resourceRead,$stream,$this->clickhouse()->transport()->getCurler());
        // исполнить запрос с жатием
        $state=$si->insert($encode);

        // инфо
        $size_upload=$state->info_upload();
        $this->msg('done, stream part ,use time '.round(microtime(true)-$start_time,1),[Shell::bold]);
        $this->msg(json_encode($state->info_upload()));
        return $size_upload;
    }

    /**
     * Загрузить данные
     *
     * @return bool
     */
    public function downloadCommand($nogzip=false)
    {
        $this->init();
        $n=new \yaLogsApi\Connector($this->counter,$this->token);

        $listRequests=$n->getList();
        if ($listRequests)
        {
            foreach ($listRequests as $request)
            {


                $request=$n->info($request);
                $this->msg("Request_Id:".$request->getRequestId()."\t in status=".$request->getStatus().' count of parts='.sizeof($request->getParts()),\Shell::bold);

                if ($request->isProcessed())
                {
                    foreach ($request->getPartsNumbers() as $partsNumber)
                    {
                        $size_upoload=$this->streamDownload($n,$request,$partsNumber,$nogzip);
                        if ($size_upoload)
                        {

                        }
                    }
                    // if success all parts
//                    $this->clearRequest($request);
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