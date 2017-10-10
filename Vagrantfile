# -*- mode: ruby -*-
# vi: set ft=ruby :

$script = <<SCRIPT
# Update & upgrade system
apt-get update
apt-get -yq upgrade

mkdir /usr/share/phpmyadmin
mkdir /etc/phpmyadmin

mv /tmp/setup/phpmyadmin-apache.conf /etc/phpmyadmin/apache.conf
mv /tmp/setup/phpmyadmin-export.php /usr/share/phpmyadmin/export.php

# Fix installlation
sed -i -e 's/vagrant/www-data/g' /etc/apache2/apache2.conf

# Install database
mysql -u "root" "-proot" < "/tmp/setup/setup.sql"

rm -r /tmp/setup/*
SCRIPT

# After provisioning, please run the following commands in a root prompt, to install phpmyadmin:
# apt-get -y install phpmyadmin;LINE='Include /etc/phpmyadmin/apache.conf';FILE=/etc/apache2/apache2.conf;grep -qF "$LINE" "$FILE" || echo "$LINE" >> "$FILE";service apache2 reload
# Answer `apache`, choose "N" to keep the current version, answer yes/blank to all other prompts, and input the root mysql password as `root`

Vagrant.configure("2") do |config|

    config.vm.box = "scotch/box"
    config.vm.network "private_network", ip: "192.168.33.11"
    config.vm.hostname = "tagntreat"
    config.vm.synced_folder "./www", "/var/www"#, :mount_options => ["dmode=777","fmode=666"]
    config.vm.synced_folder ".", "/vagrant", disabled: true

    config.vm.provision "file", source: "./setup", destination: "/tmp/setup"
    config.vm.provision "shell", inline: $script

    # Optional NFS. Make sure to remove other synced_folder line too
    #config.vm.synced_folder ".", "/var/www", :nfs => { :mount_options => ["dmode=777","fmode=666"] }

end
