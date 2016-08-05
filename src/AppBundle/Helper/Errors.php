<?php

namespace AppBundle\Helper;

abstract class Errors
{
    const Success = '000';
    const Missing_POST_Data = '001';

    /** @Deprecated */
    const DB_Connection_Error = '002';

    const Bad_Request = '003';
    const Invalid_JSON = '004';

    /**
     * Thrown when an insert error occurs
     */
    const SQL_Insert_Fail = '005';

    /**
     * Throw when we could not find the export bag
     */
    const SQL_Select_Fail = '006';
}
