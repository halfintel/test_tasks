<?php
namespace models;

use models\Db;
use models\Curl;
use views\RestApi;



class Project {
    // TODO: додати методи запроса Pie chart та категорій
    public function getAll(array $catIds = []) {
        $db = new Db();
        $projectsWhere = '';
        if (!empty($catIds)){
            foreach ($catIds as $ind => $val){
                $catIds[$ind] = (int)$val;
            }
            $projectIds = $db->getAll('
                SELECT pc.project_id
                FROM project_categories pc
                WHERE pc.category_id IN (' . implode(',', $catIds) . ')
            ');
            $projectIds = array_column($projectIds, 'project_id');
            if (empty($projectIds)){
                $projectIds = [-1];
            }
            $projectsWhere = 'WHERE p.id IN (' . implode(',', $projectIds) . ')';
        }
        $projects = $db->getAll('
            SELECT 
                p.*,
                e.login,
                CONCAT (e.first_name, " ", e.last_name) as employer_name
            FROM projects p
            LEFT JOIN employers e ON e.id = p.employer_id
            ' . $projectsWhere . '
        ');
        // TODO: додати конвертацію у грн
        return $projects;
    }
    public function parse() {
        // TODO: додати захист від параллельного пуску
        $curl = new Curl();

        
        $allData = [];
        $url = 'https://api.freelancehunt.com/v2/projects';
        $projects = [];
        $categories = [];
        $employers = [];
        do {
            $data = $curl->get($url);
            // TODO: додати перевірку на 429 помилку
            /*
                TODO: додати перевірку на сдвиг списку:
                отримали 1 сторінку (1-10 проекти)
                з'явився новий проект
                отримали 2 сторінку (10-19 проекти замість 11-20)
            */
            $data = json_decode($data, true);
            $url = $data['links']['next'] ?? null;
            if (empty($data['data'])){
                continue;
            }
            foreach ($data['data'] as $project){
                if (empty($project['attributes']['employer']['id'])){
                    continue;// TODO: обробити проекти без замовників
                }
                $projects[] = [
                    'id' => $project['id'],
                    'name' => $project['attributes']['name'],
                    'budget' => $project['attributes']['budget']['amount'],
                    'currency' => $project['attributes']['budget']['currency'],
                    'employer_id' => $project['attributes']['employer']['id'],
                    'link' => $project['links']['self']['web'],
                    'category_ids' => array_column($project['attributes']['skills'], 'id'),
                ];
                if (!isset($employers[$project['attributes']['employer']['id']])){
                    $employers[$project['attributes']['employer']['id']] = [
                        'login' => $project['attributes']['employer']['login'],
                        'first_name' => $project['attributes']['employer']['first_name'],
                        'last_name' => $project['attributes']['employer']['last_name'],
                    ];
                }
                foreach ($project['attributes']['skills'] as $cat){
                    $categories[$cat['id']] = $cat['name'];
                }
            }
        } while (isset($url));

        $allData = [
            'projects' => $projects,
            'categories' => $categories,
            'employers' => $employers,
        ];
        
        
        // TODO: перенести до окремих класів та додати перевірку даних
        $this->initDb();
        $db = new Db();
        $sql = '';
        foreach ($categories as $catId => $catName){
            $sql .= 'INSERT INTO categories (id, name) VALUES (
                "' . $catId . '", 
                "' . $catName . '"
            ); ';
        }
        if (!empty($sql)){
            $db->execute($sql);
        }
        
        
        $sql = '';
        foreach ($employers as $empId => $emp){
            $sql .= 'INSERT INTO employers (id, login, first_name, last_name) VALUES (
                "' . $empId . '", 
                "' . $emp['login'] . '", 
                "' . $emp['first_name'] . '", 
                "' . $emp['last_name'] . '"
            ); ';
        }
        if (!empty($sql)){
            $db->execute($sql);
        }
        
        $sqls = [];
        $pcSql = '';
        foreach ($projects as $project){
            $sqls[] = 'INSERT INTO projects (id, budget, currency, employer_id, link, name) VALUES (
                "' . $project['id'] . '", 
                "' . $project['budget'] . '", 
                "' . ($project['currency'] ?? '') . '",
                "' . $project['employer_id'] . '",
                "' . $project['link'] . '",
                "' . $project['name'] . '"
            ); ';
            
            foreach ($project['category_ids'] as $catId){
                $pcSql .= 'INSERT INTO project_categories (project_id, category_id) VALUES (
                    "' . $project['id'] . '", 
                    "' . $catId . '"
                ); ';
            }
            
        }
        $sqls = array_chunk($sqls, 100);// TODO: записуються не всі проекти, тре розібратися
        foreach ($sqls as $sql){
            $sql = implode(' ', $sql);
            $db->execute($sql);
        }
        $db->execute($pcSql);


        return true;
    }
    
    public function initDb() {// TODO: перенести в миграции
        $db = new Db();
        $db->execute('DROP TABLE IF EXISTS projects, categories, employers, project_categories');
        $db->execute("CREATE TABLE `projects` (
                `id` INT(11) NOT NULL AUTO_INCREMENT , 
                `budget` DECIMAL(10,2) NOT NULL , 
                `currency` ENUM('','UAH','USD','EUR') NOT NULL DEFAULT '' , 
                `employer_id` INT(11) NOT NULL , 
                `link` VARCHAR(255) NOT NULL , 
                `name` VARCHAR(1023) NOT NULL , 
                PRIMARY KEY (`id`))");
        $db->execute("CREATE TABLE `categories` (
            `id` INT(11) NOT NULL AUTO_INCREMENT , 
            `name` VARCHAR(255) NOT NULL , 
            PRIMARY KEY (`id`))");
        $db->execute("CREATE TABLE `employers` (
            `id` INT(11) NOT NULL AUTO_INCREMENT , 
            `login` VARCHAR(255) NOT NULL , 
            `first_name` VARCHAR(255) NOT NULL , 
            `last_name` VARCHAR(255) NOT NULL , 
            PRIMARY KEY (`id`))");
        $db->execute("CREATE TABLE `project_categories` ( 
            `project_id` INT(11) NOT NULL , 
            `category_id` INT(11) NOT NULL )");
            
        $db->execute("ALTER TABLE `project_categories` ADD PRIMARY KEY (`category_id`, `project_id`);");
        $db->execute("ALTER TABLE `projects` ADD FOREIGN KEY (`employer_id`) REFERENCES `employers`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;");
        $db->execute("ALTER TABLE `project_categories` ADD FOREIGN KEY (`project_id`) REFERENCES `projects`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;");
        $db->execute("ALTER TABLE `project_categories` ADD FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;");
    }
}
