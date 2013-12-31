owasp-clt-csp
=============

owasp-clt-csp is a Vagrant box for working with the Content Security Policy. It is based
on the excellent [devbox by Aboalarm](https://github.com/Aboalarm/devbox). Additional
changes have been made to the configuration to make the demo a little simpler.

* [VirtualBox](https://www.virtualbox.org/wiki/Downloads) - Free virtualization software 
* [Vagrant](https://www.vagrantup.com) - Tool for working with VirtualBox images


## Initial Setup

* Install VirtualBox and Vagrant ( &gt;= 1.3.0)
* Clone this repository `git clone https://github.com/colezlaw/owasp-clt-csp`. 
* Run `vagrant up` inside the newly created directory. (the first time you run Vagrant
it will fetch the virtual box image which is ~300mb. So this could take some time)
* Vagrant will now use Puppet to provision the devbox (this could take a few minutes)
* Point "devbox" and any other vhosts to `192.168.3.3` in your hosts file of your host
OS. e.g. `192.168.3.3 devbox myproject.dev myotherproject.dev [HOSTNAME]` 

## Firing it up
* `vagrant ssh` to ssh to the virtual box
* `sudo service nginx start` to fire up nginx
* `ngrok :80` to start ngrok pointing at the `www/csp/public` folder

## Shared Folders
The www folder is automatically synced to the VM (/var/www).  The sync works in both
directions.

## Credentials 
* SSH User: `vagrant` PW: `vagrant`
* MySQL User: `root` PW: `root` (access MySQL through SSH)

## Vagrant Commands

* `vagrant up` starts the virtual machine and provisions it
* `vagrant ssh` gives you shell access to the virtual machine
* `vagrant suspend` will essentially put the machine to 'sleep' with
`vagrant resume` waking it back up
* `vagrant reload` will reload the VM. Do this when the VM config changed.
For exmpale when you changed one of the configs (e.g. php.ini, sphinx.conf,
etc. or after a git pull of this repo)
* `vagrant halt` attempts a graceful shutdown of the machine and will need
to be brought back with `vagrant up`
* `vagrant halt --force` force shutdown if normal halt doesn't work
* `vagrant destroy` you broke something? this will destroy the VM and
reprovisions it again completely. Takes some time.


For more: Vagrant is [very well documented](http://docs.vagrantup.com/v2/)

Please fork, improve, extend, make pull request, wrap it as a gift. Use the GitHub Issues!


## Ngrok 

Ngrok creates a tunnel from the public internet (http://subdomain.ngrok.com) to a
website on your local machine. You can give this URL to anyone to allow them to try
out a website you're developing without doing any deployment.

For all the features and documentation, check their site: `http://ngrok.com` and usage
guide: `http://ngrok.com/usage`.

### Setup:

This is one change from the [Devbox](https://github.com/Aboalarm/devbox) configuration.
In this configuration, nginx is already configured to point to `/www/csp/public` for
all requests to `*.ngrok.com`.

