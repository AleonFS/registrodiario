<?php
/**
Plugin Name: IstInformesDiariosSEPE
Plugin URI: #Privado#
Description: Plugin según especificación de la resolución 000129 de 2015 por la UAE-SEPE de la República de Colombia.
Version: 1.0.0
Author: Alejandro Le&oacute;n
Author URI: #
License: Privada
 */
/* Creado el 21-01-2016*/
/*Funcionalidad SEPE*/
add_action('admin_menu','istSEPE_opciones');
function istSEPE_opciones(){
    if ( current_user_can('level_10') ) {
        add_menu_page('Informe Diario SEPE','Informe Diario SEPE','read','info_dia','istSepeRegistros','dashicons-format-aside','10');
    }
}
function istSepeRegistros(){
    $today = date('Y-m-d');
    $separator = "|\$\$|";
    $queryJob = new Daq_Db_Query();
    $queryJob->select("*")->from("Wpjb_Model_Job t1");
    $queryJob->where("t1.job_created_at = ?", $today);
    $queryJob->orWhere("t1.job_modified_at = ?", $today);
    $jobs = $queryJob->execute();

    /*Almaceno los Id de los elementos de hoy, y/o preparo una regex para hacer el where*/
    $jobsToday = array();
    foreach($jobs as $line){
        $jobsToday.array_push($jobsToday,$line->id);
    }

    //Metadatos
    function metaDato($idJob,$val)
    {
        $queryMeta = new Daq_Db_Query;
        $queryMeta->from("Wpjb_Model_MetaValue t1");
        if($val!='') {
            $queryMeta->where("t1.meta_id = ?", $val);
        }
        $queryMeta->where("t1.object_id = ?", $idJob);
        $result = $queryMeta->execute();
        return $result;
    }

    /*Genera el archivo de Texto*/
    $codigoPlataforma = "xxx";
    $codigoPuntoAtencion = "xxx";
    /*Nomenclatura de archivo*/
    $file_name = $codigoPlataforma.$codigoPuntoAtencion.date('Ymd').".txt";
    $Textcontent = "";
    $flag=1;
    foreach($jobs as $line){
        $company = $line->getCompany(true);
        if($flag < count($jobs)){
            $flag++;
            $newline = $line->id.$separator.$line->job_title.$separator.substr($line->job_description,0,3999).$separator; foreach(metaDato($line->id,19) as $m){ $newline = $newline.$m->value.$separator;}; foreach(metaDato($line->id,20) as $m){ $str = $m->value;};$newline=(preg_match('/(\d{2})/',$str))?$newline.substr($str,0,2):$newline.substr($str,0,1); $newline=$newline.$separator;foreach(metaDato($line->id,28) as $m){ $newline=$newline.$m->value;}$newline =$newline.$separator; foreach(metaDato($line->id,21) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator; foreach(metaDato($line->id,22) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator; foreach(metaDato($line->id,30) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator; foreach(metaDato($line->employer_id,25) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator; foreach(metaDato($line->employer_id,26) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator;$newline = ($company->id && $company->hasActiveProfile())?$newline."S":$newline."N";$newline = $newline.$separator.$line->job_created_at.$separator.$line->job_expires_at.$separator; foreach(metaDato($line->id,31) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator; foreach($line->getTag()->category as $category){$newline = $newline.$category->title;};$newline = $newline.$separator; foreach($line->getTag()->type as $type){$newline = $newline.$type->title;};$newline = $newline.$separator; foreach(metaDato($line->id,23) as $m){$newline = $newline.$m->value;};$newline = $newline.$separator; foreach(metaDato($line->id,24) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator;$newline = $newline.get_home_url()."/jobs/view/".$line->job_slug."\r\n";
            $Textcontent = $Textcontent.$newline;
        }else{
            $newline = $line->id.$separator.$line->job_title.$separator.substr($line->job_description,0,3999).$separator; foreach(metaDato($line->id,19) as $m){ $newline = $newline.$m->value.$separator;}; foreach(metaDato($line->id,20) as $m){ $str = $m->value;};$newline=(preg_match('/(\d{2})/',$str))?$newline.substr($str,0,2):$newline.substr($str,0,1); $newline=$newline.$separator;foreach(metaDato($line->id,28) as $m){ $newline=$newline.$m->value;}$newline =$newline.$separator; foreach(metaDato($line->id,21) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator; foreach(metaDato($line->id,22) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator; foreach(metaDato($line->id,30) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator; foreach(metaDato($line->employer_id,25) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator; foreach(metaDato($line->employer_id,26) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator;$newline = ($company->id && $company->hasActiveProfile())?$newline."S":$newline."N";$newline = $newline.$separator.$line->job_created_at.$separator.$line->job_expires_at.$separator; foreach(metaDato($line->id,31) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator; foreach($line->getTag()->category as $category){$newline = $newline.$category->title;};$newline = $newline.$separator; foreach($line->getTag()->type as $type){$newline = $newline.$type->title;};$newline = $newline.$separator; foreach(metaDato($line->id,23) as $m){$newline = $newline.$m->value;};$newline = $newline.$separator; foreach(metaDato($line->id,24) as $m){ $newline = $newline.$m->value;};$newline = $newline.$separator;$newline = $newline.get_home_url()."/jobs/view/".$line->job_slug;
            $Textcontent = $Textcontent.$newline;
        }
    };

    /*Silent Debugger*/
    error_reporting(E_ALL);
    ini_set('display_errors','1');
    /*Crear archivo*/
    $file = fopen("../export/".$file_name,"w"); // W de Write - t de Transalate scape
    fwrite($file, iconv("UTF-8", "Windows-1252",$Textcontent));
    fclose($file);
    /*Fin Archivo de texto*/
    /*Empieza VISTA*/
    echo "<h2>".__('Registro Diario')."</h2>";
    ?>
    <div class="tablenav bottom" style="margin: 10px 20px 10px 0;">
        <div class="tablenav-pages one-page">
            <?php $downLink = '../export/'.$file_name; echo "<a class='button action' href='".$downLink."' target='_blank' download>Descargar TXT</a>"; ?>
        </div>
    </div>
    <?php
    echo "<div class='wrap' style='overflow-x:scroll;'>";
    ?>
    <div id="tabla">
    <table id="today" style="width: 150vw;" class="wp-list-table widefat fixed striped posts">
    <tr>
        <td>Codigo vacante</td>
        <td>Titulo vacante</td>
        <td>Desc vacante</td>
        <td>Experiencia</td>
        <td>Nivel</td>
        <td>Disciplina / Prof</td>
        <td>Salario / Ingreso</td>
        <td>Vacantes</td>
        <td>Cargo</td>
        <td>Doc Type</td>
        <td>ID Empl</td>
        <td>Nombre Empleador</td>
        <td>Except</td>
        <td>Publicada</td>
        <td>Vence</td>
        <td>Municipios</td>
        <td>Sector</td>
        <td>Tipo</td>
        <td>Tele</td>
        <td>Discap</td>
        <td>URL</td>
    </tr>
    <?php    foreach($jobs as $line){
        $company = $line->getCompany(true);
        ?>
        <tr class="dayData">
            <td><?php echo $line->id;?></td>
            <td><?php echo $line->job_title;?></td>
            <td><?php echo substr($line->job_description,0,3999);?></td>
            <td><?php foreach(metaDato($line->id,19) as $m){ echo $m->value;};?></td>
            <td><?php foreach(metaDato($line->id,20) as $m){ $str = $m->value;}  echo (preg_match('/(\d{2})/',$str))? substr($str,0,2):substr($str,0,1); ?></td>
            <td><?php foreach(metaDato($line->id,28) as $m){ echo $m->value;};?></td>
            <td><?php foreach(metaDato($line->id,21) as $m){ echo $m->value;};?></td>
            <td><?php foreach(metaDato($line->id,22) as $m){ echo $m->value;};?></td>
            <td><?php foreach(metaDato($line->id,30) as $m){ echo $m->value;};?></td>
            <td><?php foreach(metaDato($line->employer_id,25) as $m){ echo $m->value;};?></td>
            <td><?php foreach(metaDato($line->employer_id,26) as $m){ echo $m->value;};?></td>
            <td><?php echo $company->company_name;?></td>
            <td><?php echo ($company->id && $company->hasActiveProfile())? "S":"N"?></td>
            <td><?php echo $line->job_created_at;?></td><td><?php echo $line->job_expires_at;?></td>
            <td><?php foreach(metaDato($line->id,31) as $m){ echo $m->value;};?></td>
            <td><?php foreach($line->getTag()->category as $category): esc_html_e($category->title); endforeach; ?></td>
            <td><?php foreach($line->getTag()->type as $type): esc_html_e($type->title); endforeach; ?></td>
            <td><?php foreach(metaDato($line->id,23) as $m){ echo $m->value;};?></td>
            <td><?php foreach(metaDato($line->id,24) as $m){ echo $m->value;};?></td>
            <td><?php echo get_home_url()."/jobs/view/".$line->job_slug ?></td>
        </tr>
        <?php
    }
    echo '</table></div></div>';
    echo '<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>';
    /*echo '<script>';
    foreach ( glob( plugin_dir_path( __FILE__ ) . "assets/*.js" ) as $file ) {
        include_once $file;
    }
    echo '</script>';*/
    ?>
    <div class="tablenav bottom" style="margin: 10px 20px 10px 0;">
        <div class="alignleft actions bulkactions">
            <?php $downLink = '../export/'.$file_name; echo "<a class='button action' href='".$downLink."' target='_blank' download>Descargar TXT</a>"; ?>
        </div>
        <div class="tablenav-pages one-page"><span class="displaying-num"><?php echo count($jobsToday);;?> Ofertas Nuevas o Modificadas Hoy</span>
            <br class="clear">
        </div>
    </div>
    <?php
}
?>