@echo off
rem Builds the installable Ardal zip; the real work is in build-zip.ps1.
powershell -NoProfile -ExecutionPolicy Bypass -File "%~dp0build-zip.ps1"
pause
