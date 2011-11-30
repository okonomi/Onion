<?php
/*
 * This file is part of the Onion package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Onion\Command;
use CLIFramework\Command;
use CLIFramework\CommandInterface;
use Onion\GlobalConfig;
use Onion\PackageConfigReader;

class BuildCommand extends Command 
    implements CommandInterface
{
    function options($getopt)
    {
        $getopt->add('v|verbose','verbose message');
        $getopt->add('d|debug','debug message');
    }

    function execute($cx) 
    {
        // options result.
        $options = $this->getOptions($cx);

        $cx->logger->info( 'Configuring package.ini' );

        $config = new PackageConfigReader($cx);
        $config->readAsPackageXml();
        $xml = $config->generatePackageXml();

        /*
        if( file_exists('package.xml') )
            rename('package.xml','package.xml.old');
        */
        file_put_contents('package.xml',$xml);

        # $this->logger->info('Validating package...');
        # system('pear -q package-validate');

        $cx->logger->info('Building PEAR package...');
        system('pear -q package');

        $cx->logger->info('Done.');
        return true;
    }
}

