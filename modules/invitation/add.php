<?php

include_once( 'kernel/common/i18n.php' );
include_once('kernel/common/template.php');

class AddInvitationForm
{
    
    public static $fields = array('emails'
                                  );
    
    public static function init( $module, &$result )
    {
        
        $http = eZHTTPTool::instance();

        $user = eZUser::currentUser();
        
        if(!$user)
        {
            return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
        }
        
        if(!$user->isLoggedIn())
        {
            return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
        }
        
        $input = self::retrieveInput();
        
        $errors = array();
        
        $complete = false;
        
        if( $http->hasPostVariable('emails'))
        {
            
            if(self::validateInput( $input, $errors ))
            {
                
                self::createInvitations( $input );
                
                $complete = true;
                
            }
            
        }
        
        $result = self::showForm( $input, $errors, $complete );

    }
    
    public static function createInvitations( &$input, $sendEmail = false )
    {
        $codes = array();
        foreach(explode("\n",$input['emails']) as $email)
        {
            $invite = BlendInvitation::create($email);

            $prettyCode = substr($invite->code, 0, 4) . '-' .
                substr($invite->code, 4, 4) . '-' . substr($invite->code, 8, 4);
        
            $codes[] = $prettyCode . "\t" . $email;
        }
        
        $input['emails']=implode("\n",$codes);
    }
    
    public static function validateInput( $input, &$errors )
    {
        $inputIsValid = true;

        if ( $input['emails'] == "" )
        {
            $inputIsValid = false;
            $errors["emails"]="Please enter at least one email address.";
        }

        
//        if ( ! eZMail::validate( $input['email'] ) )
//        {
//            $inputIsValid = false;
//            $errors["email"]="Please enter a valid email address.";
//        }
        
        return $inputIsValid;        
    }
    
    public static function retrieveInput()
    {
        $http = eZHTTPTool::instance();

        $input = array();
        
        foreach(self::$fields as $field)
        {
            if($http->hasPostVariable($field))
            {
                $input[$field]=trim($http->postVariable($field));
            }
            else
            {
                $input[$field]='';
            }
           
        }
        
        return $input;        
    }

    public static function showForm( $input, $errors, $complete )
    {
        $tpl = eZTemplate::factory();
        
        $res = eZTemplateDesignResource::instance();
        //$res->setKeys( array( array( 'membership', 'sendinvitation' ) ) );

        
        foreach($input as $field=>$value)
        {
            $tpl->setVariable( $field, $value );
        }
        
        $tpl->setVariable('errors', $errors);
        
        $tpl->setVariable('complete', $complete);
        
        //$tpl->setVariable('editor_user', self::isEditor(eZUser::currentUser()));

        $result = array();
        $result['content'] = $tpl->fetch( "design:blendinvitation/add.tpl" );
        $result['path'] = array(
            array(
                'url' => '/Users',
                'text' => ezi18n( 'membership', 'Users' )
            ),
            array(
                'url' => '/invitation/list',
                'text' => ezi18n( 'membership', 'Invitation Codes' )
            ),
            array(
                'url' => false,
                'text' => ezi18n( 'membership', 'Create Codes' )
            )
        );
        
        return $result;        
    }
    
}

$Result = array();

AddInvitationForm::init( $Params['Module'], $Result );

?>