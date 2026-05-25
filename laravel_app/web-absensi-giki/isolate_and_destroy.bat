@echo off
echo Starting Aggressive Cleanup...

:: 1. Create Safe Haven
if not exist _KEEP_SAFE mkdir _KEEP_SAFE

:: 2. Move Critical Java Project Files
echo Moving files to Safe Haven...
move src _KEEP_SAFE\src
move pom.xml _KEEP_SAFE\pom.xml
move web_absensi_giki.sql _KEEP_SAFE\web_absensi_giki.sql
move README.md _KEEP_SAFE\README.md

:: 3. Destroy Everything Else (The Nuclear Option)
echo Destroying remaining files...
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
rmdir /s /q target
rmdir /s /q _legacy_backup
rmdir /s /q _trash

del /q *.php
del /q artisan
del /q composer.json
del /q composer.lock
del /q package.json
del /q phpunit.xml
del /q vite.config.js
del /q .env
del /q .env.example
del /q .editorconfig
del /q .gitattributes
del /q cleanup.bat

:: 4. Restore Critical Files
echo Restoring files...
move _KEEP_SAFE\src src
move _KEEP_SAFE\pom.xml pom.xml
move _KEEP_SAFE\web_absensi_giki.sql web_absensi_giki.sql
move _KEEP_SAFE\README.md README.md

:: 5. Cleanup Safe Haven
rmdir /s /q _KEEP_SAFE

echo Cleanup Complete.
