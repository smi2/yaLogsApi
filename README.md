# Выгружайте сырые данные из Метрики через Logs API


Docs:
* [Выгружайте сырые данные из Метрики через Logs API](https://yandex.ru/blog/metrika/vygruzhayte-syrye-dannye-iz-metriki-cherez-logs-api)
* [Кастомные модели атрибуции](https://nbviewer.jupyter.org/github/miptgirl/attribution_modelling/blob/master/220volt_case.ipynb)
* [Скрипт интеграция с Logs API](https://github.com/yndx-metrika/logs_api_integration)


!! Скрипт не доделан !!

Run:
```bash

./yla.sh help

./yla.sh new  --date1=2016-10-10 --date2=2016-10-20 [--config=conf.json]

./yla.sh import [--token=XZXCV] [--counter=1234]

./yla.sh cancel --requestid=12345

```




GZ:
```

printf "\x1f\x8b\x08\x00\x00\x00\x00\x00" |cat - /tmp/YAM_16750087_29_20161119_20161119_04db_58122561_part0.tsv.gz | gzip -dc > /tmp/_ERAW

```