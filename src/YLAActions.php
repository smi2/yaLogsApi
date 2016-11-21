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
        $need_keys=['visits_fields','hits_fields','clickhouse'];

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


        $evaluate=$n->evaluate($this->config['hits_fields'],'hits',date('Y-m-d',$date1),date('Y-m-d',$date2));
        if ($evaluate)
        {
            $this->msg("evaluate = ".$evaluate);
            $n->makeNew($this->config['hits_fields'],'hits',date('Y-m-d',$date1),date('Y-m-d',$date2));
        }

        return true;
    }

    /**
     * Отменить запрос
     *
     * @param $requestid string id-запроса
     * @return bool
     */
    public function cancelCommand($requestid)
    {

        $this->init();
        $n=new \yaLogsApi\Connector($this->counter,$this->token);


        $listRequests=$n->getList();
        if ($listRequests) {
            foreach ($listRequests as $request) {
                if ($request->getRequestId()==$requestid)
                {
                    $this->msg("Try cancel, request_id = ".$request->getRequestId());

                    $n->cancel($request);
                }

            }
        }
        return true;
    }
    /**
     * Загрузить данные
     *
     * @return bool
     */
    public function importCommand()
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
                $this->msg("Request_Id:".$request->getRequestId()."\t in status=".$request->getStatus(),\Shell::bold);

                if ($request->isProcessed())
                {
                    $this->msg("Download....");
                    $n->download($request);
                }
            }
        }

        return true;
    }



}