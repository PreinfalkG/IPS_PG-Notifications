<?
class ProwlNotifications extends IPSModule {

    public function Create() {
        //Never delete this line!
        parent::Create();
        //These lines are parsed on Symcon Startup or Instance creation
        //You cannot use variables here. Just static values.
        $this->RegisterPropertyString("apikey","");
        $this->RegisterPropertyString("applicationname","Symcon Prowl Notification");
        $this->RegisterPropertyString("username","");
    }

    public function ApplyChanges() {
        //Never delete this line!
        parent::ApplyChanges();

        $this->RegisterVariableBoolean("STATE","Status","~Switch",1);

        // EnableAction
        $this->EnableAction("STATE");

        $this->SetStatus(102);
<<<<<<< HEAD
        $iqlpnguid = "{F7A352DD-EEF2-DDDB-066E-788D23BDB89A}";
=======
        $iqlpnguid = "{F7A35269-4455-BBDB-066E-758D23BDB89A}";
>>>>>>> 7ac495f06c651500e8b196927ba4dc54813d7b30
        $iqlpninstanzen = IPS_GetInstanceListByModuleID($iqlpnguid);
        foreach($iqlpninstanzen as $modulentry) {
            if($modulentry != $this->InstanceID) {
                if (IPS_GetProperty($modulentry, "apikey") == $this->ReadPropertyString("apikey")) {
                    $this->SetStatus(201);
                    break;
                }
                if (IPS_GetProperty($modulentry, "username") == $this->ReadPropertyString("username")) {
                    $this->SetStatus(202);
                    break;
                }
            }
        }
        if(preg_match('/,/',$this->ReadPropertyString("username"))) {
            $this->SetStatus(204);
        }
        if($this->ReadPropertyString("apikey") != "") {
            if($this->VerifyApiKey($this->ReadPropertyString("apikey")) == false) {
                $this->SetStatus(203);
            }
        }
    }

    public function Send(string $subject, string $message, int $priority ) {
        if(strlen($subject) > 1024) {
            return "Subject zu lang";
        }
        elseif(strlen($message) > 10000) {
            return "Message zu lang";
        }
        elseif($priority < -2 or $priority > 2) {
            return "Priorität ausserhalb des zulässigen Bereichs";
        }
        // Build Request
        if(GetValue($this->GetIDForIdent("STATE")) == true) {
            $this->ProwlRequest("add", $this->ReadPropertyString("apikey"), $subject, $message, $priority);
            return true;
        }
        return false;
    }

    public function SendEx(string $username, string $subject, string $message, int $priority ) {
        $newapikey = "";
        if(strlen($subject) > 1024) {
            return "Subject zu lang";
        }
        elseif(strlen($message) > 10000) {
            return "Message zu lang";
        }
        elseif($priority < -2 or $priority > 2) {
            return "Priorität ausserhalb des zulässigen Bereichs";
        }
        if(preg_match('/,/',$username)) {
            $apikeyarray = array();
            $userarray = explode(",",$username);
<<<<<<< HEAD
            $iqlpnguid = "{F7A352DD-EEF2-DDDB-066E-788D23BDB89A}";
=======
            $iqlpnguid = "{F7A35269-4455-BBDB-066E-758D23BDB89A}";
>>>>>>> 7ac495f06c651500e8b196927ba4dc54813d7b30
            $iqlpninstanzen = IPS_GetInstanceListByModuleID($iqlpnguid);
            foreach($iqlpninstanzen as $moduleid) {
                foreach($userarray as $userentry) {
                    if(IPS_GetProperty($moduleid, "username") == $userentry) {
                        $apikeyarray[] = IPS_GetProperty($moduleid,"apikey");
                    }
                }
            }
            foreach($apikeyarray as $apikeyentry) {
                if($newapikey == "") {
                    $newapikey = $apikeyentry;
                }
                else {
                    $newapikey = $newapikey ."," .$apikeyentry;
                }
            }
        }
        // Build Request
        if(GetValue($this->GetIDForIdent("STATE")) == true) {
            if($newapikey != "") {
                $this->ProwlRequest("add", $newapikey, $subject, $message, $priority);
                return true;
            }
            else {
                return false;
            }
        }
        return false;
    }
    private function VerifyApiKey( string $apikey) {
        $url = 'https://api.prowlapp.com/publicapi/verify?apikey=';
        $request = $url . $apikey;
        $curl_connection = curl_init($request);
        curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array("Expect:"));
        curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($curl_connection);
        curl_close($curl_connection);

        $xmlResult = new SimpleXMLElement($result);
        if(isset($xmlResult->{'error'})) {
            return false;
        }
        else {
            return true;
        }
    }

    private function ProwlRequest( string $command, string $apikey, string $subject, string $message, int $priority ) {
        $post_data['apikey'] = $apikey;
        $post_data['event'] = $subject;
        $post_data['description'] = $message;
        $post_data['priority'] = $priority;
        $post_data['application'] = $this->ReadPropertyString("applicationname");

        foreach ( $post_data as $key => $value) {
            $post_items[] = $key . '=' . $value;
        }

        if(isset($post_items)) {
            $post_string = implode('&', $post_items);
        }
        if(isset($post_string)) {
            $curl_connection = curl_init('https://api.prowlapp.com/publicapi/' .$command);
            curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array("Expect:"));
            curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
            $result = curl_exec($curl_connection);
            curl_close($curl_connection);
        }
        if(isset($result)) {
            return $result;
        }
        else {
            return false;
        }
    }

    public function RequestAction($Ident, $Value){
        switch($Ident) {
            case "STATE":
                SetValue($this->GetIDForIdent($Ident),$Value);
                break;
            default:
                throw new Exception("Invalid ident");
        }
    }
}