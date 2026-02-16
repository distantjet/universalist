=== Universalist ===
Contributors:      matiasescudero
Tags:              translate, english, spanish, espanol, block
Tested up to:      6.9
Stable tag:        0.1.2
Requires           PHP: 7.4.33
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Create content for English and Spanish.

== Description ==

Universalist lets you write page titles, headings, text, and lists in English and Spanish—side by side—and automatically shows visitors the version that matches their browser language. Stay tuned—more languages are on the way!

== Frequently Asked Questions ==

= Is there an example of how to programmatically select a language? =

Yes. You can programmatically select a language by setting the plugin’s language cookie.  
For example, the following links set the `dj_universalist_lang_cookie` to a specific language and reload the page so the change takes effect:
```html
<a href="#" onclick="document.cookie='dj_universalist_lang_cookie=en;path=/;max-age=2592000';location.reload();return false;">English</a>
<a href="#" onclick="document.cookie='dj_universalist_lang_cookie=es;path=/;max-age=2592000';location.reload();return false;">Español</a>
```
The cookie persists for 30 days (`max-age=2592000`), so the user’s language choice is remembered across visits.

== Screenshots ==

1. Page title translation.
2. Title translation.
3. Text translation.
4. List translation.

== Changelog ==

= 0.1.0 =
* Release

= 0.1.1 =
* Language detection bug fixed

= 0.1.2 =
* Title translation bug fixed

== Source Code ==

You can find the full source on GitHub:  
https://github.com/distantjet/universalist