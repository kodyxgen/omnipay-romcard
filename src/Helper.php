<?php

namespace ByTIC\Omnipay\Romcard;

/**
 * Class Helper
 * @package ByTIC\Omnipay\Romcard
 */
class Helper
{
    /**
     * Action Response Posible values:
     *
     * 0 - tranzactie aprobata
     * 1 - tranzactie duplicata
     * 2 - tranzatie respinsa
     * 3 - eraore de procesare
     */
    const ACTION_APPROVED = '0';
    const ACTION_DUPLICATE = '1';
    const ACTION_REJECTED = '2';
    const ACTION_ERROR = '3';

    const TRTTYPE_PREAUTH = 0;
    const TRTTYPE_CAPTURE = 21;
    const TRTTYPE_VOID = 24;

    const TRTTYPE_PARTIAL_REFUND = 25;
    const TRTTYPE_VOID_FRAUD = 26; //anulare pe motiv de frauda

    /**
     * @param array $params
     * @param string $encryptionKey
     * @return string
     */
    public static function generateSignHash(array $params, string $encryptionKey)
    {

        $res = '';
        foreach ($params as $_key => $_value) {
            if (is_null($_value)) {
                $res .= '-';
            } else {
                $res .= strlen($_value) . $_value;
            }
        }
        return strtoupper(hash_hmac('sha1', $res, pack('H*', $encryptionKey)));
    }

    public static function orderedResponse($params, $transactionType)
    {
        $result = [];

        //return empty result if TRTYPE is not set
        if (!isset($transactionType)) {
            return $result;
        }

        $fields = static::getResponseTypeParams($transactionType);

        foreach ($fields as $_field) {
            if (!isset($params[$_field])) {
                continue;
            }

            $result[$_field] = $params[$_field];
        }
        return $result;
    }

    public static function getResponseTypeParams($transactionType)
    {
        switch ($transactionType) {
            case self::TRTTYPE_PREAUTH:
                return [
                    'TERMINAL',
                    'TRTYPE',
                    'ORDER',
                    'AMOUNT',
                    'CURRENCY',
                    'DESC',
                    'ACTION',
                    'RC',
                    'MESSAGE',
                    'RRN',
                    'INT_REF',
                    'APPROVAL',
                    'TIMESTAMP',
                    'NONCE'
                ];
                break;
            case self::TRTTYPE_CAPTURE:
                return [
                    'ACTION',
                    'RC',
                    'MESSAGE',
                    'TRTYPE',
                    'AMOUNT',
                    'CURRENCY',
                    'ORDER',
                    'RRN',
                    'INT_REF',
                    'TIMESTAMP',
                    'NONCE'
                ];
                break;
            case self::TRTTYPE_VOID:
                return [
                    'ACTION',
                    'RC',
                    'MESSAGE',
                    'TRTYPE',
                    'AMOUNT',
                    'CURRENCY',
                    'ORDER',
                    'RRN',
                    'INT_REF',
                    'TIMESTAMP',
                    'NONCE'
                ];
                break;
        }
        return [];
    }
}