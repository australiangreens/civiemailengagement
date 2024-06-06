# CiviEmailEngagement
This extension adds an email engagement model to track

This is an [extension for CiviCRM](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/), licensed under [AGPL-3.0](LICENSE.txt).

## Requirements
This extension requires [the CiviModels extension](https://github.com/australiangreens/civimodels) to function.

## Getting Started

After installation carry out the following steps.

### Update CMS user permissions

The extension installs two permissions for administering and accessing the extension and its data.

Load the CiviCRM User and Permissions page (`/civicrm/admin/access?reset=1`) and use the link to access your CMS permissions management page (eg. "Drupal Access Control" or "WordPress Access Control").

Grant the "CiviEmailEngagement extension: Administer" and "CiviEmailEngagement Extension: Access CiviEmailEngagement" permissions as appropriate.

### Configure the extension

Head to `/civicrm/admin/setting/civiemailengagement` to set the reporting period for the model calculations.

### Review scheduled jobs for model data processing

The extension installs two scheduled jobs; one for calculating model data and another for finding expired model data and queueing them for recalculation.

The jobs are set to run hourly and daily respectively; you may wish to change these schedules to better suit your requirements.

## How it works
Once configured, the extension queues jobs to calculate email engagement values for contacts after people click trackable URLs within CiviMail mailings.

The extension similarly queues calculation jobs when merging contacts if necessary.

The scheduled job `CiviEmailEngagement calculation processing` processes these jobs and creates (or updates) records accordingly.

The scheduled job `CiviEmailEngagement find expired records' finds expired records and queues them for recalculation.

## Technical notes
The extension creates a new entity - ContactEmailEngagement - with its own table in the database (`civicrm_contact_email_engagement`) for storing data.

While scheduled jobs must use CiviCRM's APIv3 framework, the extension provides a complete set of APIv4 actions:

- Contact.calculateEE - calculate the Email Engagement values for a contact and create (or update) a ContactEmailEngagement record
- ContactEmailEngagement.refreshExpired - find and queue expired ContactEmailEngagement records for recalculation
- ContactEmailEngagement.runQueue - process queued jobs for calculating values

Therefore it's possible to avoid using Scheduled Jobs entirely and use cron jobs or similar to call the APIv4 actions to deliver all of the extension's functionality.

## Known Issues

N/A
