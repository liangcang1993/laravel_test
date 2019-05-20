<?php

namespace App\Models;

use Aws\Sns\SnsClient;
use App\Console\Commands\RunEveryDay;
use Redis;
use Log;
use FileTool;
use Config;

use DB;
use App\Services\HttpService;
use Session;
use Aws\S3\S3Client;
use FacebookAds\Object\Business;
use FacebookAds\Object\ProductCatalog;
use FacebookAds\Object\ProductFeed;
use FacebookAds\Object\ProductSet;
use FacebookAds\Object\ExternalEventSource;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\AdCreative;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdUser;
use FacebookAds\Object\AdPreview;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Fields\AdAccountFields;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Values\CampaignObjectiveValues;
use FacebookAds\Object\Values\AdSetBillingEventValues;
use FacebookAds\Object\Values\AdSetOptimizationGoalValues;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\Fields\AdImageFields;
use FacebookAds\Object\Fields\TargetingFields;
use FacebookAds\Object\AdCreativeLinkData;
use FacebookAds\Object\Fields\AdCreativeLinkDataFields;
use FacebookAds\Object\AdCreativeObjectStorySpec;
use FacebookAds\Object\Fields\AdCreativeObjectStorySpecFields;
use FacebookAds\Object\Fields\AdCreativeFields;
use FacebookAds\Object\Fields\AdFields;
use FacebookAds\Object\Values\AdCreativeCallToActionTypeValues;

use FacebookAds\Object\AdCreativeVideoData;
use FacebookAds\Object\Fields\AdCreativeVideoDataFields;
use FacebookAds\Object\AdVideo;
use FacebookAds\Object\Fields\AdVideoFields;
use FacebookAds\Object\TargetingSearch;
use FacebookAds\Object\Search\TargetingSearchTypes;


use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;

use App\Console\Commands\RunManly;

use Google\AdsApi\Common\AdsServiceDescriptor;

use Google\AdsApi\AdWords\Reporting\v201802\ReportDownloader;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\Reporting\v201802\DownloadFormat;
use Google\AdsApi\AdWords\ReportSettingsBuilder;

use \App\Models\AppleEventReport;
use \App\Models\MaterialProduct;
use \App\Models\Material;
use \App\Models\KeywordsRank;
use \App\Models\TotleStatement;
use \App\Models\Banner;

use \App\Models\AppleIncomeStatistic;

class TestModel extends BaseModel
{
    public static function test()
    {
        $start_date = '2018-06-19';
        $end_date = '2018-07-19';
        $start_date = strtotime($start_date);
        $end_date = strtotime($end_date);
//        for ($start = $start_date;  $start <= $end_date; $start += 86400) {
//            $date = date('Y-m-d', $start);
//            echo $date."-->>";
//            //date("Y-m-d",strtotime($date)-$start_date);
//            TotleStatement::getfacebookdate("manly",$date);
//            TotleStatement::getfacebookdate("photable",$date);
//            TotleStatement::getfacebookdate("android",$date);
//            TotleStatement::getfacebookdate("video",$date);
//            TotleStatement::getfacebookdate("facey",$date);
//            TotleStatement::getfacebookdate("girly",$date);
//            TotleStatement::getfacebookdate("heyoo",$date);
//        }
        // AppleIncomeStatistic::incomeStatistic('2018-05-15','photable');
        // KeywordsRank::runKeywordsRank('photable');
        // AppleIncomeStatistic::incomeStatistic('2018-05-10','poto');
        // AppleIncomeStatistic::incomeStatistic('2018-05-11','poto');
        // AppleIncomeStatistic::incomeStatistic('2018-05-13','poto');

        // AppleIncomeStatistic::incomeStatistic('2018-05-10','photable');
        // AppleIncomeStatistic::incomeStatistic('2018-05-11','photable');
        // AppleIncomeStatistic::incomeStatistic('2018-05-13','photable');

        // AppleIncomeStatistic::incomeStatistic('2018-05-10','manly');
        // AppleIncomeStatistic::incomeStatistic('2018-05-11','manly');
        // AppleIncomeStatistic::incomeStatistic('2018-05-13','manly');

        // $end_date = '2018-05-10';
        // MaterialPaidStatistic::run($end_date);
        // ManlyMaterialPaidStatistic::run($end_date);

        //初始化开始时间
        // RunManly::paidStatistic();
//      die();
//      $start_date = '2018-05-05';
//      $end_date = '2018-05-14';
//      $start_date = strtotime($start_date);
//      $end_date = strtotime($end_date);
//      for ($start = $start_date;  $start <= $end_date; $start += 86400) {
//          $date = date('Y-m-d', $start);
//          echo "{$date} 开始执行:\n";
//          ManlyMaterialPaidStatistic::run($date);
//      }
//
//    die();

        // poto
        //   event:5.1
        //   event:5.2

        //   renew:5.1 5.2
        // manly
        //   留存率测试 5.2
        //   event 5.2
        //   country 5.2
        //   renew 5.2
        // $date = "2018-05-07";
        //     AppleIncomeStatistic::incomeStatistic($date,'poto');
        //     AppleIncomeStatistic::incomeStatistic($date,'manly');
        //     AppleIncomeStatistic::incomeStatistic($date,'photable');

        // die();


        // $date = "2018-04-30";
        // // AppleEventReport::renew($date, 'manly');
        // // AppleEventReport::eventStatistic($date, 'manly');

        // AppleEventReport::renew($date, 'poto');
        // AppleEventReport::eventStatistic($date, 'poto');

        // AppleEventReport::renew($date, 'photable');
        // AppleEventReport::eventStatistic($date, 'photable');

        // die();

        //   AppleEventReport::payrate('2018-04-30', 'manly');


        //   AppleEventReport::payrate_test('2018-04-27', 'manly');
        //   AppleEventReport::payrate_test('2018-04-28', 'manly');
        // AppleEventReport::payrate('2018-05-01', 'manly');
        // AppleEventReport::payrate_test('2018-05-01', 'manly');


        // die();

        // AppleEventReport::eventStatistic('2018-04-26','poto');
        // AppleEventReport::eventStatistic('2018-04-27','poto');
        // AppleEventReport::eventStatistic('2018-04-28','poto');
        // AppleEventReport::eventStatistic('2018-04-29','poto');
        // AppleEventReport::eventStatistic('2018-04-25','poto');
        // AppleEventReport::eventStatistic('2018-04-25','photable');

        // AppleEventReport::payrate('2018-04-25', 'manly');
        // AppleEventReport::payrate('2018-04-25', 'poto');
        // AppleEventReport::payrate('2018-04-25', 'photable');

// die();

        // set_time_limit(0);

        // // AppleEventReport::renew('2018-04-25', 'manly');

        // echo "Here";
        // // $app = 'photable';
        // // $startDate = '2017-04-05';

        // $app = 'poto';
        // $startDate = '2016-12-06';

        // // $app = 'manly';
        // // $startDate = '2017-08-15';
        // // photable,2017-04-05
        // // poto,2016-12-06
        // // manly, 2017-08-15
        // if(empty($app)){
        //     die();
        // }
        // for($i = 0;;$i++){
        //     $day = date('Y-m-d',strtotime($startDate .'+ '.$i.' day'));
        //     // AppleEventReport::payrate_test($day,$app);
        //     // AppleEventReport::payrate($day,$app);
        //     // AppleIncomeStatistic::incomeStatistic($day,$app);
        //     AppleEventReport::countryStatistic($day,$app);
        //     echo $day."<br>";
        //     if($day == '2018-05-07'){
        //         break;
        //     }
        // }

//      die();
        // self::changeAPP();

        // $model = AppStatistic::where('date','2018-04-17')->where('app', 'photable')->first();
        // $data = json_decode($model->app_payrate_json);


        //   $countrys = Country::orderBy('id','desc')->get();
        //   foreach ($countrys as $key => $country) {
        //       $country = $country->name;
        //       $query = AppleEventReport::where('Event Date', $date)->whereIn('Event',['Renew','Paid Subscription from Free Trial']);
        //       $country != 'all' && $query = $query->where('Country', $country);
        //       $query->where('App Apple ID', self::getAppleId($app));

        //       $list = $query->get();

        //       foreach ($list as $key => $l) {
        //           $start_date_str = 'Original Start Date';

        //           $start_date = date('Y-m-d',strtotime($l->$start_date_str));

        //           $day = round((strtotime($date) - strtotime($start_date))/(60*60*24));

        //           if(array_key_exists($start_date, $datas)){
        //               echo $day .',';
        //               $key = 'payrate' . $day;
        //                // var_dump($datas[$start_date],$country);
        //               if(!array_key_exists($key, $datas[$start_date]->$country)){
        //                   $datas[$start_date]->$country->$key  = 0;
        //               }
        //               $datas[$start_date]->$country->$key += $l->Quantity;
        //           }
        //       }
        //   }
        //   foreach ($datas as $date => $data) {
        //       $model = AppStatistic::where('date', '=', $date)->where('app', $app)->first();
        //       if($model){
        //           $model->app_payrate_json = json_encode($data);
        //           $model->save();
        //       }
        //   }

        // phpinfo();

//           set_time_limit(0);
//           $config = array(
//                     'region' => env('S3_REGION'),
//                     'credentials' => [
//                         'key'    => env('S3_KEY') ,
//                         'secret' => env('S3_SECRET')
//                     ],
//                     'version' => 'latest',
//                 );
//             $client =  new SnsClient($config);
//             $result = $client->createPlatformEndpoint(array(
//         // PlatformApplicationArn is required
//             'PlatformApplicationArn' => 'arn:aws:sns:us-east-1:561020269087:app/APNS/jigsaw',
//             // Token is required
//             'Token' => '4ff633d334ef8c6d28feb03bc8563572fdf3d18f4c4bb36d06979fa8604cfa94',
//             'CustomUserData' => 'string',
//           ));
//           $arn = $result['EndpointArn'];
//           // dd($result['EndpointArn']);
//           var_dump($result['EndpointArn'],11);
//         return;

//      $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS_SANDBOX/manly_test';
        $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS/facey2';
        $test_arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS_SANDBOX/faceytest';
        $test_arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS/jigsaw';
        $test_arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS_SANDBOX/jigsaw_dev';

//      $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS_SANDBOX/faceydev';
//        $targetarn = 'arn:aws:sns:us-east-1:561020269087:endpoint/APNS_SANDBOX/faceytest/7fb10d88-11d9-3645-bfc4-9bdd22b32251';
        $targetarn = 'arn:aws:sns:us-east-1:561020269087:endpoint/APNS_SANDBOX/jigsaw_dev/43960761-d942-3c25-a2a1-80e3c06605a3';
        $config = array(
            'region' => env('S3_REGION'),
            'credentials' => [
                'key' => env('S3_KEY'),
                'secret' => env('S3_SECRET')
            ],
            'version' => 'latest',
        );
        $client = new SnsClient($config);
        try {
            $r = $client->publish(array(
//                    'PlatformApplicationArn' => ,
                'PlatformApplicationArn' => $test_arn,
                // Token is required
                'MessageStructure' => 'json',
                'Message' => json_encode(array(

                'APNS' => json_encode(array(
                    'aps' => array(
                        "alert"=>'faceytest messageId😄🍓🍌🍇🍉🎉🦋🎀❕🔚💯🐶😫😠🎺🎈',
                        "sound"=>"default",
                        "messageid"=>(string)1365,
                        "badge"=>1,
                        "type"=>'home',
                        "material"=>json_encode([]),
                        "mutable-content" =>1,
                        "pushType"=>'operations',
                    ),
            )),
                'APNS_SANDBOX' => json_encode(array(
                    'aps' => array(
                        "alert"=>'faceytest messageId😄🍓🍌🍇🍉🎉🦋🎀❕🔚💯🐶😫😠🎺🎈',
                        "sound"=>"default",
                        "messageid"=>(string)1365,
                        "badge"=>1,
                        "type"=>'home',
                        "material"=>json_encode([]),
                        "mutable-content" =>1,
                        "pushType"=>'operations',
                    ),
            ))
                )),
//                   'TargetArn' => 'arn:aws:sns:us-east-1:561020269087:endpoint/APNS/facey/6c34bef6-5366-3fae-8428-60f23e04f66f',
                'TargetArn' => $targetarn,
            ));
            echo 1;
            var_dump($r);
            return true;
        } catch (SnsException $e) {
            // echo 'Message: ' .$e->getMessage();
//                if($this->token_status != -1){
//                    $this->token_status = -1;
//                    $this->save();
//                }
            echo 2;

            var_dump($e);
            return false;
        }


        $adjustlinkWithGroupTrackers = "http://api.adjust.com/kpis/v1/q14493u8k9og/trackers/mk2gyl?user_token=qMxXBVVyw-xqAVTo5qqA&start_date=2018-03-11&end_date=2018-04-10&countries=de,gb&grouping=trackers";
        $adjustDataWithGroupTrackers = file_get_contents($adjustlinkWithGroupTrackers);
        dd($adjustDataWithGroupTrackers);

        $oAuth2Credential = (new OAuth2TokenBuilder())->fromFile()->build();
// dd((new OAuth2TokenBuilder())->fromFile());
        // See: AdWordsSessionBuilder for setting a client customer ID that is
        // different from that specified in your adsapi_php.ini file.
        // Construct an API session configured from a properties file and the
        // OAuth2 credentials above.
        $session = (new AdWordsSessionBuilder())->fromFile()->withOAuth2Credential($oAuth2Credential)->build();
// dd((new AdWordsSessionBuilder())->fromFile());
        self::runExample($session, DownloadFormat::CSV);


    }

    public static function testByMeng()
    {
        echo "hahah jiuzhei";
    }

    public static function runExample(AdWordsSession $session, $reportFormat)
    {
        // Create report query to get the data for last 7 days.
        $reportQuery = "SELECT AccountDescriptiveName,CampaignId,Clicks FROM CAMPAIGN_PERFORMANCE_REPORT WHERE CampaignId = '1054272162'";

        // Download report as a string.
        $reportDownloader = new ReportDownloader($session);
        // Optional: If you need to adjust report settings just for this one
        // request, you can create and supply the settings override here. Otherwise,
        // default values from the configuration file (adsapi_php.ini) are used.
        $reportSettingsOverride = (new ReportSettingsBuilder())->includeZeroImpressions(false)->build();
        $reportDownloadResult = $reportDownloader->downloadReportWithAwql(
            $reportQuery,
            $reportFormat,
            $reportSettingsOverride
        );
        print "Report was downloaded and printed below:\n";
        print $reportDownloadResult->getAsString();


        // var_dump($reportDownloadResult);


    }

    public static function changeAPP()
    {
//      echo "test::changeApp";
//      die();
        $query = NotifiToken::where('app', 'manly');
        $query->where('id', '>', 20596227);
//        $query->where('timezone','<>',20);
        $query->chunk(1000, function ($tokens) {
            foreach ($tokens as $token) {
                var_dump($token->id . ',');
//                $pay = PayAction::where('idfa',$token->udid)->first();
////                var_dump($pay->id);
//                if ($pay){
//                    $token->is_vip = 1;
//                }else{
//                    $token->is_vip = 0;
//                }
                $token->addToAws();
                $token->save();
            }
        });
    }

    public static function copyDate()
    {
        $data = Material::where('app', 'facey')->where('type', 'makeup')->get()->toArray();
        $ins = [];
        foreach ($data as $k => $v) {
            $res = [];
            $res = $v;
            $res['type'] = 'MakeUpV2';
            unset($res['id']);
            $insert = Material::create($res);
        }
        dd($insert);
    }

    public static function runOldDataZipEncrpyt()
    {
        $query = MaterialProduct::where('id', '<', '20');
        $query->chunk(1000, function ($materials) {

            foreach ($materials as $k=>$v){
                if (!empty($v->binary_data)){
                    $url = MaterialProduct::getRealUrl($v->binary_data);
                }else{
                    $url = MaterialProduct::getRealUrl($v->binary_data_new);
                }
                if (''!=$url){
                    if ('postcard'!=$v->type){
                        $file = file_get_contents($url);
                        $model = new MaterialProduct();
                        $v->en_material = $model->getZipEncrypt($file);
                        $v->save();
                    }else{
                        $file = file_get_contents($url);
                        $model = new Postcard();
                        $v->en_material = $model->getZipEncrypt($file);
                        $v->save();
                    }
                }
            }
        });
    }

    public static function runOldPostcard()
    {
        $query = MaterialProduct::where('type','postcard')->where('id','>','517');
        $query->chunk(100, function ($materials) {

            foreach ($materials as $key=>$value)
            {
                if (empty($value->id)) 
                {
                    continue;
                }
                Material::fixData($value->id);
            }
        });

    }
}





























