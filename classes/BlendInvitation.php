<?php

include_once( 'kernel/common/i18n.php' );

class BlendInvitation
{
    const INVITATION_TABLE = 'blend_invitations';
    
    public $code;
    public $email;
    public $userId;
    
    public function __construct($row)
    {
        $this->code = $row['code'];
        $this->email = $row['email'];
        $this->userId = $row['user_id'];
        $this->createdAt = $row['created_at'];
        $this->acceptedAt = $row['accepted_at'];
    }
    
    public static function create($email)
    {
        $db = eZDB::instance();
        
        $email = $db->escapeString($email);
        $code = self::generateCode();
        
        $sql = "INSERT INTO " . self::INVITATION_TABLE . " (code, email, created_at) VALUES ('$code', '$email', NOW())";
        $db->query($sql);
        
        return new self(
            array(
                'code'=>$code,
                'email'=>$email,
                'user_id'=>null,
                'created_at'=>date('Y-m-d H:i:s'),
                'accepted_at'=> null
            )
        );
            
    }
    
    protected static function printCode($in)
    {
        //Base30 with ambiguous characters replaced
        $print = array(
        '0'=>'2',
        '1'=>'3',
        '2'=>'4',
        '3'=>'5',
        '4'=>'6',
        '5'=>'7',
        '6'=>'8',
        '7'=>'9',
        '8'=>'A',
        '9'=>'B',
        '0'=>'C',
        'a'=>'D',
        'b'=>'E',
        'c'=>'F',
        'd'=>'G',
        'e'=>'H',
        'f'=>'J',
        'g'=>'K',
        'h'=>'M',
        'i'=>'N',
        'j'=>'P',
        'k'=>'Q',
        'l'=>'R',
        'm'=>'S',
        'n'=>'T',
        'o'=>'U',
        'p'=>'V',
        'q'=>'W',
        'r'=>'X',
        's'=>'Y',
        't'=>'Z'
        );
        
        return $print[$in];
        
    }
    
    protected static function generateCode()
    {
        
        //Serial number
        $n = strval(base_convert(time(), 10, 30));
        
        //echo "N: $n \n";
        //Hash
        $h = strrev(strval(base_convert(md5( strval(microtime() . rand(0,100000)) ), 16, 30)));
        //echo "H: $h \n";    
        $code = self::printCode($h[0]) .
            self::printCode($h[1]) .
            self::printCode($n[0]) .
            self::printCode($h[2]) .
            self::printCode($n[1]) .
            self::printCode($h[3]) .
            self::printCode($h[4]) .
            self::printCode($n[2]) .
            self::printCode($h[5]) .
            self::printCode($n[3]) .
            self::printCode($h[6]) .
            self::printCode($h[7]);
        
        //f-bomb remover
        $code = str_replace(array('FUCK','CUNT', 'DAMN'), array('FKUC', 'CNUT', 'DNAM'), $code);
        
        return $code;
    }
    
    public static function getByCode($code)
    {
        $db = eZDB::instance();
        $sql= "SELECT * FROM " . self::INVITATION_TABLE . " WHERE code = '"  .
            $db->escapeString($code) . "'";

        $rows = $db->arrayQuery($sql);
        
        if ($rows) {
            return new BlendInvitation(reset($rows));
        } else {
            return false;
        }
    }
    
    public static function markAsUsed($code, $userId)
    {
        $db = eZDB::instance();
        $sql = "UPDATE " . self::INVITATION_TABLE . " set accepted_at=NOW(), user_id = " .
            intval($userId) . " WHERE code = '" . $db->escapeString($code) . "'";
            
        $db->query($sql);
        
        return true;
    }
    
    public static function getCodes($page = 1, $perPage = 50, &$count)
    {
        $db = eZDB::instance();

        $offset = ($page - 1) * $perPage;

        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . self::INVITATION_TABLE;
        $sql .= " ORDER BY email LIMIT $offset, $perPage";
        
        //$invitations = array();
        
        $rows = $db->arrayQuery($sql);
        
        $countQuery = $db->arrayQuery('SELECT FOUND_ROWS()');
        $firstRow = reset($countQuery);
        $firstField = reset($firstRow);

        $count = intval(intval($firstField));
        
        $results = array();
        foreach($rows as $row)
        {
            $row['created_at']=strtotime($row['created_at']);
            $row['accepted_at']=strtotime($row['accepted_at']);
            $results[] = $row;
        }
        /*
        foreach($rows as $row)
        {
            $invitations[] = new BlendInvitation($row);
        }
        */
        
        return $results;
    }
}