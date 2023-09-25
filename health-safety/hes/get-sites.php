<?php

    require_once('../../class/base.php');
    
    $db = new Base();
    
    $db->types = 'i';
    $db->params = [];
    $db->params[] = (int) $_POST['company_id'];
    
    $sql = "
        SELECT
            Id,
            Name
        FROM
            tbl_sites
        WHERE
            Company = ?
    ";
    
    $res = $db->preparedStatementQuery($sql);
    
    $sites  = '<select name="site" class="tarea-hes">';
    $sites .= '     <option value="">Select one...</option>';

    while($row = $res->fetch_assoc()){
        
        $sites .= '     <option value="'. $row['Id'] .'">'. $row['Name'] .'</option>';
    }
    
    $sites .= '</select>';
    
    echo $sites;

