<?php namespace Scaffolding\Commands;

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
    protected $route_template;
    protected $index_template;
    protected $new_template;
    protected $edit_template;
    protected $seed_template;

    protected $model_name;
    protected $plural_name;
    protected $prefix;
    protected $active;

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
        $this->route_template = file_get_contents(__DIR__."/../templates/route.txt");

        $this->index_template = file_get_contents(__DIR__."/../templates/index.txt");
        $this->new_template = file_get_contents(__DIR__."/../templates/new.txt");
        $this->edit_template = file_get_contents(__DIR__."/../templates/edit.txt");

        $this->active = true;
    }

    public function init($model_name, $plural_name){
        $this->model_name = $model_name;
        $this->plural_name = $plural_name;

        $this->setPrefix();
        $this->setActive();
        $this->getFields();
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
        file_put_contents(base_path()."/resources/lang/es/".Str::lower($this->plural_name).".php", $this->lang_template);

        file_put_contents(app_path()."/Http/routes.php", $this->route_template, FILE_APPEND);

        if(!file_exists(base_path()."/resources/views/".($this->prefix != "" ? $this->prefix : ""))){
            mkdir(base_path()."/resources/views/".($this->prefix != "" ? $this->prefix : ""));
        }

        if(!file_exists(base_path()."/resources/views/".($this->prefix != "" ? $this->prefix."/" : "").Str::lower($this->plural_name))){
            mkdir(base_path()."/resources/views/".($this->prefix != "" ? $this->prefix."/" : "").Str::lower($this->plural_name));
        }

        file_put_contents(base_path()."/resources/views/".($this->prefix != "" ? $this->prefix."/" : "").Str::lower($this->plural_name)."/index.blade.php", $this->index_template);
        file_put_contents(base_path()."/resources/views/".($this->prefix != "" ? $this->prefix."/" : "").Str::lower($this->plural_name)."/new.blade.php", $this->new_template);
        file_put_contents(base_path()."/resources/views/".($this->prefix != "" ? $this->prefix."/" : "").Str::lower($this->plural_name)."/edit.blade.php", $this->edit_template);

        $src = __DIR__."/../layout";
        $dest = base_path()."/resources/views/".($this->prefix != "" ? $this->prefix."/" : "");

        shell_exec("cp -r $src $dest");
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
                if($is_required){
                    $data_up .= '$table->'.$field->type.'(\''.$field->name.'\');
            ';
                }
                else{
                    $data_up .= '$table->'.$field->type.'(\''.$field->name.'\')->nullable();
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

        $validations = "";
        if($this->fields) foreach($this->fields as $field){

            if(!empty($field->options)){
                foreach($field->options as $option){
                    if($option == "unique"){
                        $validations .= '
        if($MODEL$::whereRaw("'.$field->name." = '$".$field->name."'".'")->count() > 0){
            Session::flash("error", trans("$TABLE$.notifications.already_register"));

            return Redirect::to("$PREFIX_URL$/$TABLE$/create")->withInput();
        }
        ';
                    }

                    if($option == "required" && $field->name != "active"){
                        $validations .= '
        if($'.$field->name.' == ""){
            Session::flash("error", trans("$TABLE$.notifications.field_'.$field->name.'_missing"));

            return Redirect::to("$PREFIX_URL$/$TABLE$/create")->withInput();
        }
        ';
                    }
                }
            }

            if($field->type == "integer"){
                $validations .= '
        if(!is_numeric($'.$field->name.')){
            Session::flash("error", trans("$TABLE$.notifications.field_'.$field->name.'_is_integer"));

            return Redirect::to("$PREFIX_URL$/$TABLE$/create")->withInput();
        }
        ';
            }

            if($field->type == "decimal"){
                $validations .= '
        if(!is_numeric($'.$field->name.')){
            Session::flash("error", trans("$TABLE$.notifications.field_'.$field->name.'_is_decimal"));

            return Redirect::to("$PREFIX_URL$/$TABLE$/create")->withInput();
        }
        ';
            }
        }

        $validations = trim($validations);

        $this->controller_template = str_replace('$VALIDATIONS$', $validations, $this->controller_template);

        $validations_update = "";
        if($this->fields) foreach($this->fields as $field){
            if(!empty($field->options)){
                foreach($field->options as $option){
                    if($option == "unique"){
                        $validations_update .= '
        if($MODEL$::whereRaw("'.$field->name." = '$".$field->name."'".' AND id <> $'.$this->model_name.'->id")->count() > 0){
            Session::flash("error", trans("$TABLE$.notifications.already_register"));

            return Redirect::to("$PREFIX_URL$/$TABLE$/$'.$this->model_name.'->id/edit")->withInput();
        }
        ';
                    }

                    if($option == "required" && $field->name != "active"){
                        $validations_update .= '
        if($'.$field->name.' == ""){
            Session::flash("error", trans("$TABLE$.notifications.field_'.$field->name.'_missing"));

            return Redirect::to("$PREFIX_URL$/$TABLE$/$'.$this->model_name.'->id/edit")->withInput();
        }
        ';
                    }
                }
            }

            if($field->type == "integer"){
                $validations_update .= '
        if(!is_numeric($'.$field->name.')){
            Session::flash("error", trans("$TABLE$.notifications.field_'.$field->name.'_is_integer"));

            return Redirect::to("$PREFIX_URL$/$TABLE$/$'.$this->model_name.'->id/edit")->withInput();
        }
        ';
            }

            if($field->type == "decimal"){
                $validations_update .= '
        if(!is_numeric($'.$field->name.')){
            Session::flash("error", trans("$TABLE$.notifications.field_'.$field->name.'_is_decimal"));

            return Redirect::to("$PREFIX_URL$/$TABLE$/$'.$this->model_name.'->id/edit")->withInput();
        }
        ';
            }
        }

        $validations_update = trim($validations_update);

        $this->controller_template = str_replace('$VALIDATIONS_UPDATE$', $validations_update, $this->controller_template);

        $fields_create = "";
        if($this->fields) foreach($this->fields as $field){
            if($field->name == "active" && $this->active !== true && $this->active != "true") {
                continue;
            }

            $fields_create .= "'".$field->name."'".' => $'.$field->name.',
                ';
        }

        $fields_create = trim($fields_create);

        if(Str::endsWith($fields_create, ",")){
            $fields_create = substr($fields_create,0, strlen($fields_create) - 1);
        }

        $this->controller_template = str_replace('$FIELDS_CREATE$', trim($fields_create), $this->controller_template);

        $fields_update = "";
        if($this->fields) foreach($this->fields as $field){
            if($field->name == "active" && $this->active !== true && $this->active != "true") {
                continue;
            }
            $fields_update .= "$".$this->model_name."->".$field->name.' = $'.$field->name.';
        ';
        }

        $fields_update = trim($fields_update);

        $this->controller_template = str_replace('$FIELDS_UPDATE$', $fields_update, $this->controller_template);

        $this->controller_template = str_replace('$MODEL$', Str::title($this->model_name), $this->controller_template);
        $this->controller_template = str_replace('$TABLE$', Str::lower($this->plural_name), $this->controller_template);
        $this->controller_template = str_replace('$TABLE_SINGULAR$', Str::lower($this->model_name), $this->controller_template);

        $this->controller_template = str_replace('$PREFIX_VIEW$', $this->prefix ? Str::lower($this->prefix)."." : "", $this->controller_template);
        $this->controller_template = str_replace('$PREFIX_URL$', $this->prefix ? "/".Str::lower($this->prefix) : "", $this->controller_template);
    }

    public function makeTranslate(){
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

        if($this->fields) foreach($this->fields as $field) {

            if (!empty($field->options)) {
                foreach ($field->options as $option) {
                    if ($option == "required") {
                        $notifications_lang .= '"field_' . $field->name . '_missing" => "The field ' . Str::lower($field->name) . ' is required.",
        ';
                    }
                }
            }

            if ($field->type == "integer") {
                if ($option == "required") {
                    $notifications_lang .= '"field_' . $field->name . '_is_integer" => "The field ' . Str::lower($field->name) . ' must contain an integer value.",
        ';
                }
            }

            if ($field->type == "decimal") {
                if ($option == "required") {
                    $notifications_lang .= '"field_' . $field->name . '_is_decimal" => "The field ' . Str::lower($field->name) . ' must contain an numeric value.",
        ';
                }
            }
        }

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

    public function makeRoutes(){
        if($this->prefix == ""){
            $this->route_template = str_replace('$PARAMS$', "", $this->route_template);
        }
        else{
            $this->route_template = str_replace('$PARAMS$', '"prefix" => "'.$this->prefix.'"', $this->route_template);
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

        $this->index_template = str_replace('$PREFIX_VIEW$', $this->prefix ? Str::lower($this->prefix)."." : "", $this->index_template);
        $this->index_template = str_replace('$PREFIX_URL$', $this->prefix ? "/".Str::lower($this->prefix) : "", $this->index_template);

        $this->index_template = str_replace('$DELETE_TITLE$', Str::lower($this->plural_name).'.titles.delete', $this->index_template);
        $this->index_template = str_replace('$DELETE_CONFIRMATION$', Str::lower($this->plural_name).'.notifications.delete_confirmation', $this->index_template);
    }

    public function makeNewView(){
        //Make New View
        $this->new_template = str_replace('$NEW_TITLE$', Str::lower($this->plural_name).'.titles.new', $this->new_template);

        $this->new_template = str_replace('$TABLE$', Str::lower($this->plural_name), $this->new_template);
        $this->new_template = str_replace('$TABLE_SINGULAR$', Str::lower($this->model_name), $this->new_template);

        $this->new_template = str_replace('$PREFIX_VIEW$', $this->prefix ? Str::lower($this->prefix)."." : "", $this->new_template);
        $this->new_template = str_replace('$PREFIX_URL$', $this->prefix ? "/".Str::lower($this->prefix) : "", $this->new_template);

        $fields_new = "";
        if($this->fields) foreach($this->fields as $field){
            $is_required = "";

            if(!empty($field->options)){
                foreach($field->options as $option){
                    if($option == "required"){
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
                    if($option == "required"){
                        $rules_tmp .= '
                required: true,';
                        $messages_tmp .= '
                required: "<?php echo trans("'.Str::lower($this->plural_name).'.validations.required") ?>",';
                    }
                    if($option == "email"){
                        $rules_tmp .= '
                email: true,';
                        $messages_tmp .= '
                email: "<?php echo trans("'.Str::lower($this->plural_name).'.validations.email") ?>",';
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

        $this->edit_template = str_replace('$PREFIX_VIEW$', $this->prefix ? Str::lower($this->prefix)."." : "", $this->edit_template);
        $this->edit_template = str_replace('$PREFIX_URL$', $this->prefix ? "/".Str::lower($this->prefix) : "", $this->edit_template);

        $fields_update = "";
        if($this->fields) foreach($this->fields as $field){
            $is_required = "";

            if(!empty($field->options)){
                foreach($field->options as $option){
                    if($option == "required"){
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
            if(!empty($field->options)){
                foreach($field->options as $option){
                    if($option == "required"){
                        $rules_tmp .= '
                required: true,';
                        $messages_tmp .= '
                required: "<?php echo trans("'.Str::lower($this->plural_name).'.validations.required") ?>",';
                    }
                    if($option == "email"){
                        $rules_tmp .= '
                email: true,';
                        $messages_tmp .= '
                email: "<?php echo trans("'.Str::lower($this->plural_name).'.validations.email") ?>",';
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

    public function setPrefix(){
        try{
            $prefix = $this->option("prefix");
        }
        catch(\InvalidArgumentException $e){
            $prefix = "";
        }

        $this->prefix = $prefix;
    }

    public function setActive(){
        try{
            $active = $this->option("active");
        }
        catch(\InvalidArgumentException $e){
            $active = true;
        }

        $this->active = $active;
    }

    public function getFields(){
        if($this->fields){
            return $this->fields;
        }

        $fields_string = $this->option("fields");

        $fields = collect();

        if($fields_string == ""){
            $field = new \stdClass();
            $field->name = "name";
            $field->type = "string";
            $field->options = array("required");

            $fields->push($field);

            $field = new \stdClass();
            $field->name = "active";
            $field->type = "boolean";
            $field->options = array("required");

            $fields->push($field);
        }
        else{
            $fields_string = str_replace('"','', $fields_string);

            $fields_array = explode(",", $fields_string);

            if($fields_array) foreach($fields_array as $field_tmp){
                $field_tmp = explode(":", $field_tmp);

                $field = new \stdClass();
                $field->name = $field_tmp[0];
                $field->type = $field_tmp[1];

                $field->options = array();

                $i = 2;
                while(isset($field_tmp[$i])){
                    $field->options[$i-2] = $field_tmp[$i];
                    $i++;
                }

                $fields->push($field);
            }
        }

        $this->fields = $fields;

        return $this->fields;
    }

    public function fire()
    {
        $argument = $this->argument("model");

        //echo $name."\n";
        //echo $plural_name."\n";
        //echo $modal_name."\n"."\n";

        //$spanish_name = $this->ask('Escribe el nombre de la entidad en Singular?');
        //$spanish_name = Str::title($spanish_name);

        //$spanish_name_plural = $this->ask('Escribe el nombre de la entidad en Plural?');
        //$spanish_name_plural = Str::title($spanish_name_plural);

        //echo "\n".$spanish_name."\n";
        //echo $spanish_name_plural."\n";

        $this->init(Str::lower($argument), Str::plural($argument));

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

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['fields', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
            ['prefix', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
            ['active', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }

}