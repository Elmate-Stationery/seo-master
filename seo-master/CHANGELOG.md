# SEO Master — Changelog

## 1.0.0 — 2026-11-05

### Added

- **Independent SEO platform.** Manages titles, meta descriptions, canonicals, robots, schema, sitemaps, and redirects without any dependency on Yoast SEO.
- **Database layer.** Ten custom tables (`wp_seo_master_*`) with full ownership of all SEO data.
- **Migration engine.** Detects and imports existing Yoast SEO data (Free + Premium) safely and idempotently.
- **Frontend output engine.** Outputs meta tags, schema.org JSON-LD, and executes redirects from SEO Master data.
- **Admin interface.** Dashboard, settings, content editor, redirects manager, sitemap manager, and migration wizard.
- **REST API.** Full CRUD endpoints for all SEO data.
- **Security.** No license validation, no external HTTP requests, no subscription checks.
- **Independence verified.** Works with zero prior SEO plugin installed.

### Database Tables

| Table | Purpose |
|-------|---------|
| `wp_seo_master_indexables` | Central registry — one row per post/page/term/etc. |
| `wp_seo_master_indexable_hierarchy` | Parent-child relationships between indexables |
| `wp_seo_master_primary_term` | Primary term assignments for posts |
| `wp_seo_master_seo_links` | Internal linking data |
| `wp_seo_master_prominent_words` | Prominent words per indexable |
| `wp_seo_master_settings` | Global settings (key-value) |
| `wp_seo_master_redirects` | Redirect rules (origin, target, type, format) |
| `wp_seo_master_schema` | Schema.org configurations per object |
| `wp_seo_master_sitemaps` | Sitemap configuration |
| `wp_seo_master_migrations` | Migration tracking and audit trail |
| `wp_seo_master_id_map` | Old-to-new ID mapping during migration |

### Migration Sources Supported

- `wp_yoast_indexables` → `wp_seo_master_indexables`
- `wp_yoast_indexable_hierarchy` → `wp_seo_master_indexable_hierarchy`
- `wp_yoast_primary_term` → `wp_seo_master_primary_term`
- `wp_yoast_seo_links` → `wp_seo_master_seo_links`
- `wp_yoast_prominent_words` → `wp_seo_master_prominent_words`
- `wp_options.wpseo_*` → `wp_seo_master_settings`
- `wp_postmeta._yoast_wpseo_*` → `wp_seo_master_indexables`
- `wp_options.wpseo-premium-redirects-base` → `wp_seo_master_redirects`

### Redirect Types Supported

- 301 Moved Permanently
- 302 Found
- 307 Temporary Redirect
- 410 Content Deleted
- 451 Unavailable For Legal Reasons

### Redirect Formats Supported

- Plain (exact URL match)
- Regex (pattern-based matching)

### Default Settings Installed

- Title separator: `|`
- Title template: `%%title%% %%sep%% %%sitename%%`
- Description template: `%%excerpt%%`
- Default robots for posts: `noindex, nofollow`
- Default robots for pages: `index, follow`
- Default robots for products: `index, follow`
- Sitemap enabled: yes
- Breadcrumb schema: enabled
- Organization schema: from WordPress site identity

### Hooks Registered

- `save_post` — creates/updates indexable entries
- `save_page` — creates/updates indexable entries
- `save_product` — creates/updates indexable entries
- `created_term` — creates indexable entries for terms
- `updated_term` — updates indexable entries for terms
- `before_delete_post` — removes indexable entries
- `template_redirect` — executes redirects
- `wp_head` — outputs meta tags and schema
- `admin_menu` — registers admin pages
- `rest_api_init` — registers REST endpoints

### REST Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/wp-json/seo-master/v1/indexables` | List indexables |
| POST | `/wp-json/seo-master/v1/indexables` | Create indexable |
| GET | `/wp-json/seo-master/v1/indexables/{id}` | Get indexable |
| PUT | `/wp-json/seo-master/v1/indexables/{id}` | Update indexable |
| DELETE | `/wp-json/seo-master/v1/indexables/{id}` | Delete indexable |
| GET | `/wp-json/seo-master/v1/redirects` | List redirects |
| POST | `/wp-json/seo-master/v1/redirects` | Create redirect |
| PUT | `/wp-json/seo-master/v1/redirects/{id}` | Update redirect |
| DELETE | `/wp-json/seo-master/v1/redirects/{id}` | Delete redirect |
| GET | `/wp-json/seo-master/v1/settings` | Get settings |
| PUT | `/wp-json/seo-master/v1/settings` | Update settings |
| GET | `/wp-json/seo-master/v1/migration/status` | Get migration status |
| POST | `/wp-json/seo-master/v1/migration/start` | Start migration |
| POST | `/wp-json/seo-master/v1/migration/rollback` | Rollback migration |

### Capabilities Required

- `manage_options` — access to all admin pages
- `edit_posts` — edit post SEO
- `edit_pages` — edit page SEO
- `edit_products` — edit product SEO
- `manage_options` — manage settings
- `manage_redirects` — manage redirects (custom capability)

### Security Notes

- No license validation
- No external HTTP requests
- No subscription checks
- No third-party servers contacted
- All data stored locally
- Standard WordPress nonce verification on all forms
- Standard WordPress capability checks on all endpoints
- Input sanitization on all user inputs
- SQL prepared statements for all queries