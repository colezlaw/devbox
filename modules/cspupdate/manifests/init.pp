class cspupdate {
    
    notify { "cspupdate":
        name      => "cspupdate",
        message   => "Beginning composer update in csp dir",
        withpath  => true
    }
  
    exec { "composer update":
        cwd       => "/var/www/csp",
        command   => "/usr/bin/php composer.phar update",
        logoutput => true
    }
}
