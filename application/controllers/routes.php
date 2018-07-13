<?php

class Routes_Controller extends Template_Controller
{
    public $template;
    public $Options;
    public $Member_id;
    public $InitService;

    public function __construct()
    {

        parent::__construct();
        $this->template = new View('templates/' . $this->site['config']['TEMPLATE'] . '/client/index');
        $this->_get_session_template();

        $this->InitService = new Service_Controller();
        $this->Member_id = $this->sess_cus['id'];

        // $this->My_Update_Events();
        require Kohana::find_file('views/templates/pco/permission/', 'permission');
        require Kohana::find_file('views/templates/pco/options/', 'options');

    }

    private function _get_session_template()
    {

        if ($this->session->get('error_msg'))
            $this->template->error_msg = $this->session->get('error_msg');
        if ($this->session->get('warning_msg'))
            $this->template->warning_msg = $this->session->get('warning_msg');
        if ($this->session->get('success_msg'))
            $this->template->success_msg = $this->session->get('success_msg');
        if ($this->session->get('info_msg'))
            $this->template->info_msg = $this->session->get('info_msg');
    }

    public function __call($method, $arguments)
    {

    }

    //index
    public function index()
    {
        $this->template->css = new View('routes/css');
        $this->template->content = new View('routes/index');
        $this->template->Jquery = new View('routes/js');
        // $_SESSION['TimeAuto']    = array();
    }

    // get set
    public function getAllSet()
    {
        $set = $this->db->query('SELECT * FROM set_route WHERE member_id = ' . $this->Member_id)->result_array(false);
        $this->template = new View('routes/navSet');
        $this->template->jsLoad = new View('routes/loadRoutes');
        $this->template->set = $set;
        $this->template->render(true);

        die();
    }

    // show route in datatable
    public function LoadRoutes()
    {
        // echo 'vao';die();
        $idSet = $_POST['idSet'];
        // echo $idSet;die();
        $total = count($this->db->query('SELECT * FROM routes WHERE route_set = ' . $idSet . ' AND route_id_member = ' . $this->Member_id)->result_array(false));
        ini_set('memory_limit', '-1');
        $_data = array();
        $iSearch = @$_POST['search'];
        $_isSearch = ($iSearch == '') ? false : true;
        $iDisplayLength = (int)@($_POST['length']);
        $iDisplayStart = (int)@($_POST['start']);
        // $sEcho            = (int)@($_POST['draw']);
        $total_items = $total;
        $total_filter = $total_items;

        $query_main = 'SELECT * FROM routes WHERE route_set = ' . $idSet;

        if ($_isSearch)
            $query_main .= ' AND route_name LIKE "%' . $iSearch . '%"';
        $query_main .= ' AND route_id_member = ' . $this->Member_id;

        // Limit
        $query_limit = ' LIMIT ' . $iDisplayStart . ', ' . $iDisplayLength;

        $filter = $this->db->query($query_main)->result_array(false);
        $total_filter = count($filter);

        $result = $this->db->query($query_main . $query_limit)->result_array(false);

        // echo $this->getZIPString(1); die();
        // print_r($filter);die();

        foreach ($result as $key => $value) {
            $_data[] = array(
                'tdID' => $value['route_id'],
                'tdNo' => $value['route_no'],
                'tdName' => $value['route_name'],
                'tdService' => $this->getService($value['route_id'], 1),
                'tdMap' => $this->getMap($value['route_id']),
                'tdZIP' => $value['route_zip'],
                'tdTechnician' => $this->getTechnicianName($value['route_technician'])
            );
        }

        $records = array();
        $records["data"] = $_data;
        // $records["draw"]             = $sEcho;
        $records["recordsTotal"] = $total_items;
        $records["recordsFiltered"] = $total_filter;
        $records["_isSearch"] = $_isSearch;
        echo json_encode($records);
        die();
    }

    // get zone of route
    public function loadZone()
    {
        $route_id = $_POST['route_id'];

        $route_info = $this->db->query('SELECT * FROM routes WHERE route_id = ' . $route_id . ' AND route_id_member = ' . $this->Member_id)->result_array(false);
        $service = $this->db->query('SELECT * FROM customers_service WHERE service_route = ' . $route_id . ' AND member_id = ' . $this->Member_id)->result_array(false);
        $route_map = $this->db->query('SELECT * FROM route_map WHERE route_id = ' . $route_id . ' AND map_id_member = ' . $this->Member_id)->result_array(false);
        $data = array(
            'map' => $route_map,
            'service' => $service,
            'route_info' => $route_info
        );
        echo json_encode($data);
        die();
    }

    // save zone in route
    public function saveZone()
    {
        $data = $_POST['data'];
        $type = $_POST['type'];
        $id = $_POST['id'];
        $service = $_POST['serviceIn'];
        $savePosition = array();

        if (!empty($id)) {
            $r_data = json_encode($data);

            $_data = array(
                'route_id' => $id,
                'type' => strtoupper($type),
                'position' => $r_data,
                'service_in' => $service,
                'route_id_member' => $this->Member_id
            );
            $this->db->insert('route_map', $_data);
            $rs = $this->db->query('SELECT LAST_INSERT_ID() as id FROM route_map')->result_array(false);
            echo $rs[0]['id'];
        } else
            echo 'No id route to save!';
        die();
    }

    // get a template new set
    public function getNewSet()
    {
        $this->template = new View('routes/newSet');
        $this->template->jsLoad = new View('routes/loadRoutes');
        $this->template->idSet = $_POST['idSet'];
        $this->template->render(true);

        die();
    }

    // get the id set active and count all set in database
    public function countAllSet()
    {
        $data = array();

        $setActive = $this->db->query('SELECT id FROM set_route WHERE active = 1 AND member_id = ' . $this->Member_id)->result_array(false);
        $countSet = $this->db->query('SELECT id FROM set_route WHERE member_id = ' . $this->Member_id . ' ORDER BY id DESC')->result_array(false);

        array_push($data, $countSet[0], isset($setActive[0]) ? $setActive[0] : 0);
        echo json_encode($data);
        die();
    }

    // make the set active
    public function setActiveSet()
    {
        $setID = $_POST['setID'];
        $name = $_POST['name'];

        $exist = $this->db->query('SELECT * FROM set_route WHERE id = ' . $setID . ' AND member_id = ' . $this->Member_id)->result_array();

        if (count($exist) != 0) {
            $this->db->update('set_route', array('active' => 1, 'name' => $name), array('id' => $setID));
        } else {
            $this->db->insert('set_route', array('name' => $name, 'active' => 1, 'member_id' => $this->Member_id));
        }
        $this->db->query('UPDATE set_route SET active = 0 WHERE id NOT 
			IN (' . $setID . ')');
        echo 'Success';
        die();
    }

    // get the template add route
    public function addRouteHtml()
    {
        $set = $_POST['idSet'];

        $technician = $this->db->query('SELECT * FROM _technician WHERE member_id = ' . $this->Member_id);

        $this->template = new View('routes/add_route');
        $this->template->idSet = $set;
        $this->template->technician = $technician;
        $this->template->render(true);

        die();
    }

    // get the template edit route
    public function editRouteHtml()
    {
        $route = $_POST['idRoute'];
        $rs = $this->db->query('SELECT * FROM routes WHERE route_id = ' . $route . ' AND route_id_member = ' . $this->Member_id)->result_array(false);
        $technician = $this->db->query('SELECT * FROM _technician WHERE member_id = ' . $this->Member_id);
        $query = 'SELECT * FROM customers, customers_service WHERE id = customers_id AND service_route = ' . $route . ' AND customers.member_id = ' . $this->Member_id;

        $active = count($this->db->query($query . ' AND active = 1')->result_array(false));
        $noactive = count($this->db->query($query . ' AND active = 0')->result_array(false));

        $service_in = $this->getCountServiceIn($route);

        $this->template = new View('routes/edit_route');
        $this->template->technician = $technician;
        $this->template->active = $active;
        $this->template->noactive = $noactive;
        $this->template->service_in = $service_in;
        $this->template->route = $rs[0];
        $this->template->render(true);

        die();
    }

    // insert new route
    public function insertRoute()
    {
        if (isset($_POST)) {
            $no = $_POST['no'];
            $name = $_POST['name'];
            $zip = $_POST['zip'];
            $technician = $_POST['technician'];
            $idSet = $_POST['idSet'];

            $this->db->insert('routes', array(
                'route_no' => $no,
                'route_id_member' => $this->Member_id,
                'route_name' => $name,
                'route_set' => $idSet,
                'route_technician' => $technician,
                'route_zip' => $zip
            ));

            $lastID = $this->db->query('SELECT LAST_INSERT_ID() as id FROM routes')->result_array(false)[0]['id'];

            if (isset($_POST['map'])) {
                $map = $_POST['map'];
                for ($i = 0; $i < count($map); $i++) {
                    $zone = $map[$i];
                    // echo json_encode($zone);die();
                    $this->db->insert('route_map', array(
                        'route_id' => $lastID,
                        'map_id_member' => $this->Member_id,
                        'type' => $zone['type'],
                        'position' => json_encode(array($zone['position'])),
                    ));
                }
            }
            echo true;
            die();
        }

        echo false;
        die();
    }

    // check the route no
    public function checkRouteID()
    {
        if (isset($_POST['idRoute'])) {
            $route = $_POST['idRoute'];

            $rs = $this->db->query('SELECT * FROM routes WHERE route_no = ' . $route . ' AND route_id_member = ' . $this->Member_id)->result_array(false);
            if (count($rs) != 0)
                echo true;
            else
                echo false;
        } else
            echo false;

        die();
    }

    // check set exist in database
    public function checkSet()
    {
        $id = $_POST['idSet'];

        $post = $this->db->query('SELECT * FROM set_route WHERE id = ' . $id . ' AND member_id = ' . $this->Member_id . ' AND active = 1')->result_array(false);

        echo count($post);
        die();
    }

    // insert new set
    public function insertSet()
    {
        $name = $_POST['name'];

        $this->db->insert('set_route', array(
            'name' => $name,
            'member_id' => $this->Member_id,
            'active' => 0
        ));

        echo true;
        die();
    }

    // update the set
    public function updateSet()
    {
        $id = $_POST['idSet'];
        $name = $_POST['name'];

        $this->db->update('set_route', array(
            'name' => $name,
        ), array('id' => $id));

        echo true;
        die();
    }

    // make the set inactive and make first set in database active
    public function setInActive()
    {
        $idSet = $_POST['idSet'];

        $this->db->update('set_route', array('active' => 0), array('id' => $idSet));
        $id = $this->db->query('SELECT id FROM set_route WHERE member_id = ' . $this->Member_id . ' ORDER BY id ASC')->result_array(false)[0]['id'];

        $this->db->update('set_route', array('active' => 1), array('id' => $id));
        die();
    }

    //delete multiple route
    public function deleteRoute()
    {
        $selectAll = $_POST['selectAll'];
        // $selected = $_POST['selected'];
        // $unselected = $_POST['unselected'];

        if ($selectAll == 'true') {
            if (isset($unselected)) {
                $unselected = $_POST['unselected'];
                $strID = '';
                for ($i = 0; $i < count($unselected); $i++) {
                    if ($i == 0)
                        $strID .= '(';
                    $strID .= $unselected[$i];
                    if (empty($unselected[$i + 1]))
                        $strID .= ')';
                    else
                        $strID .= ',';
                }
                $this->db->query('DELETE FROM routes WHERE route_id NOT IN ' . $strID . ' AND route_id_member = ' . $this->Member_id);
                $this->db->query('DELETE FROM route_map WHERE route_id NOT IN ' . $strID . ' AND map_id_member = ' . $this->Member_id);
            } else {
                $idSet = $_POST['idSet'];
                $idRoute = $this->db->query('SELECT route_id FROM routes WHERE route_set = ' . $idSet . ' AND route_id_member = ' . $this->Member_id)->result_array(false);
                foreach ($idRoute as $value) {
                    $this->db->query('DELETE FROM route_map WHERE route_id = ' . $value['route_id'] . ' AND map_id_member = ' . $this->Member_id);
                }
                $this->db->query('DELETE FROM routes WHERE route_set = ' . $idSet . ' AND route_id_member = ' . $this->Member_id);
            }
        } else {
            $selected = $_POST['selected'];
            $strID = '';
            for ($i = 0; $i < count($selected); $i++) {
                if ($i == 0)
                    $strID .= '(';
                $strID .= $selected[$i];
                if (empty($selected[$i + 1]))
                    $strID .= ')';
                else
                    $strID .= ',';
            }
            $this->db->query('DELETE FROM routes WHERE route_id IN ' . $strID . ' AND route_id_member = ' . $this->Member_id);
            $this->db->query('DELETE FROM route_map WHERE route_id IN ' . $strID . ' AND map_id_member = ' . $this->Member_id);
        }
        die();
    }

    // delete one set
    public function deleteSet()
    {
        $idSet = $_POST['idSet'];

        $this->db->query('DELETE FROM set_route WHERE id = ' . $idSet . ' AND member_id = ' . $this->Member_id);
        $idRoute = $this->db->query('SELECT route_id FROM routes WHERE route_set = ' . $idSet . ' AND route_id_member = ' . $this->Member_id)->result_array(false);
        foreach ($idRoute as $value) {
            $this->db->query('DELETE FROM route_map WHERE route_id = ' . $value['route_id'] . ' AND map_id_member = ' . $this->Member_id);
        }
        $this->db->query('DELETE FROM routes WHERE route_set = ' . $idSet . ' AND route_id_member = ' . $this->Member_id);

        die();
    }

    // button show list service in page add and edit route
    public function showListService()
    {
        $idRoute = $_POST['idRoute'];
        $active = $_POST['active'];

        $this->template = new View('routes/loadService');
        $this->template->idRoute = $idRoute;
        if ($active)
            $this->template->title = 'Active';
        else
            $this->template->title = 'Inactive';
        $this->template->render(true);
        die();
    }

    // get service for datatable
    public function loadService()
    {
        $idRoute = $_POST['idRoute'];
        $active = $_POST['active'];

        $query = 'SELECT cus.customer_name, cus.customer_no, cussv.service_name, tec.name as `tec_name`, cussv.service_address_1, prop.name as `prop_name`, typ.name as `type_name`, sch.frequency 
				  FROM pco.customers as cus, pco.customers_service as cussv, pco.customers_service_scheduling as sch, pco._technician as tec, pco._property as prop, pco._service_type as typ
				  WHERE cus.id = cussv.customers_id AND cussv.service_id = sch.service_id AND IF(sch.technician = "", 1,sch.technician) = tec.id AND IF(cussv.service_property = "", 1, cussv.service_property) = prop.id AND IF(cussv.service_service_type = "", 1, cussv.service_service_type) = typ.id';
        $query .= ' AND cussv.service_route = ' . $idRoute;
        $query .= ' AND cus.active = ' . (($active == 'Active') ? 1 : 0);
        $query .= ' AND cus.member_id = ' . $this->Member_id . ' AND cussv.member_id = ' . $this->Member_id . ' AND sch.member_id = ' . $this->Member_id;
        // echo $query;die();

        $total = count($this->db->query($query)->result_array(false));

        // echo $total;die();

        ini_set('memory_limit', '-1');
        $_data = array();
        $iSearch = @$_POST['search'];
        $_isSearch = ($iSearch == '') ? false : true;
        $iDisplayLength = (int)@($_POST['length']);
        $iDisplayStart = (int)@($_POST['start']);
        // $sEcho            = (int)@($_POST['draw']);
        $total_items = $total;
        $total_filter = $total_items;

        if ($_isSearch)
            $query .= ' AND (cussv.service_name LIKE "%' . $iSearch . '%" OR cus.customer_name LIKE "%' . $iSearch . '%")';

        // Limit
        $query_limit = ' LIMIT ' . $iDisplayStart . ', ' . $iDisplayLength;

        $filter = $this->db->query($query . $query_limit)->result_array(false);

        $total_filter = count($filter);

        // echo $this->getZIPString(1); die();
        // print_r($filter);die();

        foreach ($filter as $key => $value) {
            $_data[] = array(
                'tdCustomer' => $value['customer_name'],
                'tdCusNo' => $value['customer_no'],
                'tdSvName' => $value['service_name'],
                'tdTechnician' => $value['tec_name'],
                'tdSvAdd' => $value['service_address_1'],
                'tdPropType' => $value['prop_name'],
                'tdTypeName' => $value['type_name'],
                'tdFre' => $value['frequency'],
                'tdInvoice' => 'NULL'
            );
        }

        $records = array();
        $records["data"] = $_data;
        // $records["draw"]             = $sEcho;
        $records["recordsTotal"] = $total_items;
        $records["recordsFiltered"] = $total_filter;
        $records["_isSearch"] = $_isSearch;
        echo json_encode($records);
        die();
    }

    // get template for button map in page add and edit route
    public function loadMap()
    {
        $idRoute = $_GET['idRoute'];

        $route_info = $this->db->query('SELECT * FROM routes WHERE route_id = ' . $idRoute . ' AND route_id_member = ' . $this->Member_id)->result_array(false);

        $this->template = new View('routes/loadMap');
        if (count($route_info) > 0)
            $this->template->route_info = $route_info[0];
        else
            $this->template->route_info = 0;
        $this->template->render(true);
        die();
    }

    // edit in map, draw or delete
    public function saveZoneForRoute()
    {
        $idRoute = $_POST['idRoute'];
        if (!isset($_POST['data'])) {
            $this->db->query('DELETE FROM route_map WHERE route_id = ' . $idRoute . ' AND map_id_member = ' . $this->Member_id);
        } else {
            $data = $_POST['data'];

            $map_id = array();
            $insertData = array();
            foreach ($data as $value) {
                if ($value['index'])
                    $result = count($this->db->query('SELECT * FROM route_map WHERE map_id = "' . $value['index'] . '" AND map_id_member = ' . $this->Member_id)->result_array(false));
                if ($result > 0) {
                    array_push($map_id, $value['index']);
                } else {
                    array_push($insertData, array(
                        'route_id' => $idRoute,
                        'type' => $value['type'],
                        'position' => json_encode(array($value['position'])),
                        'service_in' => $value['service_in'],
                        'map_id_member' => $this->Member_id
                    ));
                }
            }

            //delete
            $queryDelete = 'DELETE FROM route_map';
            $not_in = '';
            for ($i = 0; $i < count($map_id); $i++) {
                if ($i == 0)
                    $not_in .= '(';
                $not_in .= $map_id[$i];
                if (empty($map_id[$i + 1]))
                    $not_in .= ')';
                else
                    $not_in .= ',';
            }
            if ($not_in != '')
                $this->db->query($queryDelete . ' WHERE map_id NOT IN ' . $not_in . ' AND map_id_member = ' . $this->Member_id);

            //insert
            for ($j = 0; $j < count($insertData); $j++) {
                $this->db->insert('route_map', $insertData[$j]);
            }
        }
        die();
    }

    // update route information
    public function updateRoute()
    {
        if (isset($_POST)) {
            $no = $_POST['no'];
            $name = $_POST['name'];
            $zip = $_POST['zip'];
            $technician = $_POST['technician'];
            $idRoute = $_POST['id'];

            // echo kohana::Debug($idRoute);die();

            $this->db->update('routes', array(
                'route_no' => $no,
                'route_name' => $name,
                'route_technician' => $technician,
                'route_zip' => $zip
            ), array('route_id' => $idRoute));

            echo true;
            die();
        }

        echo false;
        die();
    }

    // delete selected route
    public function deleteOneRoute()
    {
        $idRoute = $_POST['route_id'];

        $this->db->query('DELETE FROM route_map WHERE route_id = ' . $idRoute . ' AND map_id_member = ' . $this->Member_id);
        $this->db->query('DELETE FROM routes WHERE route_id = ' . $idRoute . ' AND route_id_member = ' . $this->Member_id);

        die();
    }

    // count service active in selected set
    public function countServiceInSet()
    {
        $idSet = $_POST['idSet'];

        $sv_active = $this->db->query('SELECT * FROM customers_service AS cs,customers AS c WHERE cs.customers_id = c.id AND c.active = 1 AND cs.member_id = ' . $this->Member_id . ' AND c.member_id = ' . $this->Member_id)->result_array(false);

        $total_active = count($sv_active);

        echo json_encode($total_active);
        die();
    }

    // get all service active
    public function getAllService()
    {
        $service = $this->db->query('SELECT service_id,service_name,service_address_1, service_address_2, service_city, service_state, service_zip, latitude_1, longitude_1 FROM customers_service, customers WHERE customers_service.customers_id = customers.id AND customers.active = 1 AND customers.member_id = ' . $this->Member_id)->result_array(false);

        echo json_encode($service);

        die();
    }

    // update latitude and longitude for service
    public function updateService()
    {
        $idService = $_POST['idService'];
        $latlng = $_POST['latlng'];

        $this->db->update('customers_service', array(
            'latitude_1' => $latlng['lat'],
            'longitude_1' => $latlng['lng']
        ), array('service_id' => $idService));

        die();
    }

    // get all route with action (the info want get)
    public function getAllRoute()
    {
        $idSet = $_POST['idSet'];
        $action = $_POST['action'];

        $route_zone = array();
        $routes = $this->db->query('SELECT * FROM routes WHERE route_set = ' . $idSet . ' AND route_id_member = ' . $this->Member_id . ' ORDER BY route_order ASC')->result_array(false);
        for ($i = 0; $i < count($routes); $i++) {
            $result = $this->db->query('SELECT * FROM route_map WHERE route_id = ' . $routes[$i]['route_id'] . ' AND map_id_member = ' . $this->Member_id)->result_array(false);
            if ($result)
                array_push($route_zone, $result);
        }

        if ($action == 'zone') {
            $send = array(
                'route_zone' => $route_zone
            );
        } else {
            $send = array(
                'routes' => $routes,
            );
        }

        echo json_encode($send);

        die();
    }

    // update service route if match zone or zip of route
    public function updateServiceRoute()
    {
        $idService = $_POST['idService'];
        $idRoute = $_POST['idRoute'];

        $this->db->update('customers_service', array(
            'service_route' => $idRoute
        ), array('service_id' => $idService));

        die();
    }

    // update service in zone
    public function updateZoneService()
    {
        $idService = $_POST['idService'];
        $idMap = $_POST['idMap'];
        $query = 'UPDATE route_map SET service_in = IFNULL("' . $idService . ',",CONCAT("' . $idService . ',",service_in)) WHERE map_id = ' . $idMap;
        // echo $query;die();
        $this->db->query($query);

        die();
    }

    // set all zone with service = null
    public function setServiceInNull()
    {
        $this->db->query('UPDATE route_map SET service_in = null WHERE map_id_member = ' . $this->Member_id);

        die();
    }

    public function getRouteOfActiveSet(){
        $route_zone = array();
        $routes = $this->db->query('SELECT * FROM routes,set_route WHERE route_set = id AND active = 1 AND route_id_member = ' . $this->Member_id . ' ORDER BY route_order ASC')->result_array(false);
        for ($i = 0; $i < count($routes); $i++) {
            $result = $this->db->query('SELECT * FROM route_map WHERE route_id = ' . $routes[$i]['route_id'] . ' AND map_id_member = ' . $this->Member_id)->result_array(false);
            if ($result)
                array_push($route_zone, $result);
        }

        $array = array(
            'zone' => $route_zone,
            'route' => $routes
        );

        echo json_encode($array);

        die();
    }

    public function test()
    {
//        $details_url = 'bash> php -f ' . url::base() . 'application/views/routes/background.php';

//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $details_url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        $response = json_decode(curl_exec($ch), true);

//        $this->db->query('UPDATE route_map SET service_in = ""');
        echo 'Da Fuck!';
        die();
    }

    function runBackend()
    {
        require Kohana::find_file('views/routes','background');
        die();
    }

    // get latlong example
    public function lookup()
    {

        $string = $_POST['address'];

        $string = str_replace(" ", "+", urlencode($string));
        $details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $string . "&sensor=false";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $details_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);

        // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
        if ($response['status'] != 'OK') {
            echo 'none';
            die();
        }

        $geometry = $response['results'][0]['geometry'];

        $array = array(
            'latitude' => $geometry['location']['lat'],
            'longitude' => $geometry['location']['lng']
        );

        echo json_encode($array);
        die();
    }

    public function updatePriority()
    {
        $idRoute = $_POST['idRoute'];
        $index = $_POST['order'];

        $this->db->update('routes', array(
            'route_order' => $index
        ), array('route_id' => $idRoute));

        die();
    }

    public function exportSet()
    {
        $idSet = $_POST['idSet'];

        $routes = $this->db->query('SELECT * FROM routes WHERE route_set = ' . $idSet . ' AND route_id_member = ' . $this->Member_id)->result_array(false);
        $set = $this->db->query('SELECT * FROM set_route WHERE id = ' . $idSet . ' AND member_id = ' . $this->Member_id)->result_array(false)[0];

        $this->printPDF($routes, $set);
    }

    function printPDF($routes, $set)
    {
        require_once Kohana::find_file('vendor/mPdf/vendor', 'autoload');
        $date = new DateTime('', new DateTimeZone('Asia/Bangkok'));

        $this->template = new View('routes/pdf/content');
        $this->template->routes = $routes;
        $this->template->header = new View('routes/pdf/header');
        $this->template->header->set = $set;
        $this->template->footer = new View('routes/pdf/footer');

        $mpdf = new mPDF('', '', '', '', 10, 10, 50, 10, 5, 5);
        $mpdf->mirrorMargins = 0;

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->SetTitle('Demo');
        $mpdf->SetHTMLHeader($this->template->header);
        $mpdf->WriteHTML($this->template);
        $mpdf->SetHTMLFooter($this->template->footer);

        $name = $date->format('H_i_s') . '.pdf';
        $mpdf->Output($name, 'D');
    }

    public function exportServiceCSV()
    {
        $idRoute = $_POST['idRoute'];
        $active = $_POST['active'];

        $query = 'SELECT cus.customer_name, cus.customer_no, cussv.service_name, tec.name as `tec_name`, CONCAT(cussv.service_address_1, " ", cussv.service_city, " ", cussv.service_state, " ", cussv.service_zip) as `Address`, prop.name as `prop_name`, typ.name as `type_name`, sch.frequency 
				  FROM pco.customers as cus, pco.customers_service as cussv, pco.customers_service_scheduling as sch, pco._technician as tec, pco._property as prop, pco._service_type as typ
				  WHERE cus.id = cussv.customers_id AND cussv.service_id = sch.service_id AND IF(sch.technician = "", 1,sch.technician) = tec.id AND IF(cussv.service_property = "", 1, cussv.service_property) = prop.id AND IF(cussv.service_service_type = "", 1, cussv.service_service_type) = typ.id';
        $query .= ' AND cussv.service_route = ' . $idRoute;
        $query .= ' AND cus.active = ' . (($active == 'Active') ? 1 : 0);
        $query .= ' AND cus.member_id = ' . $this->Member_id . ' AND cussv.member_id = ' . $this->Member_id . ' AND sch.member_id = ' . $this->Member_id;

        $result = $this->db->query($query)->result_array(false);

        $this->exportCSV($result);

    }

    function exportCSV($data)
    {

        require Kohana::find_file('vendor/PHPExcleReader', 'PHPExcel');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()
            ->setTitle("Code Book")
            ->setCategory("Code Book");
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '#')
            ->setCellValue('B1', 'Customer Name')
            ->setCellValue('C1', 'Customer No')
            ->setCellValue('D1', 'Service Name')
            ->setCellValue('E1', 'Technician Name')
            ->setCellValue('F1', 'Service Address')
            ->setCellValue('G1', 'Service Property')
            ->setCellValue('H1', 'Service Type')
            ->setCellValue('I1', 'Service Frequency')
            ->setCellValue('J1', 'Next Day');
        $i = 1;
        foreach ($data as $value) {
            $i++;
            $objPHPExcel->getActiveSheet()->setCellValue("A" . $i, $i - 1);
            $objPHPExcel->getActiveSheet()->setCellValue("B" . $i, $value['customer_name']);
            $objPHPExcel->getActiveSheet()->setCellValue("C" . $i, $value['customer_no']);
            $objPHPExcel->getActiveSheet()->setCellValue("D" . $i, $value['service_name']);
            $objPHPExcel->getActiveSheet()->setCellValue("E" . $i, $value['tec_name']);
            $objPHPExcel->getActiveSheet()->setCellValue("F" . $i, $value['Address']);
            $objPHPExcel->getActiveSheet()->setCellValue("G" . $i, $value['prop_name']);
            $objPHPExcel->getActiveSheet()->setCellValue("H" . $i, $value['type_name']);
            $objPHPExcel->getActiveSheet()->setCellValue("I" . $i, $value['frequency']);
            $objPHPExcel->getActiveSheet()->setCellValue("J" . $i, 'Not Defined');
        }


        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="List_Description_' . date("d/M/y") . '.txt"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter->save('php://output');
//        $objWriter->save(md5(date("H_i_s")) . '.txt');
        die();
    }

    public function getTechnicianHtml()
    {
        $technician = $this->db->query('SELECT * FROM _technician WHERE member_id = ' . $this->Member_id)->result_array(false);

        $this->template = new View('routes/technician');
        $this->template->technician = $technician;
        $this->template->render(true);

        die();
    }

    public function LoadTechnician()
    {
        $query = 'SELECT * FROM _technician WHERE member_id = ' . $this->Member_id;
        $total = count($this->db->query($query)->result_array(false));

        // echo $total;die();

        ini_set('memory_limit', '-1');
        $_data = array();
        $iSearch = @$_POST['search'];
        $_isSearch = ($iSearch == '') ? false : true;
        $iDisplayLength = (int)@($_POST['length']);
        $iDisplayStart = (int)@($_POST['start']);
        // $sEcho            = (int)@($_POST['draw']);
        $total_items = $total;
        $total_filter = $total_items;

        if ($_isSearch)
            $query .= ' AND name LIKE "%' . $iSearch . '%"';

        // Limit
        $query_limit = ' LIMIT ' . $iDisplayStart . ', ' . $iDisplayLength;

        $filter = $this->db->query($query)->result_array(false);
        $result = $this->db->query($query . $query_limit)->result_array(false);

        $total_filter = count($filter);

        // echo $this->getZIPString(1); die();
        // print_r($filter);die();

        foreach ($result as $key => $value) {
            $_data[] = array(
                'tdID' => $value['id'],
                'tdName' => $value['name'],
                'tdColor' => $value['color'],
                'tdService' => $this->serviceOfTechnician($value['id'])
            );
        }

        $records = array();
        $records["data"] = $_data;
        // $records["draw"]             = $sEcho;
        $records["recordsTotal"] = $total_items;
        $records["recordsFiltered"] = $total_filter;
        $records["_isSearch"] = $_isSearch;
        echo json_encode($records);
        die();
    }

    function serviceOfTechnician($idTech)
    {
        $query = 'SELECT * 
                  FROM customers_service as cusSV,customers_service_scheduling as cusSV_schedule, customers as cus 
                  WHERE cusSV_schedule.service_id = cusSV.service_id
                  AND cusSV.customers_id = cus.id
                  AND cus.active = 1
                  AND technician = ' . $idTech . '
	              AND cusSV.member_id = ' . $this->Member_id;

        $result = $this->db->query($query)->result_array(false);

        return count($result);
    }

    //other function
    private function getCountServiceIn($idRoute)
    {
        $total_service = $this->db->query('SELECT service_in FROM route_map WHERE route_id = ' . $idRoute . ' AND map_id_member = ' . $this->Member_id)->result_array(false);
        $service = [];
        foreach ($total_service as $value) {
            if ($value['service_in'] != '') {
                $string = chop($value['service_in'], ',');
                $array = explode(',', $string);
                for ($i = 0; $i < count($array); $i++) {
                    if (!in_array($array[$i], $service))
                        array_push($service, $array[$i]);
                }
            }
        }
        return count($service);
    }

    private function getService($route_id, $active = 0, $step = 0)
    {
        $filter = $this->db->query('SELECT * FROM customers_service AS cs,customers AS c WHERE cs.customers_id = c.id AND c.active = ' . $active . ' AND cs.service_route =' . $route_id . ' AND c.member_id = ' . $this->Member_id)->result_array(false);
        if ($step == 0)
            return count($filter);
        else
            return $filter;
    }

    private function getZIPString($route_id)
    {
        $filter = $this->db->query('SELECT * FROM customers_service WHERE service_route = ' . $route_id . ' AND member_id = ' . $this->Member_id)->result_array(false);
        $str = '';

        foreach ($filter as $key => $value) {
            $str .= $value['service_zip'] . ', ';
        }

        return $str;
    }

    private function getTechnicianName($technician_id)
    {
        $filter = $this->db->query('SELECT * FROM _technician WHERE id = ' . $technician_id . ' AND member_id = ' . $this->Member_id)->result_array(false);

        foreach ($filter as $key => $value) {
            return $value['name'];
        }
    }

    private function getMap($route_id, $step = 0)
    {
        $filter = $this->db->query('SELECT * FROM route_map WHERE route_id = ' . $route_id . ' AND map_id_member = ' . $this->Member_id)->result_array(false);
        if ($step == 0) {
            return count($filter);
        } else {
            return $filter;
        }
    }
}