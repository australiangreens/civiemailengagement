# Changelog
All notable changes for the CiviEmailEngagement extension will be noted here.

## [2.1.0] - 2024-12-05
### Changed
- ContactEmailEngagement records are now deleted when there are no trackable URL
  clicks in the reporting period rather than mailings delivered
- Coding style changes

## [2.0.0] - 2024-10-08
### Changed
- BREAKING: The extension now uses Entity Framework version 2. This limits use of
  the extension to more recent versions of CiviCRM. The minimum supported version (5.70)
  is already higher than what EFv2 dictates but the change guarantees that lower versions
  of CiviCRM will be unable to install and use the extension at all.

## [1.0.3] - 2024-06-25
### Changed
- Fixed bug in refreshExpired function

## [1.0.2] - 2024-06-20
### Changed
- Removed unused JOIN in API4 call

## [1.0.1] - 2024-06-19
### Changed
- Fixed bug in reporting period definition
- Fixed issue where Models tab would render empty content for contacts with no EE record
- Fixed unbalanced single/double quotes in Smarty template

## [1.0.0] - 2024-06-17
Initial release candidate of the CiviEmailEngagement extension.
