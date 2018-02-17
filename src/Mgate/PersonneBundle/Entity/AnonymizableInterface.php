<?php
/**
 * Created by PhpStorm.
 * User: stoakes
 * Date: 17/02/18
 * Time: 11:11
 */

namespace Mgate\PersonneBundle\Entity;


interface AnonymizableInterface
{
    /** Remove all personnal data */
    public function anonymize();
}
