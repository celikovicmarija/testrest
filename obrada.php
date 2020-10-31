<?php
    include "Database.php";
    $mydb = new Database('rest');
    if(isset($_POST["posalji"]) && $_POST["posalji"]="Posalji zahtev"){
        if($_POST["naslov_novosti"]!=null && $_POST["tekst_novosti"]!=null && $_POST["kategorija_odabir"]!=null){
            $niz = ["naslov"=> "'".$_POST["naslov_novosti"]."'", "tekst"=>"'".$_POST["tekst_novosti"]."'", "datumvreme"=>"NOW()", "kategorija_id"=>$_POST["kategorija_odabir"]];
            if($mydb->insert("novosti", "naslov, tekst, datumvreme, kategorija_id", $niz)){
                echo "vrednosti ubacene";
            } else{
                echo "vrednosti nisu ubacene";
            }
            $_POST = array();
            exit();
        }       
        //Brisanje za obe tabele 
        elseif($_POST["brisanje"]!=null && $_POST["odabir_tabele"]!=null){
            $tabela = $_POST["odabir_tabele"];
            $id = "id";
            $id_val = $_POST["brisanje"];
            if($mydb->delete($tabela,$id,$id_val)){
                echo "red obrisan";
            }else{
                echo "greska prilikom brisanja";
            }
            $_POST = array();
            exit();
        }//Ubacivanje u kategorije
        elseif($_POST["kategorija_naziv"] != null) {
            $naziv = $_POST["kategorija_naziv"];
            $niz = ["kategorija"=> "'".$naziv. "'"];
            if ($mydb->insert("kategorije", "kategorija", $niz)) {
                echo "kategorija ubacena";
            } else {
                echo "kategorija nije ubacena";
            }
            $_POST = array();
            exit();
        //Azuriranje kategorije
        } elseif($_POST["kategorija_id"] != null && $_POST["kategorija_naziv_put"] != null) {
            $naziv = $_POST["kategorija_naziv_put"];
            $id=$_POST["kategorija_id"];

            if ($mydb->update("kategorije", $id,["kategorija"], ["'".$naziv."'"])) {
                echo "kategorija je izmenjena";
            } else {
                echo "kategorija nije izmenjena";
            }
            $_POST = array();
            exit();
        //Azuriranje novosti
        } elseif($_POST["novosti_id"] != null && $_POST["naslov_novosti_put"] != null && $_POST["tekst_novosti_put"] != null && $_POST["kategorija_odabir_put"] != null) {
            $niz = ["'".$_POST["naslov_novosti_put"]."'","'". $_POST["tekst_novosti_put"]."'","'"."NOW()"."'","'". $_POST["kategorija_odabir_put"]."'"];
            if ($mydb->update("novosti", $_POST["novosti_id"], ["naslov", "tekst", "datumvreme", "kategorija_id"], $niz)) {
                echo "novost je izmjenjena";
            } else {
                echo "novost nije izmjenjena";
            }
            $_POST = array();
            exit();
        //Select za sve tabele
        }else {
            if( $_POST["odabir_tabele"]!= null){
                 $tabela = $_POST["odabir_tabele"];
                if ($mydb->select($tabela, "*", null, null, null)) {
                    echo "tabela je prikazana";
                    /*
                    On ovde ode u dobru granu, ali nisam implementirala da prikazuju.
                    */
                 } else {
                 echo "tabela nije prikazana";
             }
         }
           
        }
    }
?>