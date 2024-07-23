<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

//include_once 'class.utils.php';

class Generateur
{

    private $connection;
    private $auto_inc;
    public $has_signature = FALSE;
    public $Signature_fld;
    public $Signature_Value;
    private $zero_compensation = array(0 => '0000', 1 => '000', 2 => '00', 3 => '0');

    public function __construct($db, $auto_inc = FALSE)
    {
        $this->connection = $db;
        $this->auto_inc = $auto_inc;
    }

    function Remplir($valeur)
    {
        $longueur =  strlen($valeur);
        $result = '';
        if (array_key_exists($longueur, $this->zero_compensation)) {
            $result = $this->zero_compensation[$longueur] . $valeur;
            return $result;
        }
        return $valeur;
    }


    function GetServerDateOrTime($p = "D")
    {
        $retour = "";
        if ($p == "Y") {
            $retour = date('Y');
        } else if ($p == "DT") {
            $retour = date('Y-m-d H:i:s');
        } else if ($p == "T") {
            $retour = date('H:i:s');
        } else if ($p == "D") {
            $retour = date('Y-m-d');
        }
        return $retour; //date('Y');//date('Y-m-d H:i:s');
    }

    /*
     * use $short_code_device = $this->getShortCode('generatoras_sys_base', 'num_gen_device_short_code_no', 'N',$connection);
     */

    //  ALPHABETIQUE COMPTEUR
    public function getUID($generatoras_shop_base, $Lastgen_field, $canbeReset, $originTable, $Pkey, $separator = '')
    {

        if ($this->has_signature == TRUE) {
            $query = "select annos," . $Lastgen_field . " from " . $generatoras_shop_base . "  where  $this->Signature_fld='" . $this->Signature_Value . "'";
        } else {
            $query = 'select annos,' . $Lastgen_field . ' from ' . $generatoras_shop_base;
        }


        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $yearLasto = $this->GetServerDateOrTime("Y");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $retoNB = 0;
        if (!empty($row)) {
            $Lastgen_value = isset($row[$Lastgen_field]) ? $row[$Lastgen_field] : '0'; // offset si chiffre
            $dbLastyear = $row['annos'];
            if ($this->has_signature == TRUE) {
                if ($canbeReset == 'Y') {
                    // if ($resetBy == "Y") {  /////By Year
                    // reinitialisation annuellement
                    if ($yearLasto != $dbLastyear) {
                        /*$query = "delete from " . $generatoras_shop_base . ;
							$stmt = $this->connection->prepare($query);
							$stmt->execute();

							//reinitialisation des données pour la nouvelle année
							$query = "insert into $generatoras_shop_base(annos,datesys)
									values ('" . $yearLasto . "','" . $this->GetServerDateOrTime("DT") . ")";
							$stmt = $this->connection->prepare($query);
							$stmt->execute();*/

                        $retoNB = '0';
                        $query = "update  " . $generatoras_shop_base . "  set " . $Lastgen_field . "='" . $retoNB . "',annos='" . $yearLasto . "' where  $this->Signature_fld='" . $this->Signature_Value . "'";
                        $stmt = $this->connection->prepare($query);
                        //$stmt->execute(array($shop_id));
                        $stmt->execute();
                    } else { // toza kaka na mbula ya masolo 
                        $retoNB = $this->getNextAlphaCharSequence($Lastgen_value);
                        $retoNB = $this->AvoidMaxNested($Pkey, $retoNB, $originTable);
                        $query = "update  " . $generatoras_shop_base . "  set " . $Lastgen_field . "='" . $retoNB . "' where  $this->Signature_fld='" . $this->Signature_Value . "'";
                        $stmt = $this->connection->prepare($query);
                        //$stmt->execute(array($shop_id));
                        $stmt->execute();
                    }
                    //  }
                } else { //// No  never reset 
                    $retoNB = $this->getNextAlphaCharSequence($Lastgen_value);
                    $retoNB = $this->AvoidMaxNested($Pkey, $retoNB, $originTable);
                    // $query = "update  $generatoras_shop_base  set " . $Lastgen_field . "='" . $retoNB . "',annos='" . $yearLasto+"'";
                    $query = "update  " . $generatoras_shop_base . " set " . $Lastgen_field . "='" . $retoNB . "'  where  $this->Signature_fld='" . $this->Signature_Value . "'";
                    $stmt = $this->connection->prepare($query);
                    //$stmt->execute(array($shop_id));
                    $stmt->execute();
                }
            } else {
                if ($canbeReset == 'Y') {
                    // if ($resetBy == "Y") {  /////By Year
                    // reinitialisation annuellement
                    if ($yearLasto != $dbLastyear) {
                        $query = "delete from " . $generatoras_shop_base;
                        $stmt = $this->connection->prepare($query);
                        $stmt->execute();

                        //reinitialisation des données pour la nouvelle année
                        $query = "insert into $generatoras_shop_base(annos,datesys)
									values ('" . $yearLasto . "','" . $this->GetServerDateOrTime("DT") . "')";
                        $stmt = $this->connection->prepare($query);
                        $stmt->execute();
                        $retoNB = '0';
                    } else { // toza kaka na mbula ya masolo 
                        $retoNB = $this->getNextAlphaCharSequence($Lastgen_value);
                        $retoNB = $this->AvoidMaxNested($Pkey, $retoNB, $originTable);

                        /*var_dump($retoNB);
							exit;*/
                        $query = "update  " . $generatoras_shop_base . "  set " . $Lastgen_field . "='" . $retoNB . "'";
                        $stmt = $this->connection->prepare($query);
                        //$stmt->execute(array($shop_id));
                        $stmt->execute();
                    }
                    //  }
                } else { //// No  never reset 
                    $retoNB = $this->getNextAlphaCharSequence($Lastgen_value);
                    $retoNB = $this->AvoidMaxNested($Pkey, $retoNB, $originTable);
                    // $query = "update  $generatoras_shop_base  set " . $Lastgen_field . "='" . $retoNB . "',annos='" . $yearLasto+"'";
                    $query = "update  " . $generatoras_shop_base . " set " . $Lastgen_field . "='" . $retoNB . "'";
                    $stmt = $this->connection->prepare($query);
                    //$stmt->execute(array($shop_id));
                    $stmt->execute();
                }
            }
        } else {
            if ($this->has_signature == TRUE) {
                //////// initialisation des numeros auto 
                $query = "insert into " . $generatoras_shop_base . " (annos,datesys,$this->Signature_fld) values ('" . $yearLasto . "','" . $this->GetServerDateOrTime("DT") . "','" . $this->Signature_Value . "')";
                $stmt = $this->connection->prepare($query);
                $stmt->execute();
                // $stmt->execute(array($shop_id));
                $retoNB = '0';
            } else {
                //////// initialisation des numeros auto 
                $query = "insert into " . $generatoras_shop_base . " (annos,datesys) values ('" . $yearLasto . "','" . $this->GetServerDateOrTime("DT") . "')";
                $stmt = $this->connection->prepare($query);
                $stmt->execute();
                // $stmt->execute(array($shop_id));
                $retoNB = '0';
            }
        }

        //$bytes = $this->FormatKey($separator, $bytes,$retoNB,$yearLasto,$canbeReset);
        $bytes = $this->FormatKey($separator, $retoNB, $yearLasto, $canbeReset);


        //Phase 2 verification existance avant retour code
        if ($this->VerifierExistance($Pkey, $bytes, $originTable)) {
            $bytes = $this->getUID($generatoras_shop_base, $Lastgen_field, $canbeReset, $originTable, $Pkey, $separator);
        }
        return $bytes;
    }
    function FormatKey($separator, $retoNB, $yearLasto, $canbeReset)
    {

        $result = '';
        if ($canbeReset == 'Y') {
            if ($this->auto_inc) {
                $result = $retoNB . $separator . $yearLasto;
            } else {
                $result = $this->Remplir($retoNB) . $separator . $yearLasto;
            }
        } else {
            if ($this->auto_inc) {
                $result = $retoNB;
            } else {
                $result = $this->Remplir($retoNB);
            }
        }

        if ($this->has_signature == TRUE) {

            $result = $this->Signature_Value . $separator . $result;
        }
        return $result;
    }
    //public function uniqUid($len = 13) {  
    /*   function uniqUid($table, $key_fld) {
        //uniq gives 13 CHARS BUT YOU COULD ADJUST IT TO YOUR NEEDS
        $bytes = md5(mt_rand());
        //Phase 2 verification existance avant retour code
       if (VerifierExistance($key_fld, $bytes, $table)) {
            $bytes = uniqUid($table, $key_fld);
        }
        return $bytes;
        //return substr(bin2hex($bytes),0,$len);
    }*/
    function VerifierExistance($pKey, $NoGenerated, $table)
    {
        $retour = false;
        $sql = 'select ' . $pKey . ' from ' . $table . ' where ' . $pKey . '=:NoGenerated';
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':NoGenerated', $NoGenerated);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $retour = true;
        } else {
            $retour = false;
        }
        return $retour;
    }

    // function AvoidMaxNested($Pkey, $bytes, $originTable, $annos){
    function AvoidMaxNested($Pkey, $bytes, $originTable)
    {

        if ($this->VerifierExistance($Pkey, $bytes, $originTable)) {
            // $bytes = $this->GetHigher($Pkey, $originTable, $annos);
            // var_dump($bytes);

            $bytes = $this->getNextAlphaCharSequence($bytes);
            // var_dump($bytes);
            // exit;
        }
        return  $bytes;
    }
    function GetHigher($pKey, $table, $annos)
    {
        $retour = false;
        $sql = 'select max(' . $pKey . ') as LastKey from ' . $table;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $retour = $row['LastKey'];
        //	var_dump($sql);
        //	exit;
        return $retour;
    }

    function getHigherSet()
    {
        //$alpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $alpha = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ($this->auto_inc) {
            $alpha = "0123456789";
        }
        $len = strlen($alpha);
        $next = $alpha[$len - 1];
        return $next;
    }
    function getNextChar($str)
    {
        $alpha = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ($this->auto_inc) {
            $alpha = "0123456789";
        }
        $next = "0";
        //$next = "A";
        /* if ($this->auto_inc) {
			 $next = $str + 1;
		 }
		 else{*/

        $len = strlen($alpha);
        for ($i = 0; $i < $len; $i++) {

            if ($alpha[$i] == $str && $i + 1 < $len) {
                $next = $alpha[$i + 1];

                break;
            }
        }
        // }
        return $next;
    }

    function getNextAlphaCharSequence($charSeqStr)
    {
        $nextCharSeqStr = NULL;
        $charSeqArr = array();
        $isResetAllChar = false;
        $isResetAfterIndex = false;
        $resetAfterIndex = 0;

        if ($charSeqStr == NULL) {
            $charSeqArr[] = "0";
        } else {
            $len = strlen($charSeqStr);
            for ($i = 0; $i < $len; $i++) {
                $charSeqArr[] = $charSeqStr[$i];
            }
            $charSeqLen = count($charSeqArr);
            for ($index = $charSeqLen - 1; $index >= 0; $index--) {
                $charAtIndex = $charSeqArr[$index];
                $nextCharAtIndex = $this->getNextChar($charAtIndex);
                // if ((ord($charAtIndex)) % 30 == 0) {
                if ($this->getHigherSet() == $charAtIndex) {
                    if ($index == 0) {
                        $isResetAllChar = true;
                        $charSeqArr[] = $nextCharAtIndex;
                    } else {
                        continue;
                    }
                } else {
                    $nextCharAtIndex = $this->getNextChar($charAtIndex); // + 1);
                    $charSeqArr[$index] = $nextCharAtIndex;
                    if ($index + 1 < $charSeqLen) {
                        $isResetAfterIndex = true;
                        $resetAfterIndex = $index;
                    }
                    break;
                }
            }
            $charSeqLen = count($charSeqArr); //charSeqArr.length;
            if ($isResetAllChar) {
                for ($index = 0; $index < $charSeqLen; $index++) {
                    //$charSeqArr[$index] = "A";
                    $charSeqArr[$index] = "0";
                }
            } else if ($isResetAfterIndex) {
                for ($index = $resetAfterIndex + 1; $index < $charSeqLen; $index++) {
                    // $charSeqArr[$index] = "A";
                    $charSeqArr[$index] = "0";
                }
            }
        }
        $Lenght = count($charSeqArr);
        $nextCharSeqStr = "";
        for ($i = 0; $i < $Lenght; $i++) {
            $nextCharSeqStr .= $charSeqArr[$i];
        }
        return $nextCharSeqStr;
    }

    //FIN ALPHABETIQUE COMPTEUR
}
