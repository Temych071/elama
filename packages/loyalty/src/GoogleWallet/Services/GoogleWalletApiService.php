<?php

declare(strict_types=1);

namespace Loyalty\GoogleWallet\Services;

require __DIR__ . '/Walletobjects.php';

use Firebase\JWT\JWT;
use Google\Exception;
use Google_Client;
use Google_Service_Walletobjects;
use Google_Service_Walletobjects_LoyaltyClass;
use Google_Service_Walletobjects_LoyaltyObject;
use Illuminate\Support\Str;

final class GoogleWalletApiService extends Google_Service_Walletobjects
{
    protected const SAVE_TO_PAY_BASE_URL = 'https://pay.google.com/gp/v/save/';

    protected readonly string $issuerId;
    protected readonly array $authConfig;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->issuerId = config('services.google.pay.issuer.id');
        $this->authConfig = config('services.google.service_accounts.loyalty');

        $client = new Google_Client();
        $client->setApplicationName('adscabinettest');
        $client->setScopes('https://www.googleapis.com/auth/wallet_object.issuer');
        $client->setAuthConfig($this->authConfig);

        parent::__construct($client);
    }

    protected function getClassIndex(string $classId): string
    {
        return Str::start($classId, "$this->issuerId.");
    }

    protected function getObjectIndex(string $classId, string $objectId): string
    {
        return Str::start($objectId, $this->getClassIndex($classId) . '.');
    }

    /**
     * @throws Exception
     */
    public function getClass(
        string|Google_Service_Walletobjects_LoyaltyClass $classId
    ): ?Google_Service_Walletobjects_LoyaltyClass
    {
        if ($classId instanceof Google_Service_Walletobjects_LoyaltyClass) {
            return $classId;
        }

        try {
            return $this->loyaltyclass->get($this->getClassIndex($classId));
        } catch (Exception $ex) {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            if (empty($ex->getErrors()) || $ex->getErrors()[0]['reason'] !== 'classNotFound') {
                throw $ex;
            }

            return null;
        }
    }

    /**
     * @throws Exception
     */
    public function createClass(
        string                                    $classId,
        Google_Service_Walletobjects_LoyaltyClass $newClassInfo,
        bool                                      $checkExists = true,
    ): Google_Service_Walletobjects_LoyaltyClass
    {
        $newClassInfo->id = $this->getClassIndex($classId);
        return ($checkExists ? $this->getClass($classId) : null) ?? $this->loyaltyclass->insert($newClassInfo);
    }

    /**
     * @throws Exception
     */
    public function updateClass(
        string                                    $classId,
        Google_Service_Walletobjects_LoyaltyClass $newClassInfo
    ): ?Google_Service_Walletobjects_LoyaltyClass
    {
        $class = $this->getClass($classId);
        if ($class === null) {
            return null;
        }

        return $this->loyaltyclass->update($this->getClassIndex($classId), $newClassInfo);
    }

    /**
     * @throws Exception
     */
    public function getObject(
        string|Google_Service_Walletobjects_LoyaltyClass  $classId,
        string|Google_Service_Walletobjects_LoyaltyObject $objectId
    ): ?Google_Service_Walletobjects_LoyaltyObject
    {
        $classId = $classId instanceof Google_Service_Walletobjects_LoyaltyClass
            ? $classId->id
            : $classId;

        if ($objectId instanceof Google_Service_Walletobjects_LoyaltyObject) {
            return $objectId;
        }

        try {
            return $this->loyaltyobject->get($this->getObjectIndex($classId, $objectId));
        } catch (Exception $ex) {
            /** @noinspection PhpPossiblePolymorphicInvocationInspection */
            if (empty($ex->getErrors()) || $ex->getErrors()[0]['reason'] !== 'resourceNotFound') {
                throw $ex;
            }

            return null;
        }
    }

    /**
     * @throws Exception
     */
    public function createObject(
        string|Google_Service_Walletobjects_LoyaltyClass $classId,
        string                                           $objectId,
        Google_Service_Walletobjects_LoyaltyObject       $newObjectInfo,
        bool                                             $checkExists = true,
    ): Google_Service_Walletobjects_LoyaltyObject
    {
        $classId = $classId instanceof Google_Service_Walletobjects_LoyaltyClass
            ? $classId->id
            : $classId;

        $newObjectInfo->id = $this->getObjectIndex($classId, $objectId);
        return ($checkExists ? $this->getObject($classId, $objectId) : null) ?? $this->loyaltyobject->insert($newObjectInfo);
    }

    /**
     * @throws Exception
     */
    public function getSaveToPayLink(
        string|Google_Service_Walletobjects_LoyaltyClass  $classId,
        string|Google_Service_Walletobjects_LoyaltyObject $objectId,
    ): string
    {
        $classId = $classId instanceof Google_Service_Walletobjects_LoyaltyClass
            ? $classId->id
            : $classId;

        $object = $this->getObject($classId, $objectId);

        /** @noinspection NullPointerExceptionInspection */
        $claims = [
            'iss' => $this->authConfig['client_email'],
            'aud' => 'google',
            'origins' => [url('/')],
            'typ' => 'savetowallet',
            'payload' => [
                'loyaltyClasses' => [
                    $this->getClass($classId ?? $object->classId),
                ],
                'loyaltyObjects' => [
                    $object,
                ],
            ],
        ];

        $token = JWT::encode($claims, $this->authConfig['private_key'], 'RS256');

        return self::SAVE_TO_PAY_BASE_URL . $token;
    }
}
