<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// echo CLIENTID , CLIENTSECRET, REDIRECTURI, URLAUTHORIZE,URLACCESSTOKEN,URLRESOURCEOWNERDETAILS;
class Indexcontrolador extends CI_Controller
{



    private $datos;
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->driver('cache');
    }

    
    public function index()
    {

        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => CLIENTID,
            'clientSecret'            => CLIENTSECRET,
            'redirectUri'             => REDIRECTURI,
            'urlAuthorize'            => URLAUTHORIZE,
            'urlAccessToken'          => URLACCESSTOKEN,
            'urlResourceOwnerDetails' => URLRESOURCEOWNERDETAILS

        ]);

    
        // If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {  
            $authorizationUrl = $provider->getAuthorizationUrl();
            $this->session->set_userdata('oauth2state', $provider->getState());
            redirect("$authorizationUrl");
        } else {
            if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
                $this->session->unset_userdata('oauth2state');
                exit("Invalid state, <a href='" . base_url() . "/Indexcontrolador'>Volver al login</a>");
            } else {

                try {
                    // Try to get an access token using the authorization code grant.
                    $accessToken = $provider->getAccessToken('authorization_code', [
                        'code' => $_GET['code']
                    ]);

                    $resourceOwner = $provider->getResourceOwner($accessToken);
                    $this->datos = $resourceOwner->toArray();

                    $this->session->set_userdata('sub', $this->datos['sub']);
                    $this->session->set_userdata('group_mereconoce', $this->datos['group_mereconoce']);
                    $this->session->set_userdata('email_verified', $this->datos['email_verified']);
                    $this->session->set_userdata('name', $this->datos['name']);
                    $this->session->set_userdata('preferred_username', $this->datos['preferred_username']);
                    $this->session->set_userdata('given_name', $this->datos['given_name']);
                    $this->session->set_userdata('family_name', $this->datos['family_name']);
                    $this->session->set_userdata('email', $this->datos['email']);
                    $this->session->set_userdata('rut', $this->datos['rut']);
                    $this->session->set_userdata('accessToken', $accessToken->getToken());
                    $this->session->set_userdata('Refresh', $accessToken->getRefreshToken());
                    redirect('inicio');
                } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                    echo ($e->getMessage());
                    exit("<br><a href='" . base_url() . "indexcontrolador'>Volver al login</a>");
                }
            }
        }
    }

    function home()
    {

        // var_dump($this->session);

        if (!$this->session->userdata('rut')) {
            redirect("indexcontrolador");
        }

       /* $this->dato['url_page']    = 'ingreso/principal/';
        $this->dato['url_tarjeta'] = 'tarjeta/principal/';
        $this->dato['url_online']  = 'online/buscarColab/';
        $this->load->view('header');
        $this->load->view('temp/contenidohome', $this->dato);
        $this->load->view('footer');
        */
        $data['titlePage'] = "INGRESO";
        $this->load->view("template/tpl2-sb/header",$data);
        $this->load->view("contenidos/inicio_view");
        $this->load->view("template/tpl2-sb/footer");
    }

    function logout()
    {
        $this->session->unset_userdata('rut');
        $this->session->unset_userdata('oauth2state');
        $this->session->sess_destroy();
        unset($_SESSION);
        $url = rawurlencode(base_url());
        $url2 = LOGOUTREDIRECT . "$url";
        header("Location: $url2");
    }
}
