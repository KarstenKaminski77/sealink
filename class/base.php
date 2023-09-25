<?php

session_start();


class Base {

    private $host;
    private $user;
    private $password;
    private $database;
    private $table;
    private $form_fields;
    private $where_clause;
    
    private $area_id;
    private $site_id;
    private $company_id;
    private $name;
    private $first_name;
    private $address;
    private $telephone;
    private $cell;
    private $email;
    private $engineer_id;
    private $distance;
    private $vat;
    private $areas;
    private $engineers;
    private $companies;
    private $sites;
    private $filter_company_id;
    private $rates;
    private $filter_site_id;
    private $delete_id;
    private $rate_id;
    private $filter_rate_id;
    private $rate;
    private $description;
    private $technician_id;
    private $filter_technician_id;
    private $technician;
    private $technicians;
    private $username;
    
    protected $page_no;
    protected $total_records;
    protected $total_records_per_page;
    protected $offset;
    protected $adjacents;
    protected $next_page;
    protected $previous_page;
    protected $total_no_of_pages;
    protected $second_last;
    protected $where;

    public $conn;
    public $table_columns;
    public $html;
    public $total;
    public $types;
    public $params;
    public $params_where;
    public $areas_dd;
    public $engineers_dd;
    public $logout_link;
    public $protocol;
    public $generic_areas_dd;
    public $companies_dd;
    public $list;
    public $alert_icon;
    public $alert_message;
    public $paginator;
    public $filter_companies;
    public $filter_sites;
    public $filter_rates;
    public $filter_technicians;
    public $row;

    function __construct() {

        $this->host = 'sql15.jnb1.host-h.net';
        $this->user = 'kwdaco_333';
        $this->password = 'SBbB38c8Qh8';
        $this->database = 'seavest_db333';


        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database);
        $this->conn->set_charset("utf8");

        if ($this->conn->connect_errno) {

            printf("Connect failed: %s\n", $this->conn->connect_error);

            exit();
        }
    }
    
    public function setTechnicianId($technician_id){
        
        if(!empty($technician_id)){
            
            $this->technician_id = (int) $technician_id;
        }
    }
    
    public function setUsername($username){
        
        if(!empty($username)){
            
            $this->form_fields['Username'] = $username;
            $this->username = $username;
        }
    }
    
    public function setPassword($password){
        
        if(!empty($password)){
            
            $this->form_fields['Password'] = $password;
            $this->password = $password;
        }
    }
    
    public function setCompanyId($company_id){
        
        if(!empty($company_id)){
            
            $this->company_id = (int) $company_id;
        }
    }
    
    public function setRate($rate){
        
        if(!empty($rate)){
            
            $this->form_fields['Rate'] = $rate;
            $this->rate = $rate;
        }
    }
    
    public function setDescription($description){
        
        if(!empty($description)){
            
            $this->form_fields['Description'] = $description;
            $this->description = addslashes($description);
        }
    }
    
    public function setRateId($rate_id){
        
        if(!empty($rate_id)){
            
            $this->rate_id = (int) $rate_id;
        }
    }
    
    public function setDeleteId($delete_id){
        
        if(!empty($delete_id)){
            
            $this->delete_id = $delete_id;
        }
    }
    
    public function setFilterRateId($rate_id){
        
        if(!empty($rate_id)){
            
            $this->filter_rate_id = $rate_id;
        }
    }
    
    public function setFilterTechnicianId($technician_id){

        if(!empty($technician_id)){
            
            $this->filter_technician_id = $technician_id;
        }
    }
    
    public function setFilterCompanyId($company_id){
        
        if(!empty($company_id)){
            
            $this->filter_company_id = (int) $company_id;
        }
    }
    
    public function setFilterSiteId($site_id){
        
        if(!empty($site_id)){
            
            $this->filter_site_id = (int) $site_id;
        }
    }
    
    public function setPageNo($page_no){

        if(isset($page_no) && !empty($page_no)){

            $this->page_no = $this->sanitise($page_no);

        } else {

            $this->page_no = 1;
        }
    }

    public function setTotalRecordsPerPage($total_records_per_page){

        if(!empty($total_records_per_page)){

            $this->total_records_per_page = $this->sanitise($total_records_per_page);
        }
    }

    public function setTotalRecords($sql){

        if(!empty($sql)){

            $res = $this->dbQuery($sql);
            $this->total_records = $res->num_rows;
        }
    }

    protected function setTotalRecordsWhere($where){

        if(!empty($where)){

            $this->where = $this->sanitise($where);
        }
    }

    protected function getTotalRecords(){

        $this->sql = "
            SELECT
                COUNT(Id) as total
            FROM
                $this->table
        ";

        if(isset($this->where)){

            $this->sql .= "
                WHERE
                    $this->where
                ";
        }

        if($res = $this->dbQuery($this->sql)){

            $row = $res->fetch_assoc();

            $this->total_records = $row['total'];
        }
    }

    public function setSiteId($site_id){
        
        if($site_id > 0){
            
            $this->site_id = (int) $site_id;
        }
    }
    
    public function setAreaId($area_id){
        
        if(!empty($area_id)){
            
            $this->form_fields['AreaId'] = $area_id;
            $this->area_id = (int) $area_id;
        }
    }
    
    public function setCompany($company_id){
        
        if(!empty($company_id)){
            
            $this->form_fields['Company'] = $company_id;
            $this->company_id = $company_id;
        }
    }
    
    public function setRatesCompany($company_id){
        
        if(!empty($company_id)){
            
            $this->form_fields['CompanyId'] = $company_id;
            $this->company_id = $company_id;
        }
    }
    
    public function setName($name){
        
        if(!empty($name)){
            
            $this->form_fields['Name'] = $name;
            $this->name = $name;
        }
    }
    
    public function setFirstName($first_name){
        
        if(!empty($first_name)){
            
            $this->form_fields['FirstName'] = $first_name;
            $this->first_name = $first_name;
        }
    }
    
    public function setAddress($address){
        
        if(!empty($address)){
            
            $this->form_fields['Address'] = $address;
            $this->address = $address;
        }
    }
    
    public function setTelephone($telephone){
        
        if(!empty($telephone)){
            
            $this->form_fields['Telephone'] = $telephone;
            $this->telephone = $telephone;
        }
    }
    
    public function setCell($cell){
        
        if(!empty($cell)){
            
            $this->form_fields['Cell'] = $cell;
            $this->cell = $cell;
        }
    }
    
    public function setFax($fax){
        
        if(!empty($fax)){
            
            $this->form_fields['fax'] = $fax;
        }
    }
    
    public function setEmail($email){
        
        if(!empty($email)){
            
            $this->form_fields['Email'] = $email;
            $this->email = $email;
        }
    }
    
    public function setEngineerId($engineer_id){
        
        if(!empty($engineer_id)){
            
            $this->form_fields['EngineerId'] = (int) $engineer_id;
            $this->engineer_id = (int) $engineer_id;
        }
    }
    
    public function setVat($vat){
        
        if(!empty($vat)){
            
            $this->form_fields['VAT'] = $vat;
            $this->vat = $vat;
        }
    }
    
    public function setDistance($distance){
        
        if(!empty($distance)){
            
            $this->form_fields['Distance'] = $distance;
            $this->distance = $distance;
        }
    }
    
    private function getRates(){
        
        $this->rates = [];
        
        $sql = "
            SELECT
                Id,
                Name,
                Rate
            FROM
                tbl_rates
            GROUP BY
                Name
            ORDER BY
                Name ASC
        ";
        
        $res = $this->preparedStatementQuery($sql);
        while($row = $res->fetch_assoc()){
            
            $this->rates[] = $row;
        }
    }
    
    private function getFilterRatesDd(){
        
        $this->getRates();
        
        $this->filter_rates = '';
        
        $this->filter_rates .= '<select name="rate_id" class="form-control">';
        $this->filter_rates .= '    <option value="">---</option>';
        
        foreach($this->rates as $rate){
            
            $this->filter_rates .= '<option value="'. $rate['Name'] .'">'. $rate['Name'] .'</option>';
        }
        
        $this->filter_rates .= '</select>';
    }
    
    private function getTechnicians(){
        
        $this->technicians = [];
        
        $sql = "
            SELECT
                Id,
                Name
            FROM
                tbl_technicians
            GROUP BY
                Name
            ORDER BY
                Name ASC
        ";
        
        $res = $this->preparedStatementQuery($sql);
        while($row = $res->fetch_assoc()){
            
            $this->technicians[] = $row;
        }
    }
    
    private function getFilterTechniciansDd(){
        
        $this->getTechnicians();
        
        $this->filter_technicians = '';
        
        $this->filter_technicians .= '<select name="technician_id" class="form-control">';
        $this->filter_technicians .= '    <option value="">---</option>';
        
        foreach($this->technicians as $technician){
            
            $this->filter_technicians .= '<option value="'. $technician['Name'] .'">'. $technician['Name'] .'</option>';
        }
        
        $this->filter_technicians .= '</select>';
    }
    
    private function getAreas(){
        
        $this->areas = [];
        
        $sql = "
            SELECT
                Id,
                Area
            FROM
                tbl_areas
            ORDER BY
                Area ASC
        ";
        
        $res = $this->preparedStatementQuery($sql);
        while($row = $res->fetch_assoc()){
            
            $this->areas[] = $row;
        }
    }
    
    public function getAreasDd(){
        
        $this->getAreas();
        $this->getSite();
        
        $this->areas_dd = '';
        
        $this->areas_dd .= '<select name="area-id" class="tarea-100" required>';
        $this->areas_dd .= '    <option value="">---</option>';
        
        foreach($this->areas as $area){
            
            $selected = '';
            
            if(isset($this->site_id) || isset($this->technician_id)){
                
                if($area['Id'] == $this->row['AreaId']){
                    
                    $selected = 'selected';
                }
            }
            
            $this->areas_dd .= '<option value="'. $area['Id'] .'" '. $selected .'>'. $area['Area'] .'</option>';
        }
        
        $this->areas_dd .= '</select>';
    }
    
    private function getEngineers(){
        
        $this->engineers = [];
        
        $sql = "
            SELECT
                Id,
                CompanyId,
                Name,
                Email
            FROM
                tbl_engineers
            ORDER BY
                Name ASC
        ";
        
        $res = $this->preparedStatementQuery($sql);
        while($row = $res->fetch_assoc()){
            
            $this->engineers[] = $row;
        }
    }
    
    public function getEngineersDd(){
        
        $this->getEngineers();
        $this->getSite();
        
        $this->engineers_dd = '';
        
        $this->engineers_dd .= '<select name="engineer-id" class="tarea-100" required>';
        $this->engineers_dd .= '    <option value="">---</option>';
        
        foreach($this->engineers as $engineer){
            
            $selected = '';
            
            if(isset($this->site_id)){
                
                if($engineer['Id'] == $this->row['EngineerId']){
                    
                    $selected = 'selected';
                }
            }
            
            $this->engineers_dd .= '<option value="'. $engineer['Id'] .'" '. $selected .'>'. $engineer['Name'] .'</option>';
        }
        
        $this->engineers_dd .= '</select>';
    }
    
    private function getSites(){
        
        $this->sites = [];
        
        $sql = "
            SELECT
                Id,
                Name
            FROM
                tbl_sites
            WHERE
                Name != ''
            ORDER BY
                Name ASC
        ";
        
        $res = $this->preparedStatementQuery($sql);
        while($row = $res->fetch_assoc()){
            
            $this->sites[] = $row;
        }
    }
    
    public function getFilterSitesDd(){
        
        $this->getSites();
        
        $this->filter_sites = '';
        
        $this->filter_sites .= '<select name="site_id" class="form-control">';
        $this->filter_sites .= '    <option value="">---</option>';
        
        foreach($this->sites as $site){
            
            $this->filter_sites .= '<option value="'. $site['Id'] .'">'. $site['Name'] .'</option>';
        }
        
        $this->filter_sites .= '</select>';
    }
    
    private function getCompanies(){
        
        $this->companies = [];
        
        $sql = "
            SELECT
                Id,
                Name
            FROM
                tbl_companies
            ORDER BY
                Name ASC
        ";
        
        $res = $this->preparedStatementQuery($sql);
        while($row = $res->fetch_assoc()){
            
            $this->companies[] = $row;
        }
    }
    
    public function getCompaniesDd(){
        
        $this->getCompanies();
        
        $this->companies_dd = '';
        
        $this->companies_dd .= '<select name="company" class="tarea-100" required>';
        $this->companies_dd .= '    <option value="">---</option>';
        
        foreach($this->companies as $company){
            
            $selected = '';
            
            if(isset($this->site_id) || isset($this->rate_id)){
                
                if($company['Id'] == $this->company_id){
                    
                    $selected = 'selected';
                }
            }
            
            $this->companies_dd .= '<option value="'. $company['Id'] .'" '. $selected .'>'. $company['Name'] .'</option>';
        }
        
        $this->companies_dd .= '</select>';
    }
    
    private function getFilterCompaniesDd(){
        
        $this->getCompanies();
        
        $this->filter_companies = '';
        
        $this->filter_companies .= '<select name="company_id" class="form-control">';
        $this->filter_companies .= '    <option value="">---</option>';
        
        foreach($this->companies as $company){
            
            $this->filter_companies .= '<option value="'. $company['Id'] .'">'. $company['Name'] .'</option>';
        }
        
        $this->filter_companies .= '</select>';
    }
    
    public function filter(){
        
        $this->getFilterCompaniesDd();
        $this->getFilterSitesDd();
        $this->getFilterRatesDd();
        $this->getFilterTechniciansDd();
    }
    
    private function createSite(){
        
        $this->table = 'tbl_sites';
        $this->preparedStatementInsert();
        
        $alert  = '<div id="banner-success">';
        $alert .= ' New site successfully created';
        $alert .= '</div>';
        
        $_SESSION['alert'] = $alert;
    }
    
    private function UpdateSite(){
        
        $this->types_where = 'i';
        $this->params_where = [];
        $this->params_where[] = $this->site_id;
        $this->where_clause = "Id = ?";
        
        $this->table = 'tbl_sites';
        $this->preparedStatementUpdate();
        
        $alert  = '<div id="banner-success">';
        $alert .= ' <b><i>'. $this->form_fields['Name'] .'</i></b> was successfully updated';
        $alert .= '</div>';
        
        $_SESSION['alert'] = $alert;
    }
    
    public function saveSite(){
        
        if(count($this->form_fields) > 0){
        
            if(empty($this->site_id) && empty($this->delete_id)){

                $this->createSite();

            }

            if(!empty($this->site_id)){

                $this->UpdateSite();
            }
        }
        
        if(!empty($this->delete_id)){
            
            $this->deleteSite();
        }
        
    }
    
    private function deleteSite(){
        
        if(!empty($this->delete_id)){
            
            $sql = "
                DELETE
                    FROM
                        tbl_sites
                    WHERE
                        Id = $this->delete_id
            ";

            $this->preparedStatementQuery($sql);
            
            $alert  = '<div id="banner-success">';
            $alert .= ' Site successfully deleted';
            $alert .= '</div>';

            $_SESSION['alert'] = $alert;
        }
    }
    
    private function createRate(){
        
        $this->table = 'tbl_rates';
        $this->preparedStatementInsert();
        
        $alert  = '<div id="banner-success">';
        $alert .= ' New Rate successfully created';
        $alert .= '</div>';
        
        $_SESSION['alert'] = $alert;
    }
    
    private function UpdateRate(){
        
        $this->types_where = 'i';
        $this->params_where = [];
        $this->params_where[] = $this->rate_id;
        $this->where_clause = "Id = ?";
        
        $this->table = 'tbl_rates';
        $this->preparedStatementUpdate();
        
        $alert  = '<div id="banner-success">';
        $alert .= ' <b><i>'. $this->form_fields['Name'] .'</i></b> was successfully updated';
        $alert .= '</div>';
        
        $_SESSION['alert'] = $alert;
    }
    
    public function saveRate(){
        
        unset($this->form_fields['Company']);
        
        if(count($this->form_fields) > 0){
        
            if(empty($this->rate_id) && empty($this->delete_id)){

                $this->createRate();

            }

            if(!empty($this->rate_id)){

                $this->UpdateRate();
            }
        }
        
        if(!empty($this->delete_id)){
            
            $this->deleteRate();
        }
        
    }
    
    private function deleteRate(){
        
        if(!empty($this->delete_id)){
            
            $sql = "
                DELETE
                    FROM
                        tbl_rates
                    WHERE
                        Id = $this->delete_id
            ";

            $this->preparedStatementQuery($sql);
            
            $alert  = '<div id="banner-success">';
            $alert .= ' Rate successfully deleted';
            $alert .= '</div>';

            $_SESSION['alert'] = $alert;
        }
    }
    
    private function createTechnician(){
        
        $this->table = 'tbl_technicians';
        $this->preparedStatementInsert();
        
        $alert  = '<div id="banner-success">';
        $alert .= ' New Technician successfully created';
        $alert .= '</div>';
        
        $_SESSION['alert'] = $alert;
    }
    
    private function UpdateTechnician(){
        
        $this->types_where = 'i';
        $this->params_where = [];
        $this->params_where[] = $this->technician_id;
        $this->where_clause = "Id = ?";
        
        $this->table = 'tbl_technicians';
        $this->preparedStatementUpdate();
        
        $alert  = '<div id="banner-success">';
        $alert .= ' <b><i>'. $this->form_fields['Name'] .'</i></b> was successfully updated';
        $alert .= '</div>';
        
        $_SESSION['alert'] = $alert;
    }
    
    public function saveTecnician(){
        
        if(count($this->form_fields) > 0){
        
            if(empty($this->technician_id) && empty($this->delete_id)){

                $this->createTechnician();

            }

            if(!empty($this->technician_id)){

                $this->UpdateTechnician();
            }
        }
        
        if(!empty($this->delete_id)){
            
            $this->deleteTechnician();
        }
        
    }
    
    private function deleteTechnician(){
        
        if(!empty($this->delete_id)){
            
            $sql = "
                DELETE
                    FROM
                        tbl_technicians
                    WHERE
                        Id = $this->delete_id
            ";

            $this->preparedStatementQuery($sql);
            
            $alert  = '<div id="banner-success">';
            $alert .= ' Technician successfully deleted';
            $alert .= '</div>';

            $_SESSION['alert'] = $alert;
        }
    }
    
    public function getSite(){
        
        if(isset($this->site_id)){
        
            $this->types = 'i';
            $this->params = [];
            $this->params[] = $this->site_id;

            $sql = "
                SELECT
                    s.AreaId,
                    s.Company,
                    s.EngineerId,
                    s.VAT     
                FROM
                    tbl_sites s
                WHERE
                    Id = ?
            ";

            $res = $this->preparedStatementQuery($sql);
            $this->row = $res->fetch_assoc();
        }
    }
    
    public function getRate(){
        
        if(isset($this->rate_id)){
        
            $this->types = 'i';
            $this->params = [];
            $this->params[] = $this->rate_id;

            $sql = "
                SELECT
                    r.CompanyId,
                    r.Name,
                    r.Rate,
                    r.Description    
                FROM
                    tbl_rates r
                WHERE
                    Id = ?
            ";

            $res = $this->preparedStatementQuery($sql);
            $this->row = $res->fetch_assoc();
        }
    }
    
    public function getTechnician(){
        
        if(isset($this->technician_id)){
        
            $this->types = 'i';
            $this->params = [];
            $this->params[] = $this->technician_id;

            $sql = "
                SELECT
                    t.AreaId,
                    t.Name,
                    t.Username,
                    t.Password,
                    t.Email,
                    t.Cell,
                    a.Area
                FROM
                    tbl_technicians t
                    JOIN tbl_areas a ON t.AreaId = a.Id
                WHERE
                    t.Id = ?
            ";

            $res = $this->preparedStatementQuery($sql);
            $this->row = $res->fetch_assoc();
        }
    }
    
    public function getSitesList(){
        
        $this->table = 'tbl_sites';
        $this->getTotalRecords();
        $this->paginationInit();
        $this->getProtocol();
        
        $this->list = [];
        
        $sql = "
            SELECT
                s.Id,
                s.Name as site_name,
                s.Address,
                c.Name as company_name     
            FROM
                tbl_sites s
                JOIN tbl_companies c ON s.Company = c.Id
        ";
        
        if(isset($this->filter_company_id) || isset($this->filter_site_id)){
            
            $sql .= "WHERE ";
            
            if(isset($this->filter_company_id)){
                
                $sql .= "s.Company = $this->filter_company_id";
            }
            
            if(isset($this->filter_company_id) && isset($this->filter_site_id)){
                
                $sql .= " AND ";
            }
            
            if(isset($this->filter_site_id)){
                
                $sql .= "s.Id = $this->filter_site_id";
            }
        }
        
        $sql .= "
            ORDER BY
                s.Id ASC
        ";
        
        if (isset($this->offset)) {

            $sql .= " LIMIT $this->total_records_per_page OFFSET $this->offset";
        }
   
        $res = $this->preparedStatementQuery($sql);
        while($row = $res->fetch_assoc()){
            
            $this->list[] = $row;
        }
        
        $this->getPaginator();
    }
    
    public function getRatesList(){
        
        $this->table = 'tbl_rates';
        $this->getTotalRecords();
        $this->paginationInit();
        $this->getProtocol();
        
        $this->list = [];
        
        $sql = "
            SELECT
                r.Id,
                r.Name,
                r.Rate,
                c.Name as company_name
            FROM
                tbl_rates r
                JOIN tbl_companies c ON r.CompanyId = c.Id
        ";
        
        if(isset($this->filter_rate_id)){
            
            $sql .= "WHERE r.Name = '$this->filter_rate_id'";
        }
        
        $sql .= "
            ORDER BY
                r.Id ASC
        ";
        
        if (isset($this->offset)) {

            $sql .= " LIMIT $this->total_records_per_page OFFSET $this->offset";
        }
   
        $res = $this->preparedStatementQuery($sql);
        while($row = $res->fetch_assoc()){
            
            $this->list[] = $row;
        }
        
        $this->getPaginator();
    }
    
    public function getTechnicianList(){
        
        $this->table = 'tbl_technicians';
        $this->getTotalRecords();
        $this->paginationInit();
        $this->getProtocol();
        
        $this->list = [];
        
        $sql = "
            SELECT
                t.Id,
                t.Name,
                t.Username,
                t.Password,
                t.Email,
                t.Cell,
                a.Area
            FROM
                tbl_technicians t
                JOIN tbl_areas a ON t.AreaId = a.Id
        ";
        
        if(isset($this->filter_technician_id)){
            
            $sql .= "WHERE t.Name = '$this->filter_technician_id'";
        }
        
        $sql .= "
            ORDER BY
                t.Id ASC
        ";
        
        if (isset($this->offset)) {

            $sql .= " LIMIT $this->total_records_per_page OFFSET $this->offset";
        }

        $res = $this->preparedStatementQuery($sql);
        while($row = $res->fetch_assoc()){
            
            $this->list[] = $row;
        }
        
        $this->getPaginator();
    }
    
    public function logout_link(){

	$this->getProtocol();
        
	$this->logout_link = '<a class="close" href="'. $this->protocol . trim($_SERVER['HTTP_HOST'], '/') .'?Logout" title="Logout"></a>';
    }
    
    public function getProtocol(){

        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'){
            // this is HTTPS
            $this->protocol  = "https://";
        } else {
            // this is HTTP
            $this->protocol  = "http://";
        }
    }
    
    public function preparedStatementQuery($sql){

        $this->statement = $this->conn->prepare($sql);

        if ($this->statement === false){

            die('prepare() failed: ' . htmlspecialchars($this->conn->error));
        }

        if(!empty($this->types) && !empty($this->params) && count($this->params) > 0){

            $bind_names[] = $this->types;

            for ($i=0;$i<count($this->params);$i++){

                $bind_name = 'bind' . $i;
                $$bind_name = $this->params[$i];
                $bind_names[] = &$$bind_name;
            }

            call_user_func_array(array($this->statement,'bind_param'),$bind_names);
        }

        if($this->statement->execute()){

            $this->res = $this->statement->get_result();

            $this->is_error = false;
            $this->db_response = true;

        } else {

            $this->is_error = true;
            $this->db_response = false;
            echo '<pre>', var_dump($this->types), '</pre>';
            echo '<pre>', var_dump($this->params), '</pre>';
            echo '<pre>', var_dump($sql), '</pre>';
            file_put_contents('/var/www/html/admin/email.log', date('Y-m-d H:i:s') . htmlspecialchars($this->statement->error) ."\n", FILE_APPEND | LOCK_EX);
            die('execute() failed: ' . htmlspecialchars($this->statement->error));
        }

        $this->statement->close();

        unset($this->types);
        unset($this->params);

        return $this->res;
    }

    protected function preparedStatementUpdate(){

        $sql = "UPDATE ".$this->table." SET ";

        // loop and build the columns
        $sets = [];
        $this->types = '';
        $this->params = [];

        foreach($this->form_fields as $column => $value){

             if(!empty($value) || $value == 0){

                 if(is_int($value)){

                     $this->types .= 'i';

                 } else {

                     $this->types .= 's';
                 }

                 $this->params[] = $value;
                 $sets[] = "`$column` = ?";
             }
        }

        // Glue them together
        $sql .= implode(', ', $sets);

         if(!empty($this->where_clause)){

             $this->types .= $this->types_where;

             foreach($this->params_where as $params_where){

                 $this->params[] = $params_where;
             }

            // check to see if the 'where' keyword exists
            if(substr(strtoupper(trim($this->where_clause)), 0, 5) != 'WHERE'){

                // Not found, add key word
                $sql .= " WHERE $this->where_clause";

            } else {

                $sql .= " $this->where_clause";
            }
        }
  
        $this->preparedStatementQuery($sql);
    }

    protected function preparedStatementInsert(){

        $sql = "UPDATE ".$this->table." SET ";

        // loop and build the columns
        $sets = [];
        $this->types = '';
        $this->params = [];
        $this->placeholder = [];

        if($this->form_fields >= 1){

            // retrieve the keys of the array (column titles)
            $fields = array_keys($this->form_fields);

            foreach($this->form_fields as $value){

                if(!empty($value) || $value == 0){

                    if(is_int($value)){

                        $this->types .= 'i';

                    } else {

                        $this->types .= 's';
                    }

                    $this->placeholder[] = '?';
                    $this->params[] = $value;
                }
            }


            $sql = "
                INSERT INTO
                    $this->table
                        (`" . implode('`,`', $fields) . "`)
                VALUES (". implode(",", $this->placeholder) . ")
            ";

            $this->preparedStatementQuery($sql);
        }
    }
    
    public function preparedStatementDelete(){

        // build the query
        $sql = "DELETE FROM ".$this->table;

        // check for where clause
        if(!empty($this->where_clause)){

             foreach($this->params_where as $params_where){

                 $this->params[] = $params_where;
             }

            // check to see if the 'where' keyword exists
            if(substr(strtoupper(trim($this->where_clause)), 0, 5) != 'WHERE'){

                // Not found, add key word
                $sql .= " WHERE $this->where_clause";

            } else {

                $sql .= " $this->where_clause";
            }
        }

        // Execute the query
        $this->preparedStatementQuery($sql);
    }

    // Expose query()
    public function dbQuery($sql) {

        $query = $this->conn->query($sql);

        // Check for SQL errors
        if ($query) {

            return $query;
        } else {

            return false;
        }
    }

    // Expose num_rows
    public function dbNumRows($res) {

        $numrows = $res->num_rows;

        return $numrows;
    }

    // Count the number of records in a table
    public function countRows($table, $where = NULL) {

        if ($where != NULL) {

            $where = "
              WHERE
                $where
            ";
        }

        $sql = "
          SELECT
            *
          FROM
            $table
          $where
        ";

        $res = $this->dbQuery($sql);
        $num_rows = count($res->fetch_assoc());

        return $num_rows;
    }

    protected function dbInsert() {

        if ($this->form_fields >= 1) {

            // retrieve the keys of the array (column titles)
            $fields = array_keys($this->form_fields);

            $sql = "
                INSERT INTO
                    $this->table
                        (" . implode(',', $fields) . ")
                VALUES ('" . implode("','", $this->form_fields) . "')
            ";

            $res = $this->dbQuery($sql);

            if ($res) {

                $this->html_message = '<div class="bs-example" style="margin-top: 30px">
                                        <div class="alert alert-success fade in">
                                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                                            Inserted successfully.
                                        </div>
                                    </div>';
            } else {

                $this->html_message = '<div class="bs-example" style="margin-top: 30px">
                                        <div class="alert alert-danger fade in">
                                            <a href="#" class="close" data-dismiss="alert">&times;</a>
                                            Insert failed.
                                        </div>
                                    </div>';
            }
        }
    }

    protected function dbUpdate() {

        $sql = "UPDATE " . $this->table . " SET ";

        // loop and build the columns
        $sets = array();

        foreach ($this->form_fields as $column => $value) {

            if (!empty($value) || $value == 0) {

                $sets[] = "`" . $column . "` = '" . $value . "'";
            }
        }

        // Glue them together
        $sql .= implode(', ', $sets);

        if (!empty($this->where_clause)) {

            // check to see if the 'where' keyword exists
            if (substr(strtoupper(trim($this->where_clause)), 0, 5) != 'WHERE') {

                // Not found, add key word
                $sql .= " WHERE " . $this->where_clause;
            } else {

                $sql .= " " . $this->where_clause;
            }
        }

        // Execute the query
        $this->dbQuery($sql);
    }

    protected function dbDelete() {

        // build the query
        $sql = "DELETE FROM " . $this->table;

        // check for where clause
        if (!empty($this->where_clause)) {

            // check to see if the where keyword exists
            if (substr(strtoupper(trim($this->where_clause)), 0, 5) != 'WHERE') {

                // not found, add keyword
                $sql .= " WHERE " . $this->where_clause;
            } else {

                $sql .= " " . $this->where_clause;
            }
        }

        // Execute the query
        $this->dbQuery($sql);
    }

    public function getLastId($table) {

        $sql = "
          SELECT
            id
          FROM
            $table
          ORDER BY
            id DESC
          LIMIT 1
        ";

        $res = $this->dbQuery($sql);
        $row = $res->fetch_assoc();

        return $row['id'];
    }

    public function getLastIdUpper($table) {

        $sql = "
          SELECT
            ID
          FROM
            $table
          ORDER BY
            ID DESC
          LIMIT 1
        ";

        $res = $this->dbQuery($sql);
        $row = $res->fetch_assoc();

        return $row['ID'];
    }

    // Sanitise user input
    public function sanitise($string) {

        $str = addslashes($string);

        if (is_array($str)) {

            return array_map(array($this->conn, 'real_escape_string'), $str);
        } else {

            return $this->conn->real_escape_string($str);
        }
    }

    public function real_escape_string($string) {

        return $this->conn->real_escape_string($string);
    }

    // Output message to browser
    protected function htmlOutput($success, $fail) {

        if (!mysqli_error($this->conn)) {

            // Success message
            $return = '<div class="alert alert-success" role="alert">';
            $return .= $success;
            $return .= '</div>';
        } else {

            // Failed message
            $return = '<div class="alert alert-warning" role="alert">';
            $return .= $failed;
            $return .= '</div>';
        }

        // Return the message
        return $return;
    }

    protected function truncate() {

        $sql = "
            TRUNCATE
                $this->table;
        ";

        $this->dbQuery($sql);
    }
    
    private function sendSmtpMail(){

        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;
        
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
        
        //Set the hostname of the mail server
        $mail->Host = "127.0.0.1";
        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = 25;
        //Whether to use SMTP authentication
        $mail->SMTPAuth = false;
        //Username to use for SMTP authentication
        $mail->Username = "";
        //Password to use for SMTP authentication
        $mail->Password = "";
        
        $yesterday = date("Y-m-d", strtotime( '-1 days' ));
        
        $body  = '<h1>SANRAL Tender Report - '. $yesterday .'</h1>';
        $body .= '<p>Please find attached the submissions for the tenders listed below:</p>';
        $body .= '<ol>';
        
        foreach($this->tenders_array as $attachment){
            
            $body .= '<li>'. $attachment[1] .'</li>';
        }
        
        $body .= '<ol>';
        
        if(isset($this->recipients[$this->region])){

            foreach($this->recipients[$this->region] as $email){

                //Set who the message is to be sent to
                echo $email .'<br>';
                $mail->addAddress($email);
            }
        }
            
        //Set who the message is to be sent from
        $mail->setFrom('webmaster@sanral.co.za', 'SANRAL');
        //Set an alternative reply-to address
        $mail->addReplyTo('webmaster@sanral.co.za', 'SANRAL');
        //Set the subject line
        $mail->Subject = $this->region .'Tenders Daily Report';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        $mail->msgHTML($body);
        //Replace the plain text body with one created manually
        //$mail->AltBody = 'This is a plain-text message body';
        // Send mail id

        //Attach an image file
        if(count($this->tenders_array) > 0){
           
            for($i=0;$i<count($this->tenders_array);$i++){

                $file_name = pathinfo($this->tenders_array[$i][0]);
                $mail->addAttachment($this->path . 'upload/'. $file_name['filename'].'.csv');
            }
        }

        //send the message, check for errors

        if(!$mail->send()){

            echo 'Failed';
            
        } else {

            echo $this->region .' Sent<br>';
        }
    }
    
    public function areaSelect(){
        
        $this->types = 'i';
        $this->params = [];
        $this->params[] = $_COOKIE['userid'];

	$sql = "
            SELECT
                tbl_area_relation.UserId,
                tbl_area_relation.AreaId,
                tbl_area_relation.Def,
                tbl_areas.Area
            FROM
                tbl_area_relation
                INNER JOIN tbl_areas ON tbl_area_relation.AreaId = tbl_areas.Id
            WHERE 
                tbl_area_relation.UserId = ?
            ORDER BY 
                Area ASC
        ";
        
        $res = $this->preparedStatementQuery($sql);
        
        $this->generic_areas_dd  = '';
        $this->generic_areas_dd .= '<form name="form" id="form">';
	$this->generic_areas_dd .= '    <select name="jumpMenu" class="area-dd" id="jumpMenu" onchange="MM_jumpMenu(\'parent\',this,0)">';

        while($row = $res->fetch_assoc()){
            
            $selected = '';
            
            if($_SESSION['areaid'] == $row['AreaId']){
                
                $selected = 'selected="selected"';
            }
            
            $this->generic_areas_dd .= '<option '. $selected .' value="http://www.seavest.co.za/inv/functions/sessions.php?Area='. $row['AreaId'] .'">'. $row['Area'] .'</option>';
        }

        $this->generic_areas_dd .= '    </select>';
        $this->generic_areas_dd .= '</form>';
    }
    
    protected function paginationInit(){

        $this->offset = ($this->page_no - 1) * $this->total_records_per_page;
	$this->previous_page = $this->page_no - 1;
	$this->next_page = $this->page_no + 1;

        $this->total_no_of_pages = ceil($this->total_records / $this->total_records_per_page);
	$this->second_last = $this->total_no_of_pages - 1;
    }

    protected function getPaginator(){

        if($this->total_records > $this->total_records_per_page){

            $this->paginator .= '<ul class="pagination pagination-con">';

            $disabled = '';
            
            if($this->page_no <= 1){

                $disabled = "class='page-item disabled'";
            }

            $this->paginator .= '   <li '. $disabled .'>';
            $previous_page = '';

            if($this->page_no > 1){

                $previous_page = 'href=?page_no='. $this->previous_page;
            }

            $this->paginator .= '<a class="page-link" '. $previous_page .'>Previous</a>';
            $this->paginator .= '</li>';

            if($this->total_no_of_pages <= 10){

                for ($counter = 1; $counter <= $this->total_no_of_pages; $counter++){

                    if ($counter == $this->page_no) {

                        $this->paginator .= "<li class='active'><a class='page-link'>$counter</a></li>";

                    } else {

                        $this->paginator .= "<li><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                    }
                }

            } elseif($this->total_no_of_pages > 10){

                if($this->page_no <= 4){

                    for($counter = 1; $counter < 8; $counter++){

                        if($counter == $this->page_no){

                            $this->paginator .= "<li class='page-item active'><a class='page-link'>$counter</a></li>";

                        } else {

                            $this->paginator .= "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                        }
                    }

                    $this->paginator .= "<li class='page-item'><a class='page-link'>...</a></li>";
                    $this->paginator .= "<li class='page-item'><a class='page-link' href='?page_no=$this->second_last'>$this->second_last</a></li>";
                    $this->paginator .= "<li class='page-item'><a class='page-link' href='?page_no=$this->total_no_of_pages'>$this->total_no_of_pages</a></li>";

                } elseif($page_no > 4 && $page_no < $total_no_of_pages - 4){

                    $this->paginator .= "<li class='page-item'><a class='page-link' href='?page_no=1'>1</a></li>";
                    $this->paginator .= "<li class='page-item'><a class='page-link' href='?page_no=2'>2</a></li>";
                    $this->paginator .= "<li class='page-item'><a class='page-link'>...</a></li>";

                    for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++){

                        if ($counter == $page_no) {

                            $this->paginator .= "<li class='page-item active'><a class='page-link'>$counter</a></li>";

                        } else {

                            $this->paginator .= "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                        }
                    }

                    $this->paginator .= "<li><a>...</a></li>";
                    $this->paginator .= "<li class='page-item'><a class='page-link' href='?page_no=$this->second_last'>$this->second_last</a></li>";
                    $this->paginator .= "<li class='page-item'><a class='page-link' href='?page_no=$this->total_no_of_pages'>$this->total_no_of_pages</a></li>";

                } else {

                    $this->paginator .= "<li class='page-item'><a class='page-link' href='?page_no=1'>1</a></li>";
                    $this->paginator .= "<li class='page-item'><a class='page-link' href='?page_no=2'>2</a></li>";
                    $this->paginator .= "<li><a>...</a></li>";

                    for($counter = $this->total_no_of_pages - 6; $counter <= $this->total_no_of_pages; $counter++){

                        if($counter == $this->page_no){

                            $this->paginator .= "<li class='page-item active'><a class='page-link'>$counter</a></li>";

                        } else {

                            $this->paginator .= "<li><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                        }
                    }
                }
            }

            // Reset disabled
            $disabled = '';

            if($this->page_no >= $this->total_no_of_pages){

                $disabled = "class='disabled'";
            }

            $this->paginator .= "<li ". $disabled .">";

            if($this->page_no < $this->total_no_of_pages){

                $next_page = 'href=?page_no='. $this->next_page;
            }

            $this->paginator .= '<a class="page-link" '. $next_page .'>Next</a>';
            $this->paginator .= '</li>';

            if($this->page_no < $this->total_no_of_pages){

                $this->paginator .= "<li><a class='page-link' href='?page_no=$this->total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
            }

            $this->paginator .= '</ul>';

        }
    }
    
    public function setAlertIcon(){

        if(!empty($this->db_response == true)){

            $this->alert_icon = 'fa-check-circle';

        } else {

            $this->alert_icon = 'fa-warning';
        }
    }
    
    public function setAlert(){
        
        $this->setAlertIcon();

        $this->htm  = '<div class="alert alert-info" role="alert" style="margin-top: 25px">';
        $this->htm .= '   <div class="row">';
        $this->htm .= '       <div class="col-1">';
        $this->htm .= '           <i class="fa '. $this->alert_icon .'" style="font-size: 50px"></i>';
        $this->htm .= '       </div>';
        $this->htm .= '       <div class="col-11" style="padding-left:30px">';
        $this->htm .=             $this->alert_message;
        $this->htm .= '       </div>';
        $this->htm .= '   </div>';
        $this->htm .= '</div>';
    }
    
    public function getFieldValue($table,$column,$id){
        
        $this->types = 'i';
        $this->params = [];
        $this->params[] = $id;
        
        $sql = "
            SELECT
                $column
            FROM
                $table
            WHERE
                Id = ?
        ";
        
        if($id > 0){
            
            $res = $this->preparedStatementQuery($sql);
            $row = $res->fetch_assoc();

            if($res->num_rows > 0){

                echo $row[$column];
            }
        }
    }
    
    public function editUrl($id){
        
        $page_no = 1;
        $file = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $file = end($file);
        $url = $file . '?id='. $id;

        if(!empty($_GET['page_no'])){
            
            $url .= '&page_no='. $_GET['page_no'];
        }
        
        echo $url;
    }
}

?>