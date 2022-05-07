# lgshesapla-wordpress-plugin

The WordPress Plugin development using VSCode Dev Containers.
Wordpress is automatically installed and available at http://localhost:8080.

Container configurariton in `docker-compose.yml`
WordPress settings in `.devcontainer/wp-setup.sh`, 
    - The site name, and admin user account details. 
    - Specify a space-separated list of WordPress plugins to automatically install. 
    - Set `WP_RESET` to `true`, to rebuild the WordPress instalation every time it is loaded. 

## Data folder

Any `.sql` files placed `.devcontainer/data` will be automatically imported when the site is built (using `wp db import`).

Anything placed in the `plugins` folder (single files or folders) will be copied into the WordPress plugins folder and activated as a plugin. This enables things like defining custom post types relevant to your imported data set, but not part of the development process.

## Included Tools

- XDebug, configured 
- WP-CLI
- Composer
- NodeJS
- PHP/WordPress extensions for VSCode (see `devconatainer.json`)
