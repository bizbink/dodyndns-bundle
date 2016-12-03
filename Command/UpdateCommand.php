<?php

/*
 * Copyright (C) 2016 Matthew Vanderende
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace bizbink\DODynDNSBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Log\LoggerInterface;
use DigitalOceanV2\Api\DomainRecord;

class UpdateCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('dodyndns:update')
                ->setDescription('Update DNS record')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        /* @var $logger LoggerInterface */
        $logger = $this->getContainer()->get('logger');

        /* @var $domainRecord DomainRecord */
        $domainRecord = $this->getContainer()->get('do.factory')->domainRecord();
        $records = $this->getContainer()->getParameter('bizbink_do_dyn_dns.records');
        $updated = array();

        foreach ($records as $record) {
            $updated[] = $domainRecord->update($record['domain'], $record['id'], $record['type'], file_get_contents('http://ipecho.net/plain'));
        }

        $logger->info('Updated DNS records for "vanderende.ca", "bizbink.ca", and "bizdank.ca"');
        $output->writeln('done.');
    }

}
