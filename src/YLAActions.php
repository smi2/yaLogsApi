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
    public function createCommand()
    {
        $this->init();
        return true;
    }


    /**
     * Удалить данные
     *
     * @return bool
     */
    public function dropCommand()
    {
        $this->init();
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
                $this->msg("Request_Id:".$request->getRequestId()."\t in status=".$request->getStatus());

                $request=$n->info($request);
                $this->msg("Request_Id:".$request->getRequestId()."\t in status=".$request->getStatus(),\Shell::bold);


//                $this->msg("Try cancel");$n->cancel($request);

                if ($request->isProcessed())
                {
                    $this->msg("Download....");
                    $n->download($request);
                }
            }
        }
        else
        {
            if ($n->evaluate($this->config['hits_fields'],'hits',date('Y-m-d',strtotime('-1 day')),date('Y-m-d',strtotime('-1 day'))))
            {

                $n->makeNew($this->config['hits_fields'],'hits',date('Y-m-d',strtotime('-1 day')),date('Y-m-d',strtotime('-1 day')));
            }
        }

        return true;
    }



}