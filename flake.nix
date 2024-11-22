{
  description = "Example flake for PHP development";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixos-unstable";
    nix-shell.url = "github:loophp/nix-shell";
    systems.url = "github:nix-systems/default";
  };

  outputs =
    inputs@{
      self,
      flake-parts,
      systems,
      ...
    }:
    flake-parts.lib.mkFlake { inherit inputs; } {
      systems = import systems;

      perSystem =
        {
          config,
          self',
          inputs',
          pkgs,
          system,
          lib,
          ...
        }:
        let
          php = pkgs.api.buildPhpFromComposer {
            src = inputs.self;
            php = pkgs.php83; # Change to php56, php70, ..., php81, php82, php83 etc.
          };
        in
        {
          _module.args.pkgs = import self.inputs.nixpkgs {
            inherit system;
            overlays = [ inputs.nix-shell.overlays.default ];
            config.allowUnfree = true;
          };

          devShells.default = pkgs.mkShellNoCC {
            name = "php-devshell";
            buildInputs = [
              php
              php.packages.composer
            ];
          };

          apps = {
            # nix run .#composer -- --version
            composer = {
              type = "app";
              program = lib.getExe (
                pkgs.writeShellApplication {
                  name = "composer";

                  runtimeInputs = [
                    php
                    php.packages.composer
                  ];

                  text = ''
                    ${lib.getExe php.packages.composer} "$@"
                  '';
                }
              );
            };
          };
        };
    };
}
