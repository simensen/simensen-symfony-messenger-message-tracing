{ pkgs ? import <nixpkgs> {}}:

let
  configuredPkgs = {
    php = pkgs.php83.withExtensions ({ all, enabled }: enabled ++ (with all; [ gnupg xdebug ]));
  };
in
  pkgs.mkShell {
    name = "simensen-symfony-messenger-message-tracing";
    packages = [
      configuredPkgs.php
      configuredPkgs.php.packages.composer
      configuredPkgs.php.packages.phive
      pkgs.gnupg
      pkgs.yamllint
    ];
    shellHook =
      ''
        export PATH=$(pwd)/tools:$PATH
      '';
  }
