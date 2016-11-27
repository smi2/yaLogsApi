<?php
namespace yaLogsApi;
class ChTypeFields
{
    // copypaster https://raw.githubusercontent.com/yndx-metrika/logs_api_integration/master/configs/ch_types.json
    private static $field = [

// ------------------------ Визиты ----------------
// Последовательность действий одного посетителя на сайте (на одном счетчике).
// Визит завершен, если между действиями посетителя на сайте прошло некоторое время.
// По умолчанию — 30 минут. Вы можете указать другое время с помощью опции тайм-аут визита.

        "ym:s:counterID" => "UInt32",
        "ym:s:watchIDs" => "Array(UInt64)",
        "ym:s:dateTime" => "DateTime",
        "ym:s:dateTimeUTC" => "DateTime",
        "ym:s:isNewUser" => "UInt8",
        "ym:s:startURL" => "String",
        "ym:s:endURL" => "String",
        "ym:s:pageViews" => "Int32",
        "ym:s:visitDuration" => "UInt32",
        "ym:s:bounce" => "UInt8",
        "ym:s:ipAddress" => "String",
        "ym:s:params" => "Array(String)",

//        Goals Nested
//    (
//        ID UInt32,
//        Serial UInt32,
//        EventTime DateTime,
//        Price Int64,
//        OrderID String,
//        CurrencyID UInt32
//    ),

        "ym:s:goalsID" => "Array(UInt32)",
        "ym:s:goalsSerialNumber" => "Array(UInt32)",
        "ym:s:goalsDateTime" => "Array(DateTime)",
        "ym:s:goalsPrice" => "Array(Int64)",
        "ym:s:goalsOrder" => "Array(String)",
        "ym:s:goalsCurrency" => "Array(String)",

        "ym:s:firstPartyCookie"=>'String',
        "ym:s:lastTrafficSource" => "String",
        "ym:s:lastAdvEngine" => "String",
        "ym:s:lastReferalSource" => "String",
        "ym:s:lastSearchEngineRoot" => "String",
        "ym:s:lastSearchEngine" => "String",
        "ym:s:lastSocialNetwork" => "String",
        "ym:s:lastSocialNetworkProfile" => "String",
        "ym:s:referer" => "String",
        "ym:s:lastDirectClickOrder" => "UInt32",
        "ym:s:lastDirectBannerGroup" => "UInt32",
        "ym:s:lastDirectClickBanner" => "String",
        "ym:s:lastDirectPhraseOrCond" => "String",
        "ym:s:lastDirectPlatformType" => "String",
        "ym:s:lastDirectPlatform" => "String",
//        "ym:s:lastDirectSearchPhrase" => "String",
        "ym:s:lastDirectConditionType" => "String",
        "ym:s:lastCurrencyID" => "String",
        "ym:s:from" => "String",
        "ym:s:UTMCampaign" => "String",
        "ym:s:UTMContent" => "String",
        "ym:s:UTMMedium" => "String",
        "ym:s:UTMSource" => "String",
        "ym:s:UTMTerm" => "String",
        "ym:s:openstatAd" => "String",
        "ym:s:openstatCampaign" => "String",
        "ym:s:openstatService" => "String",
        "ym:s:openstatSource" => "String",
        "ym:s:hasGCLID" => "UInt8",
        "ym:s:regionCountry" => "String",
        "ym:s:regionCity" => "String",
        "ym:s:browserLanguage" => "String",
        "ym:s:browserCountry" => "String",
        "ym:s:clientTimeZone" => "Int16",
        "ym:s:deviceCategory" => "String",
        "ym:s:mobilePhone" => "String",
        "ym:s:mobilePhoneModel" => "String",
        "ym:s:operatingSystemRoot" => "String",
        "ym:s:operatingSystem" => "String",
        "ym:s:browser" => "String",
        "ym:s:browserMajorVersion" => "UInt16",
        "ym:s:browserMinorVersion" => "UInt16",
        "ym:s:browserEngine" => "String",
        "ym:s:browserEngineVersion1" => "UInt16",
        "ym:s:browserEngineVersion2" => "UInt16",
        "ym:s:browserEngineVersion3" => "UInt16",
        "ym:s:browserEngineVersion4" => "UInt16",
        "ym:s:cookieEnabled" => "UInt8",
        "ym:s:javascriptEnabled" => "UInt8",
        "ym:s:flashMajor" => "UInt8",
        "ym:s:flashMinor" => "UInt8",
        "ym:s:screenFormat" => "UInt16",
        "ym:s:screenColors" => "UInt8",
        "ym:s:screenOrientation" => "String",
        "ym:s:screenWidth" => "UInt16",
        "ym:s:screenHeight" => "UInt16",
        "ym:s:physicalScreenWidth" => "UInt16",
        "ym:s:physicalScreenHeight" => "UInt16",
        "ym:s:windowClientWidth" => "UInt16",
        "ym:s:windowClientHeight" => "UInt16",

        "ym:s:purchaseID" => "Array(String)",
        "ym:s:purchaseDateTime" => "Array(DateTime)",
        "ym:s:purchaseAffiliation" => "Array(String)",
        "ym:s:purchaseRevenue" => "Array(Float64)",
        "ym:s:purchaseTax" => "Array(Float64)",
        "ym:s:purchaseShipping" => "Array(Float64)",
        "ym:s:purchaseCoupon" => "Array(String)",
        "ym:s:purchaseCurrency" => "Array(String)",
        "ym:s:purchaseProductQuantity" => "Array(Int64)",

        "ym:s:productsPurchaseID" => "Array(String)",
        "ym:s:productsID" => "Array(String)",
        "ym:s:productsName" => "Array(String)",
        "ym:s:productsBrand" => "Array(String)",
        "ym:s:productsCategory" => "Array(String)",
        "ym:s:productsCategory1" => "Array(String)",
        "ym:s:productsCategory2" => "Array(String)",
        "ym:s:productsCategory3" => "Array(String)",
        "ym:s:productsCategory4" => "Array(String)",
        "ym:s:productsCategory5" => "Array(String)",
        "ym:s:productsVariant" => "Array(String)",
        "ym:s:productsPosition" => "Array(Int32)",
        "ym:s:productsPrice" => "Array(Float64)",
        "ym:s:productsCurrency" => "Array(String)",
        "ym:s:productsCoupon" => "Array(String)",
        "ym:s:productsQuantity" => "Array(Int64)",

        "ym:s:impressionsURL" => "Array(String)",
        "ym:s:impressionsDateTime" => "Array(DateTime)",
        "ym:s:impressionsProductID" => "Array(String)",
        "ym:s:impressionsProductName" => "Array(String)",
        "ym:s:impressionsProductBrand" => "Array(String)",
        "ym:s:impressionsProductCategory" => "Array(String)",
        "ym:s:impressionsProductCategory1" => "Array(String)",
        "ym:s:impressionsProductCategory2" => "Array(String)",
        "ym:s:impressionsProductCategory3" => "Array(String)",
        "ym:s:impressionsProductCategory4" => "Array(String)",
        "ym:s:impressionsProductCategory5" => "Array(String)",
        "ym:s:impressionsProductVariant" => "Array(String)",
        "ym:s:impressionsProductPrice" => "Array(Int64)",
        "ym:s:impressionsProductCurrency" => "Array(String)",
        "ym:s:impressionsProductCoupon" => "Array(String)",

        "ym:s:lastDirectClickOrderName" => "String",
        "ym:s:lastClickBannerGroupName" => "String",
        "ym:s:lastDirectClickBannerName" => "String",
        "ym:s:networkType" => "String",
        "ym:s:visitID" => "UInt64",
        "ym:s:date" => "Date",



// ------------------------------- Просмотры -----------------------------------------------------------------------------------------------------------------------------------------
// Загрузка страницы сайта при переходе посетителя на нее.

        "ym:pv:watchID" => "UInt64",
        "ym:pv:counterID" => "UInt32",
        "ym:pv:date" => "Date",
        "ym:pv:dateTime" => "DateTime",
        "ym:pv:title" => "String",
        "ym:pv:URL" => "String",
        "ym:pv:referer" => "String",
        "ym:pv:UTMCampaign" => "String",
        "ym:pv:UTMContent" => "String",
        "ym:pv:UTMMedium" => "String",
        "ym:pv:UTMSource" => "String",
        "ym:pv:UTMTerm" => "String",

        "ym:pv:browser" => "String",
        "ym:pv:browserMajorVersion" => "UInt16",
        "ym:pv:browserMinorVersion" => "UInt16",
        "ym:pv:browserCountry" => "String",
        "ym:pv:browserEngine" => "String",
        "ym:pv:browserEngineVersion1" => "UInt16",
        "ym:pv:browserEngineVersion2" => "UInt16",
        "ym:pv:browserEngineVersion3" => "UInt16",
        "ym:pv:browserEngineVersion4" => "UInt16",


        "ym:pv:browserLanguage" => "String",
        "ym:pv:clientTimeZone" => "Int16",
        "ym:pv:cookieEnabled" => "UInt8",
        "ym:pv:deviceCategory" => "String",
        "ym:pv:flashMajor" => "UInt8",
        "ym:pv:flashMinor" => "UInt8",

        "ym:pv:from" => "String",
        "ym:pv:hasGCLID" => "UInt8",
        "ym:pv:ipAddress" => "String",
        "ym:pv:javascriptEnabled" => "UInt8",
        "ym:pv:mobilePhone" => "String",
        "ym:pv:mobilePhoneModel" => "String",

        "ym:pv:openstatAd" => "String",
        "ym:pv:openstatCampaign" => "String",
        "ym:pv:openstatService" => "String",
        "ym:pv:openstatSource" => "String",

        "ym:pv:operatingSystem" => "String",
        "ym:pv:operatingSystemRoot" => "String",
        "ym:pv:physicalScreenHeight" => "UInt16",
        "ym:pv:physicalScreenWidth" => "UInt16",

        "ym:pv:regionCity" => "String",
        "ym:pv:regionCountry" => "String",

        "ym:pv:screenColors" => "UInt8",
        "ym:pv:screenFormat" => "UInt16",
        "ym:pv:screenHeight" => "UInt16",
        "ym:pv:screenOrientation" => "String",
        "ym:pv:screenWidth" => "UInt16",
        "ym:pv:windowClientHeight" => "UInt16",
        "ym:pv:windowClientWidth" => "UInt16",

        "ym:pv:params" => "Array(String)",

        "ym:pv:lastTrafficSource" => "String",
        "ym:pv:lastSearchEngine" => "String",
        "ym:pv:lastSearchEngineRoot" => "String",
        "ym:pv:lastAdvEngine" => "String",
        "ym:pv:artificial" => "UInt8",
        "ym:pv:pageCharset" => "String",
        "ym:pv:link" => "UInt8",
        "ym:pv:download" => "UInt8",
        "ym:pv:notBounce" => "UInt8",
        "ym:pv:event" => "UInt8",
        "ym:pv:lastSocialNetwork" => "String",
        "ym:pv:httpError" => "String",
        "ym:pv:firstPartyCookie"=>'String',
//        "ym:pv:clientID" => "UInt64",
        "ym:pv:networkType" => "String",
        "ym:pv:lastSocialNetworkProfile" => "String",
        "ym:pv:goalsID" => "Array(UInt32)",
        "ym:pv:shareService" => "String",
        "ym:pv:shareURL" => "String",
        "ym:pv:shareTitle" => "String",
        "ym:pv:iFrame" => "UInt8"

    ];

    public static function getAllFieldVisits()
    {
        $out=[];
        foreach (self::$field as $key=>$type)
        {
            if (stripos($key,'ym:s:')!==false)
            {
                $out[]=$key;
            }

        }
        return $out;
    }
    public static function getFieldType($name)
    {
        if (isset(self::$field[$name])) return self::$field[$name];
        return null;
    }
}