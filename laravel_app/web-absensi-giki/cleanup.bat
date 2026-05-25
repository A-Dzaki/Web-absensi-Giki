@echo off
attrib -r /s *.*
del /s /q *.php
del /q artisan
del /q composer.json
del /q composer.lock
del /q package.json
del /q phpunit.xml
del /q vite.config.js
rmdir /s /q app
rmdir /s /q bootstrap
rmdir /s /q config
rmdir /s /q database
rmdir /s /q public
rmdir /s /q resources
rmdir /s /q routes
rmdir /s /q storage
rmdir /s /q tests
rmdir /s /q vendor
