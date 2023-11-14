<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

/** @noinspection PhpMultipleClassesDeclarationsInOneFile */

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

/**
 * Service definition for Walletobjects (v1).
 *
 * <p>
 * API for issuers to save and manage Google Wallet Objects.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="https://developers.google.com/pay/passes" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */

class Google_Service_Walletobjects extends Google_Service
{
    /** Private Service: https://www.googleapis.com/auth/wallet_object.issuer. */
    const WALLET_OBJECT_ISSUER =
        "https://www.googleapis.com/auth/wallet_object.issuer";

    public $eventticketclass;
    public $eventticketobject;
    public $flightclass;
    public $flightobject;
    public $genericclass;
    public $genericobject;
    public $giftcardclass;
    public $giftcardobject;
    public $issuer;
    public $jwt;
    public $loyaltyclass;
    public $loyaltyobject;
    public $media;
    public $offerclass;
    public $offerobject;
    public $permissions;
    public $smarttap;
    public $transitclass;
    public $transitobject;
    public $walletobjects_v1_privateContent;


    /**
     * Constructs the internal representation of the Walletobjects service.
     *
     * @param  Google_Client  $client
     */
    public function __construct(Google_Client $client)
    {
        parent::__construct($client);
        $this->rootUrl = 'https://walletobjects.googleapis.com/';
        $this->servicePath = '';
        $this->batchPath = 'batch';
        $this->version = 'v1';
        $this->serviceName = 'walletobjects';

        $this->eventticketclass = new Google_Service_Walletobjects_Eventticketclass_Resource(
            $this,
            $this->serviceName,
            'eventticketclass',
            array(
                'methods' => array(
                    'addmessage' => array(
                        'path' => 'walletobjects/v1/eventTicketClass/{resourceId}/addMessage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'get' => array(
                        'path' => 'walletobjects/v1/eventTicketClass/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/eventTicketClass',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/eventTicketClass',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'issuerId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/eventTicketClass/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/eventTicketClass/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->eventticketobject = new Google_Service_Walletobjects_Eventticketobject_Resource(
            $this,
            $this->serviceName,
            'eventticketobject',
            array(
                'methods' => array(
                    'addmessage' => array(
                        'path' => 'walletobjects/v1/eventTicketObject/{resourceId}/addMessage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'get' => array(
                        'path' => 'walletobjects/v1/eventTicketObject/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/eventTicketObject',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/eventTicketObject',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'classId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'modifylinkedofferobjects' => array(
                        'path' => 'walletobjects/v1/eventTicketObject/{resourceId}/modifyLinkedOfferObjects',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/eventTicketObject/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/eventTicketObject/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->flightclass = new Google_Service_Walletobjects_Flightclass_Resource(
            $this,
            $this->serviceName,
            'flightclass',
            array(
                'methods' => array(
                    'addmessage' => array(
                        'path' => 'walletobjects/v1/flightClass/{resourceId}/addMessage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'get' => array(
                        'path' => 'walletobjects/v1/flightClass/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/flightClass',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/flightClass',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'issuerId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/flightClass/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/flightClass/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->flightobject = new Google_Service_Walletobjects_Flightobject_Resource(
            $this,
            $this->serviceName,
            'flightobject',
            array(
                'methods' => array(
                    'addmessage' => array(
                        'path' => 'walletobjects/v1/flightObject/{resourceId}/addMessage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'get' => array(
                        'path' => 'walletobjects/v1/flightObject/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/flightObject',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/flightObject',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'classId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/flightObject/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/flightObject/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->genericclass = new Google_Service_Walletobjects_Genericclass_Resource(
            $this,
            $this->serviceName,
            'genericclass',
            array(
                'methods' => array(
                    'get' => array(
                        'path' => 'walletobjects/v1/genericClass/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/genericClass',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/genericClass',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'issuerId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/genericClass/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/genericClass/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->genericobject = new Google_Service_Walletobjects_Genericobject_Resource(
            $this,
            $this->serviceName,
            'genericobject',
            array(
                'methods' => array(
                    'get' => array(
                        'path' => 'walletobjects/v1/genericObject/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/genericObject',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/genericObject',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'classId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/genericObject/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/genericObject/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->giftcardclass = new Google_Service_Walletobjects_Giftcardclass_Resource(
            $this,
            $this->serviceName,
            'giftcardclass',
            array(
                'methods' => array(
                    'addmessage' => array(
                        'path' => 'walletobjects/v1/giftCardClass/{resourceId}/addMessage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'get' => array(
                        'path' => 'walletobjects/v1/giftCardClass/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/giftCardClass',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/giftCardClass',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'issuerId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/giftCardClass/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/giftCardClass/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->giftcardobject = new Google_Service_Walletobjects_Giftcardobject_Resource(
            $this,
            $this->serviceName,
            'giftcardobject',
            array(
                'methods' => array(
                    'addmessage' => array(
                        'path' => 'walletobjects/v1/giftCardObject/{resourceId}/addMessage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'get' => array(
                        'path' => 'walletobjects/v1/giftCardObject/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/giftCardObject',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/giftCardObject',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'classId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/giftCardObject/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/giftCardObject/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->issuer = new Google_Service_Walletobjects_Issuer_Resource(
            $this,
            $this->serviceName,
            'issuer',
            array(
                'methods' => array(
                    'get' => array(
                        'path' => 'walletobjects/v1/issuer/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/issuer',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/issuer',
                        'httpMethod' => 'GET',
                        'parameters' => array(),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/issuer/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/issuer/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->jwt = new Google_Service_Walletobjects_Jwt_Resource(
            $this,
            $this->serviceName,
            'jwt',
            array(
                'methods' => array(
                    'insert' => array(
                        'path' => 'walletobjects/v1/jwt',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ),
                ),
            )
        );
        $this->loyaltyclass = new Google_Service_Walletobjects_Loyaltyclass_Resource(
            $this,
            $this->serviceName,
            'loyaltyclass',
            array(
                'methods' => array(
                    'addmessage' => array(
                        'path' => 'walletobjects/v1/loyaltyClass/{resourceId}/addMessage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'get' => array(
                        'path' => 'walletobjects/v1/loyaltyClass/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/loyaltyClass',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/loyaltyClass',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'issuerId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/loyaltyClass/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/loyaltyClass/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->loyaltyobject = new Google_Service_Walletobjects_Loyaltyobject_Resource(
            $this,
            $this->serviceName,
            'loyaltyobject',
            array(
                'methods' => array(
                    'addmessage' => array(
                        'path' => 'walletobjects/v1/loyaltyObject/{resourceId}/addMessage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'get' => array(
                        'path' => 'walletobjects/v1/loyaltyObject/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/loyaltyObject',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/loyaltyObject',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'classId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'modifylinkedofferobjects' => array(
                        'path' => 'walletobjects/v1/loyaltyObject/{resourceId}/modifyLinkedOfferObjects',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/loyaltyObject/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/loyaltyObject/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->media = new Google_Service_Walletobjects_Media_Resource(
            $this,
            $this->serviceName,
            'media',
            array(
                'methods' => array(
                    'upload' => array(
                        'path' => 'walletobjects/v1/privateContent/{issuerId}/uploadPrivateImage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'issuerId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->offerclass = new Google_Service_Walletobjects_Offerclass_Resource(
            $this,
            $this->serviceName,
            'offerclass',
            array(
                'methods' => array(
                    'addmessage' => array(
                        'path' => 'walletobjects/v1/offerClass/{resourceId}/addMessage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'get' => array(
                        'path' => 'walletobjects/v1/offerClass/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/offerClass',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/offerClass',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'issuerId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/offerClass/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/offerClass/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->offerobject = new Google_Service_Walletobjects_Offerobject_Resource(
            $this,
            $this->serviceName,
            'offerobject',
            array(
                'methods' => array(
                    'addmessage' => array(
                        'path' => 'walletobjects/v1/offerObject/{resourceId}/addMessage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'get' => array(
                        'path' => 'walletobjects/v1/offerObject/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/offerObject',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/offerObject',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'classId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/offerObject/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/offerObject/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->permissions = new Google_Service_Walletobjects_Permissions_Resource(
            $this,
            $this->serviceName,
            'permissions',
            array(
                'methods' => array(
                    'get' => array(
                        'path' => 'walletobjects/v1/permissions/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/permissions/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->smarttap = new Google_Service_Walletobjects_Smarttap_Resource(
            $this,
            $this->serviceName,
            'smarttap',
            array(
                'methods' => array(
                    'insert' => array(
                        'path' => 'walletobjects/v1/smartTap',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ),
                ),
            )
        );
        $this->transitclass = new Google_Service_Walletobjects_Transitclass_Resource(
            $this,
            $this->serviceName,
            'transitclass',
            array(
                'methods' => array(
                    'addmessage' => array(
                        'path' => 'walletobjects/v1/transitClass/{resourceId}/addMessage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'get' => array(
                        'path' => 'walletobjects/v1/transitClass/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/transitClass',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/transitClass',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'issuerId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/transitClass/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/transitClass/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->transitobject = new Google_Service_Walletobjects_Transitobject_Resource(
            $this,
            $this->serviceName,
            'transitobject',
            array(
                'methods' => array(
                    'addmessage' => array(
                        'path' => 'walletobjects/v1/transitObject/{resourceId}/addMessage',
                        'httpMethod' => 'POST',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'get' => array(
                        'path' => 'walletobjects/v1/transitObject/{resourceId}',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'insert' => array(
                        'path' => 'walletobjects/v1/transitObject',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ), 'list' => array(
                        'path' => 'walletobjects/v1/transitObject',
                        'httpMethod' => 'GET',
                        'parameters' => array(
                            'classId' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'token' => array(
                                'location' => 'query',
                                'type' => 'string',
                            ),
                            'maxResults' => array(
                                'location' => 'query',
                                'type' => 'integer',
                            ),
                        ),
                    ), 'patch' => array(
                        'path' => 'walletobjects/v1/transitObject/{resourceId}',
                        'httpMethod' => 'PATCH',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ), 'update' => array(
                        'path' => 'walletobjects/v1/transitObject/{resourceId}',
                        'httpMethod' => 'PUT',
                        'parameters' => array(
                            'resourceId' => array(
                                'location' => 'path',
                                'type' => 'string',
                                'required' => true,
                            ),
                        ),
                    ),
                ),
            )
        );
        $this->walletobjects_v1_privateContent = new Google_Service_Walletobjects_WalletobjectsV1PrivateContent_Resource(
            $this,
            $this->serviceName,
            'privateContent',
            array(
                'methods' => array(
                    'uploadPrivateData' => array(
                        'path' => 'walletobjects/v1/privateContent/uploadPrivateData',
                        'httpMethod' => 'POST',
                        'parameters' => array(),
                    ),
                ),
            )
        );
    }
}


/**
 * The "eventticketclass" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $eventticketclass = $walletobjectsService->eventticketclass;
 *  </code>
 */
class Google_Service_Walletobjects_Eventticketclass_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the event ticket class referenced by the given class ID.
     * (eventticketclass.addmessage)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_AddMessageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketClassAddMessageResponse
     * @throws \Google\Exception
     */
    public function addmessage(
        $resourceId,
        Google_Service_Walletobjects_AddMessageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_EventTicketClassAddMessageResponse {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('addmessage', array($params),
            "Google_Service_Walletobjects_EventTicketClassAddMessageResponse");
    }

    /**
     * Returns the event ticket class with the given class ID.
     * (eventticketclass.get)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketClass
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_EventTicketClass
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_EventTicketClass");
    }

    /**
     * Inserts an event ticket class with the given ID and properties.
     * (eventticketclass.insert)
     *
     * @param  Google_Service_Walletobjects_EventTicketClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketClass
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_EventTicketClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_EventTicketClass {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_EventTicketClass");
    }

    /**
     * Returns a list of all event ticket classes for a given issuer ID.
     * (eventticketclass.listEventticketclass)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_EventTicketClassListResponse
     * @throws \Google\Exception
     * @opt_param string issuerId The ID of the issuer authorized to list classes.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` classes are available in a list. For
     * example, if you have a list of 200 classes and you call list with
     * `maxResults` set to 20, list will return the first 20 classes and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * classes.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listEventticketclass($optParams = array()
    ): Google_Service_Walletobjects_EventTicketClassListResponse {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_EventTicketClassListResponse");
    }

    /**
     * Updates the event ticket class referenced by the given class ID. This method
     * supports patch semantics. (eventticketclass.patch)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_EventTicketClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketClass
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_EventTicketClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_EventTicketClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_EventTicketClass");
    }

    /**
     * Updates the event ticket class referenced by the given class ID.
     * (eventticketclass.update)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_EventTicketClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketClass
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_EventTicketClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_EventTicketClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_EventTicketClass");
    }
}

/**
 * The "eventticketobject" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $eventticketobject = $walletobjectsService->eventticketobject;
 *  </code>
 */
class Google_Service_Walletobjects_Eventticketobject_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the event ticket object referenced by the given object ID.
     * (eventticketobject.addmessage)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_AddMessageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketObjectAddMessageResponse
     * @throws \Google\Exception
     */
    public function addmessage(
        $resourceId,
        Google_Service_Walletobjects_AddMessageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_EventTicketObjectAddMessageResponse {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('addmessage', array($params),
            "Google_Service_Walletobjects_EventTicketObjectAddMessageResponse");
    }

    /**
     * Returns the event ticket object with the given object ID.
     * (eventticketobject.get)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketObject
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_EventTicketObject
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_EventTicketObject");
    }

    /**
     * Inserts an event ticket object with the given ID and properties.
     * (eventticketobject.insert)
     *
     * @param  Google_Service_Walletobjects_EventTicketObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketObject
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_EventTicketObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_EventTicketObject {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_EventTicketObject");
    }

    /**
     * Returns a list of all event ticket objects for a given issuer ID.
     * (eventticketobject.listEventticketobject)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_EventTicketObjectListResponse
     * @throws \Google\Exception
     * @opt_param string classId The ID of the class whose objects will be listed.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` objects are available in a list. For
     * example, if you have a list of 200 objects and you call list with
     * `maxResults` set to 20, list will return the first 20 objects and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * objects.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listEventticketobject($optParams = array()
    ): Google_Service_Walletobjects_EventTicketObjectListResponse {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_EventTicketObjectListResponse");
    }

    /**
     * Modifies linked offer objects for the event ticket object with the given ID.
     * (eventticketobject.modifylinkedofferobjects)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_ModifyLinkedOfferObjectsRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketObject
     * @throws \Google\Exception
     */
    public function modifylinkedofferobjects(
        $resourceId,
        Google_Service_Walletobjects_ModifyLinkedOfferObjectsRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_EventTicketObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('modifylinkedofferobjects', array($params),
            "Google_Service_Walletobjects_EventTicketObject");
    }

    /**
     * Updates the event ticket object referenced by the given object ID. This
     * method supports patch semantics. (eventticketobject.patch)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_EventTicketObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketObject
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_EventTicketObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_EventTicketObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_EventTicketObject");
    }

    /**
     * Updates the event ticket object referenced by the given object ID.
     * (eventticketobject.update)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_EventTicketObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_EventTicketObject
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_EventTicketObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_EventTicketObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_EventTicketObject");
    }
}

/**
 * The "flightclass" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $flightclass = $walletobjectsService->flightclass;
 *  </code>
 */
class Google_Service_Walletobjects_Flightclass_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the flight class referenced by the given class ID.
     * (flightclass.addmessage)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_AddMessageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_FlightClassAddMessageResponse
     * @throws \Google\Exception
     */
    public function addmessage(
        $resourceId,
        Google_Service_Walletobjects_AddMessageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_FlightClassAddMessageResponse {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('addmessage', array($params), "Google_Service_Walletobjects_FlightClassAddMessageResponse");
    }

    /**
     * Returns the flight class with the given class ID. (flightclass.get)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_FlightClass
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_FlightClass
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_FlightClass");
    }

    /**
     * Inserts an flight class with the given ID and properties.
     * (flightclass.insert)
     *
     * @param  Google_Service_Walletobjects_FlightClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_FlightClass
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_FlightClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_FlightClass {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_FlightClass");
    }

    /**
     * Returns a list of all flight classes for a given issuer ID.
     * (flightclass.listFlightclass)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_FlightClassListResponse
     * @throws \Google\Exception
     * @opt_param string issuerId The ID of the issuer authorized to list classes.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` classes are available in a list. For
     * example, if you have a list of 200 classes and you call list with
     * `maxResults` set to 20, list will return the first 20 classes and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * classes.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listFlightclass($optParams = array()): Google_Service_Walletobjects_FlightClassListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_FlightClassListResponse");
    }

    /**
     * Updates the flight class referenced by the given class ID. This method
     * supports patch semantics. (flightclass.patch)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_FlightClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_FlightClass
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_FlightClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_FlightClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_FlightClass");
    }

    /**
     * Updates the flight class referenced by the given class ID.
     * (flightclass.update)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_FlightClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_FlightClass
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_FlightClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_FlightClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_FlightClass");
    }
}

/**
 * The "flightobject" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $flightobject = $walletobjectsService->flightobject;
 *  </code>
 */
class Google_Service_Walletobjects_Flightobject_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the flight object referenced by the given object ID.
     * (flightobject.addmessage)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_AddMessageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_FlightObjectAddMessageResponse
     * @throws \Google\Exception
     */
    public function addmessage(
        string $resourceId,
        Google_Service_Walletobjects_AddMessageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_FlightObjectAddMessageResponse {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('addmessage', array($params), "Google_Service_Walletobjects_FlightObjectAddMessageResponse");
    }

    /**
     * Returns the flight object with the given object ID. (flightobject.get)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_FlightObject
     * @throws \Google\Exception
     */
    public function get(string $resourceId, array $optParams = array()): Google_Service_Walletobjects_FlightObject
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_FlightObject");
    }

    /**
     * Inserts an flight object with the given ID and properties.
     * (flightobject.insert)
     *
     * @param  Google_Service_Walletobjects_FlightObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_FlightObject
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_FlightObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_FlightObject {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_FlightObject");
    }

    /**
     * Returns a list of all flight objects for a given issuer ID.
     * (flightobject.listFlightobject)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_FlightObjectListResponse
     * @throws \Google\Exception
     * @opt_param string classId The ID of the class whose objects will be listed.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` objects are available in a list. For
     * example, if you have a list of 200 objects and you call list with
     * `maxResults` set to 20, list will return the first 20 objects and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * objects.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listFlightobject($optParams = array()): Google_Service_Walletobjects_FlightObjectListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_FlightObjectListResponse");
    }

    /**
     * Updates the flight object referenced by the given object ID. This method
     * supports patch semantics. (flightobject.patch)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_FlightObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_FlightObject
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_FlightObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_FlightObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_FlightObject");
    }

    /**
     * Updates the flight object referenced by the given object ID.
     * (flightobject.update)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_FlightObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_FlightObject
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_FlightObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_FlightObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_FlightObject");
    }
}

/**
 * The "genericclass" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $genericclass = $walletobjectsService->genericclass;
 *  </code>
 */
class Google_Service_Walletobjects_Genericclass_Resource extends Google_Service_Resource
{

    /**
     * Returns the generic class with the given class ID. (genericclass.get)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value needs to follow the
     * format `issuerID.identifier` where `issuerID` is issued by Google and
     * `identifier` is chosen by you. The unique identifier can only include
     * alphanumeric characters, `.`, `_`, or `-`.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GenericClass
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_GenericClass
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_GenericClass");
    }

    /**
     * Inserts a generic class with the given ID and properties.
     * (genericclass.insert)
     *
     * @param  Google_Service_Walletobjects_GenericClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GenericClass
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_GenericClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GenericClass {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_GenericClass");
    }

    /**
     * Returns a list of all generic classes for a given issuer ID.
     * (genericclass.listGenericclass)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_GenericClassListResponse
     * @throws \Google\Exception
     * @opt_param string issuerId The ID of the issuer authorized to list classes.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` classes are available in a list. For
     * example, if you have a list of 200 classes and you call list with
     * `maxResults` set to 20, list will return the first 20 classes and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * classes.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listGenericclass($optParams = array()): Google_Service_Walletobjects_GenericClassListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_GenericClassListResponse");
    }

    /**
     * Updates the generic class referenced by the given class ID. This method
     * supports patch semantics. (genericclass.patch)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value needs to follow the
     * format `issuerID.identifier` where `issuerID` is issued by Google and
     * `identifier` is chosen by you. The unique identifier can only include
     * alphanumeric characters, `.`, `_`, or `-`.
     * @param  Google_Service_Walletobjects_GenericClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GenericClass
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_GenericClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GenericClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_GenericClass");
    }

    /**
     * Updates the Generic class referenced by the given class ID.
     * (genericclass.update)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value needs to follow the
     * format `issuerID.identifier` where `issuerID` is issued by Google and
     * `identifier` is chosen by you. The unique identifier can only include
     * alphanumeric characters, `.`, `_`, or `-`.
     * @param  Google_Service_Walletobjects_GenericClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GenericClass
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_GenericClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GenericClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_GenericClass");
    }
}

/**
 * The "genericobject" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $genericobject = $walletobjectsService->genericobject;
 *  </code>
 */
class Google_Service_Walletobjects_Genericobject_Resource extends Google_Service_Resource
{

    /**
     * Returns the generic object with the given object ID. (genericobject.get)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value needs to follow the
     * format `issuerID.identifier` where `issuerID` is issued by Google and
     * `identifier` is chosen by you. The unique identifier can only include
     * alphanumeric characters, `.`, `_`, or `-`.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GenericObject
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_GenericObject
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_GenericObject");
    }

    /**
     * Inserts a generic object with the given ID and properties.
     * (genericobject.insert)
     *
     * @param  Google_Service_Walletobjects_GenericObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GenericObject
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_GenericObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GenericObject {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_GenericObject");
    }

    /**
     * Returns a list of all generic objects for a given issuer ID.
     * (genericobject.listGenericobject)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_GenericObjectListResponse
     * @throws \Google\Exception
     * @opt_param string classId The ID of the class whose objects will be listed.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` objects are available in a list. For
     * example, if you have a list of 200 objects and you call list with
     * `maxResults` set to 20, list will return the first 20 objects and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * objects.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listGenericobject($optParams = array()): Google_Service_Walletobjects_GenericObjectListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_GenericObjectListResponse");
    }

    /**
     * Updates the generic object referenced by the given object ID. This method
     * supports patch semantics. (genericobject.patch)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value needs to follow the
     * format `issuerID.identifier` where `issuerID` is issued by Google and
     * `identifier` is chosen by you. The unique identifier can only include
     * alphanumeric characters, `.`, `_`, or `-`.
     * @param  Google_Service_Walletobjects_GenericObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GenericObject
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_GenericObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GenericObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_GenericObject");
    }

    /**
     * Updates the generic object referenced by the given object ID.
     * (genericobject.update)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value needs to follow the
     * format `issuerID.identifier` where `issuerID` is issued by Google and
     * `identifier` is chosen by you. The unique identifier can only include
     * alphanumeric characters, `.`, `_`, or `-`.
     * @param  Google_Service_Walletobjects_GenericObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GenericObject
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_GenericObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GenericObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_GenericObject");
    }
}

/**
 * The "giftcardclass" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $giftcardclass = $walletobjectsService->giftcardclass;
 *  </code>
 */
class Google_Service_Walletobjects_Giftcardclass_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the gift card class referenced by the given class ID.
     * (giftcardclass.addmessage)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_AddMessageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GiftCardClassAddMessageResponse
     * @throws \Google\Exception
     */
    public function addmessage(
        $resourceId,
        Google_Service_Walletobjects_AddMessageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GiftCardClassAddMessageResponse {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('addmessage', array($params),
            "Google_Service_Walletobjects_GiftCardClassAddMessageResponse");
    }

    /**
     * Returns the gift card class with the given class ID. (giftcardclass.get)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GiftCardClass
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_GiftCardClass
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_GiftCardClass");
    }

    /**
     * Inserts an gift card class with the given ID and properties.
     * (giftcardclass.insert)
     *
     * @param  Google_Service_Walletobjects_GiftCardClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GiftCardClass
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_GiftCardClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GiftCardClass {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_GiftCardClass");
    }

    /**
     * Returns a list of all gift card classes for a given issuer ID.
     * (giftcardclass.listGiftcardclass)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_GiftCardClassListResponse
     * @throws \Google\Exception
     * @opt_param string issuerId The ID of the issuer authorized to list classes.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` classes are available in a list. For
     * example, if you have a list of 200 classes and you call list with
     * `maxResults` set to 20, list will return the first 20 classes and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * classes.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listGiftcardclass($optParams = array()): Google_Service_Walletobjects_GiftCardClassListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_GiftCardClassListResponse");
    }

    /**
     * Updates the gift card class referenced by the given class ID. This method
     * supports patch semantics. (giftcardclass.patch)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_GiftCardClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GiftCardClass
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_GiftCardClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GiftCardClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_GiftCardClass");
    }

    /**
     * Updates the gift card class referenced by the given class ID.
     * (giftcardclass.update)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_GiftCardClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GiftCardClass
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_GiftCardClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GiftCardClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_GiftCardClass");
    }
}

/**
 * The "giftcardobject" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $giftcardobject = $walletobjectsService->giftcardobject;
 *  </code>
 */
class Google_Service_Walletobjects_Giftcardobject_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the gift card object referenced by the given object ID.
     * (giftcardobject.addmessage)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_AddMessageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GiftCardObjectAddMessageResponse
     * @throws \Google\Exception
     */
    public function addmessage(
        $resourceId,
        Google_Service_Walletobjects_AddMessageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GiftCardObjectAddMessageResponse {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('addmessage', array($params),
            "Google_Service_Walletobjects_GiftCardObjectAddMessageResponse");
    }

    /**
     * Returns the gift card object with the given object ID. (giftcardobject.get)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GiftCardObject
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_GiftCardObject
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_GiftCardObject");
    }

    /**
     * Inserts an gift card object with the given ID and properties.
     * (giftcardobject.insert)
     *
     * @param  Google_Service_Walletobjects_GiftCardObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GiftCardObject
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_GiftCardObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GiftCardObject {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_GiftCardObject");
    }

    /**
     * Returns a list of all gift card objects for a given issuer ID.
     * (giftcardobject.listGiftcardobject)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_GiftCardObjectListResponse
     * @throws \Google\Exception
     * @opt_param string classId The ID of the class whose objects will be listed.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` objects are available in a list. For
     * example, if you have a list of 200 objects and you call list with
     * `maxResults` set to 20, list will return the first 20 objects and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * objects.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listGiftcardobject($optParams = array()): Google_Service_Walletobjects_GiftCardObjectListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_GiftCardObjectListResponse");
    }

    /**
     * Updates the gift card object referenced by the given object ID. This method
     * supports patch semantics. (giftcardobject.patch)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_GiftCardObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GiftCardObject
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_GiftCardObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GiftCardObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_GiftCardObject");
    }

    /**
     * Updates the gift card object referenced by the given object ID.
     * (giftcardobject.update)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_GiftCardObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_GiftCardObject
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_GiftCardObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_GiftCardObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_GiftCardObject");
    }
}

/**
 * The "issuer" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $issuer = $walletobjectsService->issuer;
 *  </code>
 */
class Google_Service_Walletobjects_Issuer_Resource extends Google_Service_Resource
{

    /**
     * Returns the issuer with the given issuer ID. (issuer.get)
     *
     * @param  string  $resourceId  The unique identifier for an issuer.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_Issuer
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_Issuer
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_Issuer");
    }

    /**
     * Inserts an issuer with the given ID and properties. (issuer.insert)
     *
     * @param  Google_Service_Walletobjects_Issuer  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_Issuer
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_Issuer $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_Issuer {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_Issuer");
    }

    /**
     * Returns a list of all issuers shared to the caller. (issuer.listIssuer)
     *
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_IssuerListResponse
     * @throws \Google\Exception
     */
    public function listIssuer($optParams = array()): Google_Service_Walletobjects_IssuerListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_IssuerListResponse");
    }

    /**
     * Updates the issuer referenced by the given issuer ID. This method supports
     * patch semantics. (issuer.patch)
     *
     * @param  string  $resourceId  The unique identifier for an issuer.
     * @param  Google_Service_Walletobjects_Issuer  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_Issuer
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_Issuer $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_Issuer {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_Issuer");
    }

    /**
     * Updates the issuer referenced by the given issuer ID. (issuer.update)
     *
     * @param  string  $resourceId  The unique identifier for an issuer.
     * @param  Google_Service_Walletobjects_Issuer  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_Issuer
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_Issuer $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_Issuer {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_Issuer");
    }
}

/**
 * The "jwt" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $jwt = $walletobjectsService->jwt;
 *  </code>
 */
class Google_Service_Walletobjects_Jwt_Resource extends Google_Service_Resource
{

    /**
     * Inserts the resources in the JWT. (jwt.insert)
     *
     * @param  Google_Service_Walletobjects_JwtResource  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_JwtInsertResponse
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_JwtResource $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_JwtInsertResponse {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_JwtInsertResponse");
    }
}

/**
 * The "loyaltyclass" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $loyaltyclass = $walletobjectsService->loyaltyclass;
 *  </code>
 */
class Google_Service_Walletobjects_Loyaltyclass_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the loyalty class referenced by the given class ID.
     * (loyaltyclass.addmessage)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_AddMessageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_LoyaltyClassAddMessageResponse
     * @throws \Google\Exception
     */
    public function addmessage(
        $resourceId,
        Google_Service_Walletobjects_AddMessageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_LoyaltyClassAddMessageResponse {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('addmessage', array($params), "Google_Service_Walletobjects_LoyaltyClassAddMessageResponse");
    }

    /**
     * Returns the loyalty class with the given class ID. (loyaltyclass.get)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_LoyaltyClass
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_LoyaltyClass
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_LoyaltyClass");
    }

    /**
     * Inserts an loyalty class with the given ID and properties.
     * (loyaltyclass.insert)
     *
     * @param  Google_Service_Walletobjects_LoyaltyClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_LoyaltyClass
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_LoyaltyClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_LoyaltyClass {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_LoyaltyClass");
    }

    /**
     * Returns a list of all loyalty classes for a given issuer ID.
     * (loyaltyclass.listLoyaltyclass)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_LoyaltyClassListResponse
     * @throws \Google\Exception
     * @opt_param string issuerId The ID of the issuer authorized to list classes.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` classes are available in a list. For
     * example, if you have a list of 200 classes and you call list with
     * `maxResults` set to 20, list will return the first 20 classes and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * classes.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listLoyaltyclass($optParams = array()): Google_Service_Walletobjects_LoyaltyClassListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_LoyaltyClassListResponse");
    }

    /**
     * Updates the loyalty class referenced by the given class ID. This method
     * supports patch semantics. (loyaltyclass.patch)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_LoyaltyClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_LoyaltyClass
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_LoyaltyClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_LoyaltyClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_LoyaltyClass");
    }

    /**
     * Updates the loyalty class referenced by the given class ID.
     * (loyaltyclass.update)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_LoyaltyClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_LoyaltyClass
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_LoyaltyClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_LoyaltyClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_LoyaltyClass");
    }
}

/**
 * The "loyaltyobject" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $loyaltyobject = $walletobjectsService->loyaltyobject;
 *  </code>
 */
class Google_Service_Walletobjects_Loyaltyobject_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the loyalty object referenced by the given object ID.
     * (loyaltyobject.addmessage)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_AddMessageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_LoyaltyObjectAddMessageResponse
     * @throws \Google\Exception
     */
    public function addmessage(
        $resourceId,
        Google_Service_Walletobjects_AddMessageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_LoyaltyObjectAddMessageResponse {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('addmessage', array($params),
            "Google_Service_Walletobjects_LoyaltyObjectAddMessageResponse");
    }

    /**
     * Returns the loyalty object with the given object ID. (loyaltyobject.get)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_LoyaltyObject
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_LoyaltyObject
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_LoyaltyObject");
    }

    /**
     * Inserts an loyalty object with the given ID and properties.
     * (loyaltyobject.insert)
     *
     * @param  Google_Service_Walletobjects_LoyaltyObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_LoyaltyObject
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_LoyaltyObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_LoyaltyObject {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_LoyaltyObject");
    }

    /**
     * Returns a list of all loyalty objects for a given issuer ID.
     * (loyaltyobject.listLoyaltyobject)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_LoyaltyObjectListResponse
     * @throws \Google\Exception
     * @opt_param string classId The ID of the class whose objects will be listed.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` objects are available in a list. For
     * example, if you have a list of 200 objects and you call list with
     * `maxResults` set to 20, list will return the first 20 objects and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * objects.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listLoyaltyobject($optParams = array()): Google_Service_Walletobjects_LoyaltyObjectListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_LoyaltyObjectListResponse");
    }

    /**
     * Modifies linked offer objects for the loyalty object with the given ID.
     * (loyaltyobject.modifylinkedofferobjects)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_ModifyLinkedOfferObjectsRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_LoyaltyObject
     * @throws \Google\Exception
     */
    public function modifylinkedofferobjects(
        $resourceId,
        Google_Service_Walletobjects_ModifyLinkedOfferObjectsRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_LoyaltyObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('modifylinkedofferobjects', array($params), "Google_Service_Walletobjects_LoyaltyObject");
    }

    /**
     * Updates the loyalty object referenced by the given object ID. This method
     * supports patch semantics. (loyaltyobject.patch)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_LoyaltyObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_LoyaltyObject
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_LoyaltyObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_LoyaltyObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_LoyaltyObject");
    }

    /**
     * Updates the loyalty object referenced by the given object ID.
     * (loyaltyobject.update)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_LoyaltyObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_LoyaltyObject
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_LoyaltyObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_LoyaltyObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_LoyaltyObject");
    }
}

/**
 * The "media" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $media = $walletobjectsService->media;
 *  </code>
 */
class Google_Service_Walletobjects_Media_Resource extends Google_Service_Resource
{

    /**
     * Uploads a private image and returns an Id to be used in its place.
     * (media.upload)
     *
     * @param  string  $issuerId  The ID of the issuer sending the image.
     * @param  Google_Service_Walletobjects_UploadPrivateImageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_UploadPrivateImageResponse
     * @throws \Google\Exception
     */
    public function upload(
        $issuerId,
        Google_Service_Walletobjects_UploadPrivateImageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_UploadPrivateImageResponse {
        $params = array('issuerId' => $issuerId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('upload', array($params), "Google_Service_Walletobjects_UploadPrivateImageResponse");
    }
}

/**
 * The "offerclass" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $offerclass = $walletobjectsService->offerclass;
 *  </code>
 */
class Google_Service_Walletobjects_Offerclass_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the offer class referenced by the given class ID.
     * (offerclass.addmessage)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_AddMessageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_OfferClassAddMessageResponse
     * @throws \Google\Exception
     */
    public function addmessage(
        $resourceId,
        Google_Service_Walletobjects_AddMessageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_OfferClassAddMessageResponse {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('addmessage', array($params), "Google_Service_Walletobjects_OfferClassAddMessageResponse");
    }

    /**
     * Returns the offer class with the given class ID. (offerclass.get)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_OfferClass
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_OfferClass
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_OfferClass");
    }

    /**
     * Inserts an offer class with the given ID and properties. (offerclass.insert)
     *
     * @param  Google_Service_Walletobjects_OfferClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_OfferClass
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_OfferClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_OfferClass {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_OfferClass");
    }

    /**
     * Returns a list of all offer classes for a given issuer ID.
     * (offerclass.listOfferclass)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_OfferClassListResponse
     * @throws \Google\Exception
     * @opt_param string issuerId The ID of the issuer authorized to list classes.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` classes are available in a list. For
     * example, if you have a list of 200 classes and you call list with
     * `maxResults` set to 20, list will return the first 20 classes and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * classes.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listOfferclass($optParams = array()): Google_Service_Walletobjects_OfferClassListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_OfferClassListResponse");
    }

    /**
     * Updates the offer class referenced by the given class ID. This method
     * supports patch semantics. (offerclass.patch)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_OfferClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_OfferClass
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_OfferClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_OfferClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_OfferClass");
    }

    /**
     * Updates the offer class referenced by the given class ID. (offerclass.update)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_OfferClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_OfferClass
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_OfferClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_OfferClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_OfferClass");
    }
}

/**
 * The "offerobject" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $offerobject = $walletobjectsService->offerobject;
 *  </code>
 */
class Google_Service_Walletobjects_Offerobject_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the offer object referenced by the given object ID.
     * (offerobject.addmessage)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_AddMessageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_OfferObjectAddMessageResponse
     * @throws \Google\Exception
     */
    public function addmessage(
        $resourceId,
        Google_Service_Walletobjects_AddMessageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_OfferObjectAddMessageResponse {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('addmessage', array($params), "Google_Service_Walletobjects_OfferObjectAddMessageResponse");
    }

    /**
     * Returns the offer object with the given object ID. (offerobject.get)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_OfferObject
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_OfferObject
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_OfferObject");
    }

    /**
     * Inserts an offer object with the given ID and properties.
     * (offerobject.insert)
     *
     * @param  Google_Service_Walletobjects_OfferObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_OfferObject
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_OfferObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_OfferObject {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_OfferObject");
    }

    /**
     * Returns a list of all offer objects for a given issuer ID.
     * (offerobject.listOfferobject)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_OfferObjectListResponse
     * @throws \Google\Exception
     * @opt_param string classId The ID of the class whose objects will be listed.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` objects are available in a list. For
     * example, if you have a list of 200 objects and you call list with
     * `maxResults` set to 20, list will return the first 20 objects and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * objects.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listOfferobject($optParams = array()): Google_Service_Walletobjects_OfferObjectListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_OfferObjectListResponse");
    }

    /**
     * Updates the offer object referenced by the given object ID. This method
     * supports patch semantics. (offerobject.patch)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_OfferObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_OfferObject
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_OfferObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_OfferObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_OfferObject");
    }

    /**
     * Updates the offer object referenced by the given object ID.
     * (offerobject.update)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_OfferObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_OfferObject
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_OfferObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_OfferObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_OfferObject");
    }
}

/**
 * The "permissions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $permissions = $walletobjectsService->permissions;
 *  </code>
 */
class Google_Service_Walletobjects_Permissions_Resource extends Google_Service_Resource
{

    /**
     * Returns the permissions for the given issuer id. (permissions.get)
     *
     * @param  string  $resourceId  The unique identifier for an issuer. This ID must
     * be unique across all issuers.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_Permissions
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_Permissions
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_Permissions");
    }

    /**
     * Updates the permissions for the given issuer. (permissions.update)
     *
     * @param  string  $resourceId  The unique identifier for an issuer. This ID must
     * be unique across all issuers.
     * @param  Google_Service_Walletobjects_Permissions  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_Permissions
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_Permissions $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_Permissions {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_Permissions");
    }
}

/**
 * The "smarttap" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $smarttap = $walletobjectsService->smarttap;
 *  </code>
 */
class Google_Service_Walletobjects_Smarttap_Resource extends Google_Service_Resource
{

    /**
     * Inserts the smart tap. (smarttap.insert)
     *
     * @param  Google_Service_Walletobjects_SmartTap  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_SmartTap
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_SmartTap $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_SmartTap {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_SmartTap");
    }
}

/**
 * The "transitclass" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $transitclass = $walletobjectsService->transitclass;
 *  </code>
 */
class Google_Service_Walletobjects_Transitclass_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the transit class referenced by the given class ID.
     * (transitclass.addmessage)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_AddMessageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_TransitClassAddMessageResponse
     * @throws \Google\Exception
     */
    public function addmessage(
        $resourceId,
        Google_Service_Walletobjects_AddMessageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_TransitClassAddMessageResponse {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('addmessage', array($params), "Google_Service_Walletobjects_TransitClassAddMessageResponse");
    }

    /**
     * Returns the transit class with the given class ID. (transitclass.get)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_TransitClass
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_TransitClass
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_TransitClass");
    }

    /**
     * Inserts a transit class with the given ID and properties.
     * (transitclass.insert)
     *
     * @param  Google_Service_Walletobjects_TransitClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_TransitClass
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_TransitClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_TransitClass {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_TransitClass");
    }

    /**
     * Returns a list of all transit classes for a given issuer ID.
     * (transitclass.listTransitclass)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_TransitClassListResponse
     * @throws \Google\Exception
     * @opt_param string issuerId The ID of the issuer authorized to list classes.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` classes are available in a list. For
     * example, if you have a list of 200 classes and you call list with
     * `maxResults` set to 20, list will return the first 20 classes and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * classes.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listTransitclass($optParams = array()): Google_Service_Walletobjects_TransitClassListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_TransitClassListResponse");
    }

    /**
     * Updates the transit class referenced by the given class ID. This method
     * supports patch semantics. (transitclass.patch)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_TransitClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_TransitClass
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_TransitClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_TransitClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_TransitClass");
    }

    /**
     * Updates the transit class referenced by the given class ID.
     * (transitclass.update)
     *
     * @param  string  $resourceId  The unique identifier for a class. This ID must be
     * unique across all classes from an issuer. This value should follow the format
     * issuer ID. identifier where the former is issued by Google and latter is
     * chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_TransitClass  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_TransitClass
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_TransitClass $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_TransitClass {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_TransitClass");
    }
}

/**
 * The "transitobject" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $transitobject = $walletobjectsService->transitobject;
 *  </code>
 */
class Google_Service_Walletobjects_Transitobject_Resource extends Google_Service_Resource
{

    /**
     * Adds a message to the transit object referenced by the given object ID.
     * (transitobject.addmessage)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_AddMessageRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_TransitObjectAddMessageResponse
     * @throws \Google\Exception
     */
    public function addmessage(
        $resourceId,
        Google_Service_Walletobjects_AddMessageRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_TransitObjectAddMessageResponse {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('addmessage', array($params),
            "Google_Service_Walletobjects_TransitObjectAddMessageResponse");
    }

    /**
     * Returns the transit object with the given object ID. (transitobject.get)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_TransitObject
     * @throws \Google\Exception
     */
    public function get($resourceId, $optParams = array()): Google_Service_Walletobjects_TransitObject
    {
        $params = array('resourceId' => $resourceId);
        $params = array_merge($params, $optParams);

        return $this->call('get', array($params), "Google_Service_Walletobjects_TransitObject");
    }

    /**
     * Inserts an transit object with the given ID and properties.
     * (transitobject.insert)
     *
     * @param  Google_Service_Walletobjects_TransitObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_TransitObject
     * @throws \Google\Exception
     */
    public function insert(
        Google_Service_Walletobjects_TransitObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_TransitObject {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('insert', array($params), "Google_Service_Walletobjects_TransitObject");
    }

    /**
     * Returns a list of all transit objects for a given issuer ID.
     * (transitobject.listTransitobject)
     *
     * @param  array  $optParams  Optional parameters.
     *
     * @return Google_Service_Walletobjects_TransitObjectListResponse
     * @throws \Google\Exception
     * @opt_param string classId The ID of the class whose objects will be listed.
     * @opt_param string token Used to get the next set of results if `maxResults`
     * is specified, but more than `maxResults` objects are available in a list. For
     * example, if you have a list of 200 objects and you call list with
     * `maxResults` set to 20, list will return the first 20 objects and a token.
     * Call list again with `maxResults` set to 20 and the token to get the next 20
     * objects.
     * @opt_param int maxResults Identifies the max number of results returned by a
     * list. All results are returned if `maxResults` isn't defined.
     */
    public function listTransitobject($optParams = array()): Google_Service_Walletobjects_TransitObjectListResponse
    {
        $params = array();
        $params = array_merge($params, $optParams);

        return $this->call('list', array($params), "Google_Service_Walletobjects_TransitObjectListResponse");
    }

    /**
     * Updates the transit object referenced by the given object ID. This method
     * supports patch semantics. (transitobject.patch)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_TransitObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_TransitObject
     * @throws \Google\Exception
     */
    public function patch(
        $resourceId,
        Google_Service_Walletobjects_TransitObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_TransitObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('patch', array($params), "Google_Service_Walletobjects_TransitObject");
    }

    /**
     * Updates the transit object referenced by the given object ID.
     * (transitobject.update)
     *
     * @param  string  $resourceId  The unique identifier for an object. This ID must
     * be unique across all objects from an issuer. This value should follow the
     * format issuer ID. identifier where the former is issued by Google and latter
     * is chosen by you. Your unique identifier should only include alphanumeric
     * characters, '.', '_', or '-'.
     * @param  Google_Service_Walletobjects_TransitObject  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_TransitObject
     * @throws \Google\Exception
     */
    public function update(
        $resourceId,
        Google_Service_Walletobjects_TransitObject $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_TransitObject {
        $params = array('resourceId' => $resourceId, 'postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('update', array($params), "Google_Service_Walletobjects_TransitObject");
    }
}

/**
 * The "walletobjects" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $walletobjects = $walletobjectsService->walletobjects;
 *  </code>
 */
class Google_Service_Walletobjects_Walletobjects_Resource extends Google_Service_Resource
{
}

/**
 * The "v1" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $v1 = $walletobjectsService->v1;
 *  </code>
 */
class Google_Service_Walletobjects_WalletobjectsV1_Resource extends Google_Service_Resource
{
}

/**
 * The "privateContent" collection of methods.
 * Typical usage is:
 *  <code>
 *   $walletobjectsService = new Google_Service_Walletobjects(...);
 *   $privateContent = $walletobjectsService->privateContent;
 *  </code>
 */
class Google_Service_Walletobjects_WalletobjectsV1PrivateContent_Resource extends Google_Service_Resource
{

    /**
     * Upload private data (text or URI) and returns an Id to be used in its place.
     * (privateContent.uploadPrivateData)
     *
     * @param  Google_Service_Walletobjects_UploadPrivateDataRequest  $postBody
     * @param  array  $optParams  Optional parameters.
     * @return Google_Service_Walletobjects_UploadPrivateDataResponse
     * @throws \Google\Exception
     */
    public function uploadPrivateData(
        Google_Service_Walletobjects_UploadPrivateDataRequest $postBody,
        $optParams = array()
    ): Google_Service_Walletobjects_UploadPrivateDataResponse {
        $params = array('postBody' => $postBody);
        $params = array_merge($params, $optParams);

        return $this->call('uploadPrivateData', array($params),
            "Google_Service_Walletobjects_UploadPrivateDataResponse");
    }
}


class Google_Service_Walletobjects_ActivationOptions extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $activationUrl;
    public $allowReactivation;


    public function setActivationUrl($activationUrl): void
    {
        $this->activationUrl = $activationUrl;
    }

    public function getActivationUrl()
    {
        return $this->activationUrl;
    }

    public function setAllowReactivation($allowReactivation): void
    {
        $this->allowReactivation = $allowReactivation;
    }

    public function getAllowReactivation()
    {
        return $this->allowReactivation;
    }
}

class Google_Service_Walletobjects_ActivationStatus extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $state;


    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }
}

class Google_Service_Walletobjects_AddMessageRequest extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $messageType = 'Google_Service_Walletobjects_Message';
    protected $messageDataType = '';


    public function setMessage(Google_Service_Walletobjects_Message $message): void
    {
        $this->message = $message;
    }

    public function getMessage(): Google_Service_Walletobjects_Message
    {
        return $this->message;
    }
}

class Google_Service_Walletobjects_AirportInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $airportIataCode;
    protected $airportNameOverrideType = 'Google_Service_Walletobjects_LocalizedString';
    protected $airportNameOverrideDataType = '';
    public $gate;
    public $kind;
    public $terminal;


    public function setAirportIataCode($airportIataCode): void
    {
        $this->airportIataCode = $airportIataCode;
    }

    public function getAirportIataCode()
    {
        return $this->airportIataCode;
    }

    public function setAirportNameOverride(Google_Service_Walletobjects_LocalizedString $airportNameOverride): void
    {
        $this->airportNameOverride = $airportNameOverride;
    }

    public function getAirportNameOverride(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->airportNameOverride;
    }

    public function setGate($gate): void
    {
        $this->gate = $gate;
    }

    public function getGate()
    {
        return $this->gate;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setTerminal($terminal): void
    {
        $this->terminal = $terminal;
    }

    public function getTerminal()
    {
        return $this->terminal;
    }
}

class Google_Service_Walletobjects_AppLinkData extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $androidAppLinkInfoType = 'Google_Service_Walletobjects_AppLinkDataAppLinkInfo';
    protected $androidAppLinkInfoDataType = '';
    protected $iosAppLinkInfoType = 'Google_Service_Walletobjects_AppLinkDataAppLinkInfo';
    protected $iosAppLinkInfoDataType = '';
    protected $webAppLinkInfoType = 'Google_Service_Walletobjects_AppLinkDataAppLinkInfo';
    protected $webAppLinkInfoDataType = '';


    public function setAndroidAppLinkInfo(Google_Service_Walletobjects_AppLinkDataAppLinkInfo $androidAppLinkInfo): void
    {
        $this->androidAppLinkInfo = $androidAppLinkInfo;
    }

    public function getAndroidAppLinkInfo(): Google_Service_Walletobjects_AppLinkDataAppLinkInfo
    {
        return $this->androidAppLinkInfo;
    }

    public function setIosAppLinkInfo(Google_Service_Walletobjects_AppLinkDataAppLinkInfo $iosAppLinkInfo): void
    {
        $this->iosAppLinkInfo = $iosAppLinkInfo;
    }

    public function getIosAppLinkInfo(): Google_Service_Walletobjects_AppLinkDataAppLinkInfo
    {
        return $this->iosAppLinkInfo;
    }

    public function setWebAppLinkInfo(Google_Service_Walletobjects_AppLinkDataAppLinkInfo $webAppLinkInfo): void
    {
        $this->webAppLinkInfo = $webAppLinkInfo;
    }

    public function getWebAppLinkInfo(): Google_Service_Walletobjects_AppLinkDataAppLinkInfo
    {
        return $this->webAppLinkInfo;
    }
}

class Google_Service_Walletobjects_AppLinkDataAppLinkInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $appLogoImageType = 'Google_Service_Walletobjects_Image';
    protected $appLogoImageDataType = '';
    protected $appTargetType = 'Google_Service_Walletobjects_AppLinkDataAppLinkInfoAppTarget';
    protected $appTargetDataType = '';
    protected $descriptionType = 'Google_Service_Walletobjects_LocalizedString';
    protected $descriptionDataType = '';
    protected $titleType = 'Google_Service_Walletobjects_LocalizedString';
    protected $titleDataType = '';


    public function setAppLogoImage(Google_Service_Walletobjects_Image $appLogoImage): void
    {
        $this->appLogoImage = $appLogoImage;
    }

    public function getAppLogoImage(): Google_Service_Walletobjects_Image
    {
        return $this->appLogoImage;
    }

    public function setAppTarget(Google_Service_Walletobjects_AppLinkDataAppLinkInfoAppTarget $appTarget): void
    {
        $this->appTarget = $appTarget;
    }

    public function getAppTarget(): Google_Service_Walletobjects_AppLinkDataAppLinkInfoAppTarget
    {
        return $this->appTarget;
    }

    public function setDescription(Google_Service_Walletobjects_LocalizedString $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->description;
    }

    public function setTitle(Google_Service_Walletobjects_LocalizedString $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->title;
    }
}

class Google_Service_Walletobjects_AppLinkDataAppLinkInfoAppTarget extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $targetUriType = 'Google_Service_Walletobjects_Uri';
    protected $targetUriDataType = '';


    public function setTargetUri(Google_Service_Walletobjects_Uri $targetUri): void
    {
        $this->targetUri = $targetUri;
    }

    public function getTargetUri(): Google_Service_Walletobjects_Uri
    {
        return $this->targetUri;
    }
}

class Google_Service_Walletobjects_AuthenticationKey extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $id;
    public $publicKeyPem;


    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPublicKeyPem($publicKeyPem): void
    {
        $this->publicKeyPem = $publicKeyPem;
    }

    public function getPublicKeyPem()
    {
        return $this->publicKeyPem;
    }
}

class Google_Service_Walletobjects_Barcode extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $alternateText;
    public $kind;
    public $renderEncoding;
    protected $showCodeTextType = 'Google_Service_Walletobjects_LocalizedString';
    protected $showCodeTextDataType = '';
    public $type;
    public $value;


    public function setAlternateText($alternateText): void
    {
        $this->alternateText = $alternateText;
    }

    public function getAlternateText()
    {
        return $this->alternateText;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setRenderEncoding($renderEncoding): void
    {
        $this->renderEncoding = $renderEncoding;
    }

    public function getRenderEncoding()
    {
        return $this->renderEncoding;
    }

    public function setShowCodeText(Google_Service_Walletobjects_LocalizedString $showCodeText): void
    {
        $this->showCodeText = $showCodeText;
    }

    public function getShowCodeText(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->showCodeText;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}

class Google_Service_Walletobjects_BarcodeSectionDetail extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $fieldSelectorType = 'Google_Service_Walletobjects_FieldSelector';
    protected $fieldSelectorDataType = '';


    public function setFieldSelector(Google_Service_Walletobjects_FieldSelector $fieldSelector): void
    {
        $this->fieldSelector = $fieldSelector;
    }

    public function getFieldSelector(): Google_Service_Walletobjects_FieldSelector
    {
        return $this->fieldSelector;
    }
}

class Google_Service_Walletobjects_Blobstore2Info extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $blobGeneration;
    public $blobId;
    public $downloadReadHandle;
    public $readToken;
    public $uploadMetadataContainer;


    public function setBlobGeneration($blobGeneration): void
    {
        $this->blobGeneration = $blobGeneration;
    }

    public function getBlobGeneration()
    {
        return $this->blobGeneration;
    }

    public function setBlobId($blobId): void
    {
        $this->blobId = $blobId;
    }

    public function getBlobId()
    {
        return $this->blobId;
    }

    public function setDownloadReadHandle($downloadReadHandle): void
    {
        $this->downloadReadHandle = $downloadReadHandle;
    }

    public function getDownloadReadHandle()
    {
        return $this->downloadReadHandle;
    }

    public function setReadToken($readToken): void
    {
        $this->readToken = $readToken;
    }

    public function getReadToken()
    {
        return $this->readToken;
    }

    public function setUploadMetadataContainer($uploadMetadataContainer): void
    {
        $this->uploadMetadataContainer = $uploadMetadataContainer;
    }

    public function getUploadMetadataContainer()
    {
        return $this->uploadMetadataContainer;
    }
}

class Google_Service_Walletobjects_BoardingAndSeatingInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $boardingDoor;
    public $boardingGroup;
    public $boardingPosition;
    protected $boardingPrivilegeImageType = 'Google_Service_Walletobjects_Image';
    protected $boardingPrivilegeImageDataType = '';
    public $kind;
    protected $seatAssignmentType = 'Google_Service_Walletobjects_LocalizedString';
    protected $seatAssignmentDataType = '';
    public $seatClass;
    public $seatNumber;
    public $sequenceNumber;


    public function setBoardingDoor($boardingDoor): void
    {
        $this->boardingDoor = $boardingDoor;
    }

    public function getBoardingDoor()
    {
        return $this->boardingDoor;
    }

    public function setBoardingGroup($boardingGroup): void
    {
        $this->boardingGroup = $boardingGroup;
    }

    public function getBoardingGroup()
    {
        return $this->boardingGroup;
    }

    public function setBoardingPosition($boardingPosition): void
    {
        $this->boardingPosition = $boardingPosition;
    }

    public function getBoardingPosition()
    {
        return $this->boardingPosition;
    }

    public function setBoardingPrivilegeImage(Google_Service_Walletobjects_Image $boardingPrivilegeImage): void
    {
        $this->boardingPrivilegeImage = $boardingPrivilegeImage;
    }

    public function getBoardingPrivilegeImage(): Google_Service_Walletobjects_Image
    {
        return $this->boardingPrivilegeImage;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setSeatAssignment(Google_Service_Walletobjects_LocalizedString $seatAssignment): void
    {
        $this->seatAssignment = $seatAssignment;
    }

    public function getSeatAssignment(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->seatAssignment;
    }

    public function setSeatClass($seatClass): void
    {
        $this->seatClass = $seatClass;
    }

    public function getSeatClass()
    {
        return $this->seatClass;
    }

    public function setSeatNumber($seatNumber): void
    {
        $this->seatNumber = $seatNumber;
    }

    public function getSeatNumber()
    {
        return $this->seatNumber;
    }

    public function setSequenceNumber($sequenceNumber): void
    {
        $this->sequenceNumber = $sequenceNumber;
    }

    public function getSequenceNumber()
    {
        return $this->sequenceNumber;
    }
}

class Google_Service_Walletobjects_BoardingAndSeatingPolicy extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $boardingPolicy;
    public $kind;
    public $seatClassPolicy;


    public function setBoardingPolicy($boardingPolicy): void
    {
        $this->boardingPolicy = $boardingPolicy;
    }

    public function getBoardingPolicy()
    {
        return $this->boardingPolicy;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setSeatClassPolicy($seatClassPolicy): void
    {
        $this->seatClassPolicy = $seatClassPolicy;
    }

    public function getSeatClassPolicy()
    {
        return $this->seatClassPolicy;
    }
}

class Google_Service_Walletobjects_CallbackOptions extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $updateRequestUrl;
    public $url;


    public function setUpdateRequestUrl($updateRequestUrl): void
    {
        $this->updateRequestUrl = $updateRequestUrl;
    }

    public function getUpdateRequestUrl()
    {
        return $this->updateRequestUrl;
    }

    public function setUrl($url): void
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }
}

class Google_Service_Walletobjects_CardBarcodeSectionDetails extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $firstBottomDetailType = 'Google_Service_Walletobjects_BarcodeSectionDetail';
    protected $firstBottomDetailDataType = '';
    protected $firstTopDetailType = 'Google_Service_Walletobjects_BarcodeSectionDetail';
    protected $firstTopDetailDataType = '';
    protected $secondTopDetailType = 'Google_Service_Walletobjects_BarcodeSectionDetail';
    protected $secondTopDetailDataType = '';


    public function setFirstBottomDetail(Google_Service_Walletobjects_BarcodeSectionDetail $firstBottomDetail): void
    {
        $this->firstBottomDetail = $firstBottomDetail;
    }

    public function getFirstBottomDetail(): Google_Service_Walletobjects_BarcodeSectionDetail
    {
        return $this->firstBottomDetail;
    }

    public function setFirstTopDetail(Google_Service_Walletobjects_BarcodeSectionDetail $firstTopDetail): void
    {
        $this->firstTopDetail = $firstTopDetail;
    }

    public function getFirstTopDetail(): Google_Service_Walletobjects_BarcodeSectionDetail
    {
        return $this->firstTopDetail;
    }

    public function setSecondTopDetail(Google_Service_Walletobjects_BarcodeSectionDetail $secondTopDetail): void
    {
        $this->secondTopDetail = $secondTopDetail;
    }

    public function getSecondTopDetail(): Google_Service_Walletobjects_BarcodeSectionDetail
    {
        return $this->secondTopDetail;
    }
}

class Google_Service_Walletobjects_CardRowOneItem extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $itemType = 'Google_Service_Walletobjects_TemplateItem';
    protected $itemDataType = '';


    public function setItem(Google_Service_Walletobjects_TemplateItem $item): void
    {
        $this->item = $item;
    }

    public function getItem(): Google_Service_Walletobjects_TemplateItem
    {
        return $this->item;
    }
}

class Google_Service_Walletobjects_CardRowTemplateInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $oneItemType = 'Google_Service_Walletobjects_CardRowOneItem';
    protected $oneItemDataType = '';
    protected $threeItemsType = 'Google_Service_Walletobjects_CardRowThreeItems';
    protected $threeItemsDataType = '';
    protected $twoItemsType = 'Google_Service_Walletobjects_CardRowTwoItems';
    protected $twoItemsDataType = '';


    public function setOneItem(Google_Service_Walletobjects_CardRowOneItem $oneItem): void
    {
        $this->oneItem = $oneItem;
    }

    public function getOneItem(): Google_Service_Walletobjects_CardRowOneItem
    {
        return $this->oneItem;
    }

    public function setThreeItems(Google_Service_Walletobjects_CardRowThreeItems $threeItems): void
    {
        $this->threeItems = $threeItems;
    }

    public function getThreeItems(): Google_Service_Walletobjects_CardRowThreeItems
    {
        return $this->threeItems;
    }

    public function setTwoItems(Google_Service_Walletobjects_CardRowTwoItems $twoItems): void
    {
        $this->twoItems = $twoItems;
    }

    public function getTwoItems(): Google_Service_Walletobjects_CardRowTwoItems
    {
        return $this->twoItems;
    }
}

class Google_Service_Walletobjects_CardRowThreeItems extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $endItemType = 'Google_Service_Walletobjects_TemplateItem';
    protected $endItemDataType = '';
    protected $middleItemType = 'Google_Service_Walletobjects_TemplateItem';
    protected $middleItemDataType = '';
    protected $startItemType = 'Google_Service_Walletobjects_TemplateItem';
    protected $startItemDataType = '';


    public function setEndItem(Google_Service_Walletobjects_TemplateItem $endItem): void
    {
        $this->endItem = $endItem;
    }

    public function getEndItem(): Google_Service_Walletobjects_TemplateItem
    {
        return $this->endItem;
    }

    public function setMiddleItem(Google_Service_Walletobjects_TemplateItem $middleItem): void
    {
        $this->middleItem = $middleItem;
    }

    public function getMiddleItem(): Google_Service_Walletobjects_TemplateItem
    {
        return $this->middleItem;
    }

    public function setStartItem(Google_Service_Walletobjects_TemplateItem $startItem): void
    {
        $this->startItem = $startItem;
    }

    public function getStartItem(): Google_Service_Walletobjects_TemplateItem
    {
        return $this->startItem;
    }
}

class Google_Service_Walletobjects_CardRowTwoItems extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $endItemType = 'Google_Service_Walletobjects_TemplateItem';
    protected $endItemDataType = '';
    protected $startItemType = 'Google_Service_Walletobjects_TemplateItem';
    protected $startItemDataType = '';


    public function setEndItem(Google_Service_Walletobjects_TemplateItem $endItem): void
    {
        $this->endItem = $endItem;
    }

    public function getEndItem(): Google_Service_Walletobjects_TemplateItem
    {
        return $this->endItem;
    }

    public function setStartItem(Google_Service_Walletobjects_TemplateItem $startItem): void
    {
        $this->startItem = $startItem;
    }

    public function getStartItem(): Google_Service_Walletobjects_TemplateItem
    {
        return $this->startItem;
    }
}

class Google_Service_Walletobjects_CardTemplateOverride extends Google_Collection
{
    protected $collection_key = 'cardRowTemplateInfos';
    protected $internal_gapi_mappings = array();
    protected $cardRowTemplateInfosType = 'Google_Service_Walletobjects_CardRowTemplateInfo';
    protected $cardRowTemplateInfosDataType = 'array';


    public function setCardRowTemplateInfos($cardRowTemplateInfos): void
    {
        $this->cardRowTemplateInfos = $cardRowTemplateInfos;
    }

    public function getCardRowTemplateInfos()
    {
        return $this->cardRowTemplateInfos;
    }
}

class Google_Service_Walletobjects_ClassTemplateInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $cardBarcodeSectionDetailsType = 'Google_Service_Walletobjects_CardBarcodeSectionDetails';
    protected $cardBarcodeSectionDetailsDataType = '';
    protected $cardTemplateOverrideType = 'Google_Service_Walletobjects_CardTemplateOverride';
    protected $cardTemplateOverrideDataType = '';
    protected $detailsTemplateOverrideType = 'Google_Service_Walletobjects_DetailsTemplateOverride';
    protected $detailsTemplateOverrideDataType = '';
    protected $listTemplateOverrideType = 'Google_Service_Walletobjects_ListTemplateOverride';
    protected $listTemplateOverrideDataType = '';


    public function setCardBarcodeSectionDetails(
        Google_Service_Walletobjects_CardBarcodeSectionDetails $cardBarcodeSectionDetails
    ): void {
        $this->cardBarcodeSectionDetails = $cardBarcodeSectionDetails;
    }

    public function getCardBarcodeSectionDetails(): Google_Service_Walletobjects_CardBarcodeSectionDetails
    {
        return $this->cardBarcodeSectionDetails;
    }

    public function setCardTemplateOverride(Google_Service_Walletobjects_CardTemplateOverride $cardTemplateOverride
    ): void {
        $this->cardTemplateOverride = $cardTemplateOverride;
    }

    public function getCardTemplateOverride(): Google_Service_Walletobjects_CardTemplateOverride
    {
        return $this->cardTemplateOverride;
    }

    public function setDetailsTemplateOverride(
        Google_Service_Walletobjects_DetailsTemplateOverride $detailsTemplateOverride
    ): void {
        $this->detailsTemplateOverride = $detailsTemplateOverride;
    }

    public function getDetailsTemplateOverride(): Google_Service_Walletobjects_DetailsTemplateOverride
    {
        return $this->detailsTemplateOverride;
    }

    public function setListTemplateOverride(Google_Service_Walletobjects_ListTemplateOverride $listTemplateOverride
    ): void {
        $this->listTemplateOverride = $listTemplateOverride;
    }

    public function getListTemplateOverride(): Google_Service_Walletobjects_ListTemplateOverride
    {
        return $this->listTemplateOverride;
    }
}

class Google_Service_Walletobjects_CompositeMedia extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $blobRef;
    protected $blobstore2InfoType = 'Google_Service_Walletobjects_Blobstore2Info';
    protected $blobstore2InfoDataType = '';
    public $cosmoBinaryReference;
    public $crc32cHash;
    public $inline;
    public $length;
    public $md5Hash;
    protected $objectIdType = 'Google_Service_Walletobjects_ObjectId';
    protected $objectIdDataType = '';
    public $path;
    public $referenceType;
    public $sha1Hash;


    public function setBlobRef($blobRef): void
    {
        $this->blobRef = $blobRef;
    }

    public function getBlobRef()
    {
        return $this->blobRef;
    }

    public function setBlobstore2Info(Google_Service_Walletobjects_Blobstore2Info $blobstore2Info): void
    {
        $this->blobstore2Info = $blobstore2Info;
    }

    public function getBlobstore2Info(): Google_Service_Walletobjects_Blobstore2Info
    {
        return $this->blobstore2Info;
    }

    public function setCosmoBinaryReference($cosmoBinaryReference): void
    {
        $this->cosmoBinaryReference = $cosmoBinaryReference;
    }

    public function getCosmoBinaryReference()
    {
        return $this->cosmoBinaryReference;
    }

    public function setCrc32cHash($crc32cHash): void
    {
        $this->crc32cHash = $crc32cHash;
    }

    public function getCrc32cHash()
    {
        return $this->crc32cHash;
    }

    public function setInline($inline): void
    {
        $this->inline = $inline;
    }

    public function getInline()
    {
        return $this->inline;
    }

    public function setLength($length): void
    {
        $this->length = $length;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function setMd5Hash($md5Hash): void
    {
        $this->md5Hash = $md5Hash;
    }

    public function getMd5Hash()
    {
        return $this->md5Hash;
    }

    public function setObjectId(Google_Service_Walletobjects_ObjectId $objectId): void
    {
        $this->objectId = $objectId;
    }

    public function getObjectId(): Google_Service_Walletobjects_ObjectId
    {
        return $this->objectId;
    }

    public function setPath($path): void
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setReferenceType($referenceType): void
    {
        $this->referenceType = $referenceType;
    }

    public function getReferenceType()
    {
        return $this->referenceType;
    }

    public function setSha1Hash($sha1Hash): void
    {
        $this->sha1Hash = $sha1Hash;
    }

    public function getSha1Hash()
    {
        return $this->sha1Hash;
    }
}

class Google_Service_Walletobjects_ContentTypeInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $bestGuess;
    public $fromBytes;
    public $fromFileName;
    public $fromHeader;
    public $fromUrlPath;


    public function setBestGuess($bestGuess): void
    {
        $this->bestGuess = $bestGuess;
    }

    public function getBestGuess()
    {
        return $this->bestGuess;
    }

    public function setFromBytes($fromBytes): void
    {
        $this->fromBytes = $fromBytes;
    }

    public function getFromBytes()
    {
        return $this->fromBytes;
    }

    public function setFromFileName($fromFileName): void
    {
        $this->fromFileName = $fromFileName;
    }

    public function getFromFileName()
    {
        return $this->fromFileName;
    }

    public function setFromHeader($fromHeader): void
    {
        $this->fromHeader = $fromHeader;
    }

    public function getFromHeader()
    {
        return $this->fromHeader;
    }

    public function setFromUrlPath($fromUrlPath): void
    {
        $this->fromUrlPath = $fromUrlPath;
    }

    public function getFromUrlPath()
    {
        return $this->fromUrlPath;
    }
}

class Google_Service_Walletobjects_DateTime extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $date;


    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }
}

class Google_Service_Walletobjects_DetailsItemInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $itemType = 'Google_Service_Walletobjects_TemplateItem';
    protected $itemDataType = '';


    public function setItem(Google_Service_Walletobjects_TemplateItem $item): void
    {
        $this->item = $item;
    }

    public function getItem(): Google_Service_Walletobjects_TemplateItem
    {
        return $this->item;
    }
}

class Google_Service_Walletobjects_DetailsTemplateOverride extends Google_Collection
{
    protected $collection_key = 'detailsItemInfos';
    protected $internal_gapi_mappings = array();
    protected $detailsItemInfosType = 'Google_Service_Walletobjects_DetailsItemInfo';
    protected $detailsItemInfosDataType = 'array';


    public function setDetailsItemInfos($detailsItemInfos): void
    {
        $this->detailsItemInfos = $detailsItemInfos;
    }

    public function getDetailsItemInfos()
    {
        return $this->detailsItemInfos;
    }
}

class Google_Service_Walletobjects_DeviceContext extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $deviceToken;


    public function setDeviceToken($deviceToken): void
    {
        $this->deviceToken = $deviceToken;
    }

    public function getDeviceToken()
    {
        return $this->deviceToken;
    }
}

class Google_Service_Walletobjects_DiffChecksumsResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $checksumsLocationType = 'Google_Service_Walletobjects_CompositeMedia';
    protected $checksumsLocationDataType = '';
    public $chunkSizeBytes;
    protected $objectLocationType = 'Google_Service_Walletobjects_CompositeMedia';
    protected $objectLocationDataType = '';
    public $objectSizeBytes;
    public $objectVersion;


    public function setChecksumsLocation(Google_Service_Walletobjects_CompositeMedia $checksumsLocation): void
    {
        $this->checksumsLocation = $checksumsLocation;
    }

    public function getChecksumsLocation(): Google_Service_Walletobjects_CompositeMedia
    {
        return $this->checksumsLocation;
    }

    public function setChunkSizeBytes($chunkSizeBytes): void
    {
        $this->chunkSizeBytes = $chunkSizeBytes;
    }

    public function getChunkSizeBytes()
    {
        return $this->chunkSizeBytes;
    }

    public function setObjectLocation(Google_Service_Walletobjects_CompositeMedia $objectLocation): void
    {
        $this->objectLocation = $objectLocation;
    }

    public function getObjectLocation(): Google_Service_Walletobjects_CompositeMedia
    {
        return $this->objectLocation;
    }

    public function setObjectSizeBytes($objectSizeBytes): void
    {
        $this->objectSizeBytes = $objectSizeBytes;
    }

    public function getObjectSizeBytes()
    {
        return $this->objectSizeBytes;
    }

    public function setObjectVersion($objectVersion): void
    {
        $this->objectVersion = $objectVersion;
    }

    public function getObjectVersion()
    {
        return $this->objectVersion;
    }
}

class Google_Service_Walletobjects_DiffDownloadResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $objectLocationType = 'Google_Service_Walletobjects_CompositeMedia';
    protected $objectLocationDataType = '';


    public function setObjectLocation(Google_Service_Walletobjects_CompositeMedia $objectLocation): void
    {
        $this->objectLocation = $objectLocation;
    }

    public function getObjectLocation(): Google_Service_Walletobjects_CompositeMedia
    {
        return $this->objectLocation;
    }
}

class Google_Service_Walletobjects_DiffUploadRequest extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $checksumsInfoType = 'Google_Service_Walletobjects_CompositeMedia';
    protected $checksumsInfoDataType = '';
    protected $objectInfoType = 'Google_Service_Walletobjects_CompositeMedia';
    protected $objectInfoDataType = '';
    public $objectVersion;


    public function setChecksumsInfo(Google_Service_Walletobjects_CompositeMedia $checksumsInfo): void
    {
        $this->checksumsInfo = $checksumsInfo;
    }

    public function getChecksumsInfo(): Google_Service_Walletobjects_CompositeMedia
    {
        return $this->checksumsInfo;
    }

    public function setObjectInfo(Google_Service_Walletobjects_CompositeMedia $objectInfo): void
    {
        $this->objectInfo = $objectInfo;
    }

    public function getObjectInfo(): Google_Service_Walletobjects_CompositeMedia
    {
        return $this->objectInfo;
    }

    public function setObjectVersion($objectVersion): void
    {
        $this->objectVersion = $objectVersion;
    }

    public function getObjectVersion()
    {
        return $this->objectVersion;
    }
}

class Google_Service_Walletobjects_DiffUploadResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $objectVersion;
    protected $originalObjectType = 'Google_Service_Walletobjects_CompositeMedia';
    protected $originalObjectDataType = '';


    public function setObjectVersion($objectVersion): void
    {
        $this->objectVersion = $objectVersion;
    }

    public function getObjectVersion()
    {
        return $this->objectVersion;
    }

    public function setOriginalObject(Google_Service_Walletobjects_CompositeMedia $originalObject): void
    {
        $this->originalObject = $originalObject;
    }

    public function getOriginalObject(): Google_Service_Walletobjects_CompositeMedia
    {
        return $this->originalObject;
    }
}

class Google_Service_Walletobjects_DiffVersionResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $objectSizeBytes;
    public $objectVersion;


    public function setObjectSizeBytes($objectSizeBytes): void
    {
        $this->objectSizeBytes = $objectSizeBytes;
    }

    public function getObjectSizeBytes()
    {
        return $this->objectSizeBytes;
    }

    public function setObjectVersion($objectVersion): void
    {
        $this->objectVersion = $objectVersion;
    }

    public function getObjectVersion()
    {
        return $this->objectVersion;
    }
}

class Google_Service_Walletobjects_DiscoverableProgram extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $merchantSigninInfoType = 'Google_Service_Walletobjects_DiscoverableProgramMerchantSigninInfo';
    protected $merchantSigninInfoDataType = '';
    protected $merchantSignupInfoType = 'Google_Service_Walletobjects_DiscoverableProgramMerchantSignupInfo';
    protected $merchantSignupInfoDataType = '';
    public $state;


    public function setMerchantSigninInfo(
        Google_Service_Walletobjects_DiscoverableProgramMerchantSigninInfo $merchantSigninInfo
    ): void {
        $this->merchantSigninInfo = $merchantSigninInfo;
    }

    public function getMerchantSigninInfo(): Google_Service_Walletobjects_DiscoverableProgramMerchantSigninInfo
    {
        return $this->merchantSigninInfo;
    }

    public function setMerchantSignupInfo(
        Google_Service_Walletobjects_DiscoverableProgramMerchantSignupInfo $merchantSignupInfo
    ): void {
        $this->merchantSignupInfo = $merchantSignupInfo;
    }

    public function getMerchantSignupInfo(): Google_Service_Walletobjects_DiscoverableProgramMerchantSignupInfo
    {
        return $this->merchantSignupInfo;
    }

    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }
}

class Google_Service_Walletobjects_DiscoverableProgramMerchantSigninInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $signinWebsiteType = 'Google_Service_Walletobjects_Uri';
    protected $signinWebsiteDataType = '';


    public function setSigninWebsite(Google_Service_Walletobjects_Uri $signinWebsite): void
    {
        $this->signinWebsite = $signinWebsite;
    }

    public function getSigninWebsite(): Google_Service_Walletobjects_Uri
    {
        return $this->signinWebsite;
    }
}

class Google_Service_Walletobjects_DiscoverableProgramMerchantSignupInfo extends Google_Collection
{
    protected $collection_key = 'signupSharedDatas';
    protected $internal_gapi_mappings = array();
    public $signupSharedDatas;
    protected $signupWebsiteType = 'Google_Service_Walletobjects_Uri';
    protected $signupWebsiteDataType = '';


    public function setSignupSharedDatas($signupSharedDatas): void
    {
        $this->signupSharedDatas = $signupSharedDatas;
    }

    public function getSignupSharedDatas()
    {
        return $this->signupSharedDatas;
    }

    public function setSignupWebsite(Google_Service_Walletobjects_Uri $signupWebsite): void
    {
        $this->signupWebsite = $signupWebsite;
    }

    public function getSignupWebsite(): Google_Service_Walletobjects_Uri
    {
        return $this->signupWebsite;
    }
}

class Google_Service_Walletobjects_DownloadParameters extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $allowGzipCompression;
    public $ignoreRange;


    public function setAllowGzipCompression($allowGzipCompression): void
    {
        $this->allowGzipCompression = $allowGzipCompression;
    }

    public function getAllowGzipCompression()
    {
        return $this->allowGzipCompression;
    }

    public function setIgnoreRange($ignoreRange): void
    {
        $this->ignoreRange = $ignoreRange;
    }

    public function getIgnoreRange()
    {
        return $this->ignoreRange;
    }
}

class Google_Service_Walletobjects_EventDateTime extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $customDoorsOpenLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customDoorsOpenLabelDataType = '';
    public $doorsOpen;
    public $doorsOpenLabel;
    public $end;
    public $kind;
    public $start;


    public function setCustomDoorsOpenLabel(Google_Service_Walletobjects_LocalizedString $customDoorsOpenLabel): void
    {
        $this->customDoorsOpenLabel = $customDoorsOpenLabel;
    }

    public function getCustomDoorsOpenLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customDoorsOpenLabel;
    }

    public function setDoorsOpen($doorsOpen): void
    {
        $this->doorsOpen = $doorsOpen;
    }

    public function getDoorsOpen()
    {
        return $this->doorsOpen;
    }

    public function setDoorsOpenLabel($doorsOpenLabel): void
    {
        $this->doorsOpenLabel = $doorsOpenLabel;
    }

    public function getDoorsOpenLabel()
    {
        return $this->doorsOpenLabel;
    }

    public function setEnd($end): void
    {
        $this->end = $end;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setStart($start): void
    {
        $this->start = $start;
    }

    public function getStart()
    {
        return $this->start;
    }
}

class Google_Service_Walletobjects_EventReservationInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $confirmationCode;
    public $kind;


    public function setConfirmationCode($confirmationCode): void
    {
        $this->confirmationCode = $confirmationCode;
    }

    public function getConfirmationCode()
    {
        return $this->confirmationCode;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }
}

class Google_Service_Walletobjects_EventSeat extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $gateType = 'Google_Service_Walletobjects_LocalizedString';
    protected $gateDataType = '';
    public $kind;
    protected $rowType = 'Google_Service_Walletobjects_LocalizedString';
    protected $rowDataType = '';
    protected $seatType = 'Google_Service_Walletobjects_LocalizedString';
    protected $seatDataType = '';
    protected $sectionType = 'Google_Service_Walletobjects_LocalizedString';
    protected $sectionDataType = '';


    public function setGate(Google_Service_Walletobjects_LocalizedString $gate): void
    {
        $this->gate = $gate;
    }

    public function getGate(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->gate;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setRow(Google_Service_Walletobjects_LocalizedString $row): void
    {
        $this->row = $row;
    }

    public function getRow(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->row;
    }

    public function setSeat(Google_Service_Walletobjects_LocalizedString $seat): void
    {
        $this->seat = $seat;
    }

    public function getSeat(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->seat;
    }

    public function setSection(Google_Service_Walletobjects_LocalizedString $section): void
    {
        $this->section = $section;
    }

    public function getSection(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->section;
    }
}

class Google_Service_Walletobjects_EventTicketClass extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    public $allowMultipleUsersPerObject;
    protected $callbackOptionsType = 'Google_Service_Walletobjects_CallbackOptions';
    protected $callbackOptionsDataType = '';
    protected $classTemplateInfoType = 'Google_Service_Walletobjects_ClassTemplateInfo';
    protected $classTemplateInfoDataType = '';
    public $confirmationCodeLabel;
    public $countryCode;
    protected $customConfirmationCodeLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customConfirmationCodeLabelDataType = '';
    protected $customGateLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customGateLabelDataType = '';
    protected $customRowLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customRowLabelDataType = '';
    protected $customSeatLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customSeatLabelDataType = '';
    protected $customSectionLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customSectionLabelDataType = '';
    protected $dateTimeType = 'Google_Service_Walletobjects_EventDateTime';
    protected $dateTimeDataType = '';
    public $enableSmartTap;
    public $eventId;
    protected $eventNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $eventNameDataType = '';
    protected $finePrintType = 'Google_Service_Walletobjects_LocalizedString';
    protected $finePrintDataType = '';
    public $gateLabel;
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $hexBackgroundColor;
    protected $homepageUriType = 'Google_Service_Walletobjects_Uri';
    protected $homepageUriDataType = '';
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    public $issuerName;
    public $kind;
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $localizedIssuerNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedIssuerNameDataType = '';
    protected $locationsType = 'Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    protected $logoType = 'Google_Service_Walletobjects_Image';
    protected $logoDataType = '';
    protected $messagesType = 'Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    public $multipleDevicesAndHoldersAllowedStatus;
    public $redemptionIssuers;
    protected $reviewType = 'Google_Service_Walletobjects_Review';
    protected $reviewDataType = '';
    public $reviewStatus;
    public $rowLabel;
    public $seatLabel;
    public $sectionLabel;
    protected $securityAnimationType = 'Loyalty\GoogleWallet\Services\Google_Service_Walletobjects_SecurityAnimation';
    protected $securityAnimationDataType = '';
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    protected $venueType = 'Google_Service_Walletobjects_EventVenue';
    protected $venueDataType = '';
    public $version;
    public $viewUnlockRequirement;
    protected $wordMarkType = 'Google_Service_Walletobjects_Image';
    protected $wordMarkDataType = '';


    public function setAllowMultipleUsersPerObject($allowMultipleUsersPerObject): void
    {
        $this->allowMultipleUsersPerObject = $allowMultipleUsersPerObject;
    }

    public function getAllowMultipleUsersPerObject()
    {
        return $this->allowMultipleUsersPerObject;
    }

    public function setCallbackOptions(Google_Service_Walletobjects_CallbackOptions $callbackOptions): void
    {
        $this->callbackOptions = $callbackOptions;
    }

    public function getCallbackOptions(): Google_Service_Walletobjects_CallbackOptions
    {
        return $this->callbackOptions;
    }

    public function setClassTemplateInfo(Google_Service_Walletobjects_ClassTemplateInfo $classTemplateInfo): void
    {
        $this->classTemplateInfo = $classTemplateInfo;
    }

    public function getClassTemplateInfo(): Google_Service_Walletobjects_ClassTemplateInfo
    {
        return $this->classTemplateInfo;
    }

    public function setConfirmationCodeLabel($confirmationCodeLabel): void
    {
        $this->confirmationCodeLabel = $confirmationCodeLabel;
    }

    public function getConfirmationCodeLabel()
    {
        return $this->confirmationCodeLabel;
    }

    public function setCountryCode($countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setCustomConfirmationCodeLabel(
        Google_Service_Walletobjects_LocalizedString $customConfirmationCodeLabel
    ): void {
        $this->customConfirmationCodeLabel = $customConfirmationCodeLabel;
    }

    public function getCustomConfirmationCodeLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customConfirmationCodeLabel;
    }

    public function setCustomGateLabel(Google_Service_Walletobjects_LocalizedString $customGateLabel): void
    {
        $this->customGateLabel = $customGateLabel;
    }

    public function getCustomGateLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customGateLabel;
    }

    public function setCustomRowLabel(Google_Service_Walletobjects_LocalizedString $customRowLabel): void
    {
        $this->customRowLabel = $customRowLabel;
    }

    public function getCustomRowLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customRowLabel;
    }

    public function setCustomSeatLabel(Google_Service_Walletobjects_LocalizedString $customSeatLabel): void
    {
        $this->customSeatLabel = $customSeatLabel;
    }

    public function getCustomSeatLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customSeatLabel;
    }

    public function setCustomSectionLabel(Google_Service_Walletobjects_LocalizedString $customSectionLabel): void
    {
        $this->customSectionLabel = $customSectionLabel;
    }

    public function getCustomSectionLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customSectionLabel;
    }

    public function setDateTime(Google_Service_Walletobjects_EventDateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function getDateTime(): Google_Service_Walletobjects_EventDateTime
    {
        return $this->dateTime;
    }

    public function setEnableSmartTap($enableSmartTap): void
    {
        $this->enableSmartTap = $enableSmartTap;
    }

    public function getEnableSmartTap()
    {
        return $this->enableSmartTap;
    }

    public function setEventId($eventId): void
    {
        $this->eventId = $eventId;
    }

    public function getEventId()
    {
        return $this->eventId;
    }

    public function setEventName(Google_Service_Walletobjects_LocalizedString $eventName): void
    {
        $this->eventName = $eventName;
    }

    public function getEventName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->eventName;
    }

    public function setFinePrint(Google_Service_Walletobjects_LocalizedString $finePrint): void
    {
        $this->finePrint = $finePrint;
    }

    public function getFinePrint(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->finePrint;
    }

    public function setGateLabel($gateLabel): void
    {
        $this->gateLabel = $gateLabel;
    }

    public function getGateLabel()
    {
        return $this->gateLabel;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setHexBackgroundColor($hexBackgroundColor): void
    {
        $this->hexBackgroundColor = $hexBackgroundColor;
    }

    public function getHexBackgroundColor()
    {
        return $this->hexBackgroundColor;
    }

    public function setHomepageUri(Google_Service_Walletobjects_Uri $homepageUri): void
    {
        $this->homepageUri = $homepageUri;
    }

    public function getHomepageUri(): Google_Service_Walletobjects_Uri
    {
        return $this->homepageUri;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData): void
    {
        $this->infoModuleData = $infoModuleData;
    }

    public function getInfoModuleData(): Google_Service_Walletobjects_InfoModuleData
    {
        return $this->infoModuleData;
    }

    public function setIssuerName($issuerName): void
    {
        $this->issuerName = $issuerName;
    }

    public function getIssuerName()
    {
        return $this->issuerName;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLocalizedIssuerName(Google_Service_Walletobjects_LocalizedString $localizedIssuerName): void
    {
        $this->localizedIssuerName = $localizedIssuerName;
    }

    public function getLocalizedIssuerName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedIssuerName;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function setLogo(Google_Service_Walletobjects_Image $logo): void
    {
        $this->logo = $logo;
    }

    public function getLogo(): Google_Service_Walletobjects_Image
    {
        return $this->logo;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setMultipleDevicesAndHoldersAllowedStatus($multipleDevicesAndHoldersAllowedStatus): void
    {
        $this->multipleDevicesAndHoldersAllowedStatus = $multipleDevicesAndHoldersAllowedStatus;
    }

    public function getMultipleDevicesAndHoldersAllowedStatus()
    {
        return $this->multipleDevicesAndHoldersAllowedStatus;
    }

    public function setRedemptionIssuers($redemptionIssuers): void
    {
        $this->redemptionIssuers = $redemptionIssuers;
    }

    public function getRedemptionIssuers()
    {
        return $this->redemptionIssuers;
    }

    public function setReview(Google_Service_Walletobjects_Review $review): void
    {
        $this->review = $review;
    }

    public function getReview(): Google_Service_Walletobjects_Review
    {
        return $this->review;
    }

    public function setReviewStatus($reviewStatus): void
    {
        $this->reviewStatus = $reviewStatus;
    }

    public function getReviewStatus()
    {
        return $this->reviewStatus;
    }

    public function setRowLabel($rowLabel): void
    {
        $this->rowLabel = $rowLabel;
    }

    public function getRowLabel()
    {
        return $this->rowLabel;
    }

    public function setSeatLabel($seatLabel): void
    {
        $this->seatLabel = $seatLabel;
    }

    public function getSeatLabel()
    {
        return $this->seatLabel;
    }

    public function setSectionLabel($sectionLabel): void
    {
        $this->sectionLabel = $sectionLabel;
    }

    public function getSectionLabel()
    {
        return $this->sectionLabel;
    }

    public function setSecurityAnimation(Google_Service_Walletobjects_SecurityAnimation $securityAnimation): void
    {
        $this->securityAnimation = $securityAnimation;
    }

    public function getSecurityAnimation(): Google_Service_Walletobjects_SecurityAnimation
    {
        return $this->securityAnimation;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setVenue(Google_Service_Walletobjects_EventVenue $venue): void
    {
        $this->venue = $venue;
    }

    public function getVenue(): Google_Service_Walletobjects_EventVenue
    {
        return $this->venue;
    }

    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setViewUnlockRequirement($viewUnlockRequirement): void
    {
        $this->viewUnlockRequirement = $viewUnlockRequirement;
    }

    public function getViewUnlockRequirement()
    {
        return $this->viewUnlockRequirement;
    }

    public function setWordMark(Google_Service_Walletobjects_Image $wordMark): void
    {
        $this->wordMark = $wordMark;
    }

    public function getWordMark(): Google_Service_Walletobjects_Image
    {
        return $this->wordMark;
    }
}

class Google_Service_Walletobjects_EventTicketClassAddMessageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourceType = 'Google_Service_Walletobjects_EventTicketClass';
    protected $resourceDataType = '';


    public function setResource(Google_Service_Walletobjects_EventTicketClass $resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): Google_Service_Walletobjects_EventTicketClass
    {
        return $this->resource;
    }
}

class Google_Service_Walletobjects_EventTicketClassListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_EventTicketClass';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_EventTicketObject extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    protected $appLinkDataType = 'Google_Service_Walletobjects_AppLinkData';
    protected $appLinkDataDataType = '';
    protected $barcodeType = 'Google_Service_Walletobjects_Barcode';
    protected $barcodeDataType = '';
    public $classId;
    protected $classReferenceType = 'Google_Service_Walletobjects_EventTicketClass';
    protected $classReferenceDataType = '';
    public $disableExpirationNotification;
    protected $faceValueType = 'Google_Service_Walletobjects_Money';
    protected $faceValueDataType = '';
    protected $groupingInfoType = 'Google_Service_Walletobjects_GroupingInfo';
    protected $groupingInfoDataType = '';
    public $hasLinkedDevice;
    public $hasUsers;
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $hexBackgroundColor;
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    public $kind;
    public $linkedOfferIds;
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $locationsType = 'Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    protected $messagesType = 'Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    protected $passConstraintsType = 'Google_Service_Walletobjects_PassConstraints';
    protected $passConstraintsDataType = '';
    protected $reservationInfoType = 'Google_Service_Walletobjects_EventReservationInfo';
    protected $reservationInfoDataType = '';
    protected $rotatingBarcodeType = 'Google_Service_Walletobjects_RotatingBarcode';
    protected $rotatingBarcodeDataType = '';
    protected $seatInfoType = 'Google_Service_Walletobjects_EventSeat';
    protected $seatInfoDataType = '';
    public $smartTapRedemptionValue;
    public $state;
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    public $ticketHolderName;
    public $ticketNumber;
    protected $ticketTypeType = 'Google_Service_Walletobjects_LocalizedString';
    protected $ticketTypeDataType = '';
    protected $validTimeIntervalType = 'Google_Service_Walletobjects_TimeInterval';
    protected $validTimeIntervalDataType = '';
    public $version;


    public function setAppLinkData(Google_Service_Walletobjects_AppLinkData $appLinkData): void
    {
        $this->appLinkData = $appLinkData;
    }

    public function getAppLinkData(): Google_Service_Walletobjects_AppLinkData
    {
        return $this->appLinkData;
    }

    public function setBarcode(Google_Service_Walletobjects_Barcode $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getBarcode(): Google_Service_Walletobjects_Barcode
    {
        return $this->barcode;
    }

    public function setClassId($classId): void
    {
        $this->classId = $classId;
    }

    public function getClassId()
    {
        return $this->classId;
    }

    public function setClassReference(Google_Service_Walletobjects_EventTicketClass $classReference): void
    {
        $this->classReference = $classReference;
    }

    public function getClassReference(): Google_Service_Walletobjects_EventTicketClass
    {
        return $this->classReference;
    }

    public function setDisableExpirationNotification($disableExpirationNotification): void
    {
        $this->disableExpirationNotification = $disableExpirationNotification;
    }

    public function getDisableExpirationNotification()
    {
        return $this->disableExpirationNotification;
    }

    public function setFaceValue(Google_Service_Walletobjects_Money $faceValue): void
    {
        $this->faceValue = $faceValue;
    }

    public function getFaceValue(): Google_Service_Walletobjects_Money
    {
        return $this->faceValue;
    }

    public function setGroupingInfo(Google_Service_Walletobjects_GroupingInfo $groupingInfo): void
    {
        $this->groupingInfo = $groupingInfo;
    }

    public function getGroupingInfo(): Google_Service_Walletobjects_GroupingInfo
    {
        return $this->groupingInfo;
    }

    public function setHasLinkedDevice($hasLinkedDevice): void
    {
        $this->hasLinkedDevice = $hasLinkedDevice;
    }

    public function getHasLinkedDevice()
    {
        return $this->hasLinkedDevice;
    }

    public function setHasUsers($hasUsers): void
    {
        $this->hasUsers = $hasUsers;
    }

    public function getHasUsers()
    {
        return $this->hasUsers;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setHexBackgroundColor($hexBackgroundColor): void
    {
        $this->hexBackgroundColor = $hexBackgroundColor;
    }

    public function getHexBackgroundColor()
    {
        return $this->hexBackgroundColor;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData): void
    {
        $this->infoModuleData = $infoModuleData;
    }

    public function getInfoModuleData(): Google_Service_Walletobjects_InfoModuleData
    {
        return $this->infoModuleData;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLinkedOfferIds($linkedOfferIds): void
    {
        $this->linkedOfferIds = $linkedOfferIds;
    }

    public function getLinkedOfferIds()
    {
        return $this->linkedOfferIds;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setPassConstraints(Google_Service_Walletobjects_PassConstraints $passConstraints): void
    {
        $this->passConstraints = $passConstraints;
    }

    public function getPassConstraints(): Google_Service_Walletobjects_PassConstraints
    {
        return $this->passConstraints;
    }

    public function setReservationInfo(Google_Service_Walletobjects_EventReservationInfo $reservationInfo): void
    {
        $this->reservationInfo = $reservationInfo;
    }

    public function getReservationInfo(): Google_Service_Walletobjects_EventReservationInfo
    {
        return $this->reservationInfo;
    }

    public function setRotatingBarcode(Google_Service_Walletobjects_RotatingBarcode $rotatingBarcode): void
    {
        $this->rotatingBarcode = $rotatingBarcode;
    }

    public function getRotatingBarcode(): Google_Service_Walletobjects_RotatingBarcode
    {
        return $this->rotatingBarcode;
    }

    public function setSeatInfo(Google_Service_Walletobjects_EventSeat $seatInfo): void
    {
        $this->seatInfo = $seatInfo;
    }

    public function getSeatInfo(): Google_Service_Walletobjects_EventSeat
    {
        return $this->seatInfo;
    }

    public function setSmartTapRedemptionValue($smartTapRedemptionValue): void
    {
        $this->smartTapRedemptionValue = $smartTapRedemptionValue;
    }

    public function getSmartTapRedemptionValue()
    {
        return $this->smartTapRedemptionValue;
    }

    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setTicketHolderName($ticketHolderName): void
    {
        $this->ticketHolderName = $ticketHolderName;
    }

    public function getTicketHolderName()
    {
        return $this->ticketHolderName;
    }

    public function setTicketNumber($ticketNumber): void
    {
        $this->ticketNumber = $ticketNumber;
    }

    public function getTicketNumber()
    {
        return $this->ticketNumber;
    }

    public function setTicketType(Google_Service_Walletobjects_LocalizedString $ticketType): void
    {
        $this->ticketType = $ticketType;
    }

    public function getTicketType(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->ticketType;
    }

    public function setValidTimeInterval(Google_Service_Walletobjects_TimeInterval $validTimeInterval): void
    {
        $this->validTimeInterval = $validTimeInterval;
    }

    public function getValidTimeInterval(): Google_Service_Walletobjects_TimeInterval
    {
        return $this->validTimeInterval;
    }

    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }
}

class Google_Service_Walletobjects_EventTicketObjectAddMessageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourceType = 'Google_Service_Walletobjects_EventTicketObject';
    protected $resourceDataType = '';


    public function setResource(Google_Service_Walletobjects_EventTicketObject $resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): Google_Service_Walletobjects_EventTicketObject
    {
        return $this->resource;
    }
}

class Google_Service_Walletobjects_EventTicketObjectListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_EventTicketObject';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_EventVenue extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $addressType = 'Google_Service_Walletobjects_LocalizedString';
    protected $addressDataType = '';
    public $kind;
    protected $nameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $nameDataType = '';


    public function setAddress(Google_Service_Walletobjects_LocalizedString $address): void
    {
        $this->address = $address;
    }

    public function getAddress(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->address;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setName(Google_Service_Walletobjects_LocalizedString $name): void
    {
        $this->name = $name;
    }

    public function getName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->name;
    }
}

class Google_Service_Walletobjects_ExpiryNotification extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $enableNotification;


    public function setEnableNotification($enableNotification): void
    {
        $this->enableNotification = $enableNotification;
    }

    public function getEnableNotification()
    {
        return $this->enableNotification;
    }
}

class Google_Service_Walletobjects_FieldReference extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $dateFormat;
    public $fieldPath;


    public function setDateFormat($dateFormat): void
    {
        $this->dateFormat = $dateFormat;
    }

    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    public function setFieldPath($fieldPath): void
    {
        $this->fieldPath = $fieldPath;
    }

    public function getFieldPath()
    {
        return $this->fieldPath;
    }
}

class Google_Service_Walletobjects_FieldSelector extends Google_Collection
{
    protected $collection_key = 'fields';
    protected $internal_gapi_mappings = array();
    protected $fieldsType = 'Google_Service_Walletobjects_FieldReference';
    protected $fieldsDataType = 'array';


    public function setFields($fields): void
    {
        $this->fields = $fields;
    }

    public function getFields()
    {
        return $this->fields;
    }
}

class Google_Service_Walletobjects_FirstRowOption extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $fieldOptionType = 'Google_Service_Walletobjects_FieldSelector';
    protected $fieldOptionDataType = '';
    public $transitOption;


    public function setFieldOption(Google_Service_Walletobjects_FieldSelector $fieldOption): void
    {
        $this->fieldOption = $fieldOption;
    }

    public function getFieldOption(): Google_Service_Walletobjects_FieldSelector
    {
        return $this->fieldOption;
    }

    public function setTransitOption($transitOption): void
    {
        $this->transitOption = $transitOption;
    }

    public function getTransitOption()
    {
        return $this->transitOption;
    }
}

class Google_Service_Walletobjects_FlightCarrier extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $airlineAllianceLogoType = 'Google_Service_Walletobjects_Image';
    protected $airlineAllianceLogoDataType = '';
    protected $airlineLogoType = 'Google_Service_Walletobjects_Image';
    protected $airlineLogoDataType = '';
    protected $airlineNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $airlineNameDataType = '';
    public $carrierIataCode;
    public $carrierIcaoCode;
    public $kind;


    public function setAirlineAllianceLogo(Google_Service_Walletobjects_Image $airlineAllianceLogo): void
    {
        $this->airlineAllianceLogo = $airlineAllianceLogo;
    }

    public function getAirlineAllianceLogo(): Google_Service_Walletobjects_Image
    {
        return $this->airlineAllianceLogo;
    }

    public function setAirlineLogo(Google_Service_Walletobjects_Image $airlineLogo): void
    {
        $this->airlineLogo = $airlineLogo;
    }

    public function getAirlineLogo(): Google_Service_Walletobjects_Image
    {
        return $this->airlineLogo;
    }

    public function setAirlineName(Google_Service_Walletobjects_LocalizedString $airlineName): void
    {
        $this->airlineName = $airlineName;
    }

    public function getAirlineName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->airlineName;
    }

    public function setCarrierIataCode($carrierIataCode): void
    {
        $this->carrierIataCode = $carrierIataCode;
    }

    public function getCarrierIataCode()
    {
        return $this->carrierIataCode;
    }

    public function setCarrierIcaoCode($carrierIcaoCode): void
    {
        $this->carrierIcaoCode = $carrierIcaoCode;
    }

    public function getCarrierIcaoCode()
    {
        return $this->carrierIcaoCode;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }
}

class Google_Service_Walletobjects_FlightClass extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    public $allowMultipleUsersPerObject;
    protected $boardingAndSeatingPolicyType = 'Google_Service_Walletobjects_BoardingAndSeatingPolicy';
    protected $boardingAndSeatingPolicyDataType = '';
    protected $callbackOptionsType = 'Google_Service_Walletobjects_CallbackOptions';
    protected $callbackOptionsDataType = '';
    protected $classTemplateInfoType = 'Google_Service_Walletobjects_ClassTemplateInfo';
    protected $classTemplateInfoDataType = '';
    public $countryCode;
    protected $destinationType = 'Google_Service_Walletobjects_AirportInfo';
    protected $destinationDataType = '';
    public $enableSmartTap;
    protected $flightHeaderType = 'Google_Service_Walletobjects_FlightHeader';
    protected $flightHeaderDataType = '';
    public $flightStatus;
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $hexBackgroundColor;
    protected $homepageUriType = 'Google_Service_Walletobjects_Uri';
    protected $homepageUriDataType = '';
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    public $issuerName;
    public $kind;
    public $languageOverride;
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    public $localBoardingDateTime;
    public $localEstimatedOrActualArrivalDateTime;
    public $localEstimatedOrActualDepartureDateTime;
    public $localGateClosingDateTime;
    public $localScheduledArrivalDateTime;
    public $localScheduledDepartureDateTime;
    protected $localizedIssuerNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedIssuerNameDataType = '';
    protected $locationsType = 'Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    protected $messagesType = 'Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    public $multipleDevicesAndHoldersAllowedStatus;
    protected $originType = 'Google_Service_Walletobjects_AirportInfo';
    protected $originDataType = '';
    public $redemptionIssuers;
    protected $reviewType = 'Google_Service_Walletobjects_Review';
    protected $reviewDataType = '';
    public $reviewStatus;
    protected $securityAnimationType = 'Loyalty\GoogleWallet\Services\Google_Service_Walletobjects_SecurityAnimation';
    protected $securityAnimationDataType = '';
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    public $version;
    public $viewUnlockRequirement;
    protected $wordMarkType = 'Google_Service_Walletobjects_Image';
    protected $wordMarkDataType = '';


    public function setAllowMultipleUsersPerObject($allowMultipleUsersPerObject): void
    {
        $this->allowMultipleUsersPerObject = $allowMultipleUsersPerObject;
    }

    public function getAllowMultipleUsersPerObject()
    {
        return $this->allowMultipleUsersPerObject;
    }

    public function setBoardingAndSeatingPolicy(
        Google_Service_Walletobjects_BoardingAndSeatingPolicy $boardingAndSeatingPolicy
    ): void {
        $this->boardingAndSeatingPolicy = $boardingAndSeatingPolicy;
    }

    public function getBoardingAndSeatingPolicy(): Google_Service_Walletobjects_BoardingAndSeatingPolicy
    {
        return $this->boardingAndSeatingPolicy;
    }

    public function setCallbackOptions(Google_Service_Walletobjects_CallbackOptions $callbackOptions): void
    {
        $this->callbackOptions = $callbackOptions;
    }

    public function getCallbackOptions(): Google_Service_Walletobjects_CallbackOptions
    {
        return $this->callbackOptions;
    }

    public function setClassTemplateInfo(Google_Service_Walletobjects_ClassTemplateInfo $classTemplateInfo): void
    {
        $this->classTemplateInfo = $classTemplateInfo;
    }

    public function getClassTemplateInfo(): Google_Service_Walletobjects_ClassTemplateInfo
    {
        return $this->classTemplateInfo;
    }

    public function setCountryCode($countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setDestination(Google_Service_Walletobjects_AirportInfo $destination): void
    {
        $this->destination = $destination;
    }

    public function getDestination(): Google_Service_Walletobjects_AirportInfo
    {
        return $this->destination;
    }

    public function setEnableSmartTap($enableSmartTap): void
    {
        $this->enableSmartTap = $enableSmartTap;
    }

    public function getEnableSmartTap()
    {
        return $this->enableSmartTap;
    }

    public function setFlightHeader(Google_Service_Walletobjects_FlightHeader $flightHeader): void
    {
        $this->flightHeader = $flightHeader;
    }

    public function getFlightHeader(): Google_Service_Walletobjects_FlightHeader
    {
        return $this->flightHeader;
    }

    public function setFlightStatus($flightStatus): void
    {
        $this->flightStatus = $flightStatus;
    }

    public function getFlightStatus()
    {
        return $this->flightStatus;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setHexBackgroundColor($hexBackgroundColor): void
    {
        $this->hexBackgroundColor = $hexBackgroundColor;
    }

    public function getHexBackgroundColor()
    {
        return $this->hexBackgroundColor;
    }

    public function setHomepageUri(Google_Service_Walletobjects_Uri $homepageUri): void
    {
        $this->homepageUri = $homepageUri;
    }

    public function getHomepageUri(): Google_Service_Walletobjects_Uri
    {
        return $this->homepageUri;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData): void
    {
        $this->infoModuleData = $infoModuleData;
    }

    public function getInfoModuleData(): Google_Service_Walletobjects_InfoModuleData
    {
        return $this->infoModuleData;
    }

    public function setIssuerName($issuerName): void
    {
        $this->issuerName = $issuerName;
    }

    public function getIssuerName()
    {
        return $this->issuerName;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLanguageOverride($languageOverride): void
    {
        $this->languageOverride = $languageOverride;
    }

    public function getLanguageOverride()
    {
        return $this->languageOverride;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLocalBoardingDateTime($localBoardingDateTime): void
    {
        $this->localBoardingDateTime = $localBoardingDateTime;
    }

    public function getLocalBoardingDateTime()
    {
        return $this->localBoardingDateTime;
    }

    public function setLocalEstimatedOrActualArrivalDateTime($localEstimatedOrActualArrivalDateTime): void
    {
        $this->localEstimatedOrActualArrivalDateTime = $localEstimatedOrActualArrivalDateTime;
    }

    public function getLocalEstimatedOrActualArrivalDateTime()
    {
        return $this->localEstimatedOrActualArrivalDateTime;
    }

    public function setLocalEstimatedOrActualDepartureDateTime($localEstimatedOrActualDepartureDateTime): void
    {
        $this->localEstimatedOrActualDepartureDateTime = $localEstimatedOrActualDepartureDateTime;
    }

    public function getLocalEstimatedOrActualDepartureDateTime()
    {
        return $this->localEstimatedOrActualDepartureDateTime;
    }

    public function setLocalGateClosingDateTime($localGateClosingDateTime): void
    {
        $this->localGateClosingDateTime = $localGateClosingDateTime;
    }

    public function getLocalGateClosingDateTime()
    {
        return $this->localGateClosingDateTime;
    }

    public function setLocalScheduledArrivalDateTime($localScheduledArrivalDateTime): void
    {
        $this->localScheduledArrivalDateTime = $localScheduledArrivalDateTime;
    }

    public function getLocalScheduledArrivalDateTime()
    {
        return $this->localScheduledArrivalDateTime;
    }

    public function setLocalScheduledDepartureDateTime($localScheduledDepartureDateTime): void
    {
        $this->localScheduledDepartureDateTime = $localScheduledDepartureDateTime;
    }

    public function getLocalScheduledDepartureDateTime()
    {
        return $this->localScheduledDepartureDateTime;
    }

    public function setLocalizedIssuerName(Google_Service_Walletobjects_LocalizedString $localizedIssuerName): void
    {
        $this->localizedIssuerName = $localizedIssuerName;
    }

    public function getLocalizedIssuerName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedIssuerName;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setMultipleDevicesAndHoldersAllowedStatus($multipleDevicesAndHoldersAllowedStatus): void
    {
        $this->multipleDevicesAndHoldersAllowedStatus = $multipleDevicesAndHoldersAllowedStatus;
    }

    public function getMultipleDevicesAndHoldersAllowedStatus()
    {
        return $this->multipleDevicesAndHoldersAllowedStatus;
    }

    public function setOrigin(Google_Service_Walletobjects_AirportInfo $origin): void
    {
        $this->origin = $origin;
    }

    public function getOrigin(): Google_Service_Walletobjects_AirportInfo
    {
        return $this->origin;
    }

    public function setRedemptionIssuers($redemptionIssuers): void
    {
        $this->redemptionIssuers = $redemptionIssuers;
    }

    public function getRedemptionIssuers()
    {
        return $this->redemptionIssuers;
    }

    public function setReview(Google_Service_Walletobjects_Review $review): void
    {
        $this->review = $review;
    }

    public function getReview(): Google_Service_Walletobjects_Review
    {
        return $this->review;
    }

    public function setReviewStatus($reviewStatus): void
    {
        $this->reviewStatus = $reviewStatus;
    }

    public function getReviewStatus()
    {
        return $this->reviewStatus;
    }

    public function setSecurityAnimation(Google_Service_Walletobjects_SecurityAnimation $securityAnimation): void
    {
        $this->securityAnimation = $securityAnimation;
    }

    public function getSecurityAnimation(): Google_Service_Walletobjects_SecurityAnimation
    {
        return $this->securityAnimation;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setViewUnlockRequirement($viewUnlockRequirement): void
    {
        $this->viewUnlockRequirement = $viewUnlockRequirement;
    }

    public function getViewUnlockRequirement()
    {
        return $this->viewUnlockRequirement;
    }

    public function setWordMark(Google_Service_Walletobjects_Image $wordMark): void
    {
        $this->wordMark = $wordMark;
    }

    public function getWordMark(): Google_Service_Walletobjects_Image
    {
        return $this->wordMark;
    }
}

class Google_Service_Walletobjects_FlightClassAddMessageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourceType = 'Google_Service_Walletobjects_FlightClass';
    protected $resourceDataType = '';


    public function setResource(Google_Service_Walletobjects_FlightClass $resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): Google_Service_Walletobjects_FlightClass
    {
        return $this->resource;
    }
}

class Google_Service_Walletobjects_FlightClassListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_FlightClass';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_FlightHeader extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $carrierType = 'Google_Service_Walletobjects_FlightCarrier';
    protected $carrierDataType = '';
    public $flightNumber;
    public $flightNumberDisplayOverride;
    public $kind;
    protected $operatingCarrierType = 'Google_Service_Walletobjects_FlightCarrier';
    protected $operatingCarrierDataType = '';
    public $operatingFlightNumber;


    public function setCarrier(Google_Service_Walletobjects_FlightCarrier $carrier): void
    {
        $this->carrier = $carrier;
    }

    public function getCarrier(): Google_Service_Walletobjects_FlightCarrier
    {
        return $this->carrier;
    }

    public function setFlightNumber($flightNumber): void
    {
        $this->flightNumber = $flightNumber;
    }

    public function getFlightNumber()
    {
        return $this->flightNumber;
    }

    public function setFlightNumberDisplayOverride($flightNumberDisplayOverride): void
    {
        $this->flightNumberDisplayOverride = $flightNumberDisplayOverride;
    }

    public function getFlightNumberDisplayOverride()
    {
        return $this->flightNumberDisplayOverride;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setOperatingCarrier(Google_Service_Walletobjects_FlightCarrier $operatingCarrier): void
    {
        $this->operatingCarrier = $operatingCarrier;
    }

    public function getOperatingCarrier(): Google_Service_Walletobjects_FlightCarrier
    {
        return $this->operatingCarrier;
    }

    public function setOperatingFlightNumber($operatingFlightNumber): void
    {
        $this->operatingFlightNumber = $operatingFlightNumber;
    }

    public function getOperatingFlightNumber()
    {
        return $this->operatingFlightNumber;
    }
}

class Google_Service_Walletobjects_FlightObject extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    protected $appLinkDataType = 'Google_Service_Walletobjects_AppLinkData';
    protected $appLinkDataDataType = '';
    protected $barcodeType = 'Google_Service_Walletobjects_Barcode';
    protected $barcodeDataType = '';
    protected $boardingAndSeatingInfoType = 'Google_Service_Walletobjects_BoardingAndSeatingInfo';
    protected $boardingAndSeatingInfoDataType = '';
    public $classId;
    protected $classReferenceType = 'Google_Service_Walletobjects_FlightClass';
    protected $classReferenceDataType = '';
    public $disableExpirationNotification;
    protected $groupingInfoType = 'Google_Service_Walletobjects_GroupingInfo';
    protected $groupingInfoDataType = '';
    public $hasLinkedDevice;
    public $hasUsers;
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $hexBackgroundColor;
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    public $kind;
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $locationsType = 'Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    protected $messagesType = 'Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    protected $passConstraintsType = 'Google_Service_Walletobjects_PassConstraints';
    protected $passConstraintsDataType = '';
    public $passengerName;
    protected $reservationInfoType = 'Google_Service_Walletobjects_ReservationInfo';
    protected $reservationInfoDataType = '';
    protected $rotatingBarcodeType = 'Google_Service_Walletobjects_RotatingBarcode';
    protected $rotatingBarcodeDataType = '';
    protected $securityProgramLogoType = 'Google_Service_Walletobjects_Image';
    protected $securityProgramLogoDataType = '';
    public $smartTapRedemptionValue;
    public $state;
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    protected $validTimeIntervalType = 'Google_Service_Walletobjects_TimeInterval';
    protected $validTimeIntervalDataType = '';
    public $version;


    public function setAppLinkData(Google_Service_Walletobjects_AppLinkData $appLinkData): void
    {
        $this->appLinkData = $appLinkData;
    }

    public function getAppLinkData(): Google_Service_Walletobjects_AppLinkData
    {
        return $this->appLinkData;
    }

    public function setBarcode(Google_Service_Walletobjects_Barcode $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getBarcode(): Google_Service_Walletobjects_Barcode
    {
        return $this->barcode;
    }

    public function setBoardingAndSeatingInfo(
        Google_Service_Walletobjects_BoardingAndSeatingInfo $boardingAndSeatingInfo
    ): void {
        $this->boardingAndSeatingInfo = $boardingAndSeatingInfo;
    }

    public function getBoardingAndSeatingInfo(): Google_Service_Walletobjects_BoardingAndSeatingInfo
    {
        return $this->boardingAndSeatingInfo;
    }

    public function setClassId($classId): void
    {
        $this->classId = $classId;
    }

    public function getClassId()
    {
        return $this->classId;
    }

    public function setClassReference(Google_Service_Walletobjects_FlightClass $classReference): void
    {
        $this->classReference = $classReference;
    }

    public function getClassReference(): Google_Service_Walletobjects_FlightClass
    {
        return $this->classReference;
    }

    public function setDisableExpirationNotification($disableExpirationNotification): void
    {
        $this->disableExpirationNotification = $disableExpirationNotification;
    }

    public function getDisableExpirationNotification()
    {
        return $this->disableExpirationNotification;
    }

    public function setGroupingInfo(Google_Service_Walletobjects_GroupingInfo $groupingInfo): void
    {
        $this->groupingInfo = $groupingInfo;
    }

    public function getGroupingInfo(): Google_Service_Walletobjects_GroupingInfo
    {
        return $this->groupingInfo;
    }

    public function setHasLinkedDevice($hasLinkedDevice): void
    {
        $this->hasLinkedDevice = $hasLinkedDevice;
    }

    public function getHasLinkedDevice()
    {
        return $this->hasLinkedDevice;
    }

    public function setHasUsers($hasUsers): void
    {
        $this->hasUsers = $hasUsers;
    }

    public function getHasUsers()
    {
        return $this->hasUsers;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setHexBackgroundColor($hexBackgroundColor): void
    {
        $this->hexBackgroundColor = $hexBackgroundColor;
    }

    public function getHexBackgroundColor()
    {
        return $this->hexBackgroundColor;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData): void
    {
        $this->infoModuleData = $infoModuleData;
    }

    public function getInfoModuleData(): Google_Service_Walletobjects_InfoModuleData
    {
        return $this->infoModuleData;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setPassConstraints(Google_Service_Walletobjects_PassConstraints $passConstraints): void
    {
        $this->passConstraints = $passConstraints;
    }

    public function getPassConstraints(): Google_Service_Walletobjects_PassConstraints
    {
        return $this->passConstraints;
    }

    public function setPassengerName($passengerName): void
    {
        $this->passengerName = $passengerName;
    }

    public function getPassengerName()
    {
        return $this->passengerName;
    }

    public function setReservationInfo(Google_Service_Walletobjects_ReservationInfo $reservationInfo): void
    {
        $this->reservationInfo = $reservationInfo;
    }

    public function getReservationInfo(): Google_Service_Walletobjects_ReservationInfo
    {
        return $this->reservationInfo;
    }

    public function setRotatingBarcode(Google_Service_Walletobjects_RotatingBarcode $rotatingBarcode): void
    {
        $this->rotatingBarcode = $rotatingBarcode;
    }

    public function getRotatingBarcode(): Google_Service_Walletobjects_RotatingBarcode
    {
        return $this->rotatingBarcode;
    }

    public function setSecurityProgramLogo(Google_Service_Walletobjects_Image $securityProgramLogo): void
    {
        $this->securityProgramLogo = $securityProgramLogo;
    }

    public function getSecurityProgramLogo(): Google_Service_Walletobjects_Image
    {
        return $this->securityProgramLogo;
    }

    public function setSmartTapRedemptionValue($smartTapRedemptionValue): void
    {
        $this->smartTapRedemptionValue = $smartTapRedemptionValue;
    }

    public function getSmartTapRedemptionValue()
    {
        return $this->smartTapRedemptionValue;
    }

    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setValidTimeInterval(Google_Service_Walletobjects_TimeInterval $validTimeInterval): void
    {
        $this->validTimeInterval = $validTimeInterval;
    }

    public function getValidTimeInterval(): Google_Service_Walletobjects_TimeInterval
    {
        return $this->validTimeInterval;
    }

    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }
}

class Google_Service_Walletobjects_FlightObjectAddMessageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourceType = 'Google_Service_Walletobjects_FlightObject';
    protected $resourceDataType = '';


    public function setResource(Google_Service_Walletobjects_FlightObject $resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): Google_Service_Walletobjects_FlightObject
    {
        return $this->resource;
    }
}

class Google_Service_Walletobjects_FlightObjectListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_FlightObject';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_FrequentFlyerInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $frequentFlyerNumber;
    protected $frequentFlyerProgramNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $frequentFlyerProgramNameDataType = '';
    public $kind;


    public function setFrequentFlyerNumber($frequentFlyerNumber): void
    {
        $this->frequentFlyerNumber = $frequentFlyerNumber;
    }

    public function getFrequentFlyerNumber()
    {
        return $this->frequentFlyerNumber;
    }

    public function setFrequentFlyerProgramName(Google_Service_Walletobjects_LocalizedString $frequentFlyerProgramName
    ): void {
        $this->frequentFlyerProgramName = $frequentFlyerProgramName;
    }

    public function getFrequentFlyerProgramName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->frequentFlyerProgramName;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }
}

class Google_Service_Walletobjects_GenericClass extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    protected $callbackOptionsType = 'Google_Service_Walletobjects_CallbackOptions';
    protected $callbackOptionsDataType = '';
    protected $classTemplateInfoType = 'Google_Service_Walletobjects_ClassTemplateInfo';
    protected $classTemplateInfoDataType = '';
    public $enableSmartTap;
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    public $multipleDevicesAndHoldersAllowedStatus;
    public $redemptionIssuers;
    protected $securityAnimationType = 'Loyalty\GoogleWallet\Services\Google_Service_Walletobjects_SecurityAnimation';
    protected $securityAnimationDataType = '';
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    public $viewUnlockRequirement;


    public function setCallbackOptions(Google_Service_Walletobjects_CallbackOptions $callbackOptions): void
    {
        $this->callbackOptions = $callbackOptions;
    }

    public function getCallbackOptions(): Google_Service_Walletobjects_CallbackOptions
    {
        return $this->callbackOptions;
    }

    public function setClassTemplateInfo(Google_Service_Walletobjects_ClassTemplateInfo $classTemplateInfo): void
    {
        $this->classTemplateInfo = $classTemplateInfo;
    }

    public function getClassTemplateInfo(): Google_Service_Walletobjects_ClassTemplateInfo
    {
        return $this->classTemplateInfo;
    }

    public function setEnableSmartTap($enableSmartTap): void
    {
        $this->enableSmartTap = $enableSmartTap;
    }

    public function getEnableSmartTap()
    {
        return $this->enableSmartTap;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setMultipleDevicesAndHoldersAllowedStatus($multipleDevicesAndHoldersAllowedStatus): void
    {
        $this->multipleDevicesAndHoldersAllowedStatus = $multipleDevicesAndHoldersAllowedStatus;
    }

    public function getMultipleDevicesAndHoldersAllowedStatus()
    {
        return $this->multipleDevicesAndHoldersAllowedStatus;
    }

    public function setRedemptionIssuers($redemptionIssuers): void
    {
        $this->redemptionIssuers = $redemptionIssuers;
    }

    public function getRedemptionIssuers()
    {
        return $this->redemptionIssuers;
    }

    public function setSecurityAnimation(Google_Service_Walletobjects_SecurityAnimation $securityAnimation): void
    {
        $this->securityAnimation = $securityAnimation;
    }

    public function getSecurityAnimation(): Google_Service_Walletobjects_SecurityAnimation
    {
        return $this->securityAnimation;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setViewUnlockRequirement($viewUnlockRequirement): void
    {
        $this->viewUnlockRequirement = $viewUnlockRequirement;
    }

    public function getViewUnlockRequirement()
    {
        return $this->viewUnlockRequirement;
    }
}

class Google_Service_Walletobjects_GenericClassListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_GenericClass';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_GenericObject extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    protected $appLinkDataType = 'Google_Service_Walletobjects_AppLinkData';
    protected $appLinkDataDataType = '';
    protected $barcodeType = 'Google_Service_Walletobjects_Barcode';
    protected $barcodeDataType = '';
    protected $cardTitleType = 'Google_Service_Walletobjects_LocalizedString';
    protected $cardTitleDataType = '';
    public $classId;
    public $genericType;
    protected $groupingInfoType = 'Google_Service_Walletobjects_GroupingInfo';
    protected $groupingInfoDataType = '';
    public $hasUsers;
    protected $headerType = 'Google_Service_Walletobjects_LocalizedString';
    protected $headerDataType = '';
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $hexBackgroundColor;
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $logoType = 'Google_Service_Walletobjects_Image';
    protected $logoDataType = '';
    protected $notificationsType = 'Google_Service_Walletobjects_Notifications';
    protected $notificationsDataType = '';
    protected $passConstraintsType = 'Google_Service_Walletobjects_PassConstraints';
    protected $passConstraintsDataType = '';
    protected $rotatingBarcodeType = 'Google_Service_Walletobjects_RotatingBarcode';
    protected $rotatingBarcodeDataType = '';
    public $smartTapRedemptionValue;
    public $state;
    protected $subheaderType = 'Google_Service_Walletobjects_LocalizedString';
    protected $subheaderDataType = '';
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    protected $validTimeIntervalType = 'Google_Service_Walletobjects_TimeInterval';
    protected $validTimeIntervalDataType = '';


    public function setAppLinkData(Google_Service_Walletobjects_AppLinkData $appLinkData): void
    {
        $this->appLinkData = $appLinkData;
    }

    public function getAppLinkData(): Google_Service_Walletobjects_AppLinkData
    {
        return $this->appLinkData;
    }

    public function setBarcode(Google_Service_Walletobjects_Barcode $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getBarcode(): Google_Service_Walletobjects_Barcode
    {
        return $this->barcode;
    }

    public function setCardTitle(Google_Service_Walletobjects_LocalizedString $cardTitle): void
    {
        $this->cardTitle = $cardTitle;
    }

    public function getCardTitle(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->cardTitle;
    }

    public function setClassId($classId): void
    {
        $this->classId = $classId;
    }

    public function getClassId()
    {
        return $this->classId;
    }

    public function setGenericType($genericType): void
    {
        $this->genericType = $genericType;
    }

    public function getGenericType()
    {
        return $this->genericType;
    }

    public function setGroupingInfo(Google_Service_Walletobjects_GroupingInfo $groupingInfo): void
    {
        $this->groupingInfo = $groupingInfo;
    }

    public function getGroupingInfo(): Google_Service_Walletobjects_GroupingInfo
    {
        return $this->groupingInfo;
    }

    public function setHasUsers($hasUsers): void
    {
        $this->hasUsers = $hasUsers;
    }

    public function getHasUsers()
    {
        return $this->hasUsers;
    }

    public function setHeader(Google_Service_Walletobjects_LocalizedString $header): void
    {
        $this->header = $header;
    }

    public function getHeader(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->header;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setHexBackgroundColor($hexBackgroundColor): void
    {
        $this->hexBackgroundColor = $hexBackgroundColor;
    }

    public function getHexBackgroundColor()
    {
        return $this->hexBackgroundColor;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLogo(Google_Service_Walletobjects_Image $logo): void
    {
        $this->logo = $logo;
    }

    public function getLogo(): Google_Service_Walletobjects_Image
    {
        return $this->logo;
    }

    public function setNotifications(Google_Service_Walletobjects_Notifications $notifications): void
    {
        $this->notifications = $notifications;
    }

    public function getNotifications(): Google_Service_Walletobjects_Notifications
    {
        return $this->notifications;
    }

    public function setPassConstraints(Google_Service_Walletobjects_PassConstraints $passConstraints): void
    {
        $this->passConstraints = $passConstraints;
    }

    public function getPassConstraints(): Google_Service_Walletobjects_PassConstraints
    {
        return $this->passConstraints;
    }

    public function setRotatingBarcode(Google_Service_Walletobjects_RotatingBarcode $rotatingBarcode): void
    {
        $this->rotatingBarcode = $rotatingBarcode;
    }

    public function getRotatingBarcode(): Google_Service_Walletobjects_RotatingBarcode
    {
        return $this->rotatingBarcode;
    }

    public function setSmartTapRedemptionValue($smartTapRedemptionValue): void
    {
        $this->smartTapRedemptionValue = $smartTapRedemptionValue;
    }

    public function getSmartTapRedemptionValue()
    {
        return $this->smartTapRedemptionValue;
    }

    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setSubheader(Google_Service_Walletobjects_LocalizedString $subheader): void
    {
        $this->subheader = $subheader;
    }

    public function getSubheader(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->subheader;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setValidTimeInterval(Google_Service_Walletobjects_TimeInterval $validTimeInterval): void
    {
        $this->validTimeInterval = $validTimeInterval;
    }

    public function getValidTimeInterval(): Google_Service_Walletobjects_TimeInterval
    {
        return $this->validTimeInterval;
    }
}

class Google_Service_Walletobjects_GenericObjectListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_GenericObject';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_GiftCardClass extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    public $allowBarcodeRedemption;
    public $allowMultipleUsersPerObject;
    protected $callbackOptionsType = 'Google_Service_Walletobjects_CallbackOptions';
    protected $callbackOptionsDataType = '';
    public $cardNumberLabel;
    protected $classTemplateInfoType = 'Google_Service_Walletobjects_ClassTemplateInfo';
    protected $classTemplateInfoDataType = '';
    public $countryCode;
    public $enableSmartTap;
    public $eventNumberLabel;
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $hexBackgroundColor;
    protected $homepageUriType = 'Google_Service_Walletobjects_Uri';
    protected $homepageUriDataType = '';
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    public $issuerName;
    public $kind;
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $localizedCardNumberLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedCardNumberLabelDataType = '';
    protected $localizedEventNumberLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedEventNumberLabelDataType = '';
    protected $localizedIssuerNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedIssuerNameDataType = '';
    protected $localizedMerchantNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedMerchantNameDataType = '';
    protected $localizedPinLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedPinLabelDataType = '';
    protected $locationsType = 'Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    public $merchantName;
    protected $messagesType = 'Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    public $multipleDevicesAndHoldersAllowedStatus;
    public $pinLabel;
    protected $programLogoType = 'Google_Service_Walletobjects_Image';
    protected $programLogoDataType = '';
    public $redemptionIssuers;
    protected $reviewType = 'Google_Service_Walletobjects_Review';
    protected $reviewDataType = '';
    public $reviewStatus;
    protected $securityAnimationType = 'Loyalty\GoogleWallet\Services\Google_Service_Walletobjects_SecurityAnimation';
    protected $securityAnimationDataType = '';
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    public $version;
    public $viewUnlockRequirement;
    protected $wordMarkType = 'Google_Service_Walletobjects_Image';
    protected $wordMarkDataType = '';


    public function setAllowBarcodeRedemption($allowBarcodeRedemption): void
    {
        $this->allowBarcodeRedemption = $allowBarcodeRedemption;
    }

    public function getAllowBarcodeRedemption()
    {
        return $this->allowBarcodeRedemption;
    }

    public function setAllowMultipleUsersPerObject($allowMultipleUsersPerObject): void
    {
        $this->allowMultipleUsersPerObject = $allowMultipleUsersPerObject;
    }

    public function getAllowMultipleUsersPerObject()
    {
        return $this->allowMultipleUsersPerObject;
    }

    public function setCallbackOptions(Google_Service_Walletobjects_CallbackOptions $callbackOptions): void
    {
        $this->callbackOptions = $callbackOptions;
    }

    public function getCallbackOptions(): Google_Service_Walletobjects_CallbackOptions
    {
        return $this->callbackOptions;
    }

    public function setCardNumberLabel($cardNumberLabel): void
    {
        $this->cardNumberLabel = $cardNumberLabel;
    }

    public function getCardNumberLabel()
    {
        return $this->cardNumberLabel;
    }

    public function setClassTemplateInfo(Google_Service_Walletobjects_ClassTemplateInfo $classTemplateInfo): void
    {
        $this->classTemplateInfo = $classTemplateInfo;
    }

    public function getClassTemplateInfo(): Google_Service_Walletobjects_ClassTemplateInfo
    {
        return $this->classTemplateInfo;
    }

    public function setCountryCode($countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setEnableSmartTap($enableSmartTap): void
    {
        $this->enableSmartTap = $enableSmartTap;
    }

    public function getEnableSmartTap()
    {
        return $this->enableSmartTap;
    }

    public function setEventNumberLabel($eventNumberLabel): void
    {
        $this->eventNumberLabel = $eventNumberLabel;
    }

    public function getEventNumberLabel()
    {
        return $this->eventNumberLabel;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setHexBackgroundColor($hexBackgroundColor): void
    {
        $this->hexBackgroundColor = $hexBackgroundColor;
    }

    public function getHexBackgroundColor()
    {
        return $this->hexBackgroundColor;
    }

    public function setHomepageUri(Google_Service_Walletobjects_Uri $homepageUri): void
    {
        $this->homepageUri = $homepageUri;
    }

    public function getHomepageUri(): Google_Service_Walletobjects_Uri
    {
        return $this->homepageUri;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData): void
    {
        $this->infoModuleData = $infoModuleData;
    }

    public function getInfoModuleData(): Google_Service_Walletobjects_InfoModuleData
    {
        return $this->infoModuleData;
    }

    public function setIssuerName($issuerName): void
    {
        $this->issuerName = $issuerName;
    }

    public function getIssuerName()
    {
        return $this->issuerName;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLocalizedCardNumberLabel(Google_Service_Walletobjects_LocalizedString $localizedCardNumberLabel
    ): void {
        $this->localizedCardNumberLabel = $localizedCardNumberLabel;
    }

    public function getLocalizedCardNumberLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedCardNumberLabel;
    }

    public function setLocalizedEventNumberLabel(Google_Service_Walletobjects_LocalizedString $localizedEventNumberLabel
    ): void {
        $this->localizedEventNumberLabel = $localizedEventNumberLabel;
    }

    public function getLocalizedEventNumberLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedEventNumberLabel;
    }

    public function setLocalizedIssuerName(Google_Service_Walletobjects_LocalizedString $localizedIssuerName): void
    {
        $this->localizedIssuerName = $localizedIssuerName;
    }

    public function getLocalizedIssuerName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedIssuerName;
    }

    public function setLocalizedMerchantName(Google_Service_Walletobjects_LocalizedString $localizedMerchantName): void
    {
        $this->localizedMerchantName = $localizedMerchantName;
    }

    public function getLocalizedMerchantName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedMerchantName;
    }

    public function setLocalizedPinLabel(Google_Service_Walletobjects_LocalizedString $localizedPinLabel): void
    {
        $this->localizedPinLabel = $localizedPinLabel;
    }

    public function getLocalizedPinLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedPinLabel;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function setMerchantName($merchantName): void
    {
        $this->merchantName = $merchantName;
    }

    public function getMerchantName()
    {
        return $this->merchantName;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setMultipleDevicesAndHoldersAllowedStatus($multipleDevicesAndHoldersAllowedStatus): void
    {
        $this->multipleDevicesAndHoldersAllowedStatus = $multipleDevicesAndHoldersAllowedStatus;
    }

    public function getMultipleDevicesAndHoldersAllowedStatus()
    {
        return $this->multipleDevicesAndHoldersAllowedStatus;
    }

    public function setPinLabel($pinLabel): void
    {
        $this->pinLabel = $pinLabel;
    }

    public function getPinLabel()
    {
        return $this->pinLabel;
    }

    public function setProgramLogo(Google_Service_Walletobjects_Image $programLogo): void
    {
        $this->programLogo = $programLogo;
    }

    public function getProgramLogo(): Google_Service_Walletobjects_Image
    {
        return $this->programLogo;
    }

    public function setRedemptionIssuers($redemptionIssuers): void
    {
        $this->redemptionIssuers = $redemptionIssuers;
    }

    public function getRedemptionIssuers()
    {
        return $this->redemptionIssuers;
    }

    public function setReview(Google_Service_Walletobjects_Review $review): void
    {
        $this->review = $review;
    }

    public function getReview(): Google_Service_Walletobjects_Review
    {
        return $this->review;
    }

    public function setReviewStatus($reviewStatus): void
    {
        $this->reviewStatus = $reviewStatus;
    }

    public function getReviewStatus()
    {
        return $this->reviewStatus;
    }

    public function setSecurityAnimation(Google_Service_Walletobjects_SecurityAnimation $securityAnimation): void
    {
        $this->securityAnimation = $securityAnimation;
    }

    public function getSecurityAnimation(): Google_Service_Walletobjects_SecurityAnimation
    {
        return $this->securityAnimation;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setViewUnlockRequirement($viewUnlockRequirement): void
    {
        $this->viewUnlockRequirement = $viewUnlockRequirement;
    }

    public function getViewUnlockRequirement()
    {
        return $this->viewUnlockRequirement;
    }

    public function setWordMark(Google_Service_Walletobjects_Image $wordMark): void
    {
        $this->wordMark = $wordMark;
    }

    public function getWordMark(): Google_Service_Walletobjects_Image
    {
        return $this->wordMark;
    }
}

class Google_Service_Walletobjects_GiftCardClassAddMessageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourceType = 'Google_Service_Walletobjects_GiftCardClass';
    protected $resourceDataType = '';


    public function setResource(Google_Service_Walletobjects_GiftCardClass $resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): Google_Service_Walletobjects_GiftCardClass
    {
        return $this->resource;
    }
}

class Google_Service_Walletobjects_GiftCardClassListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_GiftCardClass';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_GiftCardObject extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    protected $appLinkDataType = 'Google_Service_Walletobjects_AppLinkData';
    protected $appLinkDataDataType = '';
    protected $balanceType = 'Google_Service_Walletobjects_Money';
    protected $balanceDataType = '';
    protected $balanceUpdateTimeType = 'Google_Service_Walletobjects_DateTime';
    protected $balanceUpdateTimeDataType = '';
    protected $barcodeType = 'Google_Service_Walletobjects_Barcode';
    protected $barcodeDataType = '';
    public $cardNumber;
    public $classId;
    protected $classReferenceType = 'Google_Service_Walletobjects_GiftCardClass';
    protected $classReferenceDataType = '';
    public $disableExpirationNotification;
    public $eventNumber;
    protected $groupingInfoType = 'Google_Service_Walletobjects_GroupingInfo';
    protected $groupingInfoDataType = '';
    public $hasLinkedDevice;
    public $hasUsers;
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    public $kind;
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $locationsType = 'Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    protected $messagesType = 'Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    protected $passConstraintsType = 'Google_Service_Walletobjects_PassConstraints';
    protected $passConstraintsDataType = '';
    public $pin;
    protected $rotatingBarcodeType = 'Google_Service_Walletobjects_RotatingBarcode';
    protected $rotatingBarcodeDataType = '';
    public $smartTapRedemptionValue;
    public $state;
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    protected $validTimeIntervalType = 'Google_Service_Walletobjects_TimeInterval';
    protected $validTimeIntervalDataType = '';
    public $version;


    public function setAppLinkData(Google_Service_Walletobjects_AppLinkData $appLinkData): void
    {
        $this->appLinkData = $appLinkData;
    }

    public function getAppLinkData(): Google_Service_Walletobjects_AppLinkData
    {
        return $this->appLinkData;
    }

    public function setBalance(Google_Service_Walletobjects_Money $balance): void
    {
        $this->balance = $balance;
    }

    public function getBalance(): Google_Service_Walletobjects_Money
    {
        return $this->balance;
    }

    public function setBalanceUpdateTime(Google_Service_Walletobjects_DateTime $balanceUpdateTime): void
    {
        $this->balanceUpdateTime = $balanceUpdateTime;
    }

    public function getBalanceUpdateTime(): Google_Service_Walletobjects_DateTime
    {
        return $this->balanceUpdateTime;
    }

    public function setBarcode(Google_Service_Walletobjects_Barcode $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getBarcode(): Google_Service_Walletobjects_Barcode
    {
        return $this->barcode;
    }

    public function setCardNumber($cardNumber): void
    {
        $this->cardNumber = $cardNumber;
    }

    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    public function setClassId($classId): void
    {
        $this->classId = $classId;
    }

    public function getClassId()
    {
        return $this->classId;
    }

    public function setClassReference(Google_Service_Walletobjects_GiftCardClass $classReference): void
    {
        $this->classReference = $classReference;
    }

    public function getClassReference(): Google_Service_Walletobjects_GiftCardClass
    {
        return $this->classReference;
    }

    public function setDisableExpirationNotification($disableExpirationNotification): void
    {
        $this->disableExpirationNotification = $disableExpirationNotification;
    }

    public function getDisableExpirationNotification()
    {
        return $this->disableExpirationNotification;
    }

    public function setEventNumber($eventNumber): void
    {
        $this->eventNumber = $eventNumber;
    }

    public function getEventNumber()
    {
        return $this->eventNumber;
    }

    public function setGroupingInfo(Google_Service_Walletobjects_GroupingInfo $groupingInfo): void
    {
        $this->groupingInfo = $groupingInfo;
    }

    public function getGroupingInfo(): Google_Service_Walletobjects_GroupingInfo
    {
        return $this->groupingInfo;
    }

    public function setHasLinkedDevice($hasLinkedDevice): void
    {
        $this->hasLinkedDevice = $hasLinkedDevice;
    }

    public function getHasLinkedDevice()
    {
        return $this->hasLinkedDevice;
    }

    public function setHasUsers($hasUsers): void
    {
        $this->hasUsers = $hasUsers;
    }

    public function getHasUsers()
    {
        return $this->hasUsers;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData): void
    {
        $this->infoModuleData = $infoModuleData;
    }

    public function getInfoModuleData(): Google_Service_Walletobjects_InfoModuleData
    {
        return $this->infoModuleData;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setPassConstraints(Google_Service_Walletobjects_PassConstraints $passConstraints): void
    {
        $this->passConstraints = $passConstraints;
    }

    public function getPassConstraints(): Google_Service_Walletobjects_PassConstraints
    {
        return $this->passConstraints;
    }

    public function setPin($pin): void
    {
        $this->pin = $pin;
    }

    public function getPin()
    {
        return $this->pin;
    }

    public function setRotatingBarcode(Google_Service_Walletobjects_RotatingBarcode $rotatingBarcode): void
    {
        $this->rotatingBarcode = $rotatingBarcode;
    }

    public function getRotatingBarcode(): Google_Service_Walletobjects_RotatingBarcode
    {
        return $this->rotatingBarcode;
    }

    public function setSmartTapRedemptionValue($smartTapRedemptionValue): void
    {
        $this->smartTapRedemptionValue = $smartTapRedemptionValue;
    }

    public function getSmartTapRedemptionValue()
    {
        return $this->smartTapRedemptionValue;
    }

    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setValidTimeInterval(Google_Service_Walletobjects_TimeInterval $validTimeInterval): void
    {
        $this->validTimeInterval = $validTimeInterval;
    }

    public function getValidTimeInterval(): Google_Service_Walletobjects_TimeInterval
    {
        return $this->validTimeInterval;
    }

    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }
}

class Google_Service_Walletobjects_GiftCardObjectAddMessageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourceType = 'Google_Service_Walletobjects_GiftCardObject';
    protected $resourceDataType = '';


    public function setResource(Google_Service_Walletobjects_GiftCardObject $resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): Google_Service_Walletobjects_GiftCardObject
    {
        return $this->resource;
    }
}

class Google_Service_Walletobjects_GiftCardObjectListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_GiftCardObject';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_GroupingInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $groupingId;
    public $sortIndex;


    public function setGroupingId($groupingId): void
    {
        $this->groupingId = $groupingId;
    }

    public function getGroupingId()
    {
        return $this->groupingId;
    }

    public function setSortIndex($sortIndex): void
    {
        $this->sortIndex = $sortIndex;
    }

    public function getSortIndex()
    {
        return $this->sortIndex;
    }
}

class Google_Service_Walletobjects_Image extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $contentDescriptionType = 'Google_Service_Walletobjects_LocalizedString';
    protected $contentDescriptionDataType = '';
    public $kind;
    protected $sourceUriType = 'Google_Service_Walletobjects_ImageUri';
    protected $sourceUriDataType = '';


    public function setContentDescription(Google_Service_Walletobjects_LocalizedString $contentDescription): void
    {
        $this->contentDescription = $contentDescription;
    }

    public function getContentDescription(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->contentDescription;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setSourceUri(Google_Service_Walletobjects_ImageUri $sourceUri): void
    {
        $this->sourceUri = $sourceUri;
    }

    public function getSourceUri(): Google_Service_Walletobjects_ImageUri
    {
        return $this->sourceUri;
    }
}

class Google_Service_Walletobjects_ImageModuleData extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $id;
    protected $mainImageType = 'Google_Service_Walletobjects_Image';
    protected $mainImageDataType = '';


    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setMainImage(Google_Service_Walletobjects_Image $mainImage): void
    {
        $this->mainImage = $mainImage;
    }

    public function getMainImage(): Google_Service_Walletobjects_Image
    {
        return $this->mainImage;
    }
}

class Google_Service_Walletobjects_ImageUri extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $description;
    protected $localizedDescriptionType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedDescriptionDataType = '';
    public $uri;


    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setLocalizedDescription(Google_Service_Walletobjects_LocalizedString $localizedDescription): void
    {
        $this->localizedDescription = $localizedDescription;
    }

    public function getLocalizedDescription(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedDescription;
    }

    public function setUri($uri): void
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }
}

class Google_Service_Walletobjects_InfoModuleData extends Google_Collection
{
    protected $collection_key = 'labelValueRows';
    protected $internal_gapi_mappings = array();
    protected $labelValueRowsType = 'Google_Service_Walletobjects_LabelValueRow';
    protected $labelValueRowsDataType = 'array';
    public $showLastUpdateTime;


    public function setLabelValueRows($labelValueRows): void
    {
        $this->labelValueRows = $labelValueRows;
    }

    public function getLabelValueRows()
    {
        return $this->labelValueRows;
    }

    public function setShowLastUpdateTime($showLastUpdateTime): void
    {
        $this->showLastUpdateTime = $showLastUpdateTime;
    }

    public function getShowLastUpdateTime()
    {
        return $this->showLastUpdateTime;
    }
}

class Google_Service_Walletobjects_Issuer extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $contactInfoType = 'Google_Service_Walletobjects_IssuerContactInfo';
    protected $contactInfoDataType = '';
    public $homepageUrl;
    public $issuerId;
    public $name;
    protected $smartTapMerchantDataType = 'Google_Service_Walletobjects_SmartTapMerchantData';
    protected $smartTapMerchantDataDataType = '';


    public function setContactInfo(Google_Service_Walletobjects_IssuerContactInfo $contactInfo): void
    {
        $this->contactInfo = $contactInfo;
    }

    public function getContactInfo(): Google_Service_Walletobjects_IssuerContactInfo
    {
        return $this->contactInfo;
    }

    public function setHomepageUrl($homepageUrl): void
    {
        $this->homepageUrl = $homepageUrl;
    }

    public function getHomepageUrl()
    {
        return $this->homepageUrl;
    }

    public function setIssuerId($issuerId): void
    {
        $this->issuerId = $issuerId;
    }

    public function getIssuerId()
    {
        return $this->issuerId;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSmartTapMerchantData(Google_Service_Walletobjects_SmartTapMerchantData $smartTapMerchantData
    ): void {
        $this->smartTapMerchantData = $smartTapMerchantData;
    }

    public function getSmartTapMerchantData(): Google_Service_Walletobjects_SmartTapMerchantData
    {
        return $this->smartTapMerchantData;
    }
}

class Google_Service_Walletobjects_IssuerContactInfo extends Google_Collection
{
    protected $collection_key = 'alertsEmails';
    protected $internal_gapi_mappings = array();
    public $alertsEmails;
    public $email;
    public $name;
    public $phone;


    public function setAlertsEmails($alertsEmails): void
    {
        $this->alertsEmails = $alertsEmails;
    }

    public function getAlertsEmails()
    {
        return $this->alertsEmails;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPhone($phone): void
    {
        $this->phone = $phone;
    }

    public function getPhone()
    {
        return $this->phone;
    }
}

class Google_Service_Walletobjects_IssuerListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $resourcesType = 'Google_Service_Walletobjects_Issuer';
    protected $resourcesDataType = 'array';


    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_IssuerToUserInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $action;
    protected $signUpInfoType = 'Google_Service_Walletobjects_SignUpInfo';
    protected $signUpInfoDataType = '';
    public $url;
    public $value;


    public function setAction($action): void
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setSignUpInfo(Google_Service_Walletobjects_SignUpInfo $signUpInfo): void
    {
        $this->signUpInfo = $signUpInfo;
    }

    public function getSignUpInfo(): Google_Service_Walletobjects_SignUpInfo
    {
        return $this->signUpInfo;
    }

    public function setUrl($url): void
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}

class Google_Service_Walletobjects_JwtInsertResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourcesType = 'Google_Service_Walletobjects_Resources';
    protected $resourcesDataType = '';
    public $saveUri;


    public function setResources(Google_Service_Walletobjects_Resources $resources): void
    {
        $this->resources = $resources;
    }

    public function getResources(): Google_Service_Walletobjects_Resources
    {
        return $this->resources;
    }

    public function setSaveUri($saveUri): void
    {
        $this->saveUri = $saveUri;
    }

    public function getSaveUri()
    {
        return $this->saveUri;
    }
}

class Google_Service_Walletobjects_JwtResource extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $jwt;


    public function setJwt($jwt): void
    {
        $this->jwt = $jwt;
    }

    public function getJwt()
    {
        return $this->jwt;
    }
}

class Google_Service_Walletobjects_LabelValue extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $label;
    protected $localizedLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedLabelDataType = '';
    protected $localizedValueType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedValueDataType = '';
    public $value;


    public function setLabel($label): void
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLocalizedLabel(Google_Service_Walletobjects_LocalizedString $localizedLabel): void
    {
        $this->localizedLabel = $localizedLabel;
    }

    public function getLocalizedLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedLabel;
    }

    public function setLocalizedValue(Google_Service_Walletobjects_LocalizedString $localizedValue): void
    {
        $this->localizedValue = $localizedValue;
    }

    public function getLocalizedValue(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedValue;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}

class Google_Service_Walletobjects_LabelValueRow extends Google_Collection
{
    protected $collection_key = 'columns';
    protected $internal_gapi_mappings = array();
    protected $columnsType = 'Google_Service_Walletobjects_LabelValue';
    protected $columnsDataType = 'array';


    public function setColumns($columns): void
    {
        $this->columns = $columns;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}

class Google_Service_Walletobjects_LatLongPoint extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $kind;
    public $latitude;
    public $longitude;


    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }
}

class Google_Service_Walletobjects_LinksModuleData extends Google_Collection
{
    protected $collection_key = 'uris';
    protected $internal_gapi_mappings = array();
    protected $urisType = 'Google_Service_Walletobjects_Uri';
    protected $urisDataType = 'array';


    public function setUris($uris): void
    {
        $this->uris = $uris;
    }

    public function getUris()
    {
        return $this->uris;
    }
}

class Google_Service_Walletobjects_ListTemplateOverride extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $firstRowOptionType = 'Google_Service_Walletobjects_FirstRowOption';
    protected $firstRowOptionDataType = '';
    protected $secondRowOptionType = 'Google_Service_Walletobjects_FieldSelector';
    protected $secondRowOptionDataType = '';
    protected $thirdRowOptionType = 'Google_Service_Walletobjects_FieldSelector';
    protected $thirdRowOptionDataType = '';


    public function setFirstRowOption(Google_Service_Walletobjects_FirstRowOption $firstRowOption): void
    {
        $this->firstRowOption = $firstRowOption;
    }

    public function getFirstRowOption(): Google_Service_Walletobjects_FirstRowOption
    {
        return $this->firstRowOption;
    }

    public function setSecondRowOption(Google_Service_Walletobjects_FieldSelector $secondRowOption): void
    {
        $this->secondRowOption = $secondRowOption;
    }

    public function getSecondRowOption(): Google_Service_Walletobjects_FieldSelector
    {
        return $this->secondRowOption;
    }

    public function setThirdRowOption(Google_Service_Walletobjects_FieldSelector $thirdRowOption): void
    {
        $this->thirdRowOption = $thirdRowOption;
    }

    public function getThirdRowOption(): Google_Service_Walletobjects_FieldSelector
    {
        return $this->thirdRowOption;
    }
}

class Google_Service_Walletobjects_LocalizedString extends Google_Collection
{
    protected $collection_key = 'translatedValues';
    protected $internal_gapi_mappings = array();
    protected $defaultValueType = 'Google_Service_Walletobjects_TranslatedString';
    protected $defaultValueDataType = '';
    public $kind;
    protected $translatedValuesType = 'Google_Service_Walletobjects_TranslatedString';
    protected $translatedValuesDataType = 'array';


    public function setDefaultValue(Google_Service_Walletobjects_TranslatedString $defaultValue): void
    {
        $this->defaultValue = $defaultValue;
    }

    public function getDefaultValue(): Google_Service_Walletobjects_TranslatedString
    {
        return $this->defaultValue;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setTranslatedValues($translatedValues): void
    {
        $this->translatedValues = $translatedValues;
    }

    public function getTranslatedValues()
    {
        return $this->translatedValues;
    }
}

class Google_Service_Walletobjects_LoyaltyClass extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    public $accountIdLabel;
    public $accountNameLabel;
    public $allowMultipleUsersPerObject;
    protected $callbackOptionsType = 'Google_Service_Walletobjects_CallbackOptions';
    protected $callbackOptionsDataType = '';
    protected $classTemplateInfoType = 'Google_Service_Walletobjects_ClassTemplateInfo';
    protected $classTemplateInfoDataType = '';
    public $countryCode;
    protected $discoverableProgramType = 'Google_Service_Walletobjects_DiscoverableProgram';
    protected $discoverableProgramDataType = '';
    public $enableSmartTap;
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $hexBackgroundColor;
    protected $homepageUriType = 'Google_Service_Walletobjects_Uri';
    protected $homepageUriDataType = '';
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    public $issuerName;
    public $kind;
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $localizedAccountIdLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedAccountIdLabelDataType = '';
    protected $localizedAccountNameLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedAccountNameLabelDataType = '';
    protected $localizedIssuerNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedIssuerNameDataType = '';
    protected $localizedProgramNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedProgramNameDataType = '';
    protected $localizedRewardsTierType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedRewardsTierDataType = '';
    protected $localizedRewardsTierLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedRewardsTierLabelDataType = '';
    protected $localizedSecondaryRewardsTierType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedSecondaryRewardsTierDataType = '';
    protected $localizedSecondaryRewardsTierLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedSecondaryRewardsTierLabelDataType = '';
    protected $locationsType = 'Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    protected $messagesType = 'Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    public $multipleDevicesAndHoldersAllowedStatus;
    protected $programLogoType = 'Google_Service_Walletobjects_Image';
    protected $programLogoDataType = '';
    public $programName;
    public $redemptionIssuers;
    protected $reviewType = 'Google_Service_Walletobjects_Review';
    protected $reviewDataType = '';
    public $reviewStatus;
    public $rewardsTier;
    public $rewardsTierLabel;
    public $secondaryRewardsTier;
    public $secondaryRewardsTierLabel;
    protected $securityAnimationType = 'Loyalty\GoogleWallet\Services\Google_Service_Walletobjects_SecurityAnimation';
    protected $securityAnimationDataType = '';
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    public $version;
    public $viewUnlockRequirement;
    protected $wordMarkType = 'Google_Service_Walletobjects_Image';
    protected $wordMarkDataType = '';


    public function setAccountIdLabel($accountIdLabel): void
    {
        $this->accountIdLabel = $accountIdLabel;
    }

    public function getAccountIdLabel()
    {
        return $this->accountIdLabel;
    }

    public function setAccountNameLabel($accountNameLabel): void
    {
        $this->accountNameLabel = $accountNameLabel;
    }

    public function getAccountNameLabel()
    {
        return $this->accountNameLabel;
    }

    public function setAllowMultipleUsersPerObject($allowMultipleUsersPerObject): void
    {
        $this->allowMultipleUsersPerObject = $allowMultipleUsersPerObject;
    }

    public function getAllowMultipleUsersPerObject()
    {
        return $this->allowMultipleUsersPerObject;
    }

    public function setCallbackOptions(Google_Service_Walletobjects_CallbackOptions $callbackOptions): void
    {
        $this->callbackOptions = $callbackOptions;
    }

    public function getCallbackOptions(): Google_Service_Walletobjects_CallbackOptions
    {
        return $this->callbackOptions;
    }

    public function setClassTemplateInfo(Google_Service_Walletobjects_ClassTemplateInfo $classTemplateInfo): void
    {
        $this->classTemplateInfo = $classTemplateInfo;
    }

    public function getClassTemplateInfo(): Google_Service_Walletobjects_ClassTemplateInfo
    {
        return $this->classTemplateInfo;
    }

    public function setCountryCode($countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setDiscoverableProgram(Google_Service_Walletobjects_DiscoverableProgram $discoverableProgram): void
    {
        $this->discoverableProgram = $discoverableProgram;
    }

    public function getDiscoverableProgram(): Google_Service_Walletobjects_DiscoverableProgram
    {
        return $this->discoverableProgram;
    }

    public function setEnableSmartTap($enableSmartTap): void
    {
        $this->enableSmartTap = $enableSmartTap;
    }

    public function getEnableSmartTap()
    {
        return $this->enableSmartTap;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setHexBackgroundColor($hexBackgroundColor): void
    {
        $this->hexBackgroundColor = $hexBackgroundColor;
    }

    public function getHexBackgroundColor()
    {
        return $this->hexBackgroundColor;
    }

    public function setHomepageUri(Google_Service_Walletobjects_Uri $homepageUri): void
    {
        $this->homepageUri = $homepageUri;
    }

    public function getHomepageUri(): Google_Service_Walletobjects_Uri
    {
        return $this->homepageUri;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData): void
    {
        $this->infoModuleData = $infoModuleData;
    }

    public function getInfoModuleData(): Google_Service_Walletobjects_InfoModuleData
    {
        return $this->infoModuleData;
    }

    public function setIssuerName($issuerName): void
    {
        $this->issuerName = $issuerName;
    }

    public function getIssuerName()
    {
        return $this->issuerName;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLocalizedAccountIdLabel(Google_Service_Walletobjects_LocalizedString $localizedAccountIdLabel
    ): void {
        $this->localizedAccountIdLabel = $localizedAccountIdLabel;
    }

    public function getLocalizedAccountIdLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedAccountIdLabel;
    }

    public function setLocalizedAccountNameLabel(Google_Service_Walletobjects_LocalizedString $localizedAccountNameLabel
    ): void {
        $this->localizedAccountNameLabel = $localizedAccountNameLabel;
    }

    public function getLocalizedAccountNameLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedAccountNameLabel;
    }

    public function setLocalizedIssuerName(Google_Service_Walletobjects_LocalizedString $localizedIssuerName): void
    {
        $this->localizedIssuerName = $localizedIssuerName;
    }

    public function getLocalizedIssuerName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedIssuerName;
    }

    public function setLocalizedProgramName(Google_Service_Walletobjects_LocalizedString $localizedProgramName): void
    {
        $this->localizedProgramName = $localizedProgramName;
    }

    public function getLocalizedProgramName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedProgramName;
    }

    public function setLocalizedRewardsTier(Google_Service_Walletobjects_LocalizedString $localizedRewardsTier): void
    {
        $this->localizedRewardsTier = $localizedRewardsTier;
    }

    public function getLocalizedRewardsTier(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedRewardsTier;
    }

    public function setLocalizedRewardsTierLabel(Google_Service_Walletobjects_LocalizedString $localizedRewardsTierLabel
    ): void {
        $this->localizedRewardsTierLabel = $localizedRewardsTierLabel;
    }

    public function getLocalizedRewardsTierLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedRewardsTierLabel;
    }

    public function setLocalizedSecondaryRewardsTier(
        Google_Service_Walletobjects_LocalizedString $localizedSecondaryRewardsTier
    ): void {
        $this->localizedSecondaryRewardsTier = $localizedSecondaryRewardsTier;
    }

    public function getLocalizedSecondaryRewardsTier(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedSecondaryRewardsTier;
    }

    public function setLocalizedSecondaryRewardsTierLabel(
        Google_Service_Walletobjects_LocalizedString $localizedSecondaryRewardsTierLabel
    ): void {
        $this->localizedSecondaryRewardsTierLabel = $localizedSecondaryRewardsTierLabel;
    }

    public function getLocalizedSecondaryRewardsTierLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedSecondaryRewardsTierLabel;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setMultipleDevicesAndHoldersAllowedStatus($multipleDevicesAndHoldersAllowedStatus): void
    {
        $this->multipleDevicesAndHoldersAllowedStatus = $multipleDevicesAndHoldersAllowedStatus;
    }

    public function getMultipleDevicesAndHoldersAllowedStatus()
    {
        return $this->multipleDevicesAndHoldersAllowedStatus;
    }

    public function setProgramLogo(Google_Service_Walletobjects_Image $programLogo): void
    {
        $this->programLogo = $programLogo;
    }

    public function getProgramLogo(): Google_Service_Walletobjects_Image
    {
        return $this->programLogo;
    }

    public function setProgramName($programName): void
    {
        $this->programName = $programName;
    }

    public function getProgramName()
    {
        return $this->programName;
    }

    public function setRedemptionIssuers($redemptionIssuers): void
    {
        $this->redemptionIssuers = $redemptionIssuers;
    }

    public function getRedemptionIssuers()
    {
        return $this->redemptionIssuers;
    }

    public function setReview(Google_Service_Walletobjects_Review $review): void
    {
        $this->review = $review;
    }

    public function getReview(): Google_Service_Walletobjects_Review
    {
        return $this->review;
    }

    public function setReviewStatus($reviewStatus): void
    {
        $this->reviewStatus = $reviewStatus;
    }

    public function getReviewStatus()
    {
        return $this->reviewStatus;
    }

    public function setRewardsTier($rewardsTier): void
    {
        $this->rewardsTier = $rewardsTier;
    }

    public function getRewardsTier()
    {
        return $this->rewardsTier;
    }

    public function setRewardsTierLabel($rewardsTierLabel): void
    {
        $this->rewardsTierLabel = $rewardsTierLabel;
    }

    public function getRewardsTierLabel()
    {
        return $this->rewardsTierLabel;
    }

    public function setSecondaryRewardsTier($secondaryRewardsTier): void
    {
        $this->secondaryRewardsTier = $secondaryRewardsTier;
    }

    public function getSecondaryRewardsTier()
    {
        return $this->secondaryRewardsTier;
    }

    public function setSecondaryRewardsTierLabel($secondaryRewardsTierLabel): void
    {
        $this->secondaryRewardsTierLabel = $secondaryRewardsTierLabel;
    }

    public function getSecondaryRewardsTierLabel()
    {
        return $this->secondaryRewardsTierLabel;
    }

    public function setSecurityAnimation(Google_Service_Walletobjects_SecurityAnimation $securityAnimation): void
    {
        $this->securityAnimation = $securityAnimation;
    }

    public function getSecurityAnimation(): Google_Service_Walletobjects_SecurityAnimation
    {
        return $this->securityAnimation;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setViewUnlockRequirement($viewUnlockRequirement): void
    {
        $this->viewUnlockRequirement = $viewUnlockRequirement;
    }

    public function getViewUnlockRequirement()
    {
        return $this->viewUnlockRequirement;
    }

    public function setWordMark(Google_Service_Walletobjects_Image $wordMark): void
    {
        $this->wordMark = $wordMark;
    }

    public function getWordMark(): Google_Service_Walletobjects_Image
    {
        return $this->wordMark;
    }
}

class Google_Service_Walletobjects_LoyaltyClassAddMessageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourceType = 'Google_Service_Walletobjects_LoyaltyClass';
    protected $resourceDataType = '';


    public function setResource(Google_Service_Walletobjects_LoyaltyClass $resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): Google_Service_Walletobjects_LoyaltyClass
    {
        return $this->resource;
    }
}

class Google_Service_Walletobjects_LoyaltyClassListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_LoyaltyClass';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_LoyaltyObject extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    public $accountId;
    public $accountName;
    protected $appLinkDataType = 'Google_Service_Walletobjects_AppLinkData';
    protected $appLinkDataDataType = '';
    protected $barcodeType = 'Google_Service_Walletobjects_Barcode';
    protected $barcodeDataType = '';
    public $classId;
    protected $classReferenceType = 'Google_Service_Walletobjects_LoyaltyClass';
    protected $classReferenceDataType = '';
    public $disableExpirationNotification;
    protected $groupingInfoType = 'Google_Service_Walletobjects_GroupingInfo';
    protected $groupingInfoDataType = '';
    public $hasLinkedDevice;
    public $hasUsers;
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    public $kind;
    public $linkedOfferIds;
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $locationsType = 'Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    protected $loyaltyPointsType = 'Google_Service_Walletobjects_LoyaltyPoints';
    protected $loyaltyPointsDataType = '';
    protected $messagesType = 'Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    protected $passConstraintsType = 'Google_Service_Walletobjects_PassConstraints';
    protected $passConstraintsDataType = '';
    protected $rotatingBarcodeType = 'Google_Service_Walletobjects_RotatingBarcode';
    protected $rotatingBarcodeDataType = '';
    protected $secondaryLoyaltyPointsType = 'Google_Service_Walletobjects_LoyaltyPoints';
    protected $secondaryLoyaltyPointsDataType = '';
    public $smartTapRedemptionValue;
    public $state;
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    protected $validTimeIntervalType = 'Google_Service_Walletobjects_TimeInterval';
    protected $validTimeIntervalDataType = '';
    public $version;


    public function setAccountId($accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function setAccountName($accountName): void
    {
        $this->accountName = $accountName;
    }

    public function getAccountName()
    {
        return $this->accountName;
    }

    public function setAppLinkData(Google_Service_Walletobjects_AppLinkData $appLinkData): void
    {
        $this->appLinkData = $appLinkData;
    }

    public function getAppLinkData(): Google_Service_Walletobjects_AppLinkData
    {
        return $this->appLinkData;
    }

    public function setBarcode(Google_Service_Walletobjects_Barcode $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getBarcode(): Google_Service_Walletobjects_Barcode
    {
        return $this->barcode;
    }

    public function setClassId($classId): void
    {
        $this->classId = $classId;
    }

    public function getClassId()
    {
        return $this->classId;
    }

    public function setClassReference(Google_Service_Walletobjects_LoyaltyClass $classReference): void
    {
        $this->classReference = $classReference;
    }

    public function getClassReference(): Google_Service_Walletobjects_LoyaltyClass
    {
        return $this->classReference;
    }

    public function setDisableExpirationNotification($disableExpirationNotification): void
    {
        $this->disableExpirationNotification = $disableExpirationNotification;
    }

    public function getDisableExpirationNotification()
    {
        return $this->disableExpirationNotification;
    }

    public function setGroupingInfo(Google_Service_Walletobjects_GroupingInfo $groupingInfo): void
    {
        $this->groupingInfo = $groupingInfo;
    }

    public function getGroupingInfo(): Google_Service_Walletobjects_GroupingInfo
    {
        return $this->groupingInfo;
    }

    public function setHasLinkedDevice($hasLinkedDevice): void
    {
        $this->hasLinkedDevice = $hasLinkedDevice;
    }

    public function getHasLinkedDevice()
    {
        return $this->hasLinkedDevice;
    }

    public function setHasUsers($hasUsers): void
    {
        $this->hasUsers = $hasUsers;
    }

    public function getHasUsers()
    {
        return $this->hasUsers;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData): void
    {
        $this->infoModuleData = $infoModuleData;
    }

    public function getInfoModuleData(): Google_Service_Walletobjects_InfoModuleData
    {
        return $this->infoModuleData;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLinkedOfferIds($linkedOfferIds): void
    {
        $this->linkedOfferIds = $linkedOfferIds;
    }

    public function getLinkedOfferIds()
    {
        return $this->linkedOfferIds;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function setLoyaltyPoints(Google_Service_Walletobjects_LoyaltyPoints $loyaltyPoints): void
    {
        $this->loyaltyPoints = $loyaltyPoints;
    }

    public function getLoyaltyPoints(): Google_Service_Walletobjects_LoyaltyPoints
    {
        return $this->loyaltyPoints;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setPassConstraints(Google_Service_Walletobjects_PassConstraints $passConstraints): void
    {
        $this->passConstraints = $passConstraints;
    }

    public function getPassConstraints(): Google_Service_Walletobjects_PassConstraints
    {
        return $this->passConstraints;
    }

    public function setRotatingBarcode(Google_Service_Walletobjects_RotatingBarcode $rotatingBarcode): void
    {
        $this->rotatingBarcode = $rotatingBarcode;
    }

    public function getRotatingBarcode(): Google_Service_Walletobjects_RotatingBarcode
    {
        return $this->rotatingBarcode;
    }

    public function setSecondaryLoyaltyPoints(Google_Service_Walletobjects_LoyaltyPoints $secondaryLoyaltyPoints): void
    {
        $this->secondaryLoyaltyPoints = $secondaryLoyaltyPoints;
    }

    public function getSecondaryLoyaltyPoints(): Google_Service_Walletobjects_LoyaltyPoints
    {
        return $this->secondaryLoyaltyPoints;
    }

    public function setSmartTapRedemptionValue($smartTapRedemptionValue): void
    {
        $this->smartTapRedemptionValue = $smartTapRedemptionValue;
    }

    public function getSmartTapRedemptionValue()
    {
        return $this->smartTapRedemptionValue;
    }

    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setValidTimeInterval(Google_Service_Walletobjects_TimeInterval $validTimeInterval): void
    {
        $this->validTimeInterval = $validTimeInterval;
    }

    public function getValidTimeInterval(): Google_Service_Walletobjects_TimeInterval
    {
        return $this->validTimeInterval;
    }

    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }
}

class Google_Service_Walletobjects_LoyaltyObjectAddMessageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourceType = 'Google_Service_Walletobjects_LoyaltyObject';
    protected $resourceDataType = '';


    public function setResource(Google_Service_Walletobjects_LoyaltyObject $resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): Google_Service_Walletobjects_LoyaltyObject
    {
        return $this->resource;
    }
}

class Google_Service_Walletobjects_LoyaltyObjectListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_LoyaltyObject';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_LoyaltyPoints extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $balanceType = 'Google_Service_Walletobjects_LoyaltyPointsBalance';
    protected $balanceDataType = '';
    public $label;
    protected $localizedLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedLabelDataType = '';


    public function setBalance(Google_Service_Walletobjects_LoyaltyPointsBalance $balance): void
    {
        $this->balance = $balance;
    }

    public function getBalance(): Google_Service_Walletobjects_LoyaltyPointsBalance
    {
        return $this->balance;
    }

    public function setLabel($label): void
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLocalizedLabel(Google_Service_Walletobjects_LocalizedString $localizedLabel): void
    {
        $this->localizedLabel = $localizedLabel;
    }

    public function getLocalizedLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedLabel;
    }
}

class Google_Service_Walletobjects_LoyaltyPointsBalance extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $double;
    public $int;
    protected $moneyType = 'Google_Service_Walletobjects_Money';
    protected $moneyDataType = '';
    public $string;


    public function setDouble($double): void
    {
        $this->double = $double;
    }

    public function getDouble()
    {
        return $this->double;
    }

    public function setInt($int): void
    {
        $this->int = $int;
    }

    public function getInt()
    {
        return $this->int;
    }

    public function setMoney(Google_Service_Walletobjects_Money $money): void
    {
        $this->money = $money;
    }

    public function getMoney(): Google_Service_Walletobjects_Money
    {
        return $this->money;
    }

    public function setString($string): void
    {
        $this->string = $string;
    }

    public function getString()
    {
        return $this->string;
    }
}

class Google_Service_Walletobjects_Media extends Google_Collection
{
    protected $collection_key = 'compositeMedia';
    protected $internal_gapi_mappings = array();
    public $algorithm;
    public $bigstoreObjectRef;
    public $blobRef;
    protected $blobstore2InfoType = 'Google_Service_Walletobjects_Blobstore2Info';
    protected $blobstore2InfoDataType = '';
    protected $compositeMediaType = 'Google_Service_Walletobjects_CompositeMedia';
    protected $compositeMediaDataType = 'array';
    public $contentType;
    protected $contentTypeInfoType = 'Google_Service_Walletobjects_ContentTypeInfo';
    protected $contentTypeInfoDataType = '';
    public $cosmoBinaryReference;
    public $crc32cHash;
    protected $diffChecksumsResponseType = 'Google_Service_Walletobjects_DiffChecksumsResponse';
    protected $diffChecksumsResponseDataType = '';
    protected $diffDownloadResponseType = 'Google_Service_Walletobjects_DiffDownloadResponse';
    protected $diffDownloadResponseDataType = '';
    protected $diffUploadRequestType = 'Google_Service_Walletobjects_DiffUploadRequest';
    protected $diffUploadRequestDataType = '';
    protected $diffUploadResponseType = 'Google_Service_Walletobjects_DiffUploadResponse';
    protected $diffUploadResponseDataType = '';
    protected $diffVersionResponseType = 'Google_Service_Walletobjects_DiffVersionResponse';
    protected $diffVersionResponseDataType = '';
    protected $downloadParametersType = 'Google_Service_Walletobjects_DownloadParameters';
    protected $downloadParametersDataType = '';
    public $filename;
    public $hash;
    public $hashVerified;
    public $inline;
    public $isPotentialRetry;
    public $length;
    public $md5Hash;
    public $mediaId;
    protected $objectIdType = 'Google_Service_Walletobjects_ObjectId';
    protected $objectIdDataType = '';
    public $path;
    public $referenceType;
    public $sha1Hash;
    public $sha256Hash;
    public $timestamp;
    public $token;


    public function setAlgorithm($algorithm): void
    {
        $this->algorithm = $algorithm;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    public function setBigstoreObjectRef($bigstoreObjectRef): void
    {
        $this->bigstoreObjectRef = $bigstoreObjectRef;
    }

    public function getBigstoreObjectRef()
    {
        return $this->bigstoreObjectRef;
    }

    public function setBlobRef($blobRef): void
    {
        $this->blobRef = $blobRef;
    }

    public function getBlobRef()
    {
        return $this->blobRef;
    }

    public function setBlobstore2Info(Google_Service_Walletobjects_Blobstore2Info $blobstore2Info): void
    {
        $this->blobstore2Info = $blobstore2Info;
    }

    public function getBlobstore2Info(): Google_Service_Walletobjects_Blobstore2Info
    {
        return $this->blobstore2Info;
    }

    public function setCompositeMedia($compositeMedia): void
    {
        $this->compositeMedia = $compositeMedia;
    }

    public function getCompositeMedia()
    {
        return $this->compositeMedia;
    }

    public function setContentType($contentType): void
    {
        $this->contentType = $contentType;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function setContentTypeInfo(Google_Service_Walletobjects_ContentTypeInfo $contentTypeInfo): void
    {
        $this->contentTypeInfo = $contentTypeInfo;
    }

    public function getContentTypeInfo(): Google_Service_Walletobjects_ContentTypeInfo
    {
        return $this->contentTypeInfo;
    }

    public function setCosmoBinaryReference($cosmoBinaryReference): void
    {
        $this->cosmoBinaryReference = $cosmoBinaryReference;
    }

    public function getCosmoBinaryReference()
    {
        return $this->cosmoBinaryReference;
    }

    public function setCrc32cHash($crc32cHash): void
    {
        $this->crc32cHash = $crc32cHash;
    }

    public function getCrc32cHash()
    {
        return $this->crc32cHash;
    }

    public function setDiffChecksumsResponse(Google_Service_Walletobjects_DiffChecksumsResponse $diffChecksumsResponse
    ): void {
        $this->diffChecksumsResponse = $diffChecksumsResponse;
    }

    public function getDiffChecksumsResponse(): Google_Service_Walletobjects_DiffChecksumsResponse
    {
        return $this->diffChecksumsResponse;
    }

    public function setDiffDownloadResponse(Google_Service_Walletobjects_DiffDownloadResponse $diffDownloadResponse
    ): void {
        $this->diffDownloadResponse = $diffDownloadResponse;
    }

    public function getDiffDownloadResponse(): Google_Service_Walletobjects_DiffDownloadResponse
    {
        return $this->diffDownloadResponse;
    }

    public function setDiffUploadRequest(Google_Service_Walletobjects_DiffUploadRequest $diffUploadRequest): void
    {
        $this->diffUploadRequest = $diffUploadRequest;
    }

    public function getDiffUploadRequest(): Google_Service_Walletobjects_DiffUploadRequest
    {
        return $this->diffUploadRequest;
    }

    public function setDiffUploadResponse(Google_Service_Walletobjects_DiffUploadResponse $diffUploadResponse): void
    {
        $this->diffUploadResponse = $diffUploadResponse;
    }

    public function getDiffUploadResponse(): Google_Service_Walletobjects_DiffUploadResponse
    {
        return $this->diffUploadResponse;
    }

    public function setDiffVersionResponse(Google_Service_Walletobjects_DiffVersionResponse $diffVersionResponse): void
    {
        $this->diffVersionResponse = $diffVersionResponse;
    }

    public function getDiffVersionResponse(): Google_Service_Walletobjects_DiffVersionResponse
    {
        return $this->diffVersionResponse;
    }

    public function setDownloadParameters(Google_Service_Walletobjects_DownloadParameters $downloadParameters): void
    {
        $this->downloadParameters = $downloadParameters;
    }

    public function getDownloadParameters(): Google_Service_Walletobjects_DownloadParameters
    {
        return $this->downloadParameters;
    }

    public function setFilename($filename): void
    {
        $this->filename = $filename;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setHash($hash): void
    {
        $this->hash = $hash;
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function setHashVerified($hashVerified): void
    {
        $this->hashVerified = $hashVerified;
    }

    public function getHashVerified()
    {
        return $this->hashVerified;
    }

    public function setInline($inline): void
    {
        $this->inline = $inline;
    }

    public function getInline()
    {
        return $this->inline;
    }

    public function setIsPotentialRetry($isPotentialRetry): void
    {
        $this->isPotentialRetry = $isPotentialRetry;
    }

    public function getIsPotentialRetry()
    {
        return $this->isPotentialRetry;
    }

    public function setLength($length): void
    {
        $this->length = $length;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function setMd5Hash($md5Hash): void
    {
        $this->md5Hash = $md5Hash;
    }

    public function getMd5Hash()
    {
        return $this->md5Hash;
    }

    public function setMediaId($mediaId): void
    {
        $this->mediaId = $mediaId;
    }

    public function getMediaId()
    {
        return $this->mediaId;
    }

    public function setObjectId(Google_Service_Walletobjects_ObjectId $objectId): void
    {
        $this->objectId = $objectId;
    }

    public function getObjectId(): Google_Service_Walletobjects_ObjectId
    {
        return $this->objectId;
    }

    public function setPath($path): void
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setReferenceType($referenceType): void
    {
        $this->referenceType = $referenceType;
    }

    public function getReferenceType()
    {
        return $this->referenceType;
    }

    public function setSha1Hash($sha1Hash): void
    {
        $this->sha1Hash = $sha1Hash;
    }

    public function getSha1Hash()
    {
        return $this->sha1Hash;
    }

    public function setSha256Hash($sha256Hash): void
    {
        $this->sha256Hash = $sha256Hash;
    }

    public function getSha256Hash()
    {
        return $this->sha256Hash;
    }

    public function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setToken($token): void
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }
}

class Google_Service_Walletobjects_MediaRequestInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $currentBytes;
    public $customData;
    public $diffObjectVersion;
    public $finalStatus;
    public $notificationType;
    public $requestId;
    public $totalBytes;
    public $totalBytesIsEstimated;


    public function setCurrentBytes($currentBytes): void
    {
        $this->currentBytes = $currentBytes;
    }

    public function getCurrentBytes()
    {
        return $this->currentBytes;
    }

    public function setCustomData($customData): void
    {
        $this->customData = $customData;
    }

    public function getCustomData()
    {
        return $this->customData;
    }

    public function setDiffObjectVersion($diffObjectVersion): void
    {
        $this->diffObjectVersion = $diffObjectVersion;
    }

    public function getDiffObjectVersion()
    {
        return $this->diffObjectVersion;
    }

    public function setFinalStatus($finalStatus): void
    {
        $this->finalStatus = $finalStatus;
    }

    public function getFinalStatus()
    {
        return $this->finalStatus;
    }

    public function setNotificationType($notificationType): void
    {
        $this->notificationType = $notificationType;
    }

    public function getNotificationType()
    {
        return $this->notificationType;
    }

    public function setRequestId($requestId): void
    {
        $this->requestId = $requestId;
    }

    public function getRequestId()
    {
        return $this->requestId;
    }

    public function setTotalBytes($totalBytes): void
    {
        $this->totalBytes = $totalBytes;
    }

    public function getTotalBytes()
    {
        return $this->totalBytes;
    }

    public function setTotalBytesIsEstimated($totalBytesIsEstimated): void
    {
        $this->totalBytesIsEstimated = $totalBytesIsEstimated;
    }

    public function getTotalBytesIsEstimated()
    {
        return $this->totalBytesIsEstimated;
    }
}

class Google_Service_Walletobjects_Message extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $body;
    protected $displayIntervalType = 'Google_Service_Walletobjects_TimeInterval';
    protected $displayIntervalDataType = '';
    public $header;
    public $id;
    public $kind;
    protected $localizedBodyType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedBodyDataType = '';
    protected $localizedHeaderType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedHeaderDataType = '';
    public $messageType;


    public function setBody($body): void
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setDisplayInterval(Google_Service_Walletobjects_TimeInterval $displayInterval): void
    {
        $this->displayInterval = $displayInterval;
    }

    public function getDisplayInterval(): Google_Service_Walletobjects_TimeInterval
    {
        return $this->displayInterval;
    }

    public function setHeader($header): void
    {
        $this->header = $header;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLocalizedBody(Google_Service_Walletobjects_LocalizedString $localizedBody): void
    {
        $this->localizedBody = $localizedBody;
    }

    public function getLocalizedBody(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedBody;
    }

    public function setLocalizedHeader(Google_Service_Walletobjects_LocalizedString $localizedHeader): void
    {
        $this->localizedHeader = $localizedHeader;
    }

    public function getLocalizedHeader(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedHeader;
    }

    public function setMessageType($messageType): void
    {
        $this->messageType = $messageType;
    }

    public function getMessageType()
    {
        return $this->messageType;
    }
}

class Google_Service_Walletobjects_ModifyLinkedOfferObjects extends Google_Collection
{
    protected $collection_key = 'removeLinkedOfferObjectIds';
    protected $internal_gapi_mappings = array();
    public $addLinkedOfferObjectIds;
    public $removeLinkedOfferObjectIds;


    public function setAddLinkedOfferObjectIds($addLinkedOfferObjectIds): void
    {
        $this->addLinkedOfferObjectIds = $addLinkedOfferObjectIds;
    }

    public function getAddLinkedOfferObjectIds()
    {
        return $this->addLinkedOfferObjectIds;
    }

    public function setRemoveLinkedOfferObjectIds($removeLinkedOfferObjectIds): void
    {
        $this->removeLinkedOfferObjectIds = $removeLinkedOfferObjectIds;
    }

    public function getRemoveLinkedOfferObjectIds()
    {
        return $this->removeLinkedOfferObjectIds;
    }
}

class Google_Service_Walletobjects_ModifyLinkedOfferObjectsRequest extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $linkedOfferObjectIdsType = 'Google_Service_Walletobjects_ModifyLinkedOfferObjects';
    protected $linkedOfferObjectIdsDataType = '';


    public function setLinkedOfferObjectIds(Google_Service_Walletobjects_ModifyLinkedOfferObjects $linkedOfferObjectIds
    ): void {
        $this->linkedOfferObjectIds = $linkedOfferObjectIds;
    }

    public function getLinkedOfferObjectIds(): Google_Service_Walletobjects_ModifyLinkedOfferObjects
    {
        return $this->linkedOfferObjectIds;
    }
}

class Google_Service_Walletobjects_Money extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $currencyCode;
    public $kind;
    public $micros;


    public function setCurrencyCode($currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setMicros($micros): void
    {
        $this->micros = $micros;
    }

    public function getMicros()
    {
        return $this->micros;
    }
}

class Google_Service_Walletobjects_Notifications extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $expiryNotificationType = 'Google_Service_Walletobjects_ExpiryNotification';
    protected $expiryNotificationDataType = '';
    protected $upcomingNotificationType = 'Google_Service_Walletobjects_UpcomingNotification';
    protected $upcomingNotificationDataType = '';


    public function setExpiryNotification(Google_Service_Walletobjects_ExpiryNotification $expiryNotification): void
    {
        $this->expiryNotification = $expiryNotification;
    }

    public function getExpiryNotification(): Google_Service_Walletobjects_ExpiryNotification
    {
        return $this->expiryNotification;
    }

    public function setUpcomingNotification(Google_Service_Walletobjects_UpcomingNotification $upcomingNotification
    ): void {
        $this->upcomingNotification = $upcomingNotification;
    }

    public function getUpcomingNotification(): Google_Service_Walletobjects_UpcomingNotification
    {
        return $this->upcomingNotification;
    }
}

class Google_Service_Walletobjects_ObjectId extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $bucketName;
    public $generation;
    public $objectName;


    public function setBucketName($bucketName): void
    {
        $this->bucketName = $bucketName;
    }

    public function getBucketName()
    {
        return $this->bucketName;
    }

    public function setGeneration($generation): void
    {
        $this->generation = $generation;
    }

    public function getGeneration()
    {
        return $this->generation;
    }

    public function setObjectName($objectName): void
    {
        $this->objectName = $objectName;
    }

    public function getObjectName()
    {
        return $this->objectName;
    }
}

class Google_Service_Walletobjects_OfferClass extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    public $allowMultipleUsersPerObject;
    protected $callbackOptionsType = 'Google_Service_Walletobjects_CallbackOptions';
    protected $callbackOptionsDataType = '';
    protected $classTemplateInfoType = 'Google_Service_Walletobjects_ClassTemplateInfo';
    protected $classTemplateInfoDataType = '';
    public $countryCode;
    public $details;
    public $enableSmartTap;
    public $finePrint;
    protected $helpUriType = 'Google_Service_Walletobjects_Uri';
    protected $helpUriDataType = '';
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $hexBackgroundColor;
    protected $homepageUriType = 'Google_Service_Walletobjects_Uri';
    protected $homepageUriDataType = '';
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    public $issuerName;
    public $kind;
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $localizedDetailsType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedDetailsDataType = '';
    protected $localizedFinePrintType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedFinePrintDataType = '';
    protected $localizedIssuerNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedIssuerNameDataType = '';
    protected $localizedProviderType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedProviderDataType = '';
    protected $localizedShortTitleType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedShortTitleDataType = '';
    protected $localizedTitleType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedTitleDataType = '';
    protected $locationsType = 'Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    protected $messagesType = 'Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    public $multipleDevicesAndHoldersAllowedStatus;
    public $provider;
    public $redemptionChannel;
    public $redemptionIssuers;
    protected $reviewType = 'Google_Service_Walletobjects_Review';
    protected $reviewDataType = '';
    public $reviewStatus;
    protected $securityAnimationType = 'Loyalty\GoogleWallet\Services\Google_Service_Walletobjects_SecurityAnimation';
    protected $securityAnimationDataType = '';
    public $shortTitle;
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    public $title;
    protected $titleImageType = 'Google_Service_Walletobjects_Image';
    protected $titleImageDataType = '';
    public $version;
    public $viewUnlockRequirement;
    protected $wordMarkType = 'Google_Service_Walletobjects_Image';
    protected $wordMarkDataType = '';


    public function setAllowMultipleUsersPerObject($allowMultipleUsersPerObject): void
    {
        $this->allowMultipleUsersPerObject = $allowMultipleUsersPerObject;
    }

    public function getAllowMultipleUsersPerObject()
    {
        return $this->allowMultipleUsersPerObject;
    }

    public function setCallbackOptions(Google_Service_Walletobjects_CallbackOptions $callbackOptions): void
    {
        $this->callbackOptions = $callbackOptions;
    }

    public function getCallbackOptions(): Google_Service_Walletobjects_CallbackOptions
    {
        return $this->callbackOptions;
    }

    public function setClassTemplateInfo(Google_Service_Walletobjects_ClassTemplateInfo $classTemplateInfo): void
    {
        $this->classTemplateInfo = $classTemplateInfo;
    }

    public function getClassTemplateInfo(): Google_Service_Walletobjects_ClassTemplateInfo
    {
        return $this->classTemplateInfo;
    }

    public function setCountryCode($countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setDetails($details): void
    {
        $this->details = $details;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function setEnableSmartTap($enableSmartTap): void
    {
        $this->enableSmartTap = $enableSmartTap;
    }

    public function getEnableSmartTap()
    {
        return $this->enableSmartTap;
    }

    public function setFinePrint($finePrint): void
    {
        $this->finePrint = $finePrint;
    }

    public function getFinePrint()
    {
        return $this->finePrint;
    }

    public function setHelpUri(Google_Service_Walletobjects_Uri $helpUri): void
    {
        $this->helpUri = $helpUri;
    }

    public function getHelpUri(): Google_Service_Walletobjects_Uri
    {
        return $this->helpUri;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setHexBackgroundColor($hexBackgroundColor): void
    {
        $this->hexBackgroundColor = $hexBackgroundColor;
    }

    public function getHexBackgroundColor()
    {
        return $this->hexBackgroundColor;
    }

    public function setHomepageUri(Google_Service_Walletobjects_Uri $homepageUri): void
    {
        $this->homepageUri = $homepageUri;
    }

    public function getHomepageUri(): Google_Service_Walletobjects_Uri
    {
        return $this->homepageUri;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData): void
    {
        $this->infoModuleData = $infoModuleData;
    }

    public function getInfoModuleData(): Google_Service_Walletobjects_InfoModuleData
    {
        return $this->infoModuleData;
    }

    public function setIssuerName($issuerName): void
    {
        $this->issuerName = $issuerName;
    }

    public function getIssuerName()
    {
        return $this->issuerName;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLocalizedDetails(Google_Service_Walletobjects_LocalizedString $localizedDetails): void
    {
        $this->localizedDetails = $localizedDetails;
    }

    public function getLocalizedDetails(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedDetails;
    }

    public function setLocalizedFinePrint(Google_Service_Walletobjects_LocalizedString $localizedFinePrint): void
    {
        $this->localizedFinePrint = $localizedFinePrint;
    }

    public function getLocalizedFinePrint(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedFinePrint;
    }

    public function setLocalizedIssuerName(Google_Service_Walletobjects_LocalizedString $localizedIssuerName): void
    {
        $this->localizedIssuerName = $localizedIssuerName;
    }

    public function getLocalizedIssuerName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedIssuerName;
    }

    public function setLocalizedProvider(Google_Service_Walletobjects_LocalizedString $localizedProvider): void
    {
        $this->localizedProvider = $localizedProvider;
    }

    public function getLocalizedProvider(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedProvider;
    }

    public function setLocalizedShortTitle(Google_Service_Walletobjects_LocalizedString $localizedShortTitle): void
    {
        $this->localizedShortTitle = $localizedShortTitle;
    }

    public function getLocalizedShortTitle(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedShortTitle;
    }

    public function setLocalizedTitle(Google_Service_Walletobjects_LocalizedString $localizedTitle): void
    {
        $this->localizedTitle = $localizedTitle;
    }

    public function getLocalizedTitle(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedTitle;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setMultipleDevicesAndHoldersAllowedStatus($multipleDevicesAndHoldersAllowedStatus): void
    {
        $this->multipleDevicesAndHoldersAllowedStatus = $multipleDevicesAndHoldersAllowedStatus;
    }

    public function getMultipleDevicesAndHoldersAllowedStatus()
    {
        return $this->multipleDevicesAndHoldersAllowedStatus;
    }

    public function setProvider($provider): void
    {
        $this->provider = $provider;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function setRedemptionChannel($redemptionChannel): void
    {
        $this->redemptionChannel = $redemptionChannel;
    }

    public function getRedemptionChannel()
    {
        return $this->redemptionChannel;
    }

    public function setRedemptionIssuers($redemptionIssuers): void
    {
        $this->redemptionIssuers = $redemptionIssuers;
    }

    public function getRedemptionIssuers()
    {
        return $this->redemptionIssuers;
    }

    public function setReview(Google_Service_Walletobjects_Review $review): void
    {
        $this->review = $review;
    }

    public function getReview(): Google_Service_Walletobjects_Review
    {
        return $this->review;
    }

    public function setReviewStatus($reviewStatus): void
    {
        $this->reviewStatus = $reviewStatus;
    }

    public function getReviewStatus()
    {
        return $this->reviewStatus;
    }

    public function setSecurityAnimation(Google_Service_Walletobjects_SecurityAnimation $securityAnimation): void
    {
        $this->securityAnimation = $securityAnimation;
    }

    public function getSecurityAnimation(): Google_Service_Walletobjects_SecurityAnimation
    {
        return $this->securityAnimation;
    }

    public function setShortTitle($shortTitle): void
    {
        $this->shortTitle = $shortTitle;
    }

    public function getShortTitle()
    {
        return $this->shortTitle;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitleImage(Google_Service_Walletobjects_Image $titleImage): void
    {
        $this->titleImage = $titleImage;
    }

    public function getTitleImage(): Google_Service_Walletobjects_Image
    {
        return $this->titleImage;
    }

    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setViewUnlockRequirement($viewUnlockRequirement): void
    {
        $this->viewUnlockRequirement = $viewUnlockRequirement;
    }

    public function getViewUnlockRequirement()
    {
        return $this->viewUnlockRequirement;
    }

    public function setWordMark(Google_Service_Walletobjects_Image $wordMark): void
    {
        $this->wordMark = $wordMark;
    }

    public function getWordMark(): Google_Service_Walletobjects_Image
    {
        return $this->wordMark;
    }
}

class Google_Service_Walletobjects_OfferClassAddMessageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourceType = 'Google_Service_Walletobjects_OfferClass';
    protected $resourceDataType = '';


    public function setResource(Google_Service_Walletobjects_OfferClass $resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): Google_Service_Walletobjects_OfferClass
    {
        return $this->resource;
    }
}

class Google_Service_Walletobjects_OfferClassListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_OfferClass';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_OfferObject extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    protected $appLinkDataType = 'Google_Service_Walletobjects_AppLinkData';
    protected $appLinkDataDataType = '';
    protected $barcodeType = 'Google_Service_Walletobjects_Barcode';
    protected $barcodeDataType = '';
    public $classId;
    protected $classReferenceType = 'Google_Service_Walletobjects_OfferClass';
    protected $classReferenceDataType = '';
    public $disableExpirationNotification;
    protected $groupingInfoType = 'Google_Service_Walletobjects_GroupingInfo';
    protected $groupingInfoDataType = '';
    public $hasLinkedDevice;
    public $hasUsers;
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    public $kind;
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $locationsType = 'Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    protected $messagesType = 'Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    protected $passConstraintsType = 'Google_Service_Walletobjects_PassConstraints';
    protected $passConstraintsDataType = '';
    protected $rotatingBarcodeType = 'Google_Service_Walletobjects_RotatingBarcode';
    protected $rotatingBarcodeDataType = '';
    public $smartTapRedemptionValue;
    public $state;
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    protected $validTimeIntervalType = 'Google_Service_Walletobjects_TimeInterval';
    protected $validTimeIntervalDataType = '';
    public $version;


    public function setAppLinkData(Google_Service_Walletobjects_AppLinkData $appLinkData): void
    {
        $this->appLinkData = $appLinkData;
    }

    public function getAppLinkData(): Google_Service_Walletobjects_AppLinkData
    {
        return $this->appLinkData;
    }

    public function setBarcode(Google_Service_Walletobjects_Barcode $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getBarcode(): Google_Service_Walletobjects_Barcode
    {
        return $this->barcode;
    }

    public function setClassId($classId): void
    {
        $this->classId = $classId;
    }

    public function getClassId()
    {
        return $this->classId;
    }

    public function setClassReference(Google_Service_Walletobjects_OfferClass $classReference): void
    {
        $this->classReference = $classReference;
    }

    public function getClassReference(): Google_Service_Walletobjects_OfferClass
    {
        return $this->classReference;
    }

    public function setDisableExpirationNotification($disableExpirationNotification): void
    {
        $this->disableExpirationNotification = $disableExpirationNotification;
    }

    public function getDisableExpirationNotification()
    {
        return $this->disableExpirationNotification;
    }

    public function setGroupingInfo(Google_Service_Walletobjects_GroupingInfo $groupingInfo): void
    {
        $this->groupingInfo = $groupingInfo;
    }

    public function getGroupingInfo(): Google_Service_Walletobjects_GroupingInfo
    {
        return $this->groupingInfo;
    }

    public function setHasLinkedDevice($hasLinkedDevice): void
    {
        $this->hasLinkedDevice = $hasLinkedDevice;
    }

    public function getHasLinkedDevice()
    {
        return $this->hasLinkedDevice;
    }

    public function setHasUsers($hasUsers): void
    {
        $this->hasUsers = $hasUsers;
    }

    public function getHasUsers()
    {
        return $this->hasUsers;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData): void
    {
        $this->infoModuleData = $infoModuleData;
    }

    public function getInfoModuleData(): Google_Service_Walletobjects_InfoModuleData
    {
        return $this->infoModuleData;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setPassConstraints(Google_Service_Walletobjects_PassConstraints $passConstraints): void
    {
        $this->passConstraints = $passConstraints;
    }

    public function getPassConstraints(): Google_Service_Walletobjects_PassConstraints
    {
        return $this->passConstraints;
    }

    public function setRotatingBarcode(Google_Service_Walletobjects_RotatingBarcode $rotatingBarcode): void
    {
        $this->rotatingBarcode = $rotatingBarcode;
    }

    public function getRotatingBarcode(): Google_Service_Walletobjects_RotatingBarcode
    {
        return $this->rotatingBarcode;
    }

    public function setSmartTapRedemptionValue($smartTapRedemptionValue): void
    {
        $this->smartTapRedemptionValue = $smartTapRedemptionValue;
    }

    public function getSmartTapRedemptionValue()
    {
        return $this->smartTapRedemptionValue;
    }

    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setValidTimeInterval(Google_Service_Walletobjects_TimeInterval $validTimeInterval): void
    {
        $this->validTimeInterval = $validTimeInterval;
    }

    public function getValidTimeInterval(): Google_Service_Walletobjects_TimeInterval
    {
        return $this->validTimeInterval;
    }

    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }
}

class Google_Service_Walletobjects_OfferObjectAddMessageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourceType = 'Google_Service_Walletobjects_OfferObject';
    protected $resourceDataType = '';


    public function setResource(Google_Service_Walletobjects_OfferObject $resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): Google_Service_Walletobjects_OfferObject
    {
        return $this->resource;
    }
}

class Google_Service_Walletobjects_OfferObjectListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_OfferObject';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_Pagination extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $kind;
    public $nextPageToken;
    public $resultsPerPage;


    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setNextPageToken($nextPageToken): void
    {
        $this->nextPageToken = $nextPageToken;
    }

    public function getNextPageToken()
    {
        return $this->nextPageToken;
    }

    public function setResultsPerPage($resultsPerPage): void
    {
        $this->resultsPerPage = $resultsPerPage;
    }

    public function getResultsPerPage()
    {
        return $this->resultsPerPage;
    }
}

class Google_Service_Walletobjects_PassConstraints extends Google_Collection
{
    protected $collection_key = 'nfcConstraint';
    protected $internal_gapi_mappings = array();
    public $nfcConstraint;
    public $screenshotEligibility;


    public function setNfcConstraint($nfcConstraint): void
    {
        $this->nfcConstraint = $nfcConstraint;
    }

    public function getNfcConstraint()
    {
        return $this->nfcConstraint;
    }

    public function setScreenshotEligibility($screenshotEligibility): void
    {
        $this->screenshotEligibility = $screenshotEligibility;
    }

    public function getScreenshotEligibility()
    {
        return $this->screenshotEligibility;
    }
}

class Google_Service_Walletobjects_Permission extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $emailAddress;
    public $role;


    public function setEmailAddress($emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function setRole($role): void
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }
}

class Google_Service_Walletobjects_Permissions extends Google_Collection
{
    protected $collection_key = 'permissions';
    protected $internal_gapi_mappings = array();
    public $issuerId;
    protected $permissionsType = 'Google_Service_Walletobjects_Permission';
    protected $permissionsDataType = 'array';


    public function setIssuerId($issuerId): void
    {
        $this->issuerId = $issuerId;
    }

    public function getIssuerId()
    {
        return $this->issuerId;
    }

    public function setPermissions($permissions): void
    {
        $this->permissions = $permissions;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }
}

class Google_Service_Walletobjects_PrivateText extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $bodyType = 'Google_Service_Walletobjects_LocalizedString';
    protected $bodyDataType = '';
    protected $headerType = 'Google_Service_Walletobjects_LocalizedString';
    protected $headerDataType = '';


    public function setBody(Google_Service_Walletobjects_LocalizedString $body): void
    {
        $this->body = $body;
    }

    public function getBody(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->body;
    }

    public function setHeader(Google_Service_Walletobjects_LocalizedString $header): void
    {
        $this->header = $header;
    }

    public function getHeader(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->header;
    }
}

class Google_Service_Walletobjects_PrivateUri extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $descriptionType = 'Google_Service_Walletobjects_LocalizedString';
    protected $descriptionDataType = '';
    public $uri;


    public function setDescription(Google_Service_Walletobjects_LocalizedString $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->description;
    }

    public function setUri($uri): void
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }
}

class Google_Service_Walletobjects_PurchaseDetails extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $accountId;
    public $confirmationCode;
    public $purchaseDateTime;
    public $purchaseReceiptNumber;
    protected $ticketCostType = 'Google_Service_Walletobjects_TicketCost';
    protected $ticketCostDataType = '';


    public function setAccountId($accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getAccountId()
    {
        return $this->accountId;
    }

    public function setConfirmationCode($confirmationCode): void
    {
        $this->confirmationCode = $confirmationCode;
    }

    public function getConfirmationCode()
    {
        return $this->confirmationCode;
    }

    public function setPurchaseDateTime($purchaseDateTime): void
    {
        $this->purchaseDateTime = $purchaseDateTime;
    }

    public function getPurchaseDateTime()
    {
        return $this->purchaseDateTime;
    }

    public function setPurchaseReceiptNumber($purchaseReceiptNumber): void
    {
        $this->purchaseReceiptNumber = $purchaseReceiptNumber;
    }

    public function getPurchaseReceiptNumber()
    {
        return $this->purchaseReceiptNumber;
    }

    public function setTicketCost(Google_Service_Walletobjects_TicketCost $ticketCost): void
    {
        $this->ticketCost = $ticketCost;
    }

    public function getTicketCost(): Google_Service_Walletobjects_TicketCost
    {
        return $this->ticketCost;
    }
}

class Google_Service_Walletobjects_ReservationInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $confirmationCode;
    public $eticketNumber;
    protected $frequentFlyerInfoType = 'Google_Service_Walletobjects_FrequentFlyerInfo';
    protected $frequentFlyerInfoDataType = '';
    public $kind;


    public function setConfirmationCode($confirmationCode): void
    {
        $this->confirmationCode = $confirmationCode;
    }

    public function getConfirmationCode()
    {
        return $this->confirmationCode;
    }

    public function setEticketNumber($eticketNumber): void
    {
        $this->eticketNumber = $eticketNumber;
    }

    public function getEticketNumber()
    {
        return $this->eticketNumber;
    }

    public function setFrequentFlyerInfo(Google_Service_Walletobjects_FrequentFlyerInfo $frequentFlyerInfo): void
    {
        $this->frequentFlyerInfo = $frequentFlyerInfo;
    }

    public function getFrequentFlyerInfo(): Google_Service_Walletobjects_FrequentFlyerInfo
    {
        return $this->frequentFlyerInfo;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }
}

class Google_Service_Walletobjects_Resources extends Google_Collection
{
    protected $collection_key = 'transitObjects';
    protected $internal_gapi_mappings = array();
    protected $eventTicketClassesType = 'Google_Service_Walletobjects_EventTicketClass';
    protected $eventTicketClassesDataType = 'array';
    protected $eventTicketObjectsType = 'Google_Service_Walletobjects_EventTicketObject';
    protected $eventTicketObjectsDataType = 'array';
    protected $flightClassesType = 'Google_Service_Walletobjects_FlightClass';
    protected $flightClassesDataType = 'array';
    protected $flightObjectsType = 'Google_Service_Walletobjects_FlightObject';
    protected $flightObjectsDataType = 'array';
    protected $giftCardClassesType = 'Google_Service_Walletobjects_GiftCardClass';
    protected $giftCardClassesDataType = 'array';
    protected $giftCardObjectsType = 'Google_Service_Walletobjects_GiftCardObject';
    protected $giftCardObjectsDataType = 'array';
    protected $loyaltyClassesType = 'Google_Service_Walletobjects_LoyaltyClass';
    protected $loyaltyClassesDataType = 'array';
    protected $loyaltyObjectsType = 'Google_Service_Walletobjects_LoyaltyObject';
    protected $loyaltyObjectsDataType = 'array';
    protected $offerClassesType = 'Google_Service_Walletobjects_OfferClass';
    protected $offerClassesDataType = 'array';
    protected $offerObjectsType = 'Google_Service_Walletobjects_OfferObject';
    protected $offerObjectsDataType = 'array';
    protected $transitClassesType = 'Google_Service_Walletobjects_TransitClass';
    protected $transitClassesDataType = 'array';
    protected $transitObjectsType = 'Google_Service_Walletobjects_TransitObject';
    protected $transitObjectsDataType = 'array';


    public function setEventTicketClasses($eventTicketClasses): void
    {
        $this->eventTicketClasses = $eventTicketClasses;
    }

    public function getEventTicketClasses()
    {
        return $this->eventTicketClasses;
    }

    public function setEventTicketObjects($eventTicketObjects): void
    {
        $this->eventTicketObjects = $eventTicketObjects;
    }

    public function getEventTicketObjects()
    {
        return $this->eventTicketObjects;
    }

    public function setFlightClasses($flightClasses): void
    {
        $this->flightClasses = $flightClasses;
    }

    public function getFlightClasses()
    {
        return $this->flightClasses;
    }

    public function setFlightObjects($flightObjects): void
    {
        $this->flightObjects = $flightObjects;
    }

    public function getFlightObjects()
    {
        return $this->flightObjects;
    }

    public function setGiftCardClasses($giftCardClasses): void
    {
        $this->giftCardClasses = $giftCardClasses;
    }

    public function getGiftCardClasses()
    {
        return $this->giftCardClasses;
    }

    public function setGiftCardObjects($giftCardObjects): void
    {
        $this->giftCardObjects = $giftCardObjects;
    }

    public function getGiftCardObjects()
    {
        return $this->giftCardObjects;
    }

    public function setLoyaltyClasses($loyaltyClasses): void
    {
        $this->loyaltyClasses = $loyaltyClasses;
    }

    public function getLoyaltyClasses()
    {
        return $this->loyaltyClasses;
    }

    public function setLoyaltyObjects($loyaltyObjects): void
    {
        $this->loyaltyObjects = $loyaltyObjects;
    }

    public function getLoyaltyObjects()
    {
        return $this->loyaltyObjects;
    }

    public function setOfferClasses($offerClasses): void
    {
        $this->offerClasses = $offerClasses;
    }

    public function getOfferClasses()
    {
        return $this->offerClasses;
    }

    public function setOfferObjects($offerObjects): void
    {
        $this->offerObjects = $offerObjects;
    }

    public function getOfferObjects()
    {
        return $this->offerObjects;
    }

    public function setTransitClasses($transitClasses): void
    {
        $this->transitClasses = $transitClasses;
    }

    public function getTransitClasses()
    {
        return $this->transitClasses;
    }

    public function setTransitObjects($transitObjects): void
    {
        $this->transitObjects = $transitObjects;
    }

    public function getTransitObjects()
    {
        return $this->transitObjects;
    }
}

class Google_Service_Walletobjects_Review extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $comments;


    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    public function getComments()
    {
        return $this->comments;
    }
}

class Google_Service_Walletobjects_RotatingBarcode extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $alternateText;
    public $renderEncoding;
    protected $showCodeTextType = 'Google_Service_Walletobjects_LocalizedString';
    protected $showCodeTextDataType = '';
    protected $totpDetailsType = 'Google_Service_Walletobjects_RotatingBarcodeTotpDetails';
    protected $totpDetailsDataType = '';
    public $type;
    public $valuePattern;


    public function setAlternateText($alternateText): void
    {
        $this->alternateText = $alternateText;
    }

    public function getAlternateText()
    {
        return $this->alternateText;
    }

    public function setRenderEncoding($renderEncoding): void
    {
        $this->renderEncoding = $renderEncoding;
    }

    public function getRenderEncoding()
    {
        return $this->renderEncoding;
    }

    public function setShowCodeText(Google_Service_Walletobjects_LocalizedString $showCodeText): void
    {
        $this->showCodeText = $showCodeText;
    }

    public function getShowCodeText(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->showCodeText;
    }

    public function setTotpDetails(Google_Service_Walletobjects_RotatingBarcodeTotpDetails $totpDetails): void
    {
        $this->totpDetails = $totpDetails;
    }

    public function getTotpDetails(): Google_Service_Walletobjects_RotatingBarcodeTotpDetails
    {
        return $this->totpDetails;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setValuePattern($valuePattern): void
    {
        $this->valuePattern = $valuePattern;
    }

    public function getValuePattern()
    {
        return $this->valuePattern;
    }
}

class Google_Service_Walletobjects_RotatingBarcodeTotpDetails extends Google_Collection
{
    protected $collection_key = 'parameters';
    protected $internal_gapi_mappings = array();
    public $algorithm;
    protected $parametersType = 'Google_Service_Walletobjects_RotatingBarcodeTotpDetailsTotpParameters';
    protected $parametersDataType = 'array';
    public $periodMillis;


    public function setAlgorithm($algorithm): void
    {
        $this->algorithm = $algorithm;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    public function setParameters($parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setPeriodMillis($periodMillis): void
    {
        $this->periodMillis = $periodMillis;
    }

    public function getPeriodMillis()
    {
        return $this->periodMillis;
    }
}

class Google_Service_Walletobjects_RotatingBarcodeTotpDetailsTotpParameters extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $key;
    public $valueLength;


    public function setKey($key): void
    {
        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setValueLength($valueLength): void
    {
        $this->valueLength = $valueLength;
    }

    public function getValueLength()
    {
        return $this->valueLength;
    }
}

class Google_Service_Walletobjects_SecurityAnimation extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $animationType;


    public function setAnimationType($animationType): void
    {
        $this->animationType = $animationType;
    }

    public function getAnimationType()
    {
        return $this->animationType;
    }
}

class Google_Service_Walletobjects_SignUpInfo extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $classId;


    public function setClassId($classId): void
    {
        $this->classId = $classId;
    }

    public function getClassId()
    {
        return $this->classId;
    }
}

class Google_Service_Walletobjects_SmartTap extends Google_Collection
{
    protected $collection_key = 'infos';
    protected $internal_gapi_mappings = array();
    public $id;
    protected $infosType = 'Google_Service_Walletobjects_IssuerToUserInfo';
    protected $infosDataType = 'array';
    public $kind;
    public $merchantId;


    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setInfos($infos): void
    {
        $this->infos = $infos;
    }

    public function getInfos()
    {
        return $this->infos;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setMerchantId($merchantId): void
    {
        $this->merchantId = $merchantId;
    }

    public function getMerchantId()
    {
        return $this->merchantId;
    }
}

class Google_Service_Walletobjects_SmartTapMerchantData extends Google_Collection
{
    protected $collection_key = 'authenticationKeys';
    protected $internal_gapi_mappings = array();
    protected $authenticationKeysType = 'Google_Service_Walletobjects_AuthenticationKey';
    protected $authenticationKeysDataType = 'array';
    public $smartTapMerchantId;


    public function setAuthenticationKeys($authenticationKeys): void
    {
        $this->authenticationKeys = $authenticationKeys;
    }

    public function getAuthenticationKeys()
    {
        return $this->authenticationKeys;
    }

    public function setSmartTapMerchantId($smartTapMerchantId): void
    {
        $this->smartTapMerchantId = $smartTapMerchantId;
    }

    public function getSmartTapMerchantId()
    {
        return $this->smartTapMerchantId;
    }
}

class Google_Service_Walletobjects_TemplateItem extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $firstValueType = 'Google_Service_Walletobjects_FieldSelector';
    protected $firstValueDataType = '';
    public $predefinedItem;
    protected $secondValueType = 'Google_Service_Walletobjects_FieldSelector';
    protected $secondValueDataType = '';


    public function setFirstValue(Google_Service_Walletobjects_FieldSelector $firstValue): void
    {
        $this->firstValue = $firstValue;
    }

    public function getFirstValue(): Google_Service_Walletobjects_FieldSelector
    {
        return $this->firstValue;
    }

    public function setPredefinedItem($predefinedItem): void
    {
        $this->predefinedItem = $predefinedItem;
    }

    public function getPredefinedItem()
    {
        return $this->predefinedItem;
    }

    public function setSecondValue(Google_Service_Walletobjects_FieldSelector $secondValue): void
    {
        $this->secondValue = $secondValue;
    }

    public function getSecondValue(): Google_Service_Walletobjects_FieldSelector
    {
        return $this->secondValue;
    }
}

class Google_Service_Walletobjects_TextModuleData extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $body;
    public $header;
    public $id;
    protected $localizedBodyType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedBodyDataType = '';
    protected $localizedHeaderType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedHeaderDataType = '';


    public function setBody($body): void
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setHeader($header): void
    {
        $this->header = $header;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setLocalizedBody(Google_Service_Walletobjects_LocalizedString $localizedBody): void
    {
        $this->localizedBody = $localizedBody;
    }

    public function getLocalizedBody(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedBody;
    }

    public function setLocalizedHeader(Google_Service_Walletobjects_LocalizedString $localizedHeader): void
    {
        $this->localizedHeader = $localizedHeader;
    }

    public function getLocalizedHeader(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedHeader;
    }
}

class Google_Service_Walletobjects_TicketCost extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $discountMessageType = 'Google_Service_Walletobjects_LocalizedString';
    protected $discountMessageDataType = '';
    protected $faceValueType = 'Google_Service_Walletobjects_Money';
    protected $faceValueDataType = '';
    protected $purchasePriceType = 'Google_Service_Walletobjects_Money';
    protected $purchasePriceDataType = '';


    public function setDiscountMessage(Google_Service_Walletobjects_LocalizedString $discountMessage): void
    {
        $this->discountMessage = $discountMessage;
    }

    public function getDiscountMessage(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->discountMessage;
    }

    public function setFaceValue(Google_Service_Walletobjects_Money $faceValue): void
    {
        $this->faceValue = $faceValue;
    }

    public function getFaceValue(): Google_Service_Walletobjects_Money
    {
        return $this->faceValue;
    }

    public function setPurchasePrice(Google_Service_Walletobjects_Money $purchasePrice): void
    {
        $this->purchasePrice = $purchasePrice;
    }

    public function getPurchasePrice(): Google_Service_Walletobjects_Money
    {
        return $this->purchasePrice;
    }
}

class Google_Service_Walletobjects_TicketLeg extends Google_Collection
{
    protected $collection_key = 'ticketSeats';
    protected $internal_gapi_mappings = array();
    public $arrivalDateTime;
    public $carriage;
    public $departureDateTime;
    protected $destinationNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $destinationNameDataType = '';
    public $destinationStationCode;
    protected $fareNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $fareNameDataType = '';
    protected $originNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $originNameDataType = '';
    public $originStationCode;
    public $platform;
    protected $ticketSeatType = 'Google_Service_Walletobjects_TicketSeat';
    protected $ticketSeatDataType = '';
    protected $ticketSeatsType = 'Google_Service_Walletobjects_TicketSeat';
    protected $ticketSeatsDataType = 'array';
    protected $transitOperatorNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $transitOperatorNameDataType = '';
    protected $transitTerminusNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $transitTerminusNameDataType = '';
    public $zone;


    public function setArrivalDateTime($arrivalDateTime): void
    {
        $this->arrivalDateTime = $arrivalDateTime;
    }

    public function getArrivalDateTime()
    {
        return $this->arrivalDateTime;
    }

    public function setCarriage($carriage): void
    {
        $this->carriage = $carriage;
    }

    public function getCarriage()
    {
        return $this->carriage;
    }

    public function setDepartureDateTime($departureDateTime): void
    {
        $this->departureDateTime = $departureDateTime;
    }

    public function getDepartureDateTime()
    {
        return $this->departureDateTime;
    }

    public function setDestinationName(Google_Service_Walletobjects_LocalizedString $destinationName): void
    {
        $this->destinationName = $destinationName;
    }

    public function getDestinationName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->destinationName;
    }

    public function setDestinationStationCode($destinationStationCode): void
    {
        $this->destinationStationCode = $destinationStationCode;
    }

    public function getDestinationStationCode()
    {
        return $this->destinationStationCode;
    }

    public function setFareName(Google_Service_Walletobjects_LocalizedString $fareName): void
    {
        $this->fareName = $fareName;
    }

    public function getFareName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->fareName;
    }

    public function setOriginName(Google_Service_Walletobjects_LocalizedString $originName): void
    {
        $this->originName = $originName;
    }

    public function getOriginName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->originName;
    }

    public function setOriginStationCode($originStationCode): void
    {
        $this->originStationCode = $originStationCode;
    }

    public function getOriginStationCode()
    {
        return $this->originStationCode;
    }

    public function setPlatform($platform): void
    {
        $this->platform = $platform;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function setTicketSeat(Google_Service_Walletobjects_TicketSeat $ticketSeat): void
    {
        $this->ticketSeat = $ticketSeat;
    }

    public function getTicketSeat(): Google_Service_Walletobjects_TicketSeat
    {
        return $this->ticketSeat;
    }

    public function setTicketSeats($ticketSeats): void
    {
        $this->ticketSeats = $ticketSeats;
    }

    public function getTicketSeats()
    {
        return $this->ticketSeats;
    }

    public function setTransitOperatorName(Google_Service_Walletobjects_LocalizedString $transitOperatorName): void
    {
        $this->transitOperatorName = $transitOperatorName;
    }

    public function getTransitOperatorName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->transitOperatorName;
    }

    public function setTransitTerminusName(Google_Service_Walletobjects_LocalizedString $transitTerminusName): void
    {
        $this->transitTerminusName = $transitTerminusName;
    }

    public function getTransitTerminusName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->transitTerminusName;
    }

    public function setZone($zone): void
    {
        $this->zone = $zone;
    }

    public function getZone()
    {
        return $this->zone;
    }
}

class Google_Service_Walletobjects_TicketRestrictions extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $otherRestrictionsType = 'Google_Service_Walletobjects_LocalizedString';
    protected $otherRestrictionsDataType = '';
    protected $routeRestrictionsType = 'Google_Service_Walletobjects_LocalizedString';
    protected $routeRestrictionsDataType = '';
    protected $routeRestrictionsDetailsType = 'Google_Service_Walletobjects_LocalizedString';
    protected $routeRestrictionsDetailsDataType = '';
    protected $timeRestrictionsType = 'Google_Service_Walletobjects_LocalizedString';
    protected $timeRestrictionsDataType = '';


    public function setOtherRestrictions(Google_Service_Walletobjects_LocalizedString $otherRestrictions): void
    {
        $this->otherRestrictions = $otherRestrictions;
    }

    public function getOtherRestrictions(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->otherRestrictions;
    }

    public function setRouteRestrictions(Google_Service_Walletobjects_LocalizedString $routeRestrictions): void
    {
        $this->routeRestrictions = $routeRestrictions;
    }

    public function getRouteRestrictions(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->routeRestrictions;
    }

    public function setRouteRestrictionsDetails(Google_Service_Walletobjects_LocalizedString $routeRestrictionsDetails
    ): void {
        $this->routeRestrictionsDetails = $routeRestrictionsDetails;
    }

    public function getRouteRestrictionsDetails(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->routeRestrictionsDetails;
    }

    public function setTimeRestrictions(Google_Service_Walletobjects_LocalizedString $timeRestrictions): void
    {
        $this->timeRestrictions = $timeRestrictions;
    }

    public function getTimeRestrictions(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->timeRestrictions;
    }
}

class Google_Service_Walletobjects_TicketSeat extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $coach;
    protected $customFareClassType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customFareClassDataType = '';
    public $fareClass;
    public $seat;
    protected $seatAssignmentType = 'Google_Service_Walletobjects_LocalizedString';
    protected $seatAssignmentDataType = '';


    public function setCoach($coach): void
    {
        $this->coach = $coach;
    }

    public function getCoach()
    {
        return $this->coach;
    }

    public function setCustomFareClass(Google_Service_Walletobjects_LocalizedString $customFareClass): void
    {
        $this->customFareClass = $customFareClass;
    }

    public function getCustomFareClass(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customFareClass;
    }

    public function setFareClass($fareClass): void
    {
        $this->fareClass = $fareClass;
    }

    public function getFareClass()
    {
        return $this->fareClass;
    }

    public function setSeat($seat): void
    {
        $this->seat = $seat;
    }

    public function getSeat()
    {
        return $this->seat;
    }

    public function setSeatAssignment(Google_Service_Walletobjects_LocalizedString $seatAssignment): void
    {
        $this->seatAssignment = $seatAssignment;
    }

    public function getSeatAssignment(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->seatAssignment;
    }
}

class Google_Service_Walletobjects_TimeInterval extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $endType = 'Google_Service_Walletobjects_DateTime';
    protected $endDataType = '';
    public $kind;
    protected $startType = 'Google_Service_Walletobjects_DateTime';
    protected $startDataType = '';


    public function setEnd(Google_Service_Walletobjects_DateTime $end): void
    {
        $this->end = $end;
    }

    public function getEnd(): Google_Service_Walletobjects_DateTime
    {
        return $this->end;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setStart(Google_Service_Walletobjects_DateTime $start): void
    {
        $this->start = $start;
    }

    public function getStart(): Google_Service_Walletobjects_DateTime
    {
        return $this->start;
    }
}

class Google_Service_Walletobjects_TransitClass extends Google_Collection
{
    protected $collection_key = 'textModulesData';
    protected $internal_gapi_mappings = array();
    protected $activationOptionsType = 'Google_Service_Walletobjects_ActivationOptions';
    protected $activationOptionsDataType = '';
    public $allowMultipleUsersPerObject;
    protected $callbackOptionsType = 'Google_Service_Walletobjects_CallbackOptions';
    protected $callbackOptionsDataType = '';
    protected $classTemplateInfoType = 'Google_Service_Walletobjects_ClassTemplateInfo';
    protected $classTemplateInfoDataType = '';
    public $countryCode;
    protected $customCarriageLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customCarriageLabelDataType = '';
    protected $customCoachLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customCoachLabelDataType = '';
    protected $customConcessionCategoryLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customConcessionCategoryLabelDataType = '';
    protected $customConfirmationCodeLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customConfirmationCodeLabelDataType = '';
    protected $customDiscountMessageLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customDiscountMessageLabelDataType = '';
    protected $customFareClassLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customFareClassLabelDataType = '';
    protected $customFareNameLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customFareNameLabelDataType = '';
    protected $customOtherRestrictionsLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customOtherRestrictionsLabelDataType = '';
    protected $customPlatformLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customPlatformLabelDataType = '';
    protected $customPurchaseFaceValueLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customPurchaseFaceValueLabelDataType = '';
    protected $customPurchasePriceLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customPurchasePriceLabelDataType = '';
    protected $customPurchaseReceiptNumberLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customPurchaseReceiptNumberLabelDataType = '';
    protected $customRouteRestrictionsDetailsLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customRouteRestrictionsDetailsLabelDataType = '';
    protected $customRouteRestrictionsLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customRouteRestrictionsLabelDataType = '';
    protected $customSeatLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customSeatLabelDataType = '';
    protected $customTicketNumberLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customTicketNumberLabelDataType = '';
    protected $customTimeRestrictionsLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customTimeRestrictionsLabelDataType = '';
    protected $customTransitTerminusNameLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customTransitTerminusNameLabelDataType = '';
    protected $customZoneLabelType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customZoneLabelDataType = '';
    public $enableSingleLegItinerary;
    public $enableSmartTap;
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $hexBackgroundColor;
    protected $homepageUriType = 'Google_Service_Walletobjects_Uri';
    protected $homepageUriDataType = '';
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    public $issuerName;
    public $languageOverride;
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $localizedIssuerNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedIssuerNameDataType = '';
    protected $locationsType = 'Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    protected $logoType = 'Google_Service_Walletobjects_Image';
    protected $logoDataType = '';
    protected $messagesType = 'Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    public $multipleDevicesAndHoldersAllowedStatus;
    public $redemptionIssuers;
    protected $reviewType = 'Google_Service_Walletobjects_Review';
    protected $reviewDataType = '';
    public $reviewStatus;
    protected $securityAnimationType = 'Loyalty\GoogleWallet\Services\Google_Service_Walletobjects_SecurityAnimation';
    protected $securityAnimationDataType = '';
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    protected $transitOperatorNameType = 'Google_Service_Walletobjects_LocalizedString';
    protected $transitOperatorNameDataType = '';
    public $transitType;
    public $version;
    public $viewUnlockRequirement;
    protected $watermarkType = 'Google_Service_Walletobjects_Image';
    protected $watermarkDataType = '';
    protected $wordMarkType = 'Google_Service_Walletobjects_Image';
    protected $wordMarkDataType = '';


    public function setActivationOptions(Google_Service_Walletobjects_ActivationOptions $activationOptions): void
    {
        $this->activationOptions = $activationOptions;
    }

    public function getActivationOptions(): Google_Service_Walletobjects_ActivationOptions
    {
        return $this->activationOptions;
    }

    public function setAllowMultipleUsersPerObject($allowMultipleUsersPerObject): void
    {
        $this->allowMultipleUsersPerObject = $allowMultipleUsersPerObject;
    }

    public function getAllowMultipleUsersPerObject()
    {
        return $this->allowMultipleUsersPerObject;
    }

    public function setCallbackOptions(Google_Service_Walletobjects_CallbackOptions $callbackOptions): void
    {
        $this->callbackOptions = $callbackOptions;
    }

    public function getCallbackOptions(): Google_Service_Walletobjects_CallbackOptions
    {
        return $this->callbackOptions;
    }

    public function setClassTemplateInfo(Google_Service_Walletobjects_ClassTemplateInfo $classTemplateInfo): void
    {
        $this->classTemplateInfo = $classTemplateInfo;
    }

    public function getClassTemplateInfo(): Google_Service_Walletobjects_ClassTemplateInfo
    {
        return $this->classTemplateInfo;
    }

    public function setCountryCode($countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setCustomCarriageLabel(Google_Service_Walletobjects_LocalizedString $customCarriageLabel): void
    {
        $this->customCarriageLabel = $customCarriageLabel;
    }

    public function getCustomCarriageLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customCarriageLabel;
    }

    public function setCustomCoachLabel(Google_Service_Walletobjects_LocalizedString $customCoachLabel): void
    {
        $this->customCoachLabel = $customCoachLabel;
    }

    public function getCustomCoachLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customCoachLabel;
    }

    public function setCustomConcessionCategoryLabel(
        Google_Service_Walletobjects_LocalizedString $customConcessionCategoryLabel
    ): void {
        $this->customConcessionCategoryLabel = $customConcessionCategoryLabel;
    }

    public function getCustomConcessionCategoryLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customConcessionCategoryLabel;
    }

    public function setCustomConfirmationCodeLabel(
        Google_Service_Walletobjects_LocalizedString $customConfirmationCodeLabel
    ): void {
        $this->customConfirmationCodeLabel = $customConfirmationCodeLabel;
    }

    public function getCustomConfirmationCodeLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customConfirmationCodeLabel;
    }

    public function setCustomDiscountMessageLabel(
        Google_Service_Walletobjects_LocalizedString $customDiscountMessageLabel
    ): void {
        $this->customDiscountMessageLabel = $customDiscountMessageLabel;
    }

    public function getCustomDiscountMessageLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customDiscountMessageLabel;
    }

    public function setCustomFareClassLabel(Google_Service_Walletobjects_LocalizedString $customFareClassLabel): void
    {
        $this->customFareClassLabel = $customFareClassLabel;
    }

    public function getCustomFareClassLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customFareClassLabel;
    }

    public function setCustomFareNameLabel(Google_Service_Walletobjects_LocalizedString $customFareNameLabel): void
    {
        $this->customFareNameLabel = $customFareNameLabel;
    }

    public function getCustomFareNameLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customFareNameLabel;
    }

    public function setCustomOtherRestrictionsLabel(
        Google_Service_Walletobjects_LocalizedString $customOtherRestrictionsLabel
    ): void {
        $this->customOtherRestrictionsLabel = $customOtherRestrictionsLabel;
    }

    public function getCustomOtherRestrictionsLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customOtherRestrictionsLabel;
    }

    public function setCustomPlatformLabel(Google_Service_Walletobjects_LocalizedString $customPlatformLabel): void
    {
        $this->customPlatformLabel = $customPlatformLabel;
    }

    public function getCustomPlatformLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customPlatformLabel;
    }

    public function setCustomPurchaseFaceValueLabel(
        Google_Service_Walletobjects_LocalizedString $customPurchaseFaceValueLabel
    ): void {
        $this->customPurchaseFaceValueLabel = $customPurchaseFaceValueLabel;
    }

    public function getCustomPurchaseFaceValueLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customPurchaseFaceValueLabel;
    }

    public function setCustomPurchasePriceLabel(Google_Service_Walletobjects_LocalizedString $customPurchasePriceLabel
    ): void {
        $this->customPurchasePriceLabel = $customPurchasePriceLabel;
    }

    public function getCustomPurchasePriceLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customPurchasePriceLabel;
    }

    public function setCustomPurchaseReceiptNumberLabel(
        Google_Service_Walletobjects_LocalizedString $customPurchaseReceiptNumberLabel
    ): void {
        $this->customPurchaseReceiptNumberLabel = $customPurchaseReceiptNumberLabel;
    }

    public function getCustomPurchaseReceiptNumberLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customPurchaseReceiptNumberLabel;
    }

    public function setCustomRouteRestrictionsDetailsLabel(
        Google_Service_Walletobjects_LocalizedString $customRouteRestrictionsDetailsLabel
    ): void {
        $this->customRouteRestrictionsDetailsLabel = $customRouteRestrictionsDetailsLabel;
    }

    public function getCustomRouteRestrictionsDetailsLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customRouteRestrictionsDetailsLabel;
    }

    public function setCustomRouteRestrictionsLabel(
        Google_Service_Walletobjects_LocalizedString $customRouteRestrictionsLabel
    ): void {
        $this->customRouteRestrictionsLabel = $customRouteRestrictionsLabel;
    }

    public function getCustomRouteRestrictionsLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customRouteRestrictionsLabel;
    }

    public function setCustomSeatLabel(Google_Service_Walletobjects_LocalizedString $customSeatLabel): void
    {
        $this->customSeatLabel = $customSeatLabel;
    }

    public function getCustomSeatLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customSeatLabel;
    }

    public function setCustomTicketNumberLabel(Google_Service_Walletobjects_LocalizedString $customTicketNumberLabel
    ): void {
        $this->customTicketNumberLabel = $customTicketNumberLabel;
    }

    public function getCustomTicketNumberLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customTicketNumberLabel;
    }

    public function setCustomTimeRestrictionsLabel(
        Google_Service_Walletobjects_LocalizedString $customTimeRestrictionsLabel
    ): void {
        $this->customTimeRestrictionsLabel = $customTimeRestrictionsLabel;
    }

    public function getCustomTimeRestrictionsLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customTimeRestrictionsLabel;
    }

    public function setCustomTransitTerminusNameLabel(
        Google_Service_Walletobjects_LocalizedString $customTransitTerminusNameLabel
    ): void {
        $this->customTransitTerminusNameLabel = $customTransitTerminusNameLabel;
    }

    public function getCustomTransitTerminusNameLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customTransitTerminusNameLabel;
    }

    public function setCustomZoneLabel(Google_Service_Walletobjects_LocalizedString $customZoneLabel): void
    {
        $this->customZoneLabel = $customZoneLabel;
    }

    public function getCustomZoneLabel(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customZoneLabel;
    }

    public function setEnableSingleLegItinerary($enableSingleLegItinerary): void
    {
        $this->enableSingleLegItinerary = $enableSingleLegItinerary;
    }

    public function getEnableSingleLegItinerary()
    {
        return $this->enableSingleLegItinerary;
    }

    public function setEnableSmartTap($enableSmartTap): void
    {
        $this->enableSmartTap = $enableSmartTap;
    }

    public function getEnableSmartTap()
    {
        return $this->enableSmartTap;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setHexBackgroundColor($hexBackgroundColor): void
    {
        $this->hexBackgroundColor = $hexBackgroundColor;
    }

    public function getHexBackgroundColor()
    {
        return $this->hexBackgroundColor;
    }

    public function setHomepageUri(Google_Service_Walletobjects_Uri $homepageUri): void
    {
        $this->homepageUri = $homepageUri;
    }

    public function getHomepageUri(): Google_Service_Walletobjects_Uri
    {
        return $this->homepageUri;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData): void
    {
        $this->infoModuleData = $infoModuleData;
    }

    public function getInfoModuleData(): Google_Service_Walletobjects_InfoModuleData
    {
        return $this->infoModuleData;
    }

    public function setIssuerName($issuerName): void
    {
        $this->issuerName = $issuerName;
    }

    public function getIssuerName()
    {
        return $this->issuerName;
    }

    public function setLanguageOverride($languageOverride): void
    {
        $this->languageOverride = $languageOverride;
    }

    public function getLanguageOverride()
    {
        return $this->languageOverride;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLocalizedIssuerName(Google_Service_Walletobjects_LocalizedString $localizedIssuerName): void
    {
        $this->localizedIssuerName = $localizedIssuerName;
    }

    public function getLocalizedIssuerName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedIssuerName;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function setLogo(Google_Service_Walletobjects_Image $logo): void
    {
        $this->logo = $logo;
    }

    public function getLogo(): Google_Service_Walletobjects_Image
    {
        return $this->logo;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setMultipleDevicesAndHoldersAllowedStatus($multipleDevicesAndHoldersAllowedStatus): void
    {
        $this->multipleDevicesAndHoldersAllowedStatus = $multipleDevicesAndHoldersAllowedStatus;
    }

    public function getMultipleDevicesAndHoldersAllowedStatus()
    {
        return $this->multipleDevicesAndHoldersAllowedStatus;
    }

    public function setRedemptionIssuers($redemptionIssuers): void
    {
        $this->redemptionIssuers = $redemptionIssuers;
    }

    public function getRedemptionIssuers()
    {
        return $this->redemptionIssuers;
    }

    public function setReview(Google_Service_Walletobjects_Review $review): void
    {
        $this->review = $review;
    }

    public function getReview(): Google_Service_Walletobjects_Review
    {
        return $this->review;
    }

    public function setReviewStatus($reviewStatus): void
    {
        $this->reviewStatus = $reviewStatus;
    }

    public function getReviewStatus()
    {
        return $this->reviewStatus;
    }

    public function setSecurityAnimation(Google_Service_Walletobjects_SecurityAnimation $securityAnimation): void
    {
        $this->securityAnimation = $securityAnimation;
    }

    public function getSecurityAnimation(): Google_Service_Walletobjects_SecurityAnimation
    {
        return $this->securityAnimation;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setTransitOperatorName(Google_Service_Walletobjects_LocalizedString $transitOperatorName): void
    {
        $this->transitOperatorName = $transitOperatorName;
    }

    public function getTransitOperatorName(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->transitOperatorName;
    }

    public function setTransitType($transitType): void
    {
        $this->transitType = $transitType;
    }

    public function getTransitType()
    {
        return $this->transitType;
    }

    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setViewUnlockRequirement($viewUnlockRequirement): void
    {
        $this->viewUnlockRequirement = $viewUnlockRequirement;
    }

    public function getViewUnlockRequirement()
    {
        return $this->viewUnlockRequirement;
    }

    public function setWatermark(Google_Service_Walletobjects_Image $watermark): void
    {
        $this->watermark = $watermark;
    }

    public function getWatermark(): Google_Service_Walletobjects_Image
    {
        return $this->watermark;
    }

    public function setWordMark(Google_Service_Walletobjects_Image $wordMark): void
    {
        $this->wordMark = $wordMark;
    }

    public function getWordMark(): Google_Service_Walletobjects_Image
    {
        return $this->wordMark;
    }
}

class Google_Service_Walletobjects_TransitClassAddMessageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourceType = 'Google_Service_Walletobjects_TransitClass';
    protected $resourceDataType = '';


    public function setResource(Google_Service_Walletobjects_TransitClass $resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): Google_Service_Walletobjects_TransitClass
    {
        return $this->resource;
    }
}

class Google_Service_Walletobjects_TransitClassListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_TransitClass';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_TransitObject extends Google_Collection
{
    protected $collection_key = 'ticketLegs';
    protected $internal_gapi_mappings = array();
    protected $activationStatusType = 'Google_Service_Walletobjects_ActivationStatus';
    protected $activationStatusDataType = '';
    protected $appLinkDataType = 'Google_Service_Walletobjects_AppLinkData';
    protected $appLinkDataDataType = '';
    protected $barcodeType = 'Google_Service_Walletobjects_Barcode';
    protected $barcodeDataType = '';
    public $classId;
    protected $classReferenceType = 'Google_Service_Walletobjects_TransitClass';
    protected $classReferenceDataType = '';
    public $concessionCategory;
    protected $customConcessionCategoryType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customConcessionCategoryDataType = '';
    protected $customTicketStatusType = 'Google_Service_Walletobjects_LocalizedString';
    protected $customTicketStatusDataType = '';
    protected $deviceContextType = 'Google_Service_Walletobjects_DeviceContext';
    protected $deviceContextDataType = '';
    public $disableExpirationNotification;
    protected $groupingInfoType = 'Google_Service_Walletobjects_GroupingInfo';
    protected $groupingInfoDataType = '';
    public $hasLinkedDevice;
    public $hasUsers;
    protected $heroImageType = 'Google_Service_Walletobjects_Image';
    protected $heroImageDataType = '';
    public $hexBackgroundColor;
    public $id;
    protected $imageModulesDataType = 'Google_Service_Walletobjects_ImageModuleData';
    protected $imageModulesDataDataType = 'array';
    protected $infoModuleDataType = 'Google_Service_Walletobjects_InfoModuleData';
    protected $infoModuleDataDataType = '';
    protected $linksModuleDataType = 'Google_Service_Walletobjects_LinksModuleData';
    protected $linksModuleDataDataType = '';
    protected $locationsType = 'Google_Service_Walletobjects_LatLongPoint';
    protected $locationsDataType = 'array';
    protected $messagesType = 'Google_Service_Walletobjects_Message';
    protected $messagesDataType = 'array';
    protected $passConstraintsType = 'Google_Service_Walletobjects_PassConstraints';
    protected $passConstraintsDataType = '';
    public $passengerNames;
    public $passengerType;
    protected $purchaseDetailsType = 'Google_Service_Walletobjects_PurchaseDetails';
    protected $purchaseDetailsDataType = '';
    protected $rotatingBarcodeType = 'Google_Service_Walletobjects_RotatingBarcode';
    protected $rotatingBarcodeDataType = '';
    public $smartTapRedemptionValue;
    public $state;
    protected $textModulesDataType = 'Google_Service_Walletobjects_TextModuleData';
    protected $textModulesDataDataType = 'array';
    protected $ticketLegType = 'Google_Service_Walletobjects_TicketLeg';
    protected $ticketLegDataType = '';
    protected $ticketLegsType = 'Google_Service_Walletobjects_TicketLeg';
    protected $ticketLegsDataType = 'array';
    public $ticketNumber;
    protected $ticketRestrictionsType = 'Google_Service_Walletobjects_TicketRestrictions';
    protected $ticketRestrictionsDataType = '';
    public $ticketStatus;
    public $tripId;
    public $tripType;
    protected $validTimeIntervalType = 'Google_Service_Walletobjects_TimeInterval';
    protected $validTimeIntervalDataType = '';
    public $version;


    public function setActivationStatus(Google_Service_Walletobjects_ActivationStatus $activationStatus): void
    {
        $this->activationStatus = $activationStatus;
    }

    public function getActivationStatus(): Google_Service_Walletobjects_ActivationStatus
    {
        return $this->activationStatus;
    }

    public function setAppLinkData(Google_Service_Walletobjects_AppLinkData $appLinkData): void
    {
        $this->appLinkData = $appLinkData;
    }

    public function getAppLinkData(): Google_Service_Walletobjects_AppLinkData
    {
        return $this->appLinkData;
    }

    public function setBarcode(Google_Service_Walletobjects_Barcode $barcode): void
    {
        $this->barcode = $barcode;
    }

    public function getBarcode(): Google_Service_Walletobjects_Barcode
    {
        return $this->barcode;
    }

    public function setClassId($classId): void
    {
        $this->classId = $classId;
    }

    public function getClassId()
    {
        return $this->classId;
    }

    public function setClassReference(Google_Service_Walletobjects_TransitClass $classReference): void
    {
        $this->classReference = $classReference;
    }

    public function getClassReference(): Google_Service_Walletobjects_TransitClass
    {
        return $this->classReference;
    }

    public function setConcessionCategory($concessionCategory): void
    {
        $this->concessionCategory = $concessionCategory;
    }

    public function getConcessionCategory()
    {
        return $this->concessionCategory;
    }

    public function setCustomConcessionCategory(Google_Service_Walletobjects_LocalizedString $customConcessionCategory
    ): void {
        $this->customConcessionCategory = $customConcessionCategory;
    }

    public function getCustomConcessionCategory(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customConcessionCategory;
    }

    public function setCustomTicketStatus(Google_Service_Walletobjects_LocalizedString $customTicketStatus): void
    {
        $this->customTicketStatus = $customTicketStatus;
    }

    public function getCustomTicketStatus(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->customTicketStatus;
    }

    public function setDeviceContext(Google_Service_Walletobjects_DeviceContext $deviceContext): void
    {
        $this->deviceContext = $deviceContext;
    }

    public function getDeviceContext(): Google_Service_Walletobjects_DeviceContext
    {
        return $this->deviceContext;
    }

    public function setDisableExpirationNotification($disableExpirationNotification): void
    {
        $this->disableExpirationNotification = $disableExpirationNotification;
    }

    public function getDisableExpirationNotification()
    {
        return $this->disableExpirationNotification;
    }

    public function setGroupingInfo(Google_Service_Walletobjects_GroupingInfo $groupingInfo): void
    {
        $this->groupingInfo = $groupingInfo;
    }

    public function getGroupingInfo(): Google_Service_Walletobjects_GroupingInfo
    {
        return $this->groupingInfo;
    }

    public function setHasLinkedDevice($hasLinkedDevice): void
    {
        $this->hasLinkedDevice = $hasLinkedDevice;
    }

    public function getHasLinkedDevice()
    {
        return $this->hasLinkedDevice;
    }

    public function setHasUsers($hasUsers): void
    {
        $this->hasUsers = $hasUsers;
    }

    public function getHasUsers()
    {
        return $this->hasUsers;
    }

    public function setHeroImage(Google_Service_Walletobjects_Image $heroImage): void
    {
        $this->heroImage = $heroImage;
    }

    public function getHeroImage(): Google_Service_Walletobjects_Image
    {
        return $this->heroImage;
    }

    public function setHexBackgroundColor($hexBackgroundColor): void
    {
        $this->hexBackgroundColor = $hexBackgroundColor;
    }

    public function getHexBackgroundColor()
    {
        return $this->hexBackgroundColor;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setImageModulesData($imageModulesData): void
    {
        $this->imageModulesData = $imageModulesData;
    }

    public function getImageModulesData()
    {
        return $this->imageModulesData;
    }

    public function setInfoModuleData(Google_Service_Walletobjects_InfoModuleData $infoModuleData): void
    {
        $this->infoModuleData = $infoModuleData;
    }

    public function getInfoModuleData(): Google_Service_Walletobjects_InfoModuleData
    {
        return $this->infoModuleData;
    }

    public function setLinksModuleData(Google_Service_Walletobjects_LinksModuleData $linksModuleData): void
    {
        $this->linksModuleData = $linksModuleData;
    }

    public function getLinksModuleData(): Google_Service_Walletobjects_LinksModuleData
    {
        return $this->linksModuleData;
    }

    public function setLocations($locations): void
    {
        $this->locations = $locations;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function setPassConstraints(Google_Service_Walletobjects_PassConstraints $passConstraints): void
    {
        $this->passConstraints = $passConstraints;
    }

    public function getPassConstraints(): Google_Service_Walletobjects_PassConstraints
    {
        return $this->passConstraints;
    }

    public function setPassengerNames($passengerNames): void
    {
        $this->passengerNames = $passengerNames;
    }

    public function getPassengerNames()
    {
        return $this->passengerNames;
    }

    public function setPassengerType($passengerType): void
    {
        $this->passengerType = $passengerType;
    }

    public function getPassengerType()
    {
        return $this->passengerType;
    }

    public function setPurchaseDetails(Google_Service_Walletobjects_PurchaseDetails $purchaseDetails): void
    {
        $this->purchaseDetails = $purchaseDetails;
    }

    public function getPurchaseDetails(): Google_Service_Walletobjects_PurchaseDetails
    {
        return $this->purchaseDetails;
    }

    public function setRotatingBarcode(Google_Service_Walletobjects_RotatingBarcode $rotatingBarcode): void
    {
        $this->rotatingBarcode = $rotatingBarcode;
    }

    public function getRotatingBarcode(): Google_Service_Walletobjects_RotatingBarcode
    {
        return $this->rotatingBarcode;
    }

    public function setSmartTapRedemptionValue($smartTapRedemptionValue): void
    {
        $this->smartTapRedemptionValue = $smartTapRedemptionValue;
    }

    public function getSmartTapRedemptionValue()
    {
        return $this->smartTapRedemptionValue;
    }

    public function setState($state): void
    {
        $this->state = $state;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setTextModulesData($textModulesData): void
    {
        $this->textModulesData = $textModulesData;
    }

    public function getTextModulesData()
    {
        return $this->textModulesData;
    }

    public function setTicketLeg(Google_Service_Walletobjects_TicketLeg $ticketLeg): void
    {
        $this->ticketLeg = $ticketLeg;
    }

    public function getTicketLeg(): Google_Service_Walletobjects_TicketLeg
    {
        return $this->ticketLeg;
    }

    public function setTicketLegs($ticketLegs): void
    {
        $this->ticketLegs = $ticketLegs;
    }

    public function getTicketLegs()
    {
        return $this->ticketLegs;
    }

    public function setTicketNumber($ticketNumber): void
    {
        $this->ticketNumber = $ticketNumber;
    }

    public function getTicketNumber()
    {
        return $this->ticketNumber;
    }

    public function setTicketRestrictions(Google_Service_Walletobjects_TicketRestrictions $ticketRestrictions): void
    {
        $this->ticketRestrictions = $ticketRestrictions;
    }

    public function getTicketRestrictions(): Google_Service_Walletobjects_TicketRestrictions
    {
        return $this->ticketRestrictions;
    }

    public function setTicketStatus($ticketStatus): void
    {
        $this->ticketStatus = $ticketStatus;
    }

    public function getTicketStatus()
    {
        return $this->ticketStatus;
    }

    public function setTripId($tripId): void
    {
        $this->tripId = $tripId;
    }

    public function getTripId()
    {
        return $this->tripId;
    }

    public function setTripType($tripType): void
    {
        $this->tripType = $tripType;
    }

    public function getTripType()
    {
        return $this->tripType;
    }

    public function setValidTimeInterval(Google_Service_Walletobjects_TimeInterval $validTimeInterval): void
    {
        $this->validTimeInterval = $validTimeInterval;
    }

    public function getValidTimeInterval(): Google_Service_Walletobjects_TimeInterval
    {
        return $this->validTimeInterval;
    }

    public function setVersion($version): void
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }
}

class Google_Service_Walletobjects_TransitObjectAddMessageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $resourceType = 'Google_Service_Walletobjects_TransitObject';
    protected $resourceDataType = '';


    public function setResource(Google_Service_Walletobjects_TransitObject $resource): void
    {
        $this->resource = $resource;
    }

    public function getResource(): Google_Service_Walletobjects_TransitObject
    {
        return $this->resource;
    }
}

class Google_Service_Walletobjects_TransitObjectListResponse extends Google_Collection
{
    protected $collection_key = 'resources';
    protected $internal_gapi_mappings = array();
    protected $paginationType = 'Google_Service_Walletobjects_Pagination';
    protected $paginationDataType = '';
    protected $resourcesType = 'Google_Service_Walletobjects_TransitObject';
    protected $resourcesDataType = 'array';


    public function setPagination(Google_Service_Walletobjects_Pagination $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getPagination(): Google_Service_Walletobjects_Pagination
    {
        return $this->pagination;
    }

    public function setResources($resources): void
    {
        $this->resources = $resources;
    }

    public function getResources()
    {
        return $this->resources;
    }
}

class Google_Service_Walletobjects_TranslatedString extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $kind;
    public $language;
    public $value;


    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLanguage($language): void
    {
        $this->language = $language;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}

class Google_Service_Walletobjects_UpcomingNotification extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $enableNotification;


    public function setEnableNotification($enableNotification): void
    {
        $this->enableNotification = $enableNotification;
    }

    public function getEnableNotification()
    {
        return $this->enableNotification;
    }
}

class Google_Service_Walletobjects_UploadPrivateDataRequest extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $issuerId;
    protected $textType = 'Google_Service_Walletobjects_PrivateText';
    protected $textDataType = '';
    protected $uriType = 'Google_Service_Walletobjects_PrivateUri';
    protected $uriDataType = '';


    public function setIssuerId($issuerId): void
    {
        $this->issuerId = $issuerId;
    }

    public function getIssuerId()
    {
        return $this->issuerId;
    }

    public function setText(Google_Service_Walletobjects_PrivateText $text): void
    {
        $this->text = $text;
    }

    public function getText(): Google_Service_Walletobjects_PrivateText
    {
        return $this->text;
    }

    public function setUri(Google_Service_Walletobjects_PrivateUri $uri): void
    {
        $this->uri = $uri;
    }

    public function getUri(): Google_Service_Walletobjects_PrivateUri
    {
        return $this->uri;
    }
}

class Google_Service_Walletobjects_UploadPrivateDataResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $privateContentId;


    public function setPrivateContentId($privateContentId): void
    {
        $this->privateContentId = $privateContentId;
    }

    public function getPrivateContentId()
    {
        return $this->privateContentId;
    }
}

class Google_Service_Walletobjects_UploadPrivateImageRequest extends Google_Model
{
    protected $internal_gapi_mappings = array();
    protected $blobType = 'Google_Service_Walletobjects_Media';
    protected $blobDataType = '';
    protected $mediaRequestInfoType = 'Google_Service_Walletobjects_MediaRequestInfo';
    protected $mediaRequestInfoDataType = '';


    public function setBlob(Google_Service_Walletobjects_Media $blob): void
    {
        $this->blob = $blob;
    }

    public function getBlob(): Google_Service_Walletobjects_Media
    {
        return $this->blob;
    }

    public function setMediaRequestInfo(Google_Service_Walletobjects_MediaRequestInfo $mediaRequestInfo): void
    {
        $this->mediaRequestInfo = $mediaRequestInfo;
    }

    public function getMediaRequestInfo(): Google_Service_Walletobjects_MediaRequestInfo
    {
        return $this->mediaRequestInfo;
    }
}

class Google_Service_Walletobjects_UploadPrivateImageResponse extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $privateContentId;


    public function setPrivateContentId($privateContentId): void
    {
        $this->privateContentId = $privateContentId;
    }

    public function getPrivateContentId()
    {
        return $this->privateContentId;
    }
}

class Google_Service_Walletobjects_Uri extends Google_Model
{
    protected $internal_gapi_mappings = array();
    public $description;
    public $id;
    public $kind;
    protected $localizedDescriptionType = 'Google_Service_Walletobjects_LocalizedString';
    protected $localizedDescriptionDataType = '';
    public $uri;


    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setKind($kind): void
    {
        $this->kind = $kind;
    }

    public function getKind()
    {
        return $this->kind;
    }

    public function setLocalizedDescription(Google_Service_Walletobjects_LocalizedString $localizedDescription): void
    {
        $this->localizedDescription = $localizedDescription;
    }

    public function getLocalizedDescription(): Google_Service_Walletobjects_LocalizedString
    {
        return $this->localizedDescription;
    }

    public function setUri($uri): void
    {
        $this->uri = $uri;
    }

    public function getUri()
    {
        return $this->uri;
    }
}
