<?
error_reporting(E_ALL ^ E_DEPRECATED);
require_once($_SERVER['DOCUMENT_ROOT']."/api/REST.api.php");
require_once($_SERVER['DOCUMENT_ROOT']."/libs/includes/Database.class.php");
require_once($_SERVER['DOCUMENT_ROOT']."/libs/load.php");

class API extends REST{
    public $data = "";
    private $db = NULL;
    private $current_call;
    private $auth = null;

    public function __construct(){
        parent::__construct();                      //Init parent contructor    
        $this->db =  Database::getConnection();    // Initiate Database connection
    }

    private function json($data){
        if(is_array($data)){
            return json_encode($data, JSON_PRETTY_PRINT);
        }else{
            return "{}";
        }
    }

    /**
     * public method for access api.
     * This method dynamically call the method based on the query string
     * 
     */
    public function processApi(){
        $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
        if((int)method_exists($this,$func) > 0){
            $this->$func();
        } else {
            if(isset($_GET['namespace'])){
                $dir = $_SERVER['DOCUMENT_ROOT'].'/api/apis/'.$_GET['namespace'];
                $file = $dir.'/'.$func.'.php';
                if(file_exists($file)){
                    include $file;
                    $this->current_call = Closure::bind(${$func}, $this, get_class());
                    $this->$func();
                } else {
                    $this->response($this->json(['error'=>'method_not_found']), 404);
                }
            }else {
                $this->response($this->json(['error'=>'method_not']), 400);
            }
        }
    }

    // Function for Saying Error of the API
    public function die($e){
        $data = [
            "error" => $e->getMessage()
        ];
        $data = $this->json($data);
        $this->response($data, 400);
    }
    
    // Function to pass the undefine method name to process api to call
    public function __call($method, $args){
        if(is_callable($this->current_call)){
            return call_user_func($this->current_call, $args);
        } else {
            $this->response($this->json(['error' => 'method_not_callable']), 404);
        }
    }

     /*************API SPACE START*******************/

     private function about(){
        $data = array('method'=> $this->get_request_method(),'version' => $this->_request['version'], 'desc' => 'This API is created for Transfer Data between devices and server');
        $data = $this->json($data);
        $this->response($data,200);
     }

      /*************API SPACE END*******************/

}

//Initiate Library
$api = new API;
try{
    $api->processApi();
}
catch(Exception $e){
    $api->die($e);
}

