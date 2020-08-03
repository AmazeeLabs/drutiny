<?php

namespace Drutiny\Console\Command;

use Drutiny\Report\FormatInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;

/**
 *
 */
trait ReportingCommandTrait
{
  /**
   * @inheritdoc
   */
    protected function configureReporting()
    {
        $this
        ->addOption(
            'format',
            'f',
            InputOption::VALUE_OPTIONAL,
            'Specify which output format to render the report (console, html, json). Defaults to console.',
            'terminal'
        )
        ->addOption(
            'title',
            't',
            InputOption::VALUE_OPTIONAL,
            'Override the title of the profile with the specified value.',
            false
        )
        ->addOption(
            'report-filename',
            'o',
            InputOption::VALUE_OPTIONAL,
            'For json and html formats, use this option to write report to file. Drutiny will automate a filepath if the option is omitted. Use "stdout" to print to terminal',
            false
        )
        ->addOption(
            'report-per-site',
            null,
            InputOption::VALUE_NONE,
            'Flag to additionally render a report for each site audited in multisite mode.'
        );
    }

    /**
     * Determine a default filepath.
     */
      protected function getDefaultReportFilepath(InputInterface $input, FormatInterface $format):string
      {
          $filepath = 'stdout';
        // If format is not out to console and the filepath isn't set, automate
        // what the filepath should be.
          if ($input->getOption('format') != 'terminal') {
              $filepath = strtr('target-profile-uri-date.ext', [
               'target' => preg_replace('/[^a-z0-9]/', '', strtolower($input->getArgument('target'))),
               'profile' => $input->getArgument('profile'),
               'date' => date('Ymd-His'),
               'ext' => $format->getExtension(),
              ]);
          }
          return $filepath;
      }
}
