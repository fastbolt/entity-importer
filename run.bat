@echo off

IF "%1" == "" GOTO help
IF "%1" == "test" GOTO test
IF "%1" == "cs" GOTO cs

:help
    echo "Usage: %0 [test|cs]"
    GOTO end

:test
    ./vendor/bin/phpunit
    GOTO end

:cs
    ./vendor/bin/phpcs
    GOTO end

:end
