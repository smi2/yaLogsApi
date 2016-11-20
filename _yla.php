<?php
include_once 'libs/include.php';
// ----------------------------------------



// ----------------------------------------
try
{
    \Shell::name("Yandex Logs Api to Clickhouse ");
//    \Shell::alertMail('');
    \Shell::setPathLog('/tmp');
    // -------------------------------------------------------
    \Shell::maxExecutionMinutes(60*10);//10 mins max
    // -------------------------------------------------------
    \Shell::run(
        new YLAActions()
    );
    // -------------------------------------------------------
    exit(0);
}
catch (Exception $E)
{
    Shell::exception($E);
    exit(2);
}