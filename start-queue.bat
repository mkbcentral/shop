@echo off
echo Demarrage du Queue Worker...
echo.
echo Pour arreter, appuyez sur Ctrl+C
echo.

php artisan queue:work --tries=3 --timeout=60
