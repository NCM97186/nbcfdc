# File MIME module

Drupal determines the MIME type of each uploaded file by applying a MIME
type mapping to the file name.  The default mapping is hard-coded in the
[`ExtensionMimeTypeGuesser`
class](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21File%21MimeType%21ExtensionMimeTypeGuesser.php/property/ExtensionMimeTypeGuesser%3A%3AdefaultMapping).

This module allows site administrators to alter the built-in mapping.
For example, you may wish to serve FLAC files as `audio/flac` rather than
`application/x-flac`.


## Requirements

No special requirements.


## Installation

Install as you would normally install a contributed Drupal module.

Uninstalling this module will restore Drupal's built-in MIME type
mapping.


## Configuration

Custom mappings can be extracted from the server's mime.types file
(often available on a path such as `/etc/mime.types`) and/or a
site-specific mapping string, both of which must use the standard syntax
for mime.types files.  For example:

```
audio/mpeg					mpga mpega mp2 mp3 m4a
audio/mpegurl					m3u
audio/ogg					oga ogg opus spx
```

After installing and enabling this module, the MIME type mapping can be
configured by visiting Administration > Configuration > Media > File
MIME (admin/config/media/filemime).  Use the Apply tab
(admin/config/media/filemime/apply) to apply the configured MIME type
mapping retroactively to all previously uploaded files.


## Maintainers

This module is maintained by [mfb](https://www.drupal.org/u/mfb).

You can support development by [contributing bug reports, feature requests
or support requests](https://www.drupal.org/project/issues/filemime) or by
[sponsoring](https://github.com/sponsors/mfb).
