<?php
namespace Bluefrg;

/**
 * @param $iRetries
 * @param callable $funcCallable
 * @param callable $funcFinally
 * @return mixed
 * @throws \Exception
 */
function retry($iRetries, callable $funcCallable, callable $funcFinally = null) {
    is_int($iRetries) ?: trigger_error('iRetries must be an integer', E_USER_ERROR);
    ($iRetries >= 0) ?: trigger_error('iRetries cannot be less than zero', E_USER_ERROR);

    do {
        try {
            return $funcCallable();
        }
        catch (\Exception $oEx) {
            if (! --$iRetries) {
                throw $oEx;
            }
        }
        finally {
            if ( $funcFinally ) {
                $funcFinally();
            }
        }
    } while (1);
}