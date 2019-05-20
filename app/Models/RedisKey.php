<?php

namespace App\Models;
 

class RedisKey 
{ 
    const EXPIRE_TIME         = 8600000;
    const RELATE_EXPIRE_TIME  = 8600000;

    // const RUN_TIME  = 'runTime3:';
    // const HOME_RUN_TIME  = 'homeRunTime3:';



    const USER = 'user:';
    const RUN_TIME  = 'runTime8:';
    const HOME_RUN_TIME  = 'homeRunTime7:';


    const MATERIAL_USED_NUM = 'materialUsedNum:';

    const ERROR = 'error';

    const USER_STATISTC = 'userStatistic:';

    const MATERIAL_STATISTC = 'materialStatistic:';

    const CHINA_S3 = 'chinaS3';

    const USER_INSTALL = 'USER_INSTALL';

    const USER_PAY = 'USER_PAY';

    const USER_PAY_YEAR = 'USER_PAY_YEAR';

    const DATE_MATERIALS = 'DATE_MATERIALS';

    const MANLY_MATERIAL_VERSION = 'manlyMaterialVersion';

    const VIDEO_MATERIAL_VERSION = 'videoMaterialVersion';

    const COUNTRY_LIMITED_FREE = 'countryLimitedFree';

    const USER_PAY_STATISTICS = 'userPayStatistics';

    const RECOMMEND_STATISTICS = 'recommendStatistics';

    const USER_STATISTICS = 'userStatistics:';

    const CONFIG = 'config';

    const NOTIFI_STATISTC = 'notifStatistic:';

    const NOTIFI_MSG = 'notifiMsg:';

    const INSTALL_DAY = 'install_day:';

    const UPDATE_USER = 'update_user';
}
