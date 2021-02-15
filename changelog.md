Changelog
=========

3.0.0 (2021-02-15)
------------------

- **BBC** Notifier instance is now a singleton
- **BBC** Add a configuration file and set pnotify5 as default view
- **BBC** arguments order of `show*()` methods have change (view name at last and optionnal)
- Add new predefined views:
  - bootstrap4
  - bootstrap4-advanced
  - pnotify5
- Deprecate old predefined views:
  - bootstrap3
  - bootstrap3-advanced
  - notify
  - pnotify4
- Add anothers conveniants helpers:
  - `notify()`
  - `notifier()` is now an alias of `notify()`
  - `notify_info()` shorckut for `notify()->info()`
  - `notify_success()` shorckut for `notify()->success()`
  - `notify_warning()` shorckut for `notify()->warning()`
  - `notify_error()` shorckut for `notify()->error()`
- Complements and details documentation


2.9.0 (2021-02-12)
------------------

- Add helper `notifier()`
- Minors enhancements


2.8.0 (2020-09-24)
------------------

- Add support for Laravel 8


2.7.1 (2020-03-24)
------------------

- Handle warning type in pnotify4 template


2.7.0 (2020-03-05)
------------------

- Add support for Laravel 7


2.6.0 (2019-12-29)
------------------

- Add support for Laravel 6


2.5.0 (2019-09-24)
------------------

- Add PNotify 4 template


2.4.0 (2019-03-07)
------------------

- Add support for Laravel 5.8


2.3.0 (2018-07-04)
------------------

- Add Laravel 5.7.* support


2.2.0 (2018-07-04)
------------------

- Add Laravel 5.6.* support


2.1.2 (2017-10-02)
------------------

- Anothers typos


2.1.1 (2017-10-02)
------------------

- Typo


2.1.0 (2017-10-01)
------------------

- Add support for Laravel 5.5


2.0.0 (2017-02-05)
------------------

- Add support for Laravel 5.4


1.0.5 (2016-11-02)
------------------

- Move to Github


1.0.4 (2016-03-22)
------------------

- Source code released with the MIT license
- Added license file


1.0.3 (2016-01-12)
------------------

- Completion composer.json


1.0.2 (2015-11-04)
------------------

- Correction of escaping PNotify parameters in the template


1.0.1 (2015-10-29)
------------------

- Added "jQuery document ready" to the PNotify template


1.0.0 (2015-10-29)
------------------

- First functional version
