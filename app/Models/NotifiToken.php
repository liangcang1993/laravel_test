<?php

namespace App\Models;

use App\Services\HttpService;
use Aws\Sns\SnsClient;
use Illuminate\Database\Eloquent\SoftDeletes;
use Redis;

class NotifiToken extends BaseModel
{
    protected $table = 'notifi_token';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function addToAws()
    {
        set_time_limit(0);
        $config = array(
            'region' => env('PUSH_REGION'),
            'credentials' => [
                'key'    => env('S3_KEY') ,
                'secret' => env('S3_SECRET')
            ],
            'version' => 'latest',
        );
        if(90909!=$this->version){
            $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS/jigsaw';
//            $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS_SANDBOX/jigsaw_dev';

        }else{
            $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS_SANDBOX/jigsaw_dev';
        }
//    	var_dump($this->Awstoken);
        $client =  new SnsClient($config);
        $result = $client->createPlatformEndpoint(array(
            'PlatformApplicationArn' => $arn,
            'Token' => $this->token,
            'CustomUserData' => 'string',
        ));
        $this->arn = $result['EndpointArn'];
//        echo $this->arn;
        $this->save();
    }
}