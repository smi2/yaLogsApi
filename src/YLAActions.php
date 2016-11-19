<?php
class YLAActions
{
    private $config_file='config.php';
    private $token=false;
    private $counter=false;
    private $config=[];

    public function setToken($token)
    {
        \Shell::warning("Set token:".$token);
        $this->token=$token;
    }

    public function setCounter($counter)
    {
        \Shell::warning("Set counter_id:".$counter);
        $this->counter=$counter;
    }

    public function setConfig($config)
    {
        \Shell::warning("Set config file:".$config);
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

        if (!empty($config['token'])) $this->token=$config['token'];
        if (!empty($config['counter_id'])) $this->counter=$config['counter_id'];


        if (! $this->token) throw new Exception('Not set token, use --token=XXX or config');
        if (! $this->counter) throw new Exception('Not set counter_id, use --counter=XXX or config');
        return true;
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
        return true;
    }



}