<?php namespace Raalveco\Scaffolding\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ScaffoldCommand extends Command {

    protected $name = 'make:scaffold';
    protected $description = 'Command description.';

    protected $fields = false;

    protected $schema_template;
    protected $migration_template;
    protected $model_template;
    protected $controller_template;
    protected $lang_template;
    protected $lang_es_template;
    protected $route_template;
    protected $index_template;
    protected $new_template;
    protected $edit_template;
    protected $seed_template;

    protected $model_name;
    protected $plural_name;
    protected $views_prefix;
    protected $routes_prefix;
    protected $layout;
    protected $active;
    protected $gender;
    protected $singular;
    protected $plural;

    protected $fillables;

    public function __construct()
    {
        parent::__construct();

        $this->schema_template = file_get_contents(__DIR__."/../templates/schema.txt");
        $this->migration_template = file_get_contents(__DIR__."/../templates/migration.txt");
        $this->seed_template = file_get_contents(__DIR__."/../templates/seed.txt");
        $this->model_template = file_get_contents(__DIR__."/../templates/model.txt");
        $this->controller_template = file_get_contents(__DIR__."/../templates/controller.txt");
        $this->lang_template = file_get_contents(__DIR__."/../templates/lang.txt");
        $this->lang_es_template = file_get_contents(__DIR__."/../templates/lang.txt");
        $this->route_template = file_get_contents(__DIR__."/../templates/route.txt");

        $this->index_template = file_get_contents(__DIR__."/../templates/index.txt");
        $this->new_template = file_get_contents(__DIR__."/../templates/new.txt");
        $this->edit_template = file_get_contents(__DIR__."/../templates/edit.txt");

        $this->active = true;
    }

    public function init($model_name, $plural_name, $view_layout, $views_prefix = "", $routes_prefix = "", $active = true){
        $this->model_name = $model_name;
        $this->plural_name = $plural_name;

        $this->view_layout = trim($view_layout);
        $this->views_prefix = trim(str_replace("/",".",$views_prefix));
        $this->routes_prefix = trim($routes_prefix);

        $this->getFields();
    }

    public function setGender($gender = "M"){
        $this->gender = $gender;
    }

    public function setNumbers($singular, $plural){
        $this->singular = $singular;
        $this->plural = $plural;
    }

    public function saveFiles(){
        file_put_contents(base_path()."/database/migrations/".date("Y_m_d_His")."_create_table_".Str::lower($this->plural_name)."_table.php", $this->migration_template);
        file_put_contents(base_path()."/database/seeds/".Str::title($this->plural_name)."Seeder.php", $this->seed_template);

        if(!file_exists(app_path()."/Models")){
            mkdir(app_path()."/Models");
        }

        file_put_contents(app_path()."/Models/".Str::title($this->model_name).".php", $this->model_template);
        file_put_contents(app_path()."/Http/Controllers/".Str::title($this->plural_name)."Controller".".php", $this->controller_template);

        if(!file_exists(base_path()."/resources/lang/en")){
            mkdir(base_path()."/resources/lang/en");
        }

        if(!file_exists(base_path()."/resources/lang/es")){
            mkdir(base_path()."/resources/lang/es");
        }

        file_put_contents(base_path()."/resources/lang/en/".Str::lower($this->plural_name).".php", $this->lang_template);
        file_put_contents(base_path()."/resources/lang/es/".Str::lower($this->plural_name).".php", $this->lang_es_template);

        file_put_contents(app_path()."/Http/routes.php", $this->route_template, FILE_APPEND);

        if(!file_exists(base_path()."/resources/views/".($this->views_prefix != "" ? str_replace(".","/", $this->views_prefix) : ""))){
            mkdir(base_path()."/resources/views/".($this->views_prefix != "" ? str_replace(".","/", $this->views_prefix) : ""));
        }

        if(!file_exists(base_path()."/resources/views/".($this->views_prefix != "" ? str_replace(".","/", $this->views_prefix) : "")."/".Str::lower($this->plural_name))){
            mkdir(base_path()."/resources/views/".($this->views_prefix != "" ? str_replace(".","/", $this->views_prefix) : "")."/".Str::lower($this->plural_name));
        }

        file_put_contents(base_path()."/resources/views/".($this->views_prefix != "" ? str_replace(".","/", $this->views_prefix) : "")."/".Str::lower($this->plural_name)."/index.blade.php", $this->index_template);
        file_put_contents(base_path()."/resources/views/".($this->views_prefix != "" ? str_replace(".","/", $this->views_prefix) : "")."/".Str::lower($this->plural_name)."/new.blade.php", $this->new_template);
        file_put_contents(base_path()."/resources/views/".($this->views_prefix != "" ? str_replace(".","/", $this->views_prefix) : "")."/".Str::lower($this->plural_name)."/edit.blade.php", $this->edit_template);

        if(!file_exists(base_path()."/resources/lang/es/auth.php")){
            $tmp_file = file_get_contents(__DIR__."/../templates/lang/auth.txt");
            file_put_contents(base_path()."/resources/lang/es/auth.php", $tmp_file);
        }

        if(!file_exists(base_path()."/resources/lang/es/pagination.php")){
            $tmp_file = file_get_contents(__DIR__."/../templates/lang/pagination.txt");
            file_put_contents(base_path()."/resources/lang/es/pagination.php", $tmp_file);
        }

        if(!file_exists(base_path()."/resources/lang/es/passwords.php")){
            $tmp_file = file_get_contents(__DIR__."/../templates/lang/passwords.txt");
            file_put_contents(base_path()."/resources/lang/es/passwords.php", $tmp_file);
        }

        if(!file_exists(base_path()."/resources/lang/es/validation.php")){
            $tmp_file = file_get_contents(__DIR__."/../templates/lang/validation.txt");
            file_put_contents(base_path()."/resources/lang/es/validation.php", $tmp_file);
        }

        if(!file_exists(base_path()."/resources/lang/en/custom.php")){
            $tmp_file = file_get_contents(__DIR__."/../templates/lang/en.txt");
            file_put_contents(base_path()."/resources/lang/en/custom.php", $tmp_file);
        }

        if(!file_exists(base_path()."/resources/lang/es/custom.php")){
            $tmp_file = file_get_contents(__DIR__."/../templates/lang/es.txt");
            file_put_contents(base_path()."/resources/lang/es/custom.php", $tmp_file);
        }
    }

    public function makeMigration(){
        $this->schema_template = str_replace('$METHOD$', "create", $this->schema_template);
        $this->schema_template = str_replace('$TABLE$', Str::lower($this->plural_name), $this->schema_template);

        $fillables = "";
        $data_up = '$table->engine = \'InnoDB\';

            ';

        $data_up .= '$table->increments(\'id\');

            ';
        if($this->fields) foreach($this->fields as $field){
            $fillables .= "'$field->name',";

            $is_required = false;

            if(!empty($field->options)){
                foreach($field->options as $option){
                    if($option == "required"){
                        $is_required = true;
                    }
                }
            }

            if($field->name == "active"){
                $data_up .= '$table->'.$field->type.'(\''.$field->name.'\')->default(true);
            ';
            }
            else{
                $p1 = "";

                if($field->param1){
                    if(is_numeric($field->param1)){
                        $p1 = ", ".$field->param1;
                    }
                    else{
                        $p1 = ", '".$field->param1."'";
                    }
                }

                $p2 = "";

                if($field->param2){
                    if(is_numeric($field->param2)){
                        $p2 = ", ".$field->param2;
                    }
                    else{
                        $p2 = ", '".$field->param2."'";
                    }
                }

                if($is_required){
                    $data_up .= '$table->'.$field->type.'(\''.$field->name.'\''.$p1.$p2.');
            ';
                }
                else{
                    $data_up .= '$table->'.$field->type.'(\''.$field->name.'\''.$p1.$p2.')->nullable();
            ';
                }
            }
        }

        $data_up .= '
            $table->timestamps();
            ';

        if(Str::endsWith($fillables, ",")){
            $fillables = substr($fillables,0, strlen($fillables) - 1);
        }

        $this->fillables = $fillables;

        $this->schema_template = str_replace('$FIELDS$', $data_up, $this->schema_template);

        $this->migration_template = str_replace('$CLASS$', "CreateTable".Str::title($this->plural_name)."Table", $this->migration_template);
        $this->migration_template = str_replace('$UP$', $this->schema_template, $this->migration_template);
        $this->migration_template = str_replace('$DOWN$', "Schema::drop('".Str::lower($this->plural_name)."');", $this->migration_template);
    }

    public function makeModel(){
        $rules = '';
        if($this->fields) foreach($this->fields as $field){
            $rule = "";
            if(!empty($field->options)){
                $rule = implode("|", $field->options);
                $rules .= '"'.$field->name.'" => "'.$rule.'",
        ';
            }
        }
        $rules = trim($rules);

        $this->model_template = str_replace('$RULES$', $rules, $this->model_template);

        $this->model_template = str_replace('$NAME$', Str::title($this->model_name), $this->model_template);
        $this->model_template = str_replace('$FIELDS$', $this->fillables, $this->model_template);
    }

    public function makeController(){
        $this->controller_template = str_replace('$CONTROLLER_NAME$', Str::title($this->plural_name)."Controller", $this->controller_template);

        $use = str_replace(" ","", 'App\Models\ '.Str::title($this->model_name).';
        ');

        $this->controller_template = str_replace('$IMPORTS$', trim("use ".$use), $this->controller_template);

        $fields_get = "";
        if($this->fields) foreach($this->fields as $field){
            if($field->type == "boolean"){
                if($field->name == "active" && $this->active !== true && $this->active != "true"){
                    continue;
                }
                else{
                    $fields_get .= '$'.$field->name.' = Input::has("'.$field->name.'") ? (Input::get("'.$field->name.'") ? 1 : 0) : 0;
        ';
                }
            }

            if($field->type == "string"){
                $fields_get .= '$'.$field->name.' = Input::has("'.$field->name.'") ? Input::get("'.$field->name.'") : "";
        ';
            }

            if($field->type == "integer"){
                $fields_get .= '$'.$field->name.' = Input::has("'.$field->name.'") ? Input::get("'.$field->name.'") : "";
        ';
            }

            if($field->type == "decimal"){
                $fields_get .= '$'.$field->name.' = Input::has("'.$field->name.'") ? Input::get("'.$field->name.'") : "";
        ';
            }
        }

        $this->controller_template = str_replace('$FIELDS_GET$', $fields_get, $this->controller_template);

        $fields_update = "";
        if($this->fields) foreach($this->fields as $field){
            if($field->name == "active" && $this->active !== true && $this->active != "true") {
                continue;
            }
            $fields_update .= "$".$this->model_name."->".$field->name.' = $request->'.$field->name.';
        ';
        }

        $fields_update = trim($fields_update);

        $this->controller_template = str_replace('$FIELDS_UPDATE$', $fields_update, $this->controller_template);

        $this->controller_template = str_replace('$MODEL$', Str::title($this->model_name), $this->controller_template);
        $this->controller_template = str_replace('$TABLE$', Str::lower($this->plural_name), $this->controller_template);
        $this->controller_template = str_replace('$TABLE_SINGULAR$', Str::lower($this->model_name), $this->controller_template);

        $this->controller_template = str_replace('$PREFIX_VIEW$', $this->views_prefix ? Str::lower($this->views_prefix)."." : "", $this->controller_template);
        $this->controller_template = str_replace('$PREFIX_URL$', $this->routes_prefix ? "/".Str::lower($this->routes_prefix) : "", $this->controller_template);
    }

    public function makeTranslate(){
        $this->makeSpanishTranslate();

        $fields_lang = "";
        if($this->fields) foreach($this->fields as $field){
            $fields_lang .= '"'.$field->name.'" => "'.Str::title($field->name).'",
        ';
        }

        $fields_lang = trim($fields_lang);

        if(Str::endsWith($fields_lang, ",")){
            $fields_lang = substr($fields_lang,0, strlen($fields_lang) - 1);
        }

        $this->lang_template = str_replace('$FIELDS$', $fields_lang, $this->lang_template);

        $titles_lang = "";
        $titles_lang .= '"index" => "'.Str::title($this->plural_name).'",
        ';
        $titles_lang .= '"new" => "New '.Str::title($this->model_name).'",
        ';
        $titles_lang .= '"edit" => "Edit '.Str::title($this->model_name).'",
        ';
        $titles_lang .= '"delete" => "Delete '.Str::title($this->model_name).'",
        ';

        $titles_lang = trim($titles_lang);

        if(Str::endsWith($titles_lang, ",")){
            $titles_lang = substr($titles_lang,0, strlen($titles_lang) - 1);
        }

        $this->lang_template = str_replace('$TITLES$', $titles_lang, $this->lang_template);

        $buttons_lang = "";
        $buttons_lang .= '"register" => "Register",
        ';
        $buttons_lang .= '"update" => "Update",
        ';
        $buttons_lang .= '"cancel" => "Cancel",
        ';
        $buttons_lang .= '"yes" => "Yes",
        ';
        $buttons_lang .= '"no" => "No",
        ';

        $buttons_lang = trim($buttons_lang);

        if(Str::endsWith($buttons_lang, ",")){
            $buttons_lang = substr($buttons_lang,0, strlen($buttons_lang) - 1);
        }

        $this->lang_template = str_replace('$BUTTONS$', $buttons_lang, $this->lang_template);

        $notifications_lang = "";
        $notifications_lang .= '"register_successful" => "The '.Str::lower($this->model_name).' has been successfully registered.",
        ';
        $notifications_lang .= '"update_successful" => "The '.Str::lower($this->model_name).' has been successfully updated.",
        ';
        $notifications_lang .= '"activated_successful" => "The '.Str::lower($this->model_name).' has been successfully activated.",
        ';
        $notifications_lang .= '"deactivated_successful" => "The '.Str::lower($this->model_name).' has been successfully deactivated.",
        ';
        $notifications_lang .= '"delete_successful" => "The '.Str::lower($this->model_name).' has been successfully deleted.",
        ';
        $notifications_lang .= '"already_register" => "The '.Str::lower($this->model_name).' had been registered previously.",
        ';
        $notifications_lang .= '"no_exists" => "The '.Str::lower($this->model_name).' does not exist.",
        ';
        $notifications_lang .= '"delete_confirmation" => "Are you sure, that you will delete selected '.Str::lower($this->model_name).'?",
        ';

        $notifications_lang = trim($notifications_lang);

        if(Str::endsWith($notifications_lang, ",")){
            $notifications_lang = substr($notifications_lang,0, strlen($notifications_lang) - 1);
        }

        $this->lang_template = str_replace('$NOTIFICATIONS$', $notifications_lang, $this->lang_template);

        $validations_lang = "";
        $validations_lang .= '"required" => "This field is required.",
        ';
        $validations_lang .= '"email" => "This field is an invalid email.",
        ';
        $validations_lang .= '"digits" => "This field only accepts digits.",
        ';
        $validations_lang .= '"number" => "This field only accepts numeric values.",
        ';
        $validations_lang .= '"minlength" => "the minimum length of the field is {0}.",
        ';
        $validations_lang .= '"maxlength" => "the maximum length of the field is {0}.",
        ';

        $validations_lang = trim($validations_lang);

        if(Str::endsWith($validations_lang, ",")){
            $validations_lang = substr($validations_lang,0, strlen($validations_lang) - 1);
        }

        $this->lang_template = str_replace('$VALIDATIONS$', $validations_lang, $this->lang_template);
    }

    public function makeSpanishTranslate(){
        $fields_lang = "";
        if($this->fields) foreach($this->fields as $field){
            $fields_lang .= '"'.$field->name.'" => "'.Str::title($field->name).'",
        ';
        }

        $fields_lang = trim($fields_lang);

        if(Str::endsWith($fields_lang, ",")){
            $fields_lang = substr($fields_lang,0, strlen($fields_lang) - 1);
        }

        $this->lang_es_template = str_replace('$FIELDS$', $fields_lang, $this->lang_es_template);

        $titles_lang = "";
        $titles_lang .= '"index" => "'.Str::title($this->plural).'",
        ';

        if($this->gender == "M"){
            $titles_lang .= '"new" => "Nuevo '.Str::title($this->singular).'",
        ';
        }
        else{
            $titles_lang .= '"new" => "Nueva '.Str::title($this->singular).'",
        ';
        }

        $titles_lang .= '"edit" => "Editar '.Str::title($this->singular).'",
        ';
        $titles_lang .= '"delete" => "Eliminar '.Str::title($this->singular).'",
        ';

        $titles_lang = trim($titles_lang);

        if(Str::endsWith($titles_lang, ",")){
            $titles_lang = substr($titles_lang,0, strlen($titles_lang) - 1);
        }

        $this->lang_es_template = str_replace('$TITLES$', $titles_lang, $this->lang_es_template);

        $buttons_lang = "";
        $buttons_lang .= '"register" => "Registrar",
        ';
        $buttons_lang .= '"update" => "Modificar",
        ';
        $buttons_lang .= '"cancel" => "Cancelar",
        ';
        $buttons_lang .= '"yes" => "SI",
        ';
        $buttons_lang .= '"no" => "NO",
        ';

        $buttons_lang = trim($buttons_lang);

        if(Str::endsWith($buttons_lang, ",")){
            $buttons_lang = substr($buttons_lang,0, strlen($buttons_lang) - 1);
        }

        $this->lang_es_template = str_replace('$BUTTONS$', $buttons_lang, $this->lang_es_template);

        if($this->gender == "M"){
            $gender = "el";
            $gro = "o";
        }
        else{
            $gender = "la";
            $gro = "a";
        }

        $notifications_lang = "";
        $notifications_lang .= '"register_successful" => "'.Str::title($gender).' '.Str::lower($this->singular).' ha sido registrad'.$gro.' correctamente.",
        ';
        $notifications_lang .= '"update_successful" => "'.Str::title($gender).' '.Str::lower($this->singular).' ha sido modificad'.$gro.' correctamente.",
        ';
        $notifications_lang .= '"activated_successful" => "'.Str::title($gender).' '.Str::lower($this->singular).' ha sido activad'.$gro.' correctamente.",
        ';
        $notifications_lang .= '"deactivated_successful" => "'.Str::title($gender).' '.Str::lower($this->singular).' ha sido desactivad'.$gro.' correctamente.",
        ';
        $notifications_lang .= '"delete_successful" => "'.Str::title($gender).' '.Str::lower($this->singular).' ha sido eliminad'.$gro.' correctamente.",
        ';
        $notifications_lang .= '"already_register" => "'.Str::title($gender).' '.Str::lower($this->singular).' ya había sido registrad'.$gro.' previamente.",
        ';
        $notifications_lang .= '"no_exists" => "'.Str::title($gender).' '.Str::lower($this->singular).' no existe.",
        ';
        $notifications_lang .= '"delete_confirmation" => "¿Estás seguro que deseas eliminar '.$gender.' '.Str::lower($this->singular).' seleccionad'.$gro.'?",
        ';

        $notifications_lang = trim($notifications_lang);

        if(Str::endsWith($notifications_lang, ",")){
            $notifications_lang = substr($notifications_lang,0, strlen($notifications_lang) - 1);
        }

        $this->lang_es_template = str_replace('$NOTIFICATIONS$', $notifications_lang, $this->lang_es_template);

        $validations_lang = "";
        $validations_lang .= '"required" => "El campo es requerido.",
        ';
        $validations_lang .= '"email" => "El campo no es un email válido.",
        ';
        $validations_lang .= '"digits" => "El campo solo acepta dígitos.",
        ';
        $validations_lang .= '"number" => "El campo solo acepta valores numéricos.",
        ';
        $validations_lang .= '"minlength" => "El tamaño mínimo del campo es {0}.",
        ';
        $validations_lang .= '"maxlength" => "El tamaño máximo del campo es {0}.",
        ';
        $validations_lang .= '"min" => "El valor máximo del campo es {0}.",
        ';
        $validations_lang .= '"max" => "El valor máximo del campo es {0}.",
        ';
        $validations_lang .= '"range" => "El valor del campo debe estar en el rango {0} - {1}.",
        ';

        $validations_lang = trim($validations_lang);

        if(Str::endsWith($validations_lang, ",")){
            $validations_lang = substr($validations_lang,0, strlen($validations_lang) - 1);
        }

        $this->lang_es_template = str_replace('$VALIDATIONS$', $validations_lang, $this->lang_es_template);
    }

    public function makeRoutes(){
        if($this->routes_prefix == ""){
            $this->route_template = str_replace('$PARAMS$', "", $this->route_template);
        }
        else{
            $this->route_template = str_replace('$PARAMS$', '"prefix" => "'.$this->routes_prefix.'"', $this->route_template);
        }

        $routes = 'Route::get("/'.Str::lower($this->plural_name).'", "'.Str::title($this->plural_name).'Controller@index");
    Route::get("/'.Str::lower($this->plural_name).'/create", "'.Str::title($this->plural_name).'Controller@create");
    Route::post("/'.Str::lower($this->plural_name).'/store", "'.Str::title($this->plural_name).'Controller@store");
    Route::get("/'.Str::lower($this->plural_name).'/{id}/edit", "'.Str::title($this->plural_name).'Controller@edit");
    Route::post("/'.Str::lower($this->plural_name).'/update", "'.Str::title($this->plural_name).'Controller@update");
    Route::get("/'.Str::lower($this->plural_name).'/{id}/active", "'.Str::title($this->plural_name).'Controller@active");
    Route::get("/'.Str::lower($this->plural_name).'/{id}/deactive", "'.Str::title($this->plural_name).'Controller@deactive");
    Route::post("/'.Str::lower($this->plural_name).'/delete", "'.Str::title($this->plural_name).'Controller@destroy");';

        $this->route_template = str_replace('$ROUTES$', $routes, $this->route_template);
    }

    public function makeIndexView(){
        //Make a Index View
        $fields_index = "";
        if($this->fields) foreach($this->fields as $field){

            if($field->name != "active"){
                $fields_index .= '<td style="text-align: center">{{ trans("'.Str::lower($this->plural_name).'.fields.'.$field->name.'") }}</td>
                        ';
            }
            else{
                $fields_index .= '<td style="text-align: center"></td>
                        ';
            }
        }

        $fields_index = trim($fields_index);

        $this->index_template = str_replace('$FIELD_TITLE$', $fields_index, $this->index_template);

        $data_index = "";
        if($this->fields) foreach($this->fields as $field){
            if($field->name != "active"){
                $data_index .= '<td style="text-align: center; vertical-align: middle;">{{$'.Str::lower($this->model_name).'->'.Str::lower($field->name).'}}</td>
                                ';
            }
            else{
                $url_active = '$PREFIX_URL$'.'/'.Str::lower($this->plural_name)."/'.$".Str::lower($this->model_name)."->id.'/active";
                $url_deactive = '$PREFIX_URL$'.'/'.Str::lower($this->plural_name)."/'.$".Str::lower($this->model_name)."->id.'/deactive";

                $data_index .= '<td style="text-align: center; vertical-align: middle; width: 50px;"><a href="{{$'.Str::lower($this->model_name).'->active ? '."'$url_deactive'"." : '".$url_active."'".'}}" class="btn btn-sm {{$'.Str::lower($this->model_name).'->active ? "green" : "red"}}" style="width: 35px; margin-right: 0px;"><i class="fa {{$'.Str::lower($this->model_name).'->active ? "fa-check" : "fa-times"}}"></i></a></td>
                                ';
            }
        }

        $data_index = trim($data_index);

        $this->index_template = str_replace('$FIELD_DATA$', $data_index, $this->index_template);

        $this->index_template = str_replace('$TITLE$', Str::lower($this->plural_name).'.titles.index', $this->index_template);
        $this->index_template = str_replace('$NEW_TITLE$', Str::lower($this->plural_name).'.titles.new', $this->index_template);

        $this->index_template = str_replace('$TABLE$', Str::lower($this->plural_name), $this->index_template);
        $this->index_template = str_replace('$TABLE_SINGULAR$', Str::lower($this->model_name), $this->index_template);

        $this->index_template = str_replace('$LAYOUT_VIEW$', $this->view_layout, $this->index_template);
        $this->index_template = str_replace('$PREFIX_VIEW$', $this->views_prefix ? Str::lower($this->views_prefix)."." : "", $this->index_template);
        $this->index_template = str_replace('$PREFIX_URL$', $this->routes_prefix ? "/".Str::lower($this->routes_prefix) : "", $this->index_template);

        $this->index_template = str_replace('$DELETE_TITLE$', Str::lower($this->plural_name).'.titles.delete', $this->index_template);
        $this->index_template = str_replace('$DELETE_CONFIRMATION$', Str::lower($this->plural_name).'.notifications.delete_confirmation', $this->index_template);
    }

    public function makeNewView(){
        //Make New View
        $this->new_template = str_replace('$NEW_TITLE$', Str::lower($this->plural_name).'.titles.new', $this->new_template);

        $this->new_template = str_replace('$TABLE$', Str::lower($this->plural_name), $this->new_template);
        $this->new_template = str_replace('$TABLE_SINGULAR$', Str::lower($this->model_name), $this->new_template);

        $this->new_template = str_replace('$LAYOUT_VIEW$', $this->view_layout, $this->new_template);
        $this->new_template = str_replace('$PREFIX_VIEW$', $this->views_prefix ? Str::lower($this->views_prefix)."." : "", $this->new_template);
        $this->new_template = str_replace('$PREFIX_URL$', $this->routes_prefix ? "/".Str::lower($this->routes_prefix) : "", $this->new_template);

        $fields_new = "";
        if($this->fields) foreach($this->fields as $field){
            $is_required = "";

            if(!empty($field->options)){
                foreach($field->options as $option){
                    $option = explode(":", $option);
                    if($option[0] == "required"){
                        $is_required = '<span class="required"> * </span>';
                    }
                }
            }

            if($field->type == "string") {
                $fields_new .= '<div class="form-group">
                            <label class="control-label col-md-3">
                                {{ trans("' . Str::lower($this->plural_name) . '.fields.' . Str::lower($field->name) . '") }} ' . $is_required . '
                            </label>
                            <div class="col-md-4">
                                <input type="text" name="' . Str::lower($field->name) . '" value="{{Input::old("' . Str::lower($field->name) . '")}}" class="form-control"/>
                            </div>
                        </div>
                        ';
            }

            if($field->type == "integer") {
                $fields_new .= '<div class="form-group">
                            <label class="control-label col-md-3">
                                {{ trans("' . Str::lower($this->plural_name) . '.fields.' . Str::lower($field->name) . '") }} ' . $is_required . '
                            </label>
                            <div class="col-md-4">
                                <input type="text" name="' . Str::lower($field->name) . '" value="{{Input::old("' . Str::lower($field->name) . '")}}" class="form-control digits"/>
                            </div>
                        </div>
                        ';
            }

            if($field->type == "decimal") {
                $fields_new .= '<div class="form-group">
                            <label class="control-label col-md-3">
                                {{ trans("' . Str::lower($this->plural_name) . '.fields.' . Str::lower($field->name) . '") }} ' . $is_required . '
                            </label>
                            <div class="col-md-4">
                                <input type="text" name="' . Str::lower($field->name) . '" value="{{Input::old("' . Str::lower($field->name) . '")}}" class="form-control numeric"/>
                            </div>
                        </div>
                        ';
            }

            if($field->type == "boolean") {
                if($field->name == "active"){
                    if($this->active === true || $this->active == "true"){
                        $fields_new .= '<div class="form-group">
                            <label class="control-label col-md-3">
                                {{ trans("' . Str::lower($this->plural_name) . '.fields.' . Str::lower($field->name) . '") }} ' . $is_required . '
                            </label>
                            <div class="col-md-4">
                                <input type="checkbox"{{$'.Str::lower($this->model_name).'->'.Str::lower($field->name).' == 1 '." ? '".'checked="checked"'."'".' : ""}} name="'.Str::lower($field->name).'" class="make-switch" data-on-color="success" data-off-color="danger" data-on-text="{{ trans("' . Str::lower($this->plural_name) . '.buttons.yes") }}" data-off-text="{{ trans("' . Str::lower($this->plural_name) . '.buttons.no") }}" >
                            </div>
                        </div>
                        ';
                    }
                }
                else{
                    $fields_new .= '<div class="form-group">
                            <label class="control-label col-md-3">
                                {{ trans("' . Str::lower($this->plural_name) . '.fields.' . Str::lower($field->name) . '") }} ' . $is_required . '
                            </label>
                            <div class="col-md-4">
                                <input type="checkbox"{{$'.Str::lower($this->model_name).'->'.Str::lower($field->name).' == 1 '." ? '".'checked="checked"'."'".' : ""}} name="'.Str::lower($field->name).'" class="make-switch" data-on-color="success" data-off-color="danger" data-on-text="{{ trans("' . Str::lower($this->plural_name) . '.buttons.yes") }}" data-off-text="{{ trans("' . Str::lower($this->plural_name) . '.buttons.no") }}" >
                            </div>
                        </div>
                        ';
                }
            }
        }

        $fields_new = trim($fields_new);

        $this->new_template = str_replace('$FIELDS$', $fields_new, $this->new_template);

        $rules_new = "";
        $messages_new = "";
        if($this->fields) foreach($this->fields as $field){
            $rules_tmp = '-';
            $messages_tmp = '-';
            if(!empty($field->options)){
                foreach($field->options as $option){
                    $option = explode(":", $option);

                    if($option[0] == "required"){
                        $rules_tmp .= '
                required: true,';
                        $messages_tmp .= '
                required: "<?php echo trans("'.Str::lower($this->plural_name).'.validations.required") ?>",';
                    }
                    if($option[0] == "email"){
                        $rules_tmp .= '
                email: true,';
                        $messages_tmp .= '
                email: "<?php echo trans("'.Str::lower($this->plural_name).'.validations.email") ?>",';
                    }

                    if ($option[0] == "maxlength") {
                        $rules_tmp .= '
                maxlength: ' . $option[1] . ',';
                        $messages_tmp .= '
                maxlength: "<?php echo trans("' . Str::lower($this->plural_name) . '.validations.maxlength") ?>",';
                    }

                    if ($option[0] == "minlength") {
                        $rules_tmp .= '
                minlength: ' . $option[1] . ',';
                        $messages_tmp .= '
                minlength: "<?php echo trans("' . Str::lower($this->plural_name) . '.validations.minlength") ?>",';
                    }

                    if ($option[0] == "max") {
                        $rules_tmp .= '
                max: ' . $option[1] . ',';
                        $messages_tmp .= '
                max: "<?php echo trans("' . Str::lower($this->plural_name) . '.validations.max") ?>",';
                    }

                    if ($option[0] == "min") {
                        $rules_tmp .= '
                min: ' . $option[1] . ',';
                        $messages_tmp .= '
                min: "<?php echo trans("' . Str::lower($this->plural_name) . '.validations.min") ?>",';
                    }

                    if ($option[0] == "between") {
                        $rules_tmp .= '
                range: [' . $option[1].", ". $option[2] . '],';
                        $messages_tmp .= '
                range: "<?php echo trans("' . Str::lower($this->plural_name) . '.validations.range") ?>",';
                    }
                }
            }

            if($field->type == "integer"){
                $rules_tmp .= '
                digits: true,';
                $messages_tmp .= '
                digits: "<?php echo trans("'.Str::lower($this->plural_name).'.validations.digits") ?>",';
            }

            if($field->type == "decimal"){
                $rules_tmp .= '
                number: true,';
                $messages_tmp .= '
                number: "<?php echo trans("'.Str::lower($this->plural_name).'.validations.number") ?>",';
            }

            $rules_tmp = trim($rules_tmp);
            $messages_tmp = trim($messages_tmp);

            if(Str::endsWith($rules_tmp, ",")){
                $rules_tmp = substr($rules_tmp,0, strlen($rules_tmp) - 1)."";
            }

            if(Str::endsWith($messages_tmp, ",")){
                $messages_tmp = substr($messages_tmp,0, strlen($messages_tmp) - 1)."";
            }

            if($rules_tmp == "-"){
                continue;
            }
            else{
                $rules_tmp = str_replace("-", "", $rules_tmp);
                $messages_tmp = str_replace("-", "", $messages_tmp);
            }

            $rules_new .= '
            '.$field->name.': {';

            $messages_new .= '
            '.$field->name.': {';

            $rules_new .= $rules_tmp;
            $messages_new .= $messages_tmp;

            $rules_new .= '
            },';
            $messages_new .= '
            },';

            $rules_new = trim($rules_new);
            $messages_new = trim($messages_new);
        }

        if(Str::endsWith($rules_new, ",")){
            $rules_new = substr($rules_new,0, strlen($rules_new) - 1)."";
        }

        if(Str::endsWith($messages_new, ",")){
            $messages_new = substr($messages_new,0, strlen($messages_new) - 1)."";
        }

        $this->new_template = str_replace('$RULES$', $rules_new, $this->new_template);
        $this->new_template = str_replace('$MESSAGES$', $messages_new, $this->new_template);
    }

    public function makeEditView(){
        //Make Edit View
        $this->edit_template = str_replace('$UPDATE_TITLE$', Str::lower($this->plural_name).'.titles.edit', $this->edit_template);

        $this->edit_template = str_replace('$TABLE$', Str::lower($this->plural_name), $this->edit_template);
        $this->edit_template = str_replace('$TABLE_SINGULAR$', Str::lower($this->model_name), $this->edit_template);

        $this->edit_template = str_replace('$LAYOUT_VIEW$', $this->view_layout, $this->edit_template);
        $this->edit_template = str_replace('$PREFIX_VIEW$', $this->views_prefix ? Str::lower($this->views_prefix)."." : "", $this->edit_template);
        $this->edit_template = str_replace('$PREFIX_URL$', $this->routes_prefix ? "/".Str::lower($this->routes_prefix) : "", $this->edit_template);

        $fields_update = "";
        if($this->fields) foreach($this->fields as $field){
            $is_required = "";

            if(!empty($field->options)){
                foreach($field->options as $option){
                    $option = explode(":", $option);

                    if($option[0] == "required"){
                        $is_required = '<span class="required"> * </span>';
                    }
                }
            }

            if($field->type == "boolean") {
                if($field->name == "active"){
                    if($this->active === true || $this->active == "true"){
                        $fields_update .= '<div class="form-group">
                            <label class="control-label col-md-3">
                                {{ trans("' . Str::lower($this->plural_name) . '.fields.' . Str::lower($field->name) . '") }} ' . $is_required . '
                            </label>
                            <div class="col-md-4">
                                <input type="checkbox"{{$'.Str::lower($this->model_name).'->'.Str::lower($field->name).' == 1 ? '."'".'checked="checked"'."'".' : ""}} name="'.Str::lower($field->name).'" class="make-switch" data-on-color="success" data-off-color="danger" data-on-text="{{ trans("' . Str::lower($this->plural_name) . '.buttons.yes") }}" data-off-text="{{ trans("' . Str::lower($this->plural_name) . '.buttons.no") }}" >
                            </div>
                        </div>
                        ';
                    }
                }
                else{
                    $fields_update .= '<div class="form-group">
                            <label class="control-label col-md-3">
                                {{ trans("' . Str::lower($this->plural_name) . '.fields.' . Str::lower($field->name) . '") }} ' . $is_required . '
                            </label>
                            <div class="col-md-4">
                                <input type="checkbox"{{$'.Str::lower($this->model_name).'->'.Str::lower($field->name).' == 1 ? '."'".'checked="checked"'."'".' : ""}} name="'.Str::lower($field->name).'" class="make-switch" data-on-color="success" data-off-color="danger" data-on-text="{{ trans("' . Str::lower($this->plural_name) . '.buttons.yes") }}" data-off-text="{{ trans("' . Str::lower($this->plural_name) . '.buttons.no") }}" >
                            </div>
                        </div>
                        ';
                }
            }
            else{
                $fields_update .= '<div class="form-group">
                            <label class="control-label col-md-3">
                                {{ trans("'.Str::lower($this->plural_name).'.fields.'.Str::lower($field->name).'") }} '.$is_required.'
                            </label>
                            <div class="col-md-4">
                                <input type="text" name="'.Str::lower($field->name).'" value="{{$'.Str::lower($this->model_name).'->'.Str::lower($field->name).'}}" class="form-control"/>
                            </div>
                        </div>
                        ';
            }
        }

        $fields_update = trim($fields_update);

        $this->edit_template = str_replace('$FIELDS$', $fields_update, $this->edit_template);

        $rules_update = "";
        $messages_update = "";
        if($this->fields) foreach($this->fields as $field){
            $rules_tmp = '-';
            $messages_tmp = '-';
            if(!empty($field->options)) {
                foreach ($field->options as $option) {
                    $option = explode(":", $option);

                    if ($option[0] == "required") {
                        $rules_tmp .= '
                required: true,';
                        $messages_tmp .= '
                required: "<?php echo trans("' . Str::lower($this->plural_name) . '.validations.required") ?>",';
                    }
                    if ($option[0] == "email") {
                        $rules_tmp .= '
                email: true,';
                        $messages_tmp .= '
                email: "<?php echo trans("' . Str::lower($this->plural_name) . '.validations.email") ?>",';
                    }

                    if ($option[0] == "max") {
                        $rules_tmp .= '
                max: ' . $option[1] . ',';
                        $messages_tmp .= '
                max: "<?php echo trans("' . Str::lower($this->plural_name) . '.validations.max") ?>",';
                    }

                    if ($option[0] == "min") {
                    $rules_tmp .= '
                min: ' . $option[1] . ',';
                    $messages_tmp .= '
                min: "<?php echo trans("' . Str::lower($this->plural_name) . '.validations.min") ?>",';
                    }

                    if ($option[0] == "between") {
                        $rules_tmp .= '
                range: [' . $option[1].", ". $option[2] . '],';
                        $messages_tmp .= '
                range: "<?php echo trans("' . Str::lower($this->plural_name) . '.validations.range") ?>",';
                    }
                }
            }

            if($field->type == "integer"){
                $rules_tmp .= '
                digits: true,';
                $messages_tmp .= '
                digits: "<?php echo trans("'.Str::lower($this->plural_name).'.validations.digits") ?>",';
            }

            if($field->type == "decimal"){
                $rules_tmp .= '
                number: true,';
                $messages_tmp .= '
                number: "<?php echo trans("'.Str::lower($this->plural_name).'.validations.number") ?>",';
            }

            $rules_tmp = trim($rules_tmp);
            $messages_tmp = trim($messages_tmp);

            if(Str::endsWith($rules_tmp, ",")){
                $rules_tmp = substr($rules_tmp,0, strlen($rules_tmp) - 1)."";
            }

            if(Str::endsWith($messages_tmp, ",")){
                $messages_tmp = substr($messages_tmp,0, strlen($messages_tmp) - 1)."";
            }

            if($rules_tmp == "-"){
                continue;
            }
            else{
                $rules_tmp = str_replace("-", "", $rules_tmp);
                $messages_tmp = str_replace("-", "", $messages_tmp);
            }

            $rules_update .= '
            '.$field->name.': {';

            $messages_update .= '
            '.$field->name.': {';

            $rules_update .= $rules_tmp;
            $messages_update .= $messages_tmp;

            $rules_update .= '
            },';
            $messages_update .= '
            },';

            $rules_update = trim($rules_update);
            $messages_update = trim($messages_update);
        }

        if(Str::endsWith($rules_update, ",")){
            $rules_update = substr($rules_update,0, strlen($rules_update) - 1)."";
        }

        if(Str::endsWith($messages_update, ",")){
            $messages_update = substr($messages_update,0, strlen($messages_update) - 1)."";
        }

        $this->edit_template = str_replace('$RULES$', $rules_update, $this->edit_template);
        $this->edit_template = str_replace('$MESSAGES$', $messages_update, $this->edit_template);
    }

    public function makeSeeder(){
        $this->seed_template = str_replace('$SEEDER_NAME$', Str::title($this->plural_name)."Seeder", $this->seed_template);

        $fields_create = "";
        if($this->fields) foreach($this->fields as $field){
            $value = "";
            if($field->type == "string"){
                $value = "Str::random()";
            }

            if($field->type == "integer"){
                $value = "rand(5,100)";
            }

            if($field->type == "decimal"){
                $value = "rand(5,500) / 5";
            }

            if($field->type == "boolean"){
                $value = "rand(0,1)";
            }

            $fields_create .= "'".$field->name."'".' => '.$value.',
                    ';
        }

        $fields_create = trim($fields_create);

        if(Str::endsWith($fields_create, ",")){
            $fields_create = substr($fields_create,0, strlen($fields_create) - 1);
        }

        $this->seed_template = str_replace('$FIELDS_CREATE$', trim($fields_create), $this->seed_template);
        $this->seed_template = str_replace('$MODEL$', Str::title($this->model_name), $this->seed_template);
    }

    public function getFields(){
        if($this->fields){
            return $this->fields;
        }

        $fields_string = $this->option("fields");

        $fields = collect();

        $fields_string = str_replace('"','', $fields_string);

        $fields_array = explode(",", $fields_string);

        if($fields_array) foreach($fields_array as $field_tmp){
            $field_tmp = explode("[", $field_tmp);

            $field_info = explode(":", $field_tmp[0]);

            $field = new \stdClass();
            $field->name = trim($field_info[0]);
            $field->type = trim($field_info[1]);

            $field->param1 = isset($field_info[2]) ? trim($field_info[2]) : false;
            $field->param2 = isset($field_info[3]) ? trim($field_info[3]) : false;
            $field->param3 = isset($field_info[4]) ? trim($field_info[4]) : false;

            $field->type = $field_info[1];

            $field->options = array();

            $field_tmp[1] = str_replace("]","",$field_tmp[1]);

            if($field_tmp[1]){
                $field_rules = explode("|", $field_tmp[1]);

                $i = 0;
                while(isset($field_rules[$i])){
                    $field->options[$i] = $field_rules[$i];
                    $i++;
                }
            }

            $fields->push($field);
        }

        $this->fields = $fields;

        return $this->fields;
    }

    public function fire()
    {
        $argument = $this->argument("model");

        $fields = $this->option("fields");

        if(!$fields){
            echo "Los campos del modelo no han sido definidos.";

            return;
        }

        $singular = $this->ask(Str::singular($argument).' en español se escribe: ',Str::lower(Str::singular($argument)));
        $singular = Str::upper($singular);

        $plural = $this->ask(Str::plural($argument).' en español se escribe: ',Str::lower(Str::plural($argument)));
        $plural = Str::upper($plural);

        $gender = $this->ask('Cual es el género del Modelo '.$argument.': ',"M");
        $gender = Str::upper($gender);

        $routes_prefix = $this->ask('Prefijo que se utilizará en las rutas y urls: '," ");
        $routes_prefix = Str::lower($routes_prefix);

        $layout = $this->ask('Layout con el que se crearan las vistas: ',"layout.base");
        $layout = Str::lower($layout);

        $views_prefix = $this->ask('Carpeta donde se guardaran las vistas: '," ");
        $views_prefix = Str::lower($views_prefix);

        $this->init(Str::lower($argument), Str::plural($argument), $layout, $views_prefix, $routes_prefix);
        $this->setGender($gender);
        $this->setNumbers($singular, $plural);

        $this->makeMigration();
        $this->makeModel();
        $this->makeSeeder();
        $this->makeController();
        $this->makeTranslate();
        $this->makeRoutes();
        $this->makeIndexView();
        $this->makeNewView();
        $this->makeEditView();

        $this->saveFiles();
    }

    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'Model Name.']
        ];
    }

    protected function getOptions()
    {
        return [
            ['fields', null, InputOption::VALUE_OPTIONAL, "Entity's Fields.", null]
        ];
    }

}