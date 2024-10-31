RewriteEngine On
RewriteCond %{REQUEST_URI} !/https://liag.ft.unicamp.br/act/$ [NC]
RewriteRule ^(.*)$ https://liag.ft.unicamp.br/act/ [L,R=302]