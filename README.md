# Выгружайте сырые данные из Метрики через Logs API


Docs:
* [Выгружайте сырые данные из Метрики через Logs API](https://yandex.ru/blog/metrika/vygruzhayte-syrye-dannye-iz-metriki-cherez-logs-api)
* [Кастомные модели атрибуции](https://nbviewer.jupyter.org/github/miptgirl/attribution_modelling/blob/master/220volt_case.ipynb)
* [Скрипт интеграция с Logs API](https://github.com/yndx-metrika/logs_api_integration)

Run:
```bash

./yla.sh help

./yla.sh create --config=conf.json

./yla.sh import --config=conf.json --counters=1234,4567,7891

```




steam 

```php


// Open two file handles.
$in = fopen('test.txt.bz2', 'rb');
$out = fopen('test-uppercase.txt', 'wb');

// Add a decode filter to the first.
stream_filter_prepend($in, 'bzip2.decompress', STREAM_FILTER_READ);

// Now copy. All of the filters are applied here.
stream_copy_to_stream($in, $out);

// Clean up.
fclose($in);
fclose($out);

``` 
