<?php

namespace Ibtikar\ShareEconomyPayFortBundle;

/**
 * statuses taken from https://testfort.payfort.com/api/#transactions-response-codes
 *
 * @author Karim Shendy <kareem.elshendy@ibtikar.net.sa>
 */
final class PfTransactionsResponseCodes
{
    const INVALID_REQUEST                             = '00';
    const ORDER_STORED                                = '01';
    const AUTHORIZATION_SUCCESS                       = '02';
    const AUTHORIZATION_FAILED                        = '03';
    const CAPTURE_SUCCESS                             = '04';
    const CAPTURE_FAILED                              = '05';
    const REFUND_SUCCESS                              = '06';
    const REFUND_FAILED                               = '07';
    const AUTHORIZATION_VOIDED_SUCCESSFULLY           = '08';
    const AUTHORIZATION_VOID_FAILED                   = '09';
    const INCOMPLETE                                  = '10';
    const CHECK_STATUS_FAILED                         = '11';
    const CHECK_STATUS_SUCCESS                        = '12';
    const TRANSACTION_FAILURE                         = '13';
    const TRANSACTION_SUCCESS                         = '14';
    const TRANSACTION_UNCERTAIN                       = '15';
    const TOKENIZATION_FAILED                         = '17';
    const TOKENIZATION_SUCCESS                        = '18';
    const TRANSACTION_PENDING                         = '19';
    const ON_HOLD                                     = '20';
    const PROCESS_DIGITAL_WALLET_SERVICE_FAILED       = '23';
    const DIGITAL_WALLET_ORDER_PROCESSED_SUCCESSFULLY = '24';
    const CHECK_CARD_BALANCE_FAILED                   = '27';
    const CHECK_CARD_BALANCE_SUCCESS                  = '28';
    const REDEMPTION_FAILED                           = '29';
    const REDEMPTION_SUCCESS                          = '30';
    const REVERSE_REDEMPTION_TRANSACTION_FAILED       = '31';
    const REVERSE_REDEMPTION_TRANSACTION_SUCCESS      = '32';
    const TRANSACTION_IN_REVIEW                       = '40';
    const CURRENCY_CONVERSION_SUCCESS                 = '42';
    const CURRENCY_CONVERSION_FAILED                  = '43';

    /**
     * transaction response codes messages
     */
    const CODE_MESSAGES = [
        self::INVALID_REQUEST                             => "Invalid Request.",
        self::ORDER_STORED                                => "Order Stored.",
        self::AUTHORIZATION_SUCCESS                       => "Authorization Success.",
        self::AUTHORIZATION_FAILED                        => "Authorization Failed.",
        self::CAPTURE_SUCCESS                             => "Capture Success.",
        self::CAPTURE_FAILED                              => "Capture failed.",
        self::REFUND_SUCCESS                              => "Refund Success.",
        self::REFUND_FAILED                               => "Refund Failed.",
        self::AUTHORIZATION_VOIDED_SUCCESSFULLY           => "Authorization Voided Successfully.",
        self::AUTHORIZATION_VOID_FAILED                   => "Authorization Void Failed.",
        self::INCOMPLETE                                  => "Incomplete.",
        self::CHECK_STATUS_FAILED                         => "Check status Failed.",
        self::CHECK_STATUS_SUCCESS                        => "Check status success.",
        self::TRANSACTION_FAILURE                         => "Purchase Failure.",
        self::TRANSACTION_SUCCESS                         => "Purchase Success.",
        self::UNCERTAIN_TRANSACTION                       => "Uncertain Transaction.",
        self::TOKENIZATION_FAILED                         => "Tokenization failed.",
        self::TOKENIZATION_SUCCESS                        => "Tokenization success.",
        self::TRANSACTION_PENDING                         => "Transaction pending.",
        self::ON_HOLD                                     => "On hold.",
        self::PROCESS_DIGITAL_WALLET_SERVICE_FAILED       => "Failed to process Digital Wallet service.",
        self::DIGITAL_WALLET_ORDER_PROCESSED_SUCCESSFULLY => "Digital wallet order processed successfully.",
        self::CHECK_CARD_BALANCE_FAILED                   => "Check card balance failed.",
        self::CHECK_CARD_BALANCE_SUCCESS                  => "Check card balance success.",
        self::REDEMPTION_FAILED                           => "Redemption failed.",
        self::REDEMPTION_SUCCESS                          => "Redemption success.",
        self::REVERSE_REDEMPTION_TRANSACTION_FAILED       => "Reverse Redemption transaction failed.",
        self::REVERSE_REDEMPTION_TRANSACTION_SUCCESS      => "Reverse Redemption transaction success.",
        self::TRANSACTION_IN_REVIEW                       => "Transaction In review.",
        self::CURRENCY_CONVERSION_SUCCESS                 => "currency conversion success.",
        self::CURRENCY_CONVERSION_FAILED                  => "currency conversion failed."
    ];

}