<?php

/**
 * Script to deploy software.
 * Written in PHP to be portable between Linux and Windows.
 * Dimitris Vidos, Watertown MA, 2018
 * 
 * It works with FTP, as this is what we have for volax.gr (no SSH).
 * It copies specific directory/ies from a git tag/commit/branch into a folder
 * and can maintain more than one copy, essentially renaming the folder with or without some time suffix.
 * This allows to quickly rollback to previous version, as long as there is one.
 * 
 * Should be able to maintain more than one environment, to cope for staging/testing too.
 * 
 * Syntax:
 *   php deploy.php --rollback <what> <version> <environment>
 *
 * It should read a config file with all options.
 * It should maintain x revisions on server and be able to rollback in case of error
 * It should support both (S)FTP and local deploys.
 * It should be able to both upload and delete files
 * It should be able to maintain a list of uploads, in the form of local/remote folder pairs and sync them.
 * It should have a "test" or "dryrun" mode, to just show what would be done.
 * It would be nice to support pre- and post- deploy jobs (local php scripts or called through http)
 *
 * Could be based off of https://github.com/dg/ftp-deployment
 * or https://github.com/banago/PHPloy that has Git support
 *
 * - Entity. Can be an application name, a microservice name or something relevant well defined.
 * - Version. Can be "local" where local source is used, "latest" where master branch is used, or other branch or git tag
 * - Environment. Can be "dev", "test", "prod", "sandbox" etc.
 *
 * 
 * Maintain the folder, one folder.old and one folder.new.
 * folder.new is used to deploy new version.
 * folder.old is the previous version, when only folder.old exists.
 * rollback is achieved by renaming folder to folder.new and folder.old to folder.
 * rollforward is achieved in the reverse way.
 * each time a new upload is performed, both old and new are deleted and the upload happens in folder.new.
 * then, a rollforward follows.
 *
 * therefore we need the switches: --rollback, --rollforward --status --cleanup --deploy (which is the default)
 *
 */

try {
    $config = [
        // describe environments and their connection methods
        'environments' => [
            'dev' => [
                'type' => 'local',
                'root_dir' => '/var/www/volax_gr',
            ],
            'prod' => [
                'type' => 'ftp',
                'server' => 'www.volax.gr',
                'username' => 'marinaxe904075',
                'password_base64' => 'eF5fMHBLT1ZOMD83Vnlocw==',
                'root_dir' => '/httpdocs',
            ],
        ],

        // describe local repository root
        'localroot' => '/home/dimitris/codebase/volax-gr/htdocs',

        // describe deployment paths, local => remote
        'deploys' => [
            'protected' => 'protected',
            'themes' => 'themes',
            'assets' => 'assets',
        ],
    ];
    $initial_directory = getcwd();

    $deployer = new Deployer($config);
    $deployer->run();
} catch (\Exception $e) {
    echo 'Error: ' . $e . PHP_EOL;
    exit(1);
} finally {
    chdir($initial_directory);
}

class DeployerScript {
    public function run() {
    }
}

class Deployer {

    protected $config;

    protected $logger;

    protected $local_server;

    protected $remote_server;

    public function __construct($config) {
        $this->config = $config;
    }
    public function run() {
        global $argv;
        $env_name = $argv[1] ?? '';
        if (empty($env_name)) {
            throw new RuntimeException('Environment not given');
        }
        if (empty($this->config['environments'][$env_name])) {
            throw new RuntimeException('Unknown environment "' . $env_name . '", i only know ' . implode(', ', array_keys($this->config)));
        }
        $env = $this->config['environments'][$env_name];
        
        $logger = new Logger();
        if ($env['type'] == 'ftp') {
            $remote = new FtpServer($logger, $env['server'], $env['username'], base64_decode($env['password_base64']), $env['root_dir']);
        } else {
            $remote = new LocalServer($logger, $env['root_dir']);
        }

        if (empty($this->config['localroot'])) {
            throw new RuntimeException('No local root directory given');
        }
        $local = new LocalServer($logger, $this->config['localroot']);


        try {
            $local->connect();
            $remote->connect();

            $local_files = $local->get_files();
            $remote_files = $remote->get_files();

            echo 'Local files ' . $local_files->count() . PHP_EOL;
            echo $local_files->simple_list() . PHP_EOL;
            echo PHP_EOL;
            echo 'Remote files ' . $remote_files->count() . PHP_EOL;
            echo $remote_files->simple_list() . PHP_EOL;
            echo PHP_EOL;

        } finally {
            $remote->disconnect();
        }

/*
        $cwd = ftp_pwd($conn);
        $this->log('CWD: ' . $cwd);

        $nlist = ftp_nlist($conn, $cwd);
        $this->log('nlist is: ' . var_export($nlist, true));

        $raw_list = ftp_rawlist($conn, $cwd);
        $this->log('raw_list is : ' . var_export($raw_list, true));

        $this->log('Disconnecting...');
        ftp_close($conn);
*/
    }


    /**
     * Deletes a remote folder and all its files and subfolders
     */
    public function delete_remote_directory(string $remote_directory, int $recursion_depth = 0) {    
    }

    /**
     * Synchronizes local to remote directory, by 
     * - uploading changed files and 
     * - removing orphan remote files and folders
     */
    public function syncronize_local_directory_to_remote(string $local_directory, string $remote_directory, int $recursion_depth = 0) {
        // upload any changed files
        // delete any files in remote but not local.
    }

    /**
     * Determines if there is a posiblity for rollback and roll_forward
     */
    public function get_capabilities() {
    }

    /**
     * Rollsback a deploy, by renaming current as new and old as current
     */
    public function roll_back() {
    }

    /**
     * Undoes the rollback, by renaming current as old and new as current
     */
    public function roll_forward() {
    }

    /**
     * Uploads local code/files to a .new directory
     */
    public function upload() {
    }

    /**
     * Cleans up the .old and .new directories
     */
    public function cleanup() {
    }
}


abstract class Server {
    public abstract function connect();
    public abstract function disconnect();
    public abstract function chdir(string $directory);
    public abstract function pwd();
    public abstract function get_files();
    public abstract function mkdir(string $name);
    public abstract function rmdir(string $name);
}
class LocalServer extends Server {
    protected $root_dir;
    public function __construct(Logger $logger, string $root_dir) {
        $this->root_dir = $root_dir;
    }
    public function connect() {
        if (!empty($this->root_dir)) {
            if (!chdir($this->root_dir)) {
                throw new RuntimeException('Could not chdir into ' . $this->root_dir);
            }
        }
    }
    public function disconnect() {
    }
    public function chdir(string $directory) {
        if (!chdir($directory)) {
            throw new RuntimeException('Could not chdir into ' . $this->root_dir);
        }
    }
    public function pwd() {
        return getcwd();
    }
    public function get_files() {
        $d = opendir($this->pwd());
        if (!$d) {
            throw new RuntimeException('Could not readdir ' . $this->pwd());
        }
        $files = new FilesCollection();
        while ($f = readdir($d)) {
            if ($f == '.') {
                continue;
            }
            $arr = ['name' => $f];
            $arr['modify'] = date('YmdHis', filemtime($f));
            $arr['size'] = is_dir($f) ? 0 : filesize($f);
            $arr['type'] = ($f == '..' ? 'pdir' : (is_dir($f) ? 'dir' : 'file'));
            $arr['group'] = filegroup($f);
            $arr['owner'] = fileowner($f);
            $arr['mode'] = substr(sprintf("%o",fileperms($f)),-4);

            $files->add(new File($arr));
        }
        closedir($d);

        $files->sort();
        return $files;
    }
    public function mkdir(string $name) {
        if (!mkdir($name)) {
            throw new RuntimeException('Could not create directory ' . $name);
        }
    }
    public function rmdir(string $name) {
        if (!rmdir($name)) {
            throw new RuntimeException('Could not remove directory ' . $name);
        }
    }
}
class FtpServer extends Server {
    protected $logger;
    protected $server;
    protected $username;
    protected $password;
    protected $root_dir;
    protected $ftp_connection;

    public function __construct(Logger $logger, string $server, string $username, string $password, string $root_dir) {
        $this->logger = $logger;
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->root_dir = $root_dir;
    }

    public function connect() {
        $this->logger->log('Connecting to ' . $this->server);
        $this->ftp_connection = ftp_connect($this->server);
        if (!$this->ftp_connection) {
            throw new RuntimeException('Could not connect to server ' . $this->server);
        }

        $this->logger->log('Loging in as ' . $this->username);
        if (!ftp_login($this->ftp_connection, $this->username, $this->password)) {
            throw new RuntimeException('Could not login into server with username ' . $this->username);
        }

        if (!empty($this->root_dir)) {
            if (!ftp_chdir($this->ftp_connection, $this->root_dir)) {
                throw new RuntimeException('Could not chdir into ' . $this->root_dir);
            }
            if (ftp_pwd($this->ftp_connection) != $this->root_dir) {
                throw new RuntimeException('Not in root directory of ' . $this->root_dir);
            }
        }
    }

    public function disconnect() {
        if (!$this->ftp_connection) {
            throw new RuntimeException('Not connected to a server');
        }
        $this->logger->log('Disconnecting');
        ftp_close($this->ftp_connection);
    }
    public function chdir(string $directory) {
        if (!$this->ftp_connection) {
            throw new runtimeexception('not connected to a server');
        }
        $this->logger->log('Changing to ' . $this->root_dir);
        $ok = ftp_chdir($this->ftp_connection, $directory);
        if (!$ok) {
            throw new RuntimeException('Could not chdir to ' . $directory);
        }
    }
    public function pwd(): string {
        if (!$this->ftp_connection) {
            throw new runtimeexception('not connected to a server');
        }
        return ftp_pwd($this->ftp_connection);
    }
    public function get_files() {
        if (!$this->ftp_connection) {
            throw new RuntimeException('Not connected to a server');
        }
        $mlsd = ftp_mlsd($this->ftp_connection, $this->pwd());
        // $this->logger->log('Gotten list ' . var_export($mlsd, true));
        $files = new FilesCollection();
        foreach ($mlsd as $entry) {
            if ($entry['name'] == '.') {
                continue;
            }
            $files->add(new File($entry));
        }

        $files->sort();
        return $files;
    }
    public function mkdir(string $name) {
        if (!$this->ftp_connection) {
            throw new RuntimeException('Not connected to a server');
        }
        $this->logger->log('Creating directory ' . $name);
        if (!ftp_mkdir($this->ftp_connection, $name)) {
            throw new RuntimeException('Could not create directory ' . $name);
        }
    }
    public function rmdir(string $name) {
        if (!$this->ftp_connection) {
            throw new RuntimeException('Not connected to a server');
        }
        $this->logger->log('Removing directory ' . $name);
        if (!ftp_rmdir($this->ftp_connection, $name)) {
            throw new RuntimeException('Could not remove directory ' . $name);
        }
    }
}
class Logger {
    public function log($message, $context = null) {
        echo $message . PHP_EOL;
        if (!empty($context)) {
            echo '    ' . var_export($context, true) . PHP_EOL;
        }
    }
}
class File {
    /* array (
        'name' => 'robots.txt',
        'modify' => '20160114091355',
        'perm' => 'adfrw',
        'size' => '47',
        'type' => 'file',
        'unique' => '901UF657EF',
        'UNIX.group' => '1003',
        'UNIX.mode' => '0664',
        'UNIX.owner' => '10168',
      ),
    */
    public $name;   // e.g. 'robots.txt'
    public $modify; // e.g. 20160114091355
    public $perm;   // not sure, 'adfrw' ?
    public $size;   // e.g. 47
    public $type;   // 'file' or 'dir', 'cdir' (current), 'pdir' (parent)
    public $unique; // e.g. '901UF657EF'
    public $group;  // e.g. '1003'
    public $owner;  // e.g. '10168'
    public $mode;   // e.g. '0664'

    public $files = [];

    public function __construct($mlsd_array) {
        $this->name = $mlsd_array['name'];
        $this->modify = $mlsd_array['modify'];
        $this->perm = $mlsd_array['perm'] ?? '';
        $this->size = $mlsd_array['size'] ?? 0;
        $this->type = $mlsd_array['type'];
        $this->unique = $mlsd_array['unique'] ?? '';
    }

    public function is_dir() {
        return $this->type == 'dir';
    }

    public function is_file() {
        return $this->type == 'file';
    }
}
class FilesCollection {
    protected $items;

    public function count() {
        return count($this->items);
    }

    public function clear() {
        $this->items = [];
    }

    public function add(File $file) {
        $this->items[] = $file;
    }

    public function dir_exists($name) {
        foreach ($this->items as $file) {
            if ($file->name == $name && $file->type == 'dir') {
                return true;
            }
        }
        return false;
    }
    public function file_exists($name) {
        foreach ($this->items as $file) {
            if ($file->name == $name && $file->type == 'file') {
                return true;
            }
        }
        return false;
    }
    public function simple_list() {
        $names = [];
        foreach ($this->items as $file) {
            $names[] = 
                str_pad($file->type, 8) . ' ' . 
                str_pad($file->modify, 12) . ' ' .
                str_pad($file->size, 10, ' ', STR_PAD_LEFT) . ' ' .
                $file->name;
        }
        return implode(PHP_EOL, $names);
    }

    public function sort() {
        uasort($this->items, function($a, $b) { return strcmp($a->name, $b->name); });
    }
}




