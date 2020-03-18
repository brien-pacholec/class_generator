<?php

require("/home/nginx/html/shared/mydaemen_auth.php");
include_once("config.php");
if($session_access_level !== "admin") {
	header("location: invalid_access.php?id=2");
}
if(isset($_POST["class_name"])){

    $class_name = $_POST['class_name'];
    $name_space = $_POST['name_space'];
    $config_file = $_POST['config_file'];
    $table_name = $_POST['table_name'];
    $table_user = $_POST['table_user'];
    $password_variable = $_POST['password_variable'];
    $database =  $_POST['database'];
    if($_POST['transaction']==1){
        $transaction = "PDO";
    }

    //GET all of the field names imported
    $field_names = array();
    foreach($_POST as $key => $value) {
        if (strpos($key, 'field-') === 0) {
            if($value!=""){
                $field_names[] = strtolower($value);
            }
        }
    }

    $function_names = array();
    foreach($field_names as $key=>$field){
        $new = $field;
        if(strpos($new, "_")){
            $strings =explode("_",$new);
            $new = "";
            foreach($strings as $string){
                $new .= ucfirst($string);
            }
        } else{
            $new = ucfirst($new);
        }
        $function_names[] = $new;
    }
    $one_tab = "&nbsp;&nbsp;&nbsp;&nbsp;";
    $two_tabs = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $three_tabs = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $four_tabs = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    $five_tabs = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}

$page_title="Generate";

?>

<?php require("/home/nginx/html/portal/resources/partials/system_wrapper.php"); ?>
<link rel="stylesheet" type="text/css" href = "<?php echo($css_path)?>">
<br>

<div id="main-container" class = "article centered-block">
    <h2 class = "page-title centered-heading" style="color:black;"><?php echo($system_title); ?></h2>
    <h5 class = "page-title centered-heading text-muted"><?php echo($page_title);?></h5>
    <hr>

    <?php if(!isset($_POST["class_name"])):?>
        <form action = "main.php" method="post" autocomplete="off">
            <!-- CLASS Information -->
            <div class="row ">
                <div class="col-6">
                    <h4>Class Information</h4>

                    <!-- Class Name -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3"><strong>Class Name</strong></span>
                        </div>
                        <input type="text" class="form-control" id="class_name" name="class_name" required>
                    </div>

                     <!-- Name Space -->
                     <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3"><strong>Name Space</strong></span>
                        </div>
                        <input type="text" class="form-control" id="name_space" name="name_space" required>
                    </div>


                    <!-- Type of Data Transaction -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3"><strong>Transaction</strong></span>
                        </div>
                        <select class="custom-select" id="transaction" name="transaction"required>
                            <option value="1" selected>PDO</option>
                        </select>
                    </div>

                    <!-- Database -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3"><strong>Database</strong></span>
                        </div>
                        <select class="custom-select" id="database" name="database"required>
                            <option value="daemen" selected>daemen</option>
                            <option value="adp">adp</option>
                            <option value="computing">computing</option>
                            <option value="data_testing">data_testing</option>
                            <option value="information_schema">information_schema</option>
                            <option value="mysql">mysql</option>
                            <option value="performance_schema">performance_schema</option>
                            <option value="phpmyadmin">phpmyadmin</option>
                        </select>
                    </div>

                    
                </div>
                <div class="col-6">
                    <h4>&nbsp;</h4>

                    <!-- Config File Name -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3"><strong>Config File Name</strong></span>
                        </div>
                        <input type="text" class="form-control" id="config_file" name="config_file" required>
                    </div>

                    <!-- Main Table -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3"><strong>Table Name</strong></span>
                        </div>
                        <input type="text" class="form-control" id="table_name" name="table_name" required>
                    </div>

                    <!-- Authorized User of that table -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3"><strong>Table User</strong></span>
                        </div>
                        <input type="text" class="form-control" id="table_user" name="table_user" required>
                    </div>

                    <!-- Authorized User of that table -->
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3"><strong>Password Variable</strong></span>
                        </div>
                        <input type="text" class="form-control" id="password_variable" name="password_variable" required>
                    </div>

                    
                </div>
            </div>

            <br>

            <!-- DATA Fields -->
            <div class="row justify-content-md-center">
                <div class="col-12">
                    <span class="d-inline-block justify-content-center align-self-center"><h4>Data Fields</h4></span>
                    <span class="d-inline-block justify-content-center align-self-center float-right"> 
                        <button id="field-add" type="button" class="btn btn-success btn-sm py-0 mx-3">Add Field <span id="field-count" class="badge badge-pill badge-dark">1</span></button>
                    </span>

                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="fields-text">Field 1</span>
                        </div>
                        <input type="text" class="form-control" id="field-1" name="field-1" value="id"  required>
                    </div>

                    <div id="additional-fields">
                        <input type="hidden" value="1" id="extra-fields">
                    </div>
                    


                
                </div>
            </div>

            <div class = "text-center">
                <button type = "submit" name = "submit_request" class = "btn btn-secondary" value = "1" id = "submit_request">Submit Request</button>
            </div>

        </form>
    <?php else :?>
        <div class="row justify-content-md-center">
            <div class="col-12">
                <h4><strong><?php echo($class_name);?>.php</strong> Generated</h4>
            </div>      
        </div>
        

        <div class="row">
            <div class="col-12 text-center">
                <button class="btn btn-primary btn-sm" onclick="location.href='./main.php'">Generate Another <i class="fas fa-file-plus"></i></button>
                <button class="btn btn-primary btn-sm" onclick="selectText('class-text')">Copy to Clipboard <i class="fas fa-copy"></i></button>
            </div>
        </div>
        <br>
        <div class="row justify-content-md-center pl-3 pr-3">
            <div class="col-12 card" style="overflow: scroll; max-height:300px; overflow-x:hidden;"  id="class-text">
                
                namespace <?php echo($name_space);?>;<br>
                use <?php echo($transaction);?>; <br><br>
                class <?php echo($class_name);?> {<br>
                        <!-- TABLE -->
                        <?php echo($one_tab);?>const MAIN_TABLE = "<?php echo($table_name);?>";<br>

                        <!-- FIELD NAMES -->
                        <?php foreach($field_names as $field) :?>
                        <?php echo($one_tab);?>private $<?php echo($field);?>;<br>
                        <?php endforeach;?>
                        
                        <br>

                        <!-- GETCONN -->
                        <?php echo($one_tab);?>//ESTABLISHES a connection with the database.<br>
                        <?php echo($one_tab);?>public static function getConn(){<br>
                            <?php echo($two_tabs);?>include "/home/nginx/config/<?php echo($config_file);?>.php";<br>
                            <?php echo($two_tabs);?>try {<br>
                                <?php echo($three_tabs);?>$event_db = new PDO("mysql:host=localhost;dbname=<?php echo($database);?>", "<?php echo($table_user);?>", $<?php echo($password_variable);?>);<br>
                                <?php echo($three_tabs);?>$event_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);<br>
                                <?php echo($three_tabs);?>return $event_db;<br>
                            <?php echo($two_tabs);?>} catch (\PDOException $e) {<br>                        
                                <?php echo($three_tabs);?> echo($e);<br>
                                <?php echo($three_tabs);?> return false;<br>
                            <?php echo($two_tabs);?>}<br>
                        <?php echo($one_tab);?>}<br>

                        <br>

                        <!-- CREATE -->
                        <?php echo($one_tab);?>//CREATES an entry into the <?php echo($table_name);?> table.<br>
                        <?php echo($one_tab);?>public function create() {<br>
                            <?php echo($two_tabs);?>$conn = self::getConn();<br>
                            <?php echo($two_tabs);?>$sql = "<br>
                            <?php echo($two_tabs);?>INSERT INTO<br>
                                <?php echo($two_tabs);?>`".self::MAIN_TABLE."` <br>
                                <?php echo($two_tabs);?>(<br>
                                    <?php foreach($field_names as $key=>$field) :?>
                                    <?php if(count($field_names)-1 != $key) :?>
                                    <?php echo($three_tabs);?>`<?php echo($field);?>`,<br>
                                    <?php else:?>
                                    <?php echo($three_tabs);?>`<?php echo($field);?>`<br>
                                    <?php endif;?>
                                    <?php endforeach;?>
                                <?php echo($two_tabs);?>)<br>
                            <?php echo($two_tabs);?>VALUES<br>
                            <?php echo($two_tabs);?>(<br>
                                <?php foreach($field_names as $key=>$field) :?>
                                <?php if(count($field_names)-1 != $key) :?>
                                <?php echo($three_tabs);?>:<?php echo($field);?>,<br>
                                <?php else:?>
                                <?php echo($three_tabs);?>:<?php echo($field);?><br>
                                <?php endif;?>
                                <?php endforeach;?>
                            <?php echo($two_tabs);?>)<br>
                            <?php echo($two_tabs);?>";<br>
                    		<?php echo($two_tabs);?>$conn->prepare($sql)->execute([<br>
                                <?php foreach($field_names as $key=>$field) :?>
                                <?php if(count($field_names)-1 != $key) :?>
                                <?php echo($three_tabs);?>"<?php echo($field);?>" => $this-><?php echo($field);?>,<br>
                                <?php else:?>
                                <?php echo($three_tabs);?>"<?php echo($field);?>" => $this-><?php echo($field);?><br>
                                <?php endif;?>
                                <?php endforeach;?>
                            <?php echo($two_tabs);?>]);<br>
		                    <?php echo($two_tabs);?>return $conn->lastInsertId();<br>
                        <?php echo($one_tab);?>}<br>

                        <br>

                        <!-- UPDATE -->
                        <?php echo($one_tab);?>//UPDATES an entry in the <?php echo($table_name);?> table.<br>
                        <?php echo($one_tab);?>public function update() {<br>
                            <?php echo($two_tabs);?>$conn = self::getConn();<br>
                            <?php echo($two_tabs);?>$sql = "<br>
                                <?php echo($three_tabs);?>UPDATE<br>
                                    <?php echo($four_tabs);?>`".self::MAIN_TABLE."`<br>
                                <?php echo($three_tabs);?>SET<br>
                                    <?php foreach($field_names as $key=>$field) :?>
                                    <?php if(count($field_names)-1 != $key) :?>
                                    <?php echo($four_tabs);?>`<?php echo($field);?>` = :<?php echo($field);?>,<br>
                                    <?php else:?>
                                    <?php echo($four_tabs);?>`<?php echo($field);?>` = :<?php echo($field);?><br>
                                    <?php endif;?>
                                    <?php endforeach;?>
                                <?php echo($three_tabs);?>WHERE<br>
                                    <?php echo($four_tabs);?>`id` = :id<br>
                                <?php echo($three_tabs);?>";<br>		
                            <?php echo($two_tabs);?>try {<br>
                                <?php echo($three_tabs);?>$conn->prepare($sql)->execute([<br>
                                    <?php foreach($field_names as $key=>$field) :?>
                                    <?php if(count($field_names)-1 != $key) :?>
                                    <?php echo($four_tabs);?>"<?php echo($field);?>" => $this-><?php echo($field);?>,<br>
                                    <?php else:?>
                                    <?php echo($four_tabs);?>"<?php echo($field);?>" => $this-><?php echo($field);?><br>
                                    <?php endif;?>
                                    <?php endforeach;?>
                                <?php echo($three_tabs);?>]);<br>
                                <?php echo($three_tabs);?>return true;<br>
                            <?php echo($two_tabs);?>} catch(\PDOException $e) {<br>
                                <?php echo($three_tabs);?>throw new \Exception($e);<br>
                            <?php echo($two_tabs);?>}<br>
                        <?php echo($one_tab);?>}<br>

                        <br>

                        <!-- DELETE -->
                        <?php echo($one_tab);?>//DELETES an entry in the <?php echo($table_name);?> table.<br>
                        <?php echo($one_tab);?>public static function delete($id){<br>
                            <?php echo($two_tabs);?>$conn = self::getConn();<br>
                            <?php echo($two_tabs);?>$sql = "<br>
                            <?php echo($two_tabs);?>DELETE FROM<br>
                                <?php echo($three_tabs);?>`".self::MAIN_TABLE."`<br>
                            <?php echo($two_tabs);?>WHERE<br>
                                <?php echo($three_tabs);?>`id` = :id<br>
                            <?php echo($two_tabs);?>";<br>
                            <?php echo($two_tabs);?>$stmt = $conn->prepare($sql);<br>
                                <?php echo($two_tabs);?>$stmt->execute([<br>
                                    <?php echo($three_tabs);?>"id" => $id<br>
                            <?php echo($two_tabs);?>]);<br>
                        <?php echo($one_tab);?>}<br>

                        <br>

                        <!-- GET ALL -->
                        <?php echo($one_tab);?>//GETS all of the records from the <?php echo($table_name);?> table.<br>
                        <?php echo($one_tab);?>public static function getAll(){<br>
                            <?php echo($two_tabs);?>$conn = self::getConn();<br>
                            <?php echo($two_tabs);?>$sql = "<br>
                            <?php echo($two_tabs);?>SELECT<br>
                                <?php echo($three_tabs);?>*<br>
                            <?php echo($two_tabs);?>FROM<br>
                                <?php echo($three_tabs);?>`".self::MAIN_TABLE."`<br>
                            <?php echo($two_tabs);?>";<br>
                            <?php echo($two_tabs);?>$stmt = $conn->query($sql);<br>
                            <?php echo($two_tabs);?>$records = $stmt->fetchAll(PDO::FETCH_ASSOC);<br><br>
                            <?php echo($two_tabs);?>$object_array=array();<br>
                            <?php echo($two_tabs);?>foreach($records as $record){<br>
                                <?php echo($three_tabs);?>$object= new self();<br>
                                <?php foreach($field_names as $key=>$field) :?>
                                <?php echo($three_tabs);?>$object->set<?php echo($function_names[$key]);?>($record['<?php echo($field);?>']);<br>
                                <?php endforeach;?>
                                <?php echo($three_tabs);?>$object_array[] = $object;<br>
                            <?php echo($two_tabs);?>}<br>
                            <?php echo($two_tabs);?>return $object_array;<br>
                        <?php echo($one_tab);?>}<br>

                        <br>

                        <!-- GET ALL BY ID -->
                        <?php echo($one_tab);?>//GETS the first record from the <?php echo($table_name);?> table based on a specific id.<br>
                        <?php echo($one_tab);?>public static function getById($id){<br>
                            <?php echo($two_tabs);?>$conn = self::getConn();<br>
                            <?php echo($two_tabs);?>$sql = "<br>
                                <?php echo($three_tabs);?>SELECT * FROM `".self::MAIN_TABLE."` WHERE `id`= '$id';<br>
                            <?php echo($two_tabs);?>";<br>
                            <?php echo($two_tabs);?>$stmt = $conn->query($sql);<br>
                            <?php echo($two_tabs);?>$records = $stmt->fetchAll(PDO::FETCH_ASSOC);<br><br>
                            <?php echo($two_tabs);?>$record = $records[0];<br><br>
                            <?php echo($two_tabs);?>$object= new self();<br>
                            <?php foreach($field_names as $key=>$field) :?>
                            <?php echo($two_tabs);?>$object->set<?php echo($function_names[$key]);?>($record['<?php echo($field);?>']);<br>
                            <?php endforeach;?>
                            <?php echo($two_tabs);?>return $object;<br>
                        <?php echo($one_tab);?>}<br>

                        <br>

                        <!-- GET ALL BY ID -->
                        <?php echo($one_tab);?>//GETS all of the records from the <?php echo($table_name);?> table based on a specific id.<br>
                        <?php echo($one_tab);?>public static function getAllById($id){<br>
                            <?php echo($two_tabs);?>$conn = self::getConn();<br>
                            <?php echo($two_tabs);?>$sql = "<br>
                                <?php echo($three_tabs);?>SELECT * FROM `".self::MAIN_TABLE."` WHERE `id`= '$id';<br>
                            <?php echo($two_tabs);?>";<br>
                            <?php echo($two_tabs);?>$stmt = $conn->query($sql);<br>
                            <?php echo($two_tabs);?>$records = $stmt->fetchAll(PDO::FETCH_ASSOC);<br><br>
                            <?php echo($two_tabs);?>$object_array=array();<br>
                            <?php echo($two_tabs);?>foreach($records as $record){<br>
                                <?php echo($three_tabs);?>$object= new self();<br>
                                <?php foreach($field_names as $key=>$field) :?>
                                <?php echo($three_tabs);?>$object->set<?php echo($function_names[$key]);?>($record['<?php echo($field);?>']);<br>
                                <?php endforeach;?>
                                <?php echo($three_tabs);?>$object_array[] = $object;<br>
                            <?php echo($two_tabs);?>}<br>
                            <?php echo($two_tabs);?>return $object_array;<br>
                        <?php echo($one_tab);?>}<br>

                        <br>

                        <!-- GETTER / SETTERS -->
                        <?php foreach($field_names as $key=>$field) :?>
                            <br>
                            <?php echo($one_tab);?>//<?php echo(strtoupper($function_names[$key]));?>
                            <br>
                            <?php echo($one_tab);?>public function get<?php echo($function_names[$key]);?>(){<br>
                                <?php echo($two_tabs);?>return $this-><?php echo($field)?>;<br>
                            <?php echo($one_tab);?>}<br>
                            <?php echo($one_tab);?>public function set<?php echo($function_names[$key]);?>($<?php echo($field)?>){<br>
                                <?php echo($two_tabs);?>$this-><?php echo($field)?> = $<?php echo($field)?>;<br>
                            <?php echo($one_tab);?>}<br>
                            <br>
                        <?php endforeach;?>
         }
            </div>      
        </div>

    <?php endif;?>
    


   
</div>
<?php require("/home/nginx/html/portal/resources/partials/footer.php");?>
<script>

    $(document).ready(function() {

        $("#field-add").click(function(){
            

            var new_extra_fields = parseInt($('#extra-fields').val()) + 1;
            var new_input = "<div class='input-group mb-3'><div class='input-group-prepend'><span class='input-group-text' id='fields-text-"+new_extra_fields+"'>Field "+new_extra_fields+"</span></div><input type='text' class='form-control' id='field-"+new_extra_fields+"' name='field-"+new_extra_fields+"'></div>"

            $('#additional-fields').append(new_input);
            $('#extra-fields').val(new_extra_fields);

            var fieldcount =  $(":input[id^=fields-text]").length
            $("#field-count").text(new_extra_fields);
        });
    });

    function add() {
        var new_chq_no = parseInt($('#total_chq').val()) + 1;

        $('#additional-fields').append(new_input);
        
        $('#total_chq').val(new_chq_no);
    }
    function selectText(containerid) {
        if (document.selection) { // IE
            var range = document.body.createTextRange();
            range.moveToElementText(document.getElementById(containerid));
            range.select();
        } else if (window.getSelection) {
            var range = document.createRange();
            range.selectNode(document.getElementById(containerid));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand("copy");
            alert("text copied");
        }
    }
        
</script>