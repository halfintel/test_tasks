<?php
namespace controllers;

use models\Project;
use views\RestApi;

class Projects {
    public function all() {
        $project = new Project();
        $projects = $project->getAll();// TODO: додати фільтр за категоріями
        RestApi::setResponse200($projects);
    }
    
    public function parse() {
        $project = new Project();
        $project->parse();
        RestApi::setResponse200('all projects parsed');
    }
}
