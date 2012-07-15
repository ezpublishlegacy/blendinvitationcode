<?php

include_once( 'kernel/common/i18n.php' );
include_once( 'kernel/common/template.php' );

class ListInvitationsForm
{
    public static function init($params , &$result)
    {
        //Get a list of invitations for the current user
        $module = $params['Module'];
        
        
        //Get their membership type
        //Display the form
        $result = self::displayForm( $params );
    }
    
    public static function displayForm( $params )
    {
        $tpl = eZTemplate::factory();
        
        $res = eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'membership', 'invitations' ) ) );

        //$page = 1;
        $perPage = 50;
        $totalCount = 0;
        $userParameters = $params['UserParameters'];
        if (isset($userParameters['offset'])){
            $currentOffset = $userParameters['offset'];
        }else {
            $currentOffset = 0;
        }
        $page = ($currentOffset / $perPage) + 1;
        //echo "<pre>"; print_r($page); echo "</pre>";
        $invitations = BlendInvitation::getCodes($page, $perPage, $totalCount);
        //echo "<pre>"; print_r($invitations); echo "</pre>";
        $tpl->setVariable('per_page', $perPage);
        $tpl->setVariable('user_parameters', $userParameters);
        $tpl->setVariable('invitations', $invitations);
        $tpl->setVariable('total_count', $totalCount);
        //echo "<pre>"; print_r($tpl); echo "</pre>";
        $result = array();
        $result['content'] = $tpl->fetch('design:blendinvitation/list.tpl');
        $result['navigationpart'] = 'user';
        $result['path'] = array(
            array(
                'url' => '/Users',
                'text' => ezi18n( 'membership', 'Users' )
            ),
            array(
                'url' => false,
                'text' => ezi18n( 'membership', 'Invitation Codes' )
            )
        );
        return $result;
    }
}

$Result = array();
//echo "<pre>"; print_r($Params); echo "</pre>";
ListInvitationsForm::init($Params, $Result);

