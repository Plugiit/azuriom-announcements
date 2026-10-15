# Changelog

## 1.0.1

- Changed: the plugin id is now `announcements` (was `plugiit-announcements`). Tables, routes, permissions and settings follow the new id.
- Fixed: the admin index route (`/admin/announcements`) no longer uses a closure, so `php artisan route:cache` works on sites using the plugin.

## 1.0.0

- Initial release.
