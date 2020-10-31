<?php

class Database
{
    private $hostname = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname;
    private $dblink;
    private $result;
    private $records;
    private $affected;

    function __construct($par_dbname)
    {
        $this->dbname = $par_dbname;
        $this->Connect();
    }

    function Connect(){
        $this->dblink = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);
        if($this->dblink->connect_errno){ //true-> doslo do greske
            printf("Konekcija neuspešna: %s\n", $this->dblink->connect_error);
            exit();
        }
        $this->dblink->set_charset("utf8");
    }

    function ExecuteQuery($query){
        $this->result = $this->dblink->query($query);
       //broj izmenjenih redova i onoliko koliko je affectovano promenom
       
        if($this->result){
            if(isset($this->result->num_rows)){
                $this->records = $this->result->num_rows;
               
            }
            if(isset($this->result->affected_rows)){
                $this->affected = $this->result->affected_rows;
                echo $this->affected;
            }

            return true;
        }else{
            return false;
        }
    }

    function getResult(){
        return $this->result;
    }

    function select($table="novosti",$rows="*",$join_table="kategorije",$join_key1="kategorija_id",$join_key2="id", $where =null, $order=null){
        $q = 'SELECT '.$rows.' FROM '.$table;
        //SELECT * FROM novosti
        echo $q;
        if($join_table!=null){
            $q.=' JOIN '.$join_table.' ON '.$table.'.'.$join_key1.'='.$join_table.'.'.$join_key2;
            //SELECT * FROM novosti JOIN kategorije ON novosti.kategorija_id = kategorije.id
            
        }
        if($where!=null){
            $q.=' WHERE '.$where;
        }
        if($order!=null){
            $q.=' ORDER BY '.$order;
        }
        if( $this->ExecuteQuery($q)){         
            return true;
        }
        else{
            return false;
        }
       
    }
    function insert($table="novosti",$rows="naslov, tekst, datumvreme, kategorija_id", $values){
        $query_values = implode(',',$values);
        //implode ih pakuje sa zarezima jedno do drugog
        $q ='INSERT INTO '.$table;
        if($rows!=null){
            $q.='('.$rows.')';
        }
        $q.=" VALUES($query_values)";
        if($this->ExecuteQuery($q)){
            return true;
           
        }else{
            return false;
        }
    }
    function update($table, $id, $keys,$values){
        $query_values ="";
        $set_query = array();
        for($i =0; $i<sizeof($keys); $i++){
            $set_query[] = "$keys[$i] = $values[$i]";
        }
        $query_values = implode(",", $set_query);  
        echo $query_values;
        echo "<br>";
        $q = "UPDATE $table SET $query_values WHERE id=$id";
       // echo ($q);
       // update ne radi za kategorije kada stoji && $this->affected>0
        if($this->ExecuteQuery($q)){

            return true;
        }else{
            return false;
        }
    }

    function delete($table, $id, $id_value){
        $q = "DELETE FROM $table WHERE $table.$id=$id_value";
        // echo $q;
        if($this->ExecuteQuery($q)){
            return true;
        }else{
            return false;
        }
    }


}

?>