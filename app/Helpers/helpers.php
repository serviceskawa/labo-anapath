<?php

use App\Models\Article;
use App\Models\CashboxDaily;
use App\Models\CashboxTicket;
use App\Models\chat;
use App\Models\Role;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Setting;
use App\Models\TestOrder;
use Illuminate\Support\Str;
use App\Models\Consultation;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\RefundRequest;
use App\Models\Report;
use App\Models\SettingApp;
use App\Models\SettingInvoice;
use App\Models\test_pathology_macro;
use App\Models\TestOrderAssignment;
use App\Models\TestOrderAssignmentDetail;
use App\Models\Ticket;
use App\Models\TypeConsultation;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

define("SERVER", "http://sms.wallyskak.com");
define("API_KEY", "cd571010a5549230264e74b9c89349fdcf5ed81c");

define("USE_SPECIFIED", 0);
define("USE_ALL_DEVICES", 1);
define("USE_ALL_SIMS", 2);

/**
 * @param string     $number      The mobile number where you want to send message.
 * @param string     $message     The message you want to send.
 * @param int|string $device      The ID of a device you want to use to send this message.
 * @param int        $schedule    Set it to timestamp when you want to send this message.
 * @param bool       $isMMS       Set it to true if you want to send MMS message instead of SMS.
 * @param string     $attachments Comma separated list of image links you want to attach to the message. Only works for MMS messages.
 * @param bool       $prioritize  Set it to true if you want to prioritize this message.
 *
 * @return array     Returns The array containing information about the message.
 * @throws Exception If there is an error while sending a message.
 */
// Fonction pour envoyer un message SMS
function sendSingleSMS($number, $message, $device = 0, $schedule = null, $prioritize = false)
{
    $url = SERVER . "/services/send.php";
    $postData = array(
        'number' => $number,
        'message' => $message,
        'schedule' => $schedule,
        'key' => API_KEY,
        'devices' => $device,
        'type' => "sms",
        'prioritize' => $prioritize ? 1 : 0,
    );
    return sendRequest($url, $postData)["messages"][0];
}

// Fonction pour envoyer un message MMS
function sendSingleMMS($number, $message, $device = 0, $schedule = null, $attachments = null, $prioritize = false)
{
    $url = SERVER . "/services/send.php";
    $postData = array(
        'number' => $number,
        'message' => $message,
        'schedule' => $schedule,
        'key' => API_KEY,
        'devices' => $device,
        'type' => "mms",
        'attachments' => $attachments,
        'prioritize' => $prioritize ? 1 : 0,
    );
    return sendRequest($url, $postData)["messages"][0];
}


/**
 * @param array  $messages        The array containing numbers and messages.
 * @param int    $option          Set this to USE_SPECIFIED if you want to use devices and SIMs specified in devices argument.
 *                                Set this to USE_ALL_DEVICES if you want to use all available devices and their default SIM to send messages.
 *                                Set this to USE_ALL_SIMS if you want to use all available devices and all their SIMs to send messages.
 * @param array  $devices         The array of ID of devices you want to use to send these messages.
 * @param int    $schedule        Set it to timestamp when you want to send these messages.
 * @param bool   $useRandomDevice Set it to true if you want to send messages using only one random device from selected devices.
 *
 * @return array     Returns The array containing messages.
 *                   For example :-
 *                   [
 *                      0 => [
 *                              "ID" => "1",
 *                              "number" => "+11234567890",
 *                              "message" => "This is a test message.",
 *                              "deviceID" => "1",
 *                              "simSlot" => "0",
 *                              "userID" => "1",
 *                              "status" => "Pending",
 *                              "type" => "sms",
 *                              "attachments" => null,
 *                              "sentDate" => "2018-10-20T00:00:00+02:00",
 *                              "deliveredDate" => null
 *                              "groupID" => ")V5LxqyBMEbQrl9*J$5bb4c03e8a07b7.62193871"
 *                           ]
 *                   ]
 * @throws Exception If there is an error while sending messages.
 */
function sendMessages($messages, $option = USE_SPECIFIED, $devices = [], $schedule = null, $useRandomDevice = false)
{
    $url = SERVER . "/services/send.php";
    $postData = [
        'messages' => json_encode($messages),
        'schedule' => $schedule,
        'key' => API_KEY,
        'devices' => json_encode($devices),
        'option' => $option,
        'useRandomDevice' => $useRandomDevice,
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int    $listID      The ID of the contacts list where you want to send this message.
 * @param string $message     The message you want to send.
 * @param int    $option      Set this to USE_SPECIFIED if you want to use devices and SIMs specified in devices argument.
 *                            Set this to USE_ALL_DEVICES if you want to use all available devices and their default SIM to send messages.
 *                            Set this to USE_ALL_SIMS if you want to use all available devices and all their SIMs to send messages.
 * @param array  $devices     The array of ID of devices you want to use to send the message.
 * @param int    $schedule    Set it to timestamp when you want to send this message.
 * @param bool   $isMMS       Set it to true if you want to send MMS message instead of SMS.
 * @param string $attachments Comma separated list of image links you want to attach to the message. Only works for MMS messages.
 *
 * @return array     Returns The array containing messages.
 * @throws Exception If there is an error while sending messages.
 */
function sendMessageToContactsList($listID, $message, $option = USE_SPECIFIED, $devices = [], $schedule = null, $isMMS = false, $attachments = null)
{
    $url = SERVER . "/services/send.php";
    $postData = [
        'listID' => $listID,
        'message' => $message,
        'schedule' => $schedule,
        'key' => API_KEY,
        'devices' => json_encode($devices),
        'option' => $option,
        'type' => $isMMS ? "mms" : "sms",
        'attachments' => $attachments,
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int $id The ID of a message you want to retrieve.
 *
 * @return array     The array containing a message.
 * @throws Exception If there is an error while getting a message.
 */
function getMessageByID($id)
{
    $url = SERVER . "/services/read-messages.php";
    $postData = [
        'key' => API_KEY,
        'id' => $id,
    ];
    return sendRequest($url, $postData)["messages"][0];
}

/**
 * @param string $groupID The group ID of messages you want to retrieve.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while getting messages.
 */
function getMessagesByGroupID($groupID)
{
    $url = SERVER . "/services/read-messages.php";
    $postData = [
        'key' => API_KEY,
        'groupId' => $groupID,
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param string $status         The status of messages you want to retrieve.
 * @param int    $deviceID       The deviceID of the device which messages you want to retrieve.
 * @param int    $simSlot        Sim slot of the device which messages you want to retrieve. Similar to array index. 1st slot is 0 and 2nd is 1.
 * @param int    $startTimestamp Search for messages sent or received after this time.
 * @param int    $endTimestamp   Search for messages sent or received before this time.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while getting messages.
 */
function getMessagesByStatus($status, $deviceID = null, $simSlot = null, $startTimestamp = null, $endTimestamp = null)
{
    $url = SERVER . "/services/read-messages.php";
    $postData = [
        'key' => API_KEY,
        'status' => $status,
        'deviceID' => $deviceID,
        'simSlot' => $simSlot,
        'startTimestamp' => $startTimestamp,
        'endTimestamp' => $endTimestamp,
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int $id The ID of a message you want to resend.
 *
 * @return array     The array containing a message.
 * @throws Exception If there is an error while resending a message.
 */
function resendMessageByID($id)
{
    $url = SERVER . "/services/resend.php";
    $postData = [
        'key' => API_KEY,
        'id' => $id,
    ];
    return sendRequest($url, $postData)["messages"][0];
}

/**
 * @param string $groupID The group ID of messages you want to resend.
 * @param string $status  The status of messages you want to resend.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while resending messages.
 */
function resendMessagesByGroupID($groupID, $status = null)
{
    $url = SERVER . "/services/resend.php";
    $postData = [
        'key' => API_KEY,
        'groupId' => $groupID,
        'status' => $status,
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param string $status         The status of messages you want to resend.
 * @param int    $deviceID       The deviceID of the device which messages you want to resend.
 * @param int    $simSlot        Sim slot of the device which messages you want to resend. Similar to array index. 1st slot is 0 and 2nd is 1.
 * @param int    $startTimestamp Resend messages sent or received after this time.
 * @param int    $endTimestamp   Resend messages sent or received before this time.
 *
 * @return array     The array containing messages.
 * @throws Exception If there is an error while resending messages.
 */
function resendMessagesByStatus($status, $deviceID = null, $simSlot = null, $startTimestamp = null, $endTimestamp = null)
{
    $url = SERVER . "/services/resend.php";
    $postData = [
        'key' => API_KEY,
        'status' => $status,
        'deviceID' => $deviceID,
        'simSlot' => $simSlot,
        'startTimestamp' => $startTimestamp,
        'endTimestamp' => $endTimestamp,
    ];
    return sendRequest($url, $postData)["messages"];
}

/**
 * @param int    $listID      The ID of the contacts list where you want to add this contact.
 * @param string $number      The mobile number of the contact.
 * @param string $name        The name of the contact.
 * @param bool   $resubscribe Set it to true if you want to resubscribe this contact if it already exists.
 *
 * @return array     The array containing a newly added contact.
 * @throws Exception If there is an error while adding a new contact.
 */
function addContact($listID, $number, $name = null, $resubscribe = false)
{
    $url = SERVER . "/services/manage-contacts.php";
    $postData = [
        'key' => API_KEY,
        'listID' => $listID,
        'number' => $number,
        'name' => $name,
        'resubscribe' => $resubscribe,
    ];
    return sendRequest($url, $postData)["contact"];
}

/**
 * @param int    $listID The ID of the contacts list from which you want to unsubscribe this contact.
 * @param string $number The mobile number of the contact.
 *
 * @return array     The array containing the unsubscribed contact.
 * @throws Exception If there is an error while setting subscription to false.
 */
function unsubscribeContact($listID, $number)
{
    $url = SERVER . "/services/manage-contacts.php";
    $postData = [
        'key' => API_KEY,
        'listID' => $listID,
        'number' => $number,
        'unsubscribe' => true,
    ];
    return sendRequest($url, $postData)["contact"];
}

/**
 * @return string    The amount of message credits left.
 * @throws Exception If there is an error while getting message credits.
 */
function getBalance()
{
    $url = SERVER . "/services/send.php";
    $postData = [
        'key' => API_KEY,
    ];
    $credits = sendRequest($url, $postData)["credits"];
    return is_null($credits) ? "Unlimited" : $credits;
}

/**
 * @param string $request   USSD request you want to execute. e.g. *150#
 * @param int $device       The ID of a device you want to use to send this message.
 * @param int|null $simSlot Sim you want to use for this USSD request. Similar to array index. 1st slot is 0 and 2nd is 1.
 *
 * @return array     The array containing details about USSD request that was sent.
 * @throws Exception If there is an error while sending a USSD request.
 */
function sendUssdRequest($request, $device, $simSlot = null)
{
    $url = SERVER . "/services/send-ussd-request.php";
    $postData = [
        'key' => API_KEY,
        'request' => $request,
        'device' => $device,
        'sim' => $simSlot,
    ];
    return sendRequest($url, $postData)["request"];
}

/**
 * @param int $id The ID of a USSD request you want to retrieve.
 *
 * @return array     The array containing details about USSD request you requested.
 * @throws Exception If there is an error while getting a USSD request.
 */
function getUssdRequestByID($id)
{
    $url = SERVER . "/services/read-ussd-requests.php";
    $postData = [
        'key' => API_KEY,
        'id' => $id,
    ];
    return sendRequest($url, $postData)["requests"][0];
}

/**
 * @param string   $request        The request text you want to look for.
 * @param int      $deviceID       The deviceID of the device which USSD requests you want to retrieve.
 * @param int      $simSlot        Sim slot of the device which USSD requests you want to retrieve. Similar to array index. 1st slot is 0 and 2nd is 1.
 * @param int|null $startTimestamp Search for USSD requests sent after this time.
 * @param int|null $endTimestamp   Search for USSD requests sent before this time.
 *
 * @return array     The array containing USSD requests.
 * @throws Exception If there is an error while getting USSD requests.
 */
function getUssdRequests($request, $deviceID = null, $simSlot = null, $startTimestamp = null, $endTimestamp = null)
{
    $url = SERVER . "/services/read-ussd-requests.php";
    $postData = [
        'key' => API_KEY,
        'request' => $request,
        'deviceID' => $deviceID,
        'simSlot' => $simSlot,
        'startTimestamp' => $startTimestamp,
        'endTimestamp' => $endTimestamp,
    ];
    return sendRequest($url, $postData)["requests"];
}

/**
 * @return array     The array containing all enabled devices
 * @throws Exception If there is an error while getting devices.
 */
function getDevices()
{
    $url = SERVER . "/services/get-devices.php";
    $postData = [
        'key' => API_KEY,
    ];
    return sendRequest($url, $postData)["devices"];
}

function sendRequest($url, $postData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        throw new Exception(curl_error($ch));
    }
    curl_close($ch);
    if ($httpCode == 200) {
        $json = json_decode($response, true);
        if ($json == false) {
            if (empty($response)) {
                throw new Exception("Missing data in request. Please provide all the required information to send messages.");
            } else {
                throw new Exception($response);
            }
        } else {
            if ($json["success"]) {
                return $json["data"];
            } else {
                throw new Exception($json["error"]["message"]);
            }
        }
    } else {
        throw new Exception("HTTP Error Code : {$httpCode}");
    }
}

if (!function_exists('getPermission')) {
    function getPermission($role_id, $operation, $permission_id)
    {
        $role = Role::findorfail($role_id);
        $permissions = $role->permissions();

        $data = $role->permissions()->where('permission_id', $permission_id)->exists();

        return $data;
    }
}

if (!function_exists('getSignatory1')) {
    function getSignatory1($signatory_id)
    {
        $users = getUsersByRole('docteur');
        $signatory = $users->find($signatory_id);
        if ($signatory)
            return $signatory->lastname . " " . $signatory->firstname;
    }
}
if (!function_exists('getSignatory2')) {
    function getSignatory2($signatory_id)
    {
        $setting = Setting::findorfail(1);
        return $setting->signatory2;
    }
}
if (!function_exists('getSignatory3')) {
    function getSignatory3()
    {
        $setting = Setting::findorfail(1);
        return $setting->signatory3;
    }
}
if (!function_exists('getOnlineUser')) {
    function getOnlineUser()
    {
        $user = Auth::user();
        return $user;
    }
}

// generate code examen
if (!function_exists('generateCodeExamen')) {
    function generateCodeExamen()
    {
        //Récupère le dernier enregistrement de la même année avec un code non null et dont les 4 derniers caractères du code sont les plus grands
        $lastTestOrder = TestOrder::whereYear('created_at', '=', now()->year)
            ->whereNotNull('code')
            ->orderByRaw('RIGHT(code, 4) DESC')
            ->first();


             // Récupérer la valeur de la base de données
            $configuration = SettingApp::where('key', 'entete')->first();

           



        // Si c'est le premier enregistrement ou si la date de l'enregistrement est différente de l'année actuelle, le code sera "0001"
        if (!$lastTestOrder || $lastTestOrder->created_at->year != now()->year) {
            $code = "0001";
        }
        // Sinon, incrémente le dernier code de 1
        else {
            // Récupère les quatre derniers caractères du code
            $lastCode = substr($lastTestOrder->code, -4);

            // Convertit la chaîne en entier et l'incrémente de 1
            $code = intval($lastCode) + 1;
            $code = str_pad($code, 4, '0', STR_PAD_LEFT);
        }

        $configuration = SettingApp::where('key', 'prefixe_code_demande_examen')->first();
        // Décoder la chaîne JSON en un tableau associatif
        $data = json_decode($configuration, true);

        $codeexamen = $data['value'];

        // Ajoute les deux derniers chiffres de l'année au début du code
        return $codeexamen.now()->year % 100 . "-$code";
    }
}

// generate code cashbox ticket
if (!function_exists('generateCodeTicket')) {
    function generateCodeTicket()
    {
        //Récupère le dernier enregistrement de la même année avec un code non null et dont les 4 derniers caractères du code sont les plus grands
        $lastTestOrder = CashboxTicket::whereYear('created_at', '=', now()->year)
            ->whereNotNull('code')
            ->orderByRaw('RIGHT(code, 4) DESC')
            ->first();

        // Si c'est le premier enregistrement ou si la date de l'enregistrement est différente de l'année actuelle, le code sera "0001"
        if (!$lastTestOrder || $lastTestOrder->created_at->year != now()->year) {
            $code = "0001";
        }
        // Sinon, incrémente le dernier code de 1
        else {
            // Récupère les quatre derniers caractères du code
            $lastCode = substr($lastTestOrder->code, -4);

            // Convertit la chaîne en entier et l'incrémente de 1
            $code = intval($lastCode) + 1;
            $code = str_pad($code, 4, '0', STR_PAD_LEFT);
        }

        // Ajoute les deux derniers chiffres de l'année au début du code
        return "BC".now()->year % 100 . "-$code";
    }
}


if(!function_exists('isAffecte')){
    function isAffecte($id){
        $detail = TestOrderAssignmentDetail::where('test_order_id',$id)->first();
        if ($detail) {
            $assignment = TestOrderAssignment::find($detail->test_order_assignment_id);
            $data = $assignment->user;
        }else{
            $data = null;
        }
        return $data;
    }
}
if(!function_exists('dateLimite')){
    function dateLimite($date){
        $formattedDate = Carbon::parse($date)->format('Y-m-d');
        // Ajouter 10 jours
        $newDate = Carbon::parse($formattedDate)->addDays(9);
        $newDate = Carbon::parse($newDate)->format('Y-m-d');
        return $newDate;
    }
}

if(!function_exists('dateFormat')){
    function dateFormat($date){
        $formattedDate = Carbon::parse($date)->format('d-m-Y');
        return $formattedDate;
    }
}

if(!function_exists('isMacro')){
    function isMacro($id){
        $detail = test_pathology_macro::where('id_test_pathology_order',$id)->first();
        if ($detail) {
            $data = true;
        }else{
            $data = false;
        }
        return $data;
    }
}

if(!function_exists('getAllRoles')){
    function getAllRoles(){
        return Role::latest()->get();
    }
}

// generate code affectation
if (!function_exists('generateCodeAssignment')) {
    function generateCodeAssignment()
    {
        //Récupère le dernier enregistrement de la même année avec un code non null et dont les 4 derniers caractères du code sont les plus grands
        $lastTestOrder = TestOrderAssignment::whereYear('created_at', '=', now()->year)
            ->whereNotNull('code')
            ->orderByRaw('RIGHT(code, 4) DESC')
            ->first();

        // Si c'est le premier enregistrement ou si la date de l'enregistrement est différente de l'année actuelle, le code sera "0001"
        if (!$lastTestOrder || $lastTestOrder->created_at->year != now()->year) {
            $code = "0001";
        }
        // Sinon, incrémente le dernier code de 1
        else {
            // Récupère les quatre derniers caractères du code
            $lastCode = substr($lastTestOrder->code, -4);

            // Convertit la chaîne en entier et l'incrémente de 1
            $code = intval($lastCode) + 1;
            $code = str_pad($code, 4, '0', STR_PAD_LEFT);
        }

        // Ajoute les deux derniers chiffres de l'année au début du code
        return "AF".now()->year % 100 . "-$code";
    }
}


// generate code facture
if (!function_exists('generateCodeFacture')) {
    function generateCodeFacture()
    {
        //Récupère le dernier enregistrement de la même année avec un code non null et dont les 4 derniers caractères du code sont les plus grands
        $invoice = Invoice::whereYear('created_at', '=', now()->year)
            ->whereNotNull('code')
            ->orderByRaw('RIGHT(code, 4) DESC')
            ->first();

        // Si c'est le premier enregistrement ou si la date de l'enregistrement est différente de l'année actuelle, le code sera "0001"
        if (!$invoice || $invoice->created_at->year != now()->year) {
            $code = "0001";
        }
        // Sinon, incrémente le dernier code de 1
        else {
            // Récupère les quatre derniers caractères du code
            $lastCode = substr($invoice->code, -4);

            // Convertit la chaîne en entier et l'incrémente de 1
            $code = intval($lastCode) + 1;
            $code = str_pad($code, 4, '0', STR_PAD_LEFT);
        }

        // Ajoute les deux derniers chiffres de l'année au début du code
        return "FA" . now()->year % 100 . "$code";
    }
}

if (!function_exists('getNameInitials')) {
    function getNameInitials($fullName) {
        $initials = "";
        $nameParts = explode(" ", trim($fullName));

        foreach ($nameParts as $namePart) {
            if (!empty($namePart)) {
                $initials .= strtoupper($namePart[0]);
            }
        }
        return $initials;

    }
}

if (!function_exists('getUnreadMessageCount')) {
    function getUnreadMessageCount($userId) {
        // Remplacez "messages" par le nom de votre table de messages
        $unreadMessages = DB::table('chats')
            ->where('receve_id', $userId) // Remplacez "receve_id" par le nom de votre colonne pour l'ID du destinataire
            ->where('status', 1) // Les messages non lus ont lu = 0
            ->where('read', 0) // Les messages non lus ont lu = 0
            ->count();

        return $unreadMessages;
    }
}

if (!function_exists('getUnreadMessageBySenderCount')) {
    function getUnreadMessageBySenderCount($userId,$senderID) {
        // Remplacez "messages" par le nom de votre table de messages
        $unreadMessages = DB::table('chats')
            ->where('receve_id', $userId) // Remplacez "receve_id" par le nom de votre colonne pour l'ID du destinataire
            ->where('sender_id', $senderID) // Remplacez "receve_id" par le nom de votre colonne pour l'ID du destinataire
            ->where('status', 1) // Les messages non lus ont lu = 0
            ->where('read', 0) // Les messages non lus ont lu = 0
            ->count();

        return $unreadMessages;
    }
}

if (!function_exists('getMessageUnreadBySender')) {
    function getMessageUnreadBySender($userId,$senderID) {

        $lastMessage =  Chat::where(function ($query) use ($senderID,$userId) {
            $query->where('sender_id', $senderID)->where('receve_id', $userId);
        })->orWhere(function ($query) use ($senderID, $userId) {
            $query->where('sender_id', $userId)->where('receve_id', $senderID);
        })->orderBy('created_at', 'desc')
        ->first();
        return $lastMessage->sender_id == $userId ? 'Vous: '.$lastMessage->message: $lastMessage->message;
    }
}

if (!function_exists('getMessageUnreadSender')) {
    function getMessageUnreadSender($userId,$senderID) {

      $lastSentMessage = chat::where('sender_id', $senderID)
        ->where('receve_id', $userId)
        ->orderBy('created_at', 'desc') // Triez par date d'envoi décroissante
        ->first(); // Récupérez le premier message (le plus récent)
        return $lastSentMessage;
    }
}

//Récupérer le nombre de demandes d'examen en attente
if (!function_exists('getnbrTestOrderpending')) {
    function getnbrTestOrderpending(){
        $nbr = TestOrder::whereHas('type', function($query) {
            $query->where('slug','cytologie')
                  ->orwhere('slug','histologie');
        })
        ->whereHas('report', function($query){
            $query->where('status',0);
        })->count();
        return $nbr;
    }
}


//Récupérer le nombre de demandes d'examen en attente
if (!function_exists('getnbrTestOrderImmunopending')) {
    function getnbrTestOrderImmunopending(){
        $nbr = TestOrder::whereHas('type', function($query) {
            $query->where('slug','immuno-interne')
                  ->orwhere('slug','immuno-exterme');
        })
        ->whereHas('report', function($query){
            $query->where('status',0);
        })->count();
        return $nbr;
    }
}

//Récupérer le nombre de factures impayées
if (!function_exists('getnbrInvoicepending')) {
    function getnbrInvoicepending(){
        $nbr = Invoice::where('paid',0)->count();
        return $nbr;
    }
}

//Récupérer le nombre de stocks minimum atteint
if (!function_exists('getnbrStockMinim')) {
    function getnbrStockMinim(){
        $nbr = Article::where('quantity_in_stock', '<=', DB::raw('minimum'))->count();
        return $nbr;
    }
}

//Récupérer le nombre de demandes de remboursement en attente
if (!function_exists('getnbrRefundRequestPending')) {
    function getnbrRefundRequestPending(){
        $nbr = $nbr = RefundRequest::where('status', 'En attente')->count();
        return $nbr;
    }
}

//Récupérer le nombre de bon de caisse en attente
if (!function_exists('getnbrBonCaissePending')) {
    function getnbrBonCaissePending(){
        $nbr = $nbr = CashboxTicket::where('status', 'En attente')->count();
        return $nbr;
    }
}

//Récupérer le nombre de tickets en attente
if (!function_exists('getnbrTicketPending')) {
    function getnbrTicketPending($userId){
        $user = User::find($userId);
        $is_admin = $user->userCheckRole('rootuser');
        $nbr = null;
        if ($is_admin) {
            $nbr = Ticket::where('status','ouvert')->count();
        } else {
            $nbr = Ticket::where('user_id',$userId)->where('status','ouvert')->count();
        }

        return $nbr;
    }
}



// generate code facture
if (!function_exists('generateCodeFactureAvoir')) {
    function generateCodeFactureAvoir()
    {
        //Récupère le dernier enregistrement de la même année avec un code non null et dont les 4 derniers caractères du code sont les plus grands
        $invoice = RefundRequest::whereYear('created_at', '=', now()->year)
            ->whereNotNull('code')
            ->orderByRaw('RIGHT(code, 4) DESC')
            ->first();

        // Si c'est le premier enregistrement ou si la date de l'enregistrement est différente de l'année actuelle, le code sera "0001"
        if (!$invoice || $invoice->created_at->year != now()->year) {
            $code = "0001";
        }
        // Sinon, incrémente le dernier code de 1
        else {
            // Récupère les quatre derniers caractères du code
            $lastCode = substr($invoice->code, -4);

            // Convertit la chaîne en entier et l'incrémente de 1
            $code = intval($lastCode) + 1;
            $code = str_pad($code, 4, '0', STR_PAD_LEFT);
        }

        // Ajoute les deux derniers chiffres de l'année au début du code
        return "DER" . now()->year % 100 . "$code";
    }
}


// generate code tickets
if (!function_exists('generateCodeBillet')) {
    function generateCodeBillet()
    {
        //Récupère le dernier enregistrement de la même année avec un code non null et dont les 4 derniers caractères du code sont les plus grands
        $billet = Ticket::whereYear('created_at', '=', now()->year)
            ->whereNotNull('ticket_code')
            ->orderByRaw('RIGHT(ticket_code, 4) DESC')
            ->first();

        // Si c'est le premier enregistrement ou si la date de l'enregistrement est différente de l'année actuelle, le code sera "0001"
        if (!$billet || $billet->created_at->year != now()->year) {
            $code = "0001";
        }
        // Sinon, incrémente le dernier code de 1
        else {
            // Récupère les quatre derniers caractères du code
            $lastCode = substr($billet->ticket_code, -4);

            // Convertit la chaîne en entier et l'incrémente de 1
            $code = intval($lastCode) + 1;
            $code = str_pad($code, 4, '0', STR_PAD_LEFT);
        }

        // Ajoute les deux derniers chiffres de l'année au début du code
        return "TI-" . now()->year % 100 . "$code";
    }
}


// generate code facture
if (!function_exists('generateCodeOpeningCashbox')) {
    function generateCodeOpeningCashbox()
    {
        //Récupère le dernier enregistrement de la même année avec un code non null et dont les 4 derniers caractères du code sont les plus grands
        $invoice = CashboxDaily::whereYear('created_at', '=', now()->year)
            ->whereNotNull('code')
            ->orderByRaw('RIGHT(code, 4) DESC')
            ->first();

        // Si c'est le premier enregistrement ou si la date de l'enregistrement est différente de l'année actuelle, le code sera "0001"
        if (!$invoice || $invoice->created_at->year != now()->year) {
            $code = "0001";
        }
        // Sinon, incrémente le dernier code de 1
        else {
            // Récupère les quatre derniers caractères du code
            $lastCode = substr($invoice->code, -4);

            // Convertit la chaîne en entier et l'incrémente de 1
            $code = intval($lastCode) + 1;
            $code = str_pad($code, 4, '0', STR_PAD_LEFT);
        }

        // Ajoute les deux derniers chiffres de l'année au début du code
        return "OC" . now()->year % 100 . "$code";
    }
}



function tronquerChaine($chaine,$nbr=50) {

    if (strlen($chaine) > $nbr) {
        // Si la chaîne est plus longue que 100 caractères, on la tronque et on ajoute les trois points de suspension
        $chaine_tronquee = substr($chaine, 0, $nbr) . '...';
        return $chaine_tronquee;
    } else {
        // Sinon, on retourne la chaîne telle quelle
        return $chaine;
    }
}


// recupère les informations d'une demande d'examen
if (!function_exists('getTestOrderData')) {
    function getTestOrderData(int $testOrderId = null)
    {
        $result = "";
        if (!empty($testOrderId)) {
            $testOrder = TestOrder::find($testOrderId);
            $result = $testOrder;
        }
        return $result;
    }
}


if (!function_exists('invoiceNormeTest')) {
    function invoiceNormeTest($id)
    {
        $client = new Client();
        $testOrderModel = new TestOrder();
        $invoiceModel = new Invoice();

        $testOrder = $testOrderModel->find($id);
        $invoice = $invoiceModel->where('test_order_id', '=', $id)->first();

        $item = [];
        $items = [];

        $details = $testOrder->details()->get();

        if (!empty($details)) {
            foreach ($details as  $value) { {
                    $item['name'] = $value->test_name;
                    $item['price'] = $value->total;
                    $item['quantity'] = 1;
                    $item['taxGroup'] = "B";
                    $items[] = $item;
                }
            }
        }

        // $accessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjAyMDIzNjc4MDc0MDN8VFMwMTAwNTQ2NyIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTY3OTU1OTk2OCwiZXhwIjoxNjk1NDU3NTY4LCJpYXQiOjE2Nzk1NTk5NjgsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.g80Hdsm2VInc7WBfiSvc7MVC34ZEXbwqyJX_66ePDGQ';
        // $ifu = "0202367807403";
        $settingInvoiceModel = new SettingInvoice();
        $settingInvoice = $settingInvoiceModel->find(1);
        $accessToken = $settingInvoice->token;
        $ifu = $settingInvoice->ifu;
        // $ifu = "0".$settingInvoice->ifu;
        $response = $client->request('POST','https://developper.impots.bj/sygmef-emcf/api/invoice',
        [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'ifu' => $ifu,
                    'type' => "FV",
                    // 'aib' => "B",
                    'items' => $items,
                    // 'items' => [
                    //     //    [ "name"=>"Jus d'orange",
                    //     //     "price"=>5000,
                    //     //     "quantity"=>2,
                    //     //     "taxGroup"=>"A"],
                    //         [ "name"=>"Jus de mangue",
                    //         "price"=>5000,
                    //         "quantity"=>3,
                    //         "taxGroup"=>"B",
                    //         "taxSpecific" => 230]
                    // ],

                    "client" => [
                        // "ifu"=>"0202367807403",
                        "name" => $invoice->client_name,
                        "address" => $invoice->client_address?$invoice->client_address:'a',
                    ],
                    "operator" => [
                        "id" => Auth::user()->id,
                        "name" => Auth::user()->lastname,
                    ],
                    // "reference" => "TEST-QWMW-F22F-GOA5-45BB-S6PF",
                    // "taxSpecific" => 230,
                    "payment" => [
                        [
                            "name" => $invoice->payment,
                            "amount" => $invoice->total,
                            // "amount" => 25000,
                            ]
                        ],
                ]
        ]);
       $test = json_decode($response->getBody(), true);

        return ["id" => $id, "uid" => $test['uid']];
    }
}


// recupère les informations d'un patient
if (!function_exists('getPatientData')) {
    function getPatientData(int $patientId = null)
    {
        $result = "";
        if (!empty($patientId)) {
            $patient = Patient::find($patientId);
            $result = $patient;
        }
        return $result;
    }
}

// recupère les informations d'un utilisateur
if (!function_exists('getUsertData')) {
    function getUserData(int $userId = null)
    {
        $result = "";
        if (!empty($userId)) {
            $user = User::find($userId);
            $result = $user;
        }
        return $result;
    }
}

if (!function_exists('getDoctorData')) {
    function getDoctorData(int $doctorId = null)
    {
        $result = "";
        if (!empty($doctorId)) {
            $doctor = Doctor::find($doctorId);
            $result = $doctor;
        }
        return $result;
    }
}

if (!function_exists('randColor')) {
    function randColor($priority)
    {
        $data = [
            "normal" => 'bg-info',
            "urgent" => 'bg-warning',
            "tres urgent" => 'bg-danger',
        ];

        return $data[$priority];
    }
}

if (!function_exists('convertDate')) {
    function convertDateTime($date)
    {
        $date_input = date('Y-m-d H:i:s', strtotime($date));
        return $date_input;
    }
}

if (!function_exists('getAllUsers')) {
    function getAllUsers()
    {
        return User::all();
    }
}

if (!function_exists('getAllEmployees')) {
    function getAllEmployees()
    {
        return Employee::all();
    }
}

if (!function_exists('randColorStatus')) {
    function randColorStatus($priority)
    {
        $data = [
            "pending" => 'bg-warning',
            "approved" => 'bg-success',
            "cancel" => 'bg-danger',
        ];

        return $data[$priority];
    }
}


if (!function_exists('checkTypeConsultationFile')) {
    function checkTypeConsultationFile($typeConsultationFileId, $typeConsultationId)
    {
        $typeConsultation = TypeConsultation::findorfail($typeConsultationId);
        $type_files = $typeConsultation->type_files();

        $data = $typeConsultation->type_files()->where('type_file_id', $typeConsultationFileId)->exists();

        return $data;
    }
}

if (!function_exists('getConsultationTypeFiles')) {
    function getConsultationTypeFiles($ConsultationId, $typeConsultationFileId)
    {
        $Consultation = Consultation::findorfail($ConsultationId);

        $data = $Consultation->type_files()->where('type_file_id', $typeConsultationFileId)->first();

        return $data;
    }
}

if (!function_exists('getUsersByRole')) {
    function getUsersByRole($roleSlug)
    {
        $users = [];
        $role = Role::whereSlug($roleSlug)->first();

        if (!empty($role)) {
            $users = $role->users;
        }

        return $users;
    }
}

if (!function_exists('getRolesByUser')) {
    function getRolesByUser($userID)
    {
        $roles = [];
        $rolesusers = UserRole::where('user_id', $userID)->get();
        foreach ($rolesusers as $value) {
            $role = Role::find($value->role_id);
            $roles[] = $role;
        }
        return $roles;
    }
}

if (!function_exists('formatMontant')) {
    function formatMontant($montant) {
        $formattedMontant = number_format($montant, 0, ',', ' ');
        $formattedMontant .= ' F CFA';
        return $formattedMontant;
    }
}

if (!function_exists('getTotalByPatient')) {
    function getTotalByPatient($id)
    {
        $total = Invoice::where('patient_id', '=', $id)->sum('total');
        return $total;
    }
}

if (!function_exists('getNoPaidByPatient')) {
    function getNoPaidByPatient($id)
    {
        $nopaye = Invoice::where(['patient_id' => $id, 'paid' => 0])->sum('total');
        return $nopaye;
    }
}

if (!function_exists('getPaidByPatient')) {
    function getPaidByPatient($id)
    {
        $paye = Invoice::where(['patient_id' => $id, 'paid' => 1])->sum('total');
        return $paye;
    }
}
