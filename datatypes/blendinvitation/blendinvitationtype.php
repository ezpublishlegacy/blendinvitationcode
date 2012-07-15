<?php
//
// Definition of BlendInvitationType class
//
// SOFTWARE NAME: Blend Invitation Class
// SOFTWARE RELEASE: 1.0
// COPYRIGHT NOTICE: Copyright (C) 20011 Blend Interactive
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: 
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
include_once( 'kernel/common/i18n.php' );

/**
 * File containing the BlendInvitationType class.
 *
 * @package eZDatatype
 */

/**
 * Class providing the BlendInvitationType datatype.
 *
 * @package eZDatatype
 * @see eZGmapLocation
 */
include_once( 'kernel/common/i18n.php' );

class BlendInvitationType extends eZDataType
{
    const DATA_TYPE_STRING = 'blendinvitation';
    

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct(
            self::DATA_TYPE_STRING,
            ezi18n('extension/blendinvitation/datatype', "Invitation Code", 'Datatype name'),
            array('serialize_supported' => true)
        );
    }

    /**
     * Validate post data, these are then used by
     * {@link BlendInvitationType::fetchObjectAttributeHTTPInput()}
     * 
     * @param eZHTTPTool $http
     * @param string $base
     * @param eZContentObjectAttribute $contentObjectAttribute
     */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $code = '';
        $attrId = $contentObjectAttribute->attribute('id');
        $attrName = $base . '_data_invitation_' . $attrId;
        
        if (!$http->hasPostVariable($attrName)) {
            $contentObjectAttribute->setValidationError(
                ezi18n(
                    'extension/blendinvitation',
                    'Missing Invitation Code'
                )
            );
            return eZInputValidator::STATE_INVALID;
        }
        
        $code = preg_replace('/[^0-9a-z]/', '', strtolower($http->postVariable($attrName)));
        
        //Check against invitation code table
        
        
        $invitation = BlendInvitation::getByCode($code);
        
        if (!$invitation) {
            $contentObjectAttribute->setValidationError(
                ezi18n(
                    'extension/blendinvitation',
                    'Invalid Invitation Code'
                )
            );
            return eZInputValidator::STATE_INVALID;            
        }
        
        if ($invitation->userId &&
            $invitation->userId != $contentObjectAttribute->attribute('contentobject_id')) {
            $contentObjectAttribute->setValidationError(
                ezi18n(
                    'extension/blendinvitation',
                    'That code has already been used by another user'
                )
            );
            return eZInputValidator::STATE_INVALID;            
        }
        
        return eZInputValidator::STATE_ACCEPTED;
    }

    /**
     * Set parameters from post data, expects post data to be validated by
     * {@link BlendInvitationType::validateObjectAttributeHTTPInput()}
     * 
     * @param eZHTTPTool $http
     * @param string $base
     * @param eZContentObjectAttribute $contentObjectAttribute
     */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $validPostData = false;
        $attrName = $base . '_data_invitation_' . $contentObjectAttribute->attribute('id');
        
        $code = preg_replace('/[^0-9a-z]/', '', strtolower($http->postVariable($attrName)));
        
        if ($http->hasPostVariable($attrName)) {
            $contentObjectAttribute->setAttribute('data_text', $code);
        }
        return true;
    }

    /**
     * Stores the content, as set by {@link BlendInvitationType::fetchObjectAttributeHTTPInput()}
     * or {@link BlendInvitationType::initializeObjectAttribute()}
     * 
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return bool
     */
    function storeObjectAttribute( $contentObjectAttribute )
    {
        $code = $contentObjectAttribute->attribute('data_text');
        $userId = $contentObjectAttribute->attribute('contentobject_id');
        BlendInvitation::markAsUsed($code, $userId);
    }

    /**
     * Init attribute ( also handles version to version copy, and attribute to attribute copy )
     * 
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param int|null $currentVersion
     * @param eZContentObjectAttribute $originalContentObjectAttribute
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
    }
     */

    /**
     * Return content, either stored one or a new empty one based on
     * if attribute has data or not (as signaled by data_int)
     * 
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return eZGmapLocation
     */
    function objectAttributeContent( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute('data_text');
    }

    /**
     * Indicates if attribute has content or not (data_int is used for this)
     * 
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return bool
     */
    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return (count($contentObjectAttribute->attribute('data_text')) < 1);
    }

    /**
     * Generate meta data of attribute
     * 
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return string
     */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute('data_text');
        /*
        $gmapObject = $contentObjectAttribute->attribute( 'content' );
        return $gmapObject->attribute( 'address' );
        */
    }

    /**
     * Indicates that datatype is searchable {@link eZGmapLocationType::metaData()}
     * 
     * @return bool
     */
    function isIndexable()
    {
        return true;
    }

    /**
     * Returns sort value for attribute
     * 
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return string
     */
    function sortKey( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute('data_text');
    }

    /**
     * Tells what kind of sort value is returned, see {@link eZGmapLocationType::sortKey()}
     * 
     * @return string
     */
    function sortKeyType()
    {
        return 'string';
    }

    /**
     * Return string data for cosumption by {@link eZGmapLocationType::fromString()}
     * 
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @return string
     */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute('data_text');
    }

    /**
     * Store data from string format as created in  {@link eZGmapLocationType::toString()}
     * 
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param string $string
     */
    function fromString( $contentObjectAttribute, $string )
    {
        $code = preg_replace('/[^a-z0-9]/', '', strtolower($string));
        
        $contentObjectAttribute->setAttribute('data_text', $code);
    }

    /**
     * Generate title of attribute
     * 
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param string|null $name
     * @return string
     */
    function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute('data_text');
        /*
        $gmapObject = $contentObjectAttribute->attribute( 'content' );
        return $gmapObject->attribute( 'address' );
        */
    }

    /**
     * Delete map location data when attribute (version) is removed
     * 
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param int|null $version (Optional, deletes all versions if null)
     */
    function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
        /*
    	eZGmapLocation::removeById( $contentObjectAttribute->attribute('id'), $version );
        */
    }
}
eZDataType::register(BlendInvitationType::DATA_TYPE_STRING, 'BlendInvitationType');

