<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\SettingApp;
use App\Models\TitleReport;
use Illuminate\Http\Request;

class SettingAppController extends Controller
{
    protected $setting;
    public function __construct(SettingApp $setting)
    {
        $this->setting = $setting;
    }
    public function index()
    {
        $app_name = $this->setting->where('key', 'lab_name')->first();
        $prefixe_code_demande_examen = $this->setting->where('key', 'prefixe_code_demande_examen')->first();
        $devise = $this->setting->where('key', 'devise')->first();
        $adress = $this->setting->where('key', 'adress')->first();

        $token_payment = $this->setting->where('key', 'token_payment')->first();
        $show_signator_invoice = $this->setting->where('key', 'show_signator_invoice')->first();
        // $public_key = $this->setting->where('key','public_key')->first();
        // $private_key = $this->setting->where('key','private_key')->first();
        // $secret_key = $this->setting->where('key','secret_key')->first();

        $whatsapp_number = $this->setting->where('key', 'whatsapp_number')->first();
        $ifu = $this->setting->where('key', 'ifu')->first();
        $rccm = $this->setting->where('key', 'rccm')->first();

        $phone = $this->setting->where('key', 'phone')->first();
        $email = $this->setting->where('key', 'email')->first();
        $web_site = $this->setting->where('key', 'web_site')->first();
        $footer = $this->setting->where('key', 'footer')->first();
        $email_host = $this->setting->where('key', 'email_host')->first();
        $email_port = $this->setting->where('key', 'email_port')->first();
        $username = $this->setting->where('key', 'username')->first();
        $password = $this->setting->where('key', 'password')->first();
        $encryption = $this->setting->where('key', 'encryption')->first();
        $from_adresse = $this->setting->where('key', 'from_adresse')->first();
        $from_name = $this->setting->where('key', 'from_name')->first();
        $api_sms = $this->setting->where('key', 'api_sms')->first();
        $link_api_sms = $this->setting->where('key', 'link_api_sms')->first();
        $key_ourvoice = $this->setting->where('key', 'key_ourvoice')->first();
        $link_ourvoice_call = $this->setting->where('key', 'link_ourvoice_call')->first();
        $link_ourvoice_sms = $this->setting->where('key', 'link_ourvoice_sms')->first();
        $token_fluid_sender = $this->setting->where('key', 'token_fluid_sender')->first();
        $session_name = $this->setting->where('key', 'session_name')->first();
        $report_footer = $this->setting->where('key', 'report_footer')->first();
        $report_review_title = $this->setting->where('key', 'report_review_title')->first();
        $mail = $this->setting->where('key', 'admin_mails')->first();
        $service = $this->setting->where('key', 'services')->first();
        $email_technician = $this->setting->where('key', 'email_technician')->first();
        $banks = Bank::latest()->get();
        $titles = TitleReport::latest()->get();

        return view('settings.app.setting', compact(
            'token_payment',
            'app_name',
            'prefixe_code_demande_examen',
            'devise',
            'adress',
            'phone',
            'email',
            'web_site',
            'footer',
            'banks',
            'titles',
            'email_host',
            'username',
            'email_port',
            'password',
            'encryption',
            'from_name',
            'from_adresse',
            'mail',
            'service',
            'api_sms',
            'link_api_sms',
            'key_ourvoice',
            'link_ourvoice_call',
            'link_ourvoice_sms',
            'token_fluid_sender',
            'session_name',
            'report_footer',
            'report_review_title',
            'email_technician',
            'show_signator_invoice',
            'whatsapp_number',
            'ifu',
            'rccm',
        ));
    }

    public function store(Request $request)
    {
        // Récupérez les valeurs directement à partir de la demande HTTP
        $nbr = intval($request->nbrform);
        $appNameValue = $request->input('app_name');
        $prefixeCodeDemandeExamenValue = $request->input('prefixe_code_demande_examen');
        $deviseValue = $request->input('devise');
        $adresseValue = $request->input('adress');
        $telephoneValue = $request->input('phone');
        $emailValue = $request->input('email');
        $whatsapp_numberValue = $request->input('whatsapp_number');
        $rccmValue = $request->input('rccm');
        $ifuValue = $request->input('ifu');
        $webSiteValue = $request->input('web_site');
        $footerValue = $request->input('footer');
        $email_hostValue = $request->input('email_host');
        $email_portValue = $request->input('email_port');
        $email_technicianValue = $request->input('email_technician');
        $usernameValue = $request->input('username');
        $passwordValue = $request->input('password');
        $encryptionValue = $request->input('encryption');
        $from_adresseValue = $request->input('from_adresse');
        $from_nameValue = $request->input('from_name');
        $api_smsValue = $request->input('api_sms');
        $link_api_smsValue = $request->input('link_api_sms');
        $key_ourvoiceValue = $request->input('key_ourvoice');
        $link_ourvoice_callValue = $request->input('link_ourvoice_call');
        $link_ourvoice_smsValue = $request->input('link_ourvoice_sms');
        $token_fluid_senderValue = $request->input('token_fluid_sender');
        $session_nameValue = $request->input('session_name');
        $report_footerValue = $request->input('report_footer');
        $report_review_titleValue = $request->input('report_review_title');
        $mails = $request->input('mails');
        $services = $request->input('services');

        $token_payment_value = $request->input('token_payment');
        $show_signator_invoice_value = $request->input('show_signator_invoice');

        // Mettez à jour les enregistrements dans la base de données
        switch ($nbr) {
            case 1:
                $app_name = $this->setting->where('key', 'lab_name')->first();
                $app_name ? $app_name->update(['value' => $appNameValue]) : '';

                $devise = $this->setting->where('key', 'devise')->first();
                $devise ? $devise->update(['value' => $deviseValue]) : '';



                $ifu = $this->setting->where('key', 'ifu')->first();
                $ifu ? $ifu->update(['value' => $ifuValue]) : '';

                $whatsapp_number = $this->setting->where('key', 'whatsapp_number')->first();
                $whatsapp_number ? $whatsapp_number->update(['value' => $whatsapp_numberValue]) : '';

                $rccm = $this->setting->where('key', 'rccm')->first();
                $rccm ? $whatsapp_number->update(['value' => $rccmValue]) : '';


                $adress = $this->setting->where('key', 'adress')->first();
                $adress ? $adress->update(['value' => $adresseValue]) : '';

                $telephone = $this->setting->where('key', 'phone')->first();
                $telephone ? $telephone->update(['value' => $telephoneValue]) : '';

                $email = $this->setting->where('key', 'email')->first();
                $email ? $email->update(['value' => $emailValue]) : '';

                $web_site = $this->setting->where('key', 'web_site')->first();
                $web_site ? $web_site->update(['value' => $webSiteValue]) : '';

                $footer = $this->setting->where('key', 'footer')->first();
                $footer ? $footer->update(['value' => $footerValue]) : '';

                if ($request->file('logo')) {

                    $logo = time() . '_settings_app_logo.' . $request->file('logo')->extension();

                    $path_logo = $request->file('logo')->storeAs('settings/app', $logo, 'public');
                }
                if ($request->file('favicon')) {

                    $favicon = time() . '_settings_app_favicon.' . $request->file('favicon')->extension();

                    $path_favicon = $request->file('favicon')->storeAs('settings/app', $favicon, 'public');
                }
                if ($request->file('logo_white')) {

                    $img3 = time() . '_settings_app_blanc.' . $request->file('logo_white')->extension();

                    $path_img3 = $request->file('logo_white')->storeAs('settings/app', $img3, 'public');
                }

                $logo = $this->setting->where('key', 'logo')->first();
                $logo ? $logo->update(['value' => $path_logo]) : '';

                $favicon = $this->setting->where('key', 'favicon')->first();
                $favicon ? $favicon->update(['value' => $path_favicon]) : '';

                $logo_white = $this->setting->where('key', 'logo_white')->first();
                $logo_white ? $logo_white->update(['value' => $path_img3]) : '';
                break;
            case 2:

                $email_host = $this->setting->where('key', 'email_host')->first();
                $email_host ? $email_host->update(['value' => $email_hostValue]) : '';

                $email_port = $this->setting->where('key', 'email_port')->first();
                $email_port ? $email_port->update(['value' => $email_portValue]) : '';

                $email_technician = $this->setting->where('key', 'email_technician')->first();
                $email_technician ? $email_technician->update(['value' => $email_technicianValue]) : '';

                $username = $this->setting->where('key', 'username')->first();
                $username ? $username->update(['value' => $usernameValue]) : '';

                $password = $this->setting->where('key', 'password')->first();
                $password ? $password->update(['value' => $passwordValue]) : '';

                $encryption = $this->setting->where('key', 'encryption')->first();
                $encryption ? $encryption->update(['value' => $encryptionValue]) : '';

                $from_adresse = $this->setting->where('key', 'from_adresse')->first();
                $from_adresse ? $from_adresse->update(['value' => $from_adresseValue]) : '';

                $from_name = $this->setting->where('key', 'from_name')->first();
                $from_name ? $from_name->update(['value' => $from_nameValue]) : '';

                // $mail = $this->setting->where('key', 'admin_mails')->first();
                // $mail ? $mail->update(['value' => implode('|', $mails)]) : '';

                $service = $this->setting->where('key', 'services')->first();
                $service ? $service->update(['value' => implode('|', $services)]) : '';
                break;

            case 3:
                $api_sms = $this->setting->where('key', 'api_sms')->first();
                $api_sms ?  $api_sms->update(['value' => $api_smsValue]) : '';

                $link_api_sms = $this->setting->where('key', 'link_api_sms')->first();
                $link_api_sms ? $link_api_sms->update(['value' => $link_api_smsValue]) : '';

                $key_ourvoice = $this->setting->where('key', 'key_ourvoice')->first();
                $key_ourvoice ? $key_ourvoice->update(['value' => $key_ourvoiceValue]) : '';

                $link_ourvoice_call = $this->setting->where('key', 'link_ourvoice_call')->first();
                $link_ourvoice_call ? $link_ourvoice_call->update(['value' => $link_ourvoice_callValue]) : '';

                $link_ourvoice_sms = $this->setting->where('key', 'link_ourvoice_sms')->first();
                $link_ourvoice_sms ? $link_ourvoice_sms->update(['value' => $link_ourvoice_smsValue]) : '';

                $token_fluid_sender = $this->setting->where('key', 'token_fluid_sender')->first();
                $token_fluid_sender ? $token_fluid_sender->update(['value' => $token_fluid_senderValue]) : '';

                $session_name = $this->setting->where('key', 'session_name')->first();
                $session_name ? $session_name->update(['value' => $session_nameValue]) : '';
                break;

            case 4:
                if ($request->file('entete')) {

                    // debut
                    $imageFile = $request->file('entete');
                    // Obtenez le nom d'origine du fichier
                    $namefichier = "entete_pdf_cr." . $imageFile->getClientOriginalExtension();

                    // Enregistrez le fichier image dans le dossier public
                    $re = $request->file('entete')->move(public_path('adminassets/images'), $namefichier);
                    // fin

                }

                $report_footer = $this->setting->where('key', 'report_footer')->first();
                $report_footer ?  $report_footer->update(['value' => $report_footerValue]) : '';

                $prefixe_code_demande_examen = $this->setting->where('key', 'prefixe_code_demande_examen')->first();
                $prefixe_code_demande_examen ?  $prefixe_code_demande_examen->update(['value' => $prefixeCodeDemandeExamenValue]) : '';

                // $entete = $this->setting->where('key', 'entete')->first();
                // $entete ? $entete->update(['value' => $namefichier]) : '';

                $show_signator_invoice = $this->setting->where('key', 'show_signator_invoice')->first();
                $show_signator_invoice ?  $show_signator_invoice->update(['value' => $show_signator_invoice_value]) : '';

                $report_review_title = $this->setting->where('key', 'report_review_title')->first();
                $report_review_title ?  $report_review_title->update(['value' => $report_review_titleValue]) : '';
                break;

            case 7:

                $token_payment = $this->setting->where('key', 'token_payment')->first();
                $token_payment ?  $token_payment->update(['value' => $token_payment_value]) : '';
            default:
                break;
        }

        return back()->with('success', "Mise à jour effectuée avec succès");
    }
}
